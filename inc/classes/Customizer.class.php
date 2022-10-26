<?php

namespace Bric;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\OutputStyle;
use Padaliyajay\PHPAutoprefixer\Autoprefixer;

class Customizer {

    public static $instance;
    public static $defaults_file;
    public $default_colors;



    public function __construct() {

        self::$defaults_file =  file_exists( \get_stylesheet_directory() . '/theme-defaults.json' ) ?  \get_stylesheet_directory() . '/theme-defaults.json' :  \get_template_directory() . '/theme-defaults.json';

        add_action( 'customize_register', [ $this, 'add_theme_defaults_to_customizer'] );

        add_action( 'customize_register', [ $this, 'add_site_info_to_customizer'] );
        add_action( 'customize_register', [ $this, 'add_breadcrumb_options_to_customizer'] );
        
      //  add_action( 'customize_register', [ $this, 'register_social_media_customizer'] );

        add_action( 'update_option_theme_mods_' . get_stylesheet(), [ $this, 'after_theme_mod_update' ], 10, 3 );
     
       // add_action( 'init', [ $this, 'remove_action_update_option' ]);

        add_action( 'wp_head', [ $this, 'write_css_vars' ], 10 ); 
        
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_customizer_preview_styles' ] );
       
        add_action( 'compile_css', [ $this, 'compile_scss' ] );

       
    }




    public function remove_action_update_option() {

        $stylesheet = get_option( 'theme_switched' );

        error_log( 'Init check theme switched: ' . $stylesheet );
       

        if ( !empty( $stylesheet ) ) {

            remove_action( 'update_option_theme_mods_' . $stylesheet, [ $this, 'after_theme_mod_update' ], 10, 3 );

        }

    }



    public function enqueue_customizer_preview_styles() {

        if ( is_customize_preview() ) {

            wp_enqueue_style( 'bric-customizer2',  get_template_directory_uri() . '/assets/css/bric-customizer.css', ['bric'], time() );

        }
    }









    /**
     *  Register our theme-defaults.json
     *  in the customizer
     * 
     * 
     * 
     */

     public function add_theme_defaults_to_customizer( $wp_customize ) {
    
    
        $wp_customize->add_panel( 'theme_options', [
            'title' => __( 'Theme Options' ),
            'description' => __( 'Define theme colors here.' ),
            'priority' => 160,
            'capability' => 'edit_theme_options',
        ]);


        $theme_defaults = $this->get_theme_defaults();


        /**
         *  Make the Sections
         *  and put in the controls
         * 
         * 
         */


         $sections = $theme_defaults->sections;

         if ( !empty( $sections ) && is_array( $sections ) ){

            foreach( $sections as $section ) {

                $wp_customize->add_section( $section->id, [
                    'title' => __( $section->label ),
                    'panel' => 'theme_options'
                ]);


                $section_id = $section->id;

                //Do the parts of each section
                if ( !empty( $theme_defaults->$section_id ) ) {

                    foreach( $theme_defaults->$section_id as $default ) {
        
                        $choices = [];
        
                        $default_prop = $default->prop;
                        $description = isset( $default->description ) ? $default->description : '';



                        $wp_customize->add_setting( sprintf( "%s__%s", $section_id, $default_prop ), [
                            'default' => is_object( $default->val ) ? json_encode( $default->val ) : $default->val,
                            'transport' => isset( $section->transport ) ? $section->transport : 'refresh',
                            'type' => 'theme_mod',
                            'capability' => 'edit_theme_options',
                            'sanitize_callback' => isset( $default->sanitize_callback ) ? $default->sanitize_callback : null
                       ]);           
        


                       if ( isset( $default->choices ) && is_array( $default->choices ) ) {
        
                            foreach ( $default->choices as $choice ) {
        
                                $choices[ $choice->prop ] = __( $choice->val );
                            }
                       } elseif ( isset( $default->choices ) && is_string( $default->choices ) ) {

                            switch ( $default->choices ) {

                                case "wp_get_nav_menus" : 

                                    $nav_menus = wp_get_nav_menus();

                                    $choices[0] = 'None';

                                    foreach ( $nav_menus as $nav_menu ) {

                                        $choices[ $nav_menu->term_id ] = $nav_menu->name;
                                    }

                                    break;


                            }

                       }

                       switch( $default->type ) {                        

                            case "color" :

                                $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, sprintf( "%s__%s", $section_id, $default_prop ), [
                                    'label' => __( $default->label ),
                                    //'description' => __( $default->description ),
                                    'section' => $section_id,
                                    'settings' => sprintf( "%s__%s", $section_id, $default_prop )
                                ] ));



                                break;
                            
                                case "image" :

                                $wp_customize->add_control( new \WP_Customize_Image_Control( $wp_customize, sprintf( "%s__%s", $section_id, $default_prop ), [
                                    'label' => __( $default->label ),
                                    //'description' => __( $default->description ),
                                    'section' => $section_id,
                                    'settings' => sprintf( "%s__%s", $section_id, $default_prop )
                                ] ));



                                break;

                            case "google_font" :                   
                                
                                $wp_customize->add_control( new \Skyrocket_Google_Font_Select_Custom_Control( $wp_customize,  sprintf( "%s__%s", $section_id, $default_prop ),
                                    [
                                        'label' => __( $default->label ),
                                        'description' => __( $description ),
                                        'section' => $section_id,
                                        'settings' => sprintf( "%s__%s", $section_id, $default_prop ),
                                        'input_attrs' => array(
                                            'font_count' => 'all',
                                            'orderby' => 'alpha',
                                        ),
                                    ]
                                ) );
                                        
                                break;

                           

                            default :

                                $wp_customize->add_control( sprintf( "%s__%s", $section_id, $default_prop ), [
                                    'label' => __( $default->label ),
                                    'description' => __( $description ),
                                    'section' => $section_id,
                                    'priority' => 10,
                                    'type' => $default->type,
                                    'capability' => 'edit_theme_options',
                                    'choices' => $choices
                                ]);
            
                            
                       }


                       /**
                        *   Register Partials
                        */

                        $render_callback = null;


                       //error_log( json_encode( $section ) );
/*
                       if ( is_array( $section->callback ) ) {

                            $render_callback = [ $$section->callback[0], $section->callback[1] ];

                       }
*/

                        if ( isset( $section->transport ) && $section->transport == 'postMessage' && $section->partial ) {

                            $partial_refresh_args = [
                                'selector' => $section->selector,
                                'container_inclusive' => $section->container_inclusive,
                                'render_callback' => $section->callback,
                                'fallback_refresh' => true
                            ];
                    
                            $wp_customize->selective_refresh->add_partial( sprintf( "%s__%s", $section_id, $default_prop ), $partial_refresh_args );

                        }

                
                       
                    }
        
                }


            }
         }



         






    }





    /**
     *      Add the Site Info fields
     *      to customizer
     *      Opted to manually code into theme without json file
     * 
     * 
     */

    public function add_site_info_to_customizer( $wp_customize ) {

        /*
                $wp_customize->add_panel( 'bric_si', [
                    'title' => __( 'Site Info' ),
                    'description' => __( 'Define business info.' ),
                    'priority' => 160,
                    'capability' => 'edit_theme_options',
                ]);
        */
        
        /*
                $wp_customize->add_section( 'bric_si', [
                    'title' => __( 'Site Info'),
                    'panel' => 'bric_si'
                ]);
        */
        
        
                $wp_customize->add_setting( 'bric_si_name', [
                    'default' => get_bloginfo( 'title' ),
                    'transport' => 'refresh',
                    'type' => 'option',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'wp_filter_nohtml_kses'
               ]);           
        
        
                $wp_customize->add_control( 'bric_si_name', [
                    'label' => __( 'Company Name' ),
                    'description' => __( 'Company name, or whatever the main entity is on the website.' ),
                    'section' => 'title_tagline',
                    'priority' => 100,
                    'type' => 'text',
                    'capability' => 'edit_theme_options'
                ]);
        
        
                
        
                $wp_customize->add_setting( 'bric_si_address_1', [
                    'default' => '',
                    'transport' => 'refresh',
                    'type' => 'option',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'wp_filter_nohtml_kses'
               ]);           
        
        
                $wp_customize->add_control( 'bric_si_address_1', [
                    'label' => __( 'Address' ),
                    'description' => null,
                    'section' => 'title_tagline',
                    'priority' => 100,
                    'type' => 'text',
                    'capability' => 'edit_theme_options'
                ]);
        
        
        
                $wp_customize->add_setting( 'bric_si_address_2', [
                    'default' => '',
                    'transport' => 'refresh',
                    'type' => 'option',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'wp_filter_nohtml_kses'
               ]);           
        
        
                $wp_customize->add_control( 'bric_si_address_2', [
                    'label' => __( 'Address Line 2' ),
                    'description' => null,
                    'section' => 'title_tagline',
                    'priority' => 100,
                    'type' => 'text',
                    'capability' => 'edit_theme_options'
                ]);
        
        
                $wp_customize->add_setting( 'bric_si_city', [
                    'default' => '',
                    'transport' => 'refresh',
                    'type' => 'option',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'wp_filter_nohtml_kses'
               ]);           
        
        
                $wp_customize->add_control( 'bric_si_city', [
                    'label' => __( 'City' ),
                    'description' => null,
                    'section' => 'title_tagline',
                    'priority' => 100,
                    'type' => 'text',
                    'capability' => 'edit_theme_options'
                ]);
        
        
                $wp_customize->add_setting( 'bric_si_state', [
                    'default' => '',
                    'transport' => 'refresh',
                    'type' => 'option',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'wp_filter_nohtml_kses'
               ]);           
        
        
                $wp_customize->add_control( 'bric_si_state', [
                    'label' => __( 'State' ),
                    'description' => null,
                    'section' => 'title_tagline',
                    'priority' => 100,
                    'type' => 'text',
                    'capability' => 'edit_theme_options'
                ]);
        
        
        
                $wp_customize->add_setting( 'bric_si_zip', [
                    'default' => '',
                    'transport' => 'refresh',
                    'type' => 'option',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'wp_filter_nohtml_kses'
               ]);           
        
        
                $wp_customize->add_control( 'bric_si_zip', [
                    'label' => __( 'Zip' ),
                    'description' => null,
                    'section' => 'title_tagline',
                    'priority' => 100,
                    'type' => 'text',
                    'capability' => 'edit_theme_options'
                ]);
        
                $wp_customize->add_setting( 'bric_si_phone_prefix', [
                    'default' => '',
                    'transport' => 'refresh',
                    'type' => 'option',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'wp_filter_nohtml_kses'
               ]);           
        
        
                $wp_customize->add_control( 'bric_si_phone_prefix', [
                    'label' => __( 'Phone Prefix' ),
                    'description' => null,
                    'section' => 'title_tagline',
                    'priority' => 100,
                    'type' => 'tel',
                    'capability' => 'edit_theme_options'
                ]);
        
        
                $wp_customize->add_setting( 'bric_si_phone', [
                    'default' => '',
                    'transport' => 'refresh',
                    'type' => 'option',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'wp_filter_nohtml_kses'
               ]);           
        
        
                $wp_customize->add_control( 'bric_si_phone', [
                    'label' => __( 'Phone' ),
                    'description' => null,
                    'section' => 'title_tagline',
                    'priority' => 100,
                    'type' => 'tel',
                    'capability' => 'edit_theme_options'
                ]);
        
                $wp_customize->add_setting( 'bric_si_fax_prefix', [
                    'default' => '',
                    'transport' => 'refresh',
                    'type' => 'option',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'wp_filter_nohtml_kses'
               ]);           
        
        
                $wp_customize->add_control( 'bric_si_fax_prefix', [
                    'label' => __( 'Fax Prefix' ),
                    'description' => null,
                    'section' => 'title_tagline',
                    'priority' => 100,
                    'type' => 'tel',
                    'capability' => 'edit_theme_options'
                ]);
        
        
                $wp_customize->add_setting( 'bric_si_fax', [
                    'default' => '',
                    'transport' => 'refresh',
                    'type' => 'option',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'wp_filter_nohtml_kses'
               ]);           
        
        
                $wp_customize->add_control( 'bric_si_fax', [
                    'label' => __( 'Fax' ),
                    'description' => null,
                    'section' => 'title_tagline',
                    'priority' => 100,
                    'type' => 'tel',
                    'capability' => 'edit_theme_options'
                ]);
        
        
                $wp_customize->add_setting( 'bric_si_email', [
                    'default' => '',
                    'transport' => 'refresh',
                    'type' => 'option',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'sanitize_email'
               ]);           
        
        
                $wp_customize->add_control( 'bric_si_email', [
                    'label' => __( 'Email' ),
                    'description' => null,
                    'section' => 'title_tagline',
                    'priority' => 100,
                    'type' => 'tel',
                    'capability' => 'edit_theme_options'
                ]);
        
        
        
        
        
        
        
            }
        
        
        
        
        

    /**
     *  Social Media in the Customizer
     * 
     * 
     * 
     */


     public function register_social_media_customizer( $wp_customize ) {

 
        $wp_customize->add_panel( 'bric_social_media', [
            'title' => __( 'Social Media' ),
            'description' => __( 'Add social media accounts.' ),
            'priority' => 160,
            'capability' => 'edit_theme_options',
        ]);

        $wp_customize->add_section( 'bric_social_media_accounts', [
            'title' => __( 'Social Media'),
            'panel' => 'bric_social_media'
        ]);


        

        $wp_customize->add_control( new \Bric_Font_Awesome_Select_Custom_Control( $wp_customize,  'bric_social_media_facebook' ),
            [
                'label' => __( 'Facebook' ),
                'description' => __( 'Facebook account info' ),
                'section' => $section_id,
                'settings' => 'bric_social_media_facebook',
                'input_attrs' => array(
                    'font_count' => 'all',

                ),
            ]
        );
                    
          
     }





    /**
     *  Breadcrumb Options in the Customizer
     * 
     *  This will only load if Yoast SEO is active.
     * 
     * 
     */


    public function add_breadcrumb_options_to_customizer( $wp_customize ) {

 
        $wp_customize->add_setting( 'bric_bc_container', [
            'default' => 'container-xxl',
            'transport' => 'refresh',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'wp_filter_nohtml_kses'
       ]);           


        $wp_customize->add_control( 'bric_bc_container', [
            'label' => __( 'Breadcrumb Container Class' ),
            'description' => __( '' ),
            'section' => 'wpseo_breadcrumbs_customizer_section',
            'priority' => 100,
            'type' => 'select',
            'capability' => 'edit_theme_options',
            'choices' => [ 
                'container-xxl' => __( 'Container XXL'),
                'container-xl'  => __( 'Container XL'),
                'container-lg'  => __( 'Container LG'),
                'container-md' =>  __( 'Container MD'),
                'container'     => __( 'Container'),
                'container-fluid' => __( 'Container Fluid'),
            ]
        ]);

            
          
     }







    /**
     *  Just get the Theme Default Colors
     *  from the defaults object
     *     
     * 
     * 
     */


    public function get_theme_default_colors() {


        //Don't get it again if it's already loaded
        if ( !empty( $this->default_colors ) ) {

            return $this->default_colors;
        }

        $defaults = $this->get_theme_defaults();

        if ( !empty( $defaults->theme_colors ) ) {

            return $defaults->theme_colors;

        } else {

            return false;

        }


    }





   /**
    *   Get all the Theme Defaults
    *   from theme-defaults.json
    *
    *
    *
    */


    public function get_theme_defaults() {


        if ( !empty( $this->theme_defaults ) ) {
            
            return $this->theme_defaults;

        }


        //Ok, let's get the file
        $theme_defaults_file = self::$defaults_file; 

        //see if we have the file
        if ( file_exists( $theme_defaults_file ) ) {

            $theme_defaults = \file_get_contents( $theme_defaults_file );

            $this->theme_defaults = json_decode( $theme_defaults );


            if ( !empty( $this->theme_defaults ) ) {

                return $this->theme_defaults;
            
            } else {

                return false;

            }


        } else {

            return false;

        }

    }









    /**
     *  Gets a single Theme Default value
     *  from the theme-defaults.json 
     *  object
     * 
     * 
     */


    public function get_theme_default( $section, $setting ) {

        $defaults = $this->get_theme_defaults();

        //Get the section
        foreach( $defaults->$section as $find_setting ) {

            if ( isset( $find_setting->prop ) && $find_setting->prop == $setting ) {

                return $find_setting->val;

            }


        }

        return "";

    }







    /**
     *  Wrapper for get_theme_mod
     *  which auto-injects the 
     *  default value from our 
     *  theme-defaults.json
     *  
     */


    public function get_theme_mod( $section, $setting ) {

        return get_theme_mod( "{$section}__{$setting}", $this->get_theme_default( $section, $setting ) );

    }











    public function get_merged_theme_mods( $mods = null ) {


        if ( isset( $this->get_merged_theme_mods ) ) {

            return $this->get_merged_theme_mods;

        }



        $defaults = $this->get_theme_defaults();

        if ( empty( $mods ) ) {
    
            $mods = get_theme_mods();
    
        }


        $merged_mods = [];

        //Start with the sections, then arrange the values
        foreach( $defaults->sections as $default_section ) {

            //Get the section values
            $section_id = $default_section->id;

            foreach( $defaults->$section_id as $setting ) {

               
                if ( isset( $mods["{$section_id}__{$setting->prop}"] ) ) {

                    $merged_mods[ $section_id ][ $setting->prop ] = $mods["{$section_id}__{$setting->prop}"];
                    

                } else {


                    $merged_mods[ $section_id ][ $setting->prop ] = $setting->val;
                }


            } 

        }


        $this->merged_theme_mods = $merged_mods;


        return $this->merged_theme_mods;


    }






    /**
     * 
     *  Initialize the SCSS Compiler
     * 
     * 
     */

     public function scss_init() {

        $compiler = new Compiler();

        $import_paths = [
            get_template_directory() . '/assets/src/css/bric/',
            get_template_directory() . '/assets/src/css/vendor/bootstrap/',
            //get_template_directory() . '/assets/src/css/bric/acf-blocks/',
            //get_template_directory() . '/assets/src/css/bric/blocks/',
            //get_template_directory() . '/assets/src/css/bric/bric-mixins/',
            get_template_directory() . '/assets/src/css/photoswipe/',
            get_stylesheet_directory() . '/assets/src/css/',
            get_stylesheet_directory() . '/node_modules/bootstrap/scss/',
        ];

        foreach( $import_paths as $import_path ) {

            $compiler->addImportPath( $import_path );
        }

        return $compiler;

     }




    /**    
     *     Schedule the Compile
     * 
     */

    public function schedule_compile_scss( ) {


        if ( function_exists( 'as_has_scheduled_action' ) ) {

            if ( false === as_has_scheduled_action( 'compile_css' ) ) {

                error_log( 'schedule compile css' );

                as_enqueue_async_action( 'compile_css' );
            }
        }

    }


    /**
     *  Compile SCSS using PHP SCSS class
     * 
     * 
     * 
     * 
     */

     public function compile_scss() {


        if ( defined( 'COMPILE_CSS' ) ) {

            if ( ! COMPILE_CSS ) {
                error_log( 'COMPILE_CSS set to false. Did not compile CSS via PHP.' );
                return;
                
            }
        }



        error_log( 'begin compile css' );

        $stylesheet = '';

        try {

           
            $compiler = $this->scss_init();
    
            $contents = file_get_contents( get_stylesheet_directory() . '/assets/src/css/bric-style.scss' );
    
            //error_log( $contents );

           

            $auto_compile_comment = sprintf( "/*!This stylesheet auto-generated by bric theme for WP on %s.*/\r\n", date( "Y-m-d H:i:s" ) );

            $stylesheet = $compiler->compileString( $auto_compile_comment . $contents )->getCss();

 
        } catch ( \Exception $e ) {

            error_log( 'SCSS Compiler Error: ' . $e->getMessage() );

        }




        if ( !empty( $stylesheet ) ) {
            
            //Autoprefix it
            $autoprefixer = new Autoprefixer( $stylesheet );

            $stylesheet = $autoprefixer->compile();

            //Re-compile with SCSSPHP to minize
            $compiler->setOutputStyle( OutputStyle::COMPRESSED );
            $stylesheet = $compiler->compileString( $stylesheet )->getCss();

            
            //Write the file
            file_put_contents( get_stylesheet_directory() . '/assets/css/bric-style.css', $stylesheet );

            error_log( 'complete compile css' );

        }


     }





     /**
      *   Wrapper to get and write the
      *   SCSS Variables
      *
      *
      *
      */


     public function write_css_variables( $theme_mods ) {

        //If we're not a child, then forget writing custom scss
        if ( !is_child_theme() ) {

            return;

        }

        //Colors
        //$theme_colors = $theme_mods['theme_colors'];
        $scss_path = \get_stylesheet_directory() . '/assets/src/css/_theme-colors-auto.scss';

        //Used by the included files
        $write_file = true;

        ob_start();

        include( locate_template( 'template-parts/scss/child-colors-auto.php' ) );
      
        $scss_contents = ob_get_clean();


        \file_put_contents( $scss_path, $scss_contents );


          
        //Variables
        $scss_path = \get_stylesheet_directory() . '/assets/src/css/_theme-variables-auto.scss';


        ob_start();

        include( locate_template( 'template-parts/scss/child-variables-auto.php' ) );
      
        $scss_contents = ob_get_clean();


        \file_put_contents( $scss_path, $scss_contents );



     }





    /**
     *  Write the SCSS after Customizer is "Published"
     * 
     * 
     * 
     *  colors
     *  variables
     */

    public function after_theme_mod_update( $old_value, $value, $option ) {

        /*error_log( sprintf( "Update option old value: %s
        new value: %s
        option: $option", json_encode( $old_value ), json_encode( $value ) ) );

        error_log( json_encode( $_REQUEST ) );
*/

        if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'activate' ) {

            return;
        }


        //error_log( json_encode( $value ) );

        //$theme_defaults = $this->get_theme_defaults();
       
        //$theme_mods = $this->get_merged_theme_mods( $value );

        //error_log( json_encode( $theme_mods ) );

        $this->write_css_variables( $value ); //$theme_mods );

        $this->schedule_compile_scss();
       // $this->compile_scss();

        return;

    }










    /** 
     *  Wrapper function to output CSS Vars
     *  Gets called by wp_head
     * 
     * 
     * 
     * 
     */

    public function write_css_vars() {

            if ( is_customize_preview() || ( isset( $_GET['preview_css'] ) ) ) {
                
                echo $this->get_vars_stylesheet();
        
            }
        
    }





    /**
     *  Prints the CSS Variables
     *  for the preview 
     * 
     * 
     * 
     */



    public function get_vars_stylesheet() {


        $write_inline = true;

        ob_start();
?>
<style id="bric-css-vars">
:root {
<?php
        include locate_template( 'template-parts/scss/child-colors-auto.php' );
        include locate_template( 'template-parts/scss/child-variables-auto.php' );
?>

}
</style>
<?php
        $css = ob_get_clean();

        return $css;

    
}















    public static function get_instance() {

        if( self::$instance == null ) {

            self::$instance = new self;

        }

        return self::$instance;
    }


}


function Customizer() {

    return Customizer::get_instance();

}

Customizer();