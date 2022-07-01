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


        self::$defaults_file =  \get_template_directory() . '/theme-defaults.json';

        add_action( 'customize_register', [ $this, 'add_theme_defaults_to_customizer'] );
        //add_action( 'customize_register', [ $this, 'add_google_fonts_to_customizer'] );
        add_action( 'customize_register', [ $this, 'add_site_info_to_customizer'] );

        add_action( 'update_option_theme_mods_' . get_stylesheet(), [ $this, 'after_theme_mod_update' ], 10, 3 );
     

        add_action( 'wp_head', [ $this, 'write_css_vars' ], 10 ); 
        
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_customizer_preview_styles' ] );
       


    }


    public function enqueue_customizer_preview_styles() {

        if ( is_customize_preview() ) {

            wp_enqueue_style( 'bric-customizer2',  get_template_directory_uri() . '/assets/css/bric-customizer.css' );

        }
    }




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
     *  Compile SCSS
     * 
     * 
     * 
     * 
     */

     public function compile_scss() {


        $stylesheet = '';

        try {

           
            $compiler = $this->scss_init();
    
            $contents = file_get_contents( get_stylesheet_directory() . '/assets/src/css/bric-style.scss' );
    
            //error_log( $contents );

           

            $auto_compile_comment = sprintf( "/*!This stylesheet auto-generated by bric theme for WP on %s.*/\r\n", date( "Y-m-d H:i:s" ) );

            $stylesheet = $compiler->compileString( $auto_compile_comment . $contents )->getCss();

 
        } catch ( \Exception $e ) {

            error_log( 'SCSS Compiler Error:' . $e->getMessage() );

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

        }


     }



     public function write_css_variables( $theme_mods ) {

        //Colors
        $theme_colors = $theme_mods['theme_colors'];
        
        $scss_path = \get_stylesheet_directory() . '/assets/src/css/_theme-colors-auto.scss';


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
     *  Write the SCSS 
     * 
     *  colors
     *  variables
     */



    public function after_theme_mod_update( $old_value, $value, $option ) {

        error_log( json_encode( $value ) );

        //$theme_defaults = $this->get_theme_defaults();
       
        $theme_mods = $this->get_merged_theme_mods( $value );

        //error_log( json_encode( $theme_mods ) );

        $this->write_css_variables( $theme_mods );

        $this->compile_scss();

        return;

    }






    public function merge_theme_colors( $defaults, $theme ) {


        //Defaults are set up like [ 'slug' => '', 'name' => '', 'hex' => '' ]
        //Theme are set up like [ 'slug' => 'color' ]
        //We'll make it like the theme, so that way we can output our SCSS

        $merged_colors = [];

        foreach( $defaults as $default ) {

            if ( array_key_exists( $default->slug, $theme ) ) {

               $merged_colors[ $default->slug ] = $theme[ $default->slug ];

            } else {

                $merged_colors[ $default->slug ] = $default->hex;
            }


        }


        return $merged_colors;


    }








    public function write_css_vars() {

            if ( true) { //} is_customize_preview() ) {
                
                //wp_add_inline_style( 'bric', bric_write_css_vars() );
                //echo bric_write_css_vars();
                echo $this->get_vars_stylesheet();
        
            }
        
    }



    public function get_theme_mods_preview() {

        $preview_theme_mods = [];

        $defaults = $this->get_theme_defaults();


      //  var_dump( $defaults );


        foreach( $defaults->sections as $section ) {

            $section_id = $section->id;

            $settings = $defaults->$section_id;

            foreach ( $settings as $setting ) {

                $preview_theme_mods[ sprintf( '%s__%s', $section_id, $setting->prop ) ] = $this->get_theme_mod( $section_id, $setting->prop );

            }

        }

    

        return $preview_theme_mods;

    }




    public function get_vars_stylesheet() {

/*
        $theme_mods = $this->get_merged_theme_mods( $this->get_theme_mods_preview() );

        var_dump( $theme_mods );

        $this->write_css_variables( $theme_mods );

        
        

        $compiler = $this->scss_init();

        $css = $compiler->compileString( file_get_contents( get_stylesheet_directory() . '/assets/src/css/bric-customizer-vars.scss' ) );

        return '<style id="bric-css-vars">' . $css->getCss() . '</style>';
        



        return;
*/


        ob_start();
            
        $scss_prefix = $this->get_theme_defaults()->scss->prefix;


        
            ?>
<style id="bric-css-vars">
:root{
<?php

            //Get the theme colors
            $colors = $this->get_theme_default_colors();

            foreach ( $colors as $color ) {

                $value = $this->get_theme_mod( 'theme_colors', $color->prop );


                printf ( "--%s%s: %s;\r\n", $scss_prefix, $color->prop, $value );
                printf ( "--%s%s-rgb: %s;\r\n", $scss_prefix, $color->prop, hex2rgb( $value ) );

            }


            //Do fonts
           //$fonts = $this->get_theme_defaults()->fonts;
           //$body_els =  $this->get_theme_defaults()->body;

            //$fonts = array_merge( $fonts, $body_els );

            //var_dump( $fonts );

            $defaults = $this->get_theme_defaults();

            //var_dump( $defaults );

            
            foreach( $defaults->sections as $section ) {

                if ( $section->id == 'theme_colors' ) {
                    continue;
                } 

                $section_id = $section->id;

                foreach( $defaults->$section_id as $setting ) {

                    //var_dump( $setting );

                    $value = maybe_json_decode( $this->get_theme_mod( $section_id, $setting->prop ) );


                    //var_dump( $value );


                    if ( isset( $setting->css_var ) ) {

                        if ( $setting->css_var === false ) {
                            
                            //skip it
                            continue;

                        }

                        $css_variable = $setting->css_var;
    
                    } else {
    
                        $css_variable = str_replace( '_', '-', $setting->prop );
                    }
                               
                    
                   
    
    
    
                    if ( empty( $value ) || $value == 'null' ) {

                        //var_dump( $section_id . '__' . $setting->prop );
                                               
                        continue;
    
                    } elseif ( is_object( $value ) ) { //it's a font family
    
                        printf ( "--%s%s: \"%s\", %s;\r\n", $scss_prefix, $css_variable, $value->font, $value->category );
                    
                    } elseif ( is_numeric( $value ) ) {

                            $converted_value = $value;

                            //var_dump( $setting );

                            if (  isset( $setting->units ) && $setting->units == 'px' ) {

                                $converted_value = intval( $value ) / 16;

                                printf ( "--%s%s: %srem;\r\n", $scss_prefix, $css_variable, $converted_value );
                           
                            } else {

                                printf ( "--%s%s: %s;\r\n", $scss_prefix, $css_variable, $value );
                            }
    
                           
    
                    } elseif ( isset( $setting->choices ) && is_array( $setting->choices) ) {

                        //Assume the value is a variable
                        printf ( "--%s%s: var( --%s%s );\r\n", $scss_prefix, $css_variable, $scss_prefix, $value );
                    
                    
                    } else {
                        
                        printf ( "--%s%s: %s;\r\n", $scss_prefix, $css_variable, $value );

                        
                    }
    
                       
                    

                }

            }

            /*

            foreach( $fonts as $font ) {

                if ( isset( $font->css_var ) ) {

                    $css_variable = $font->css_var;

                } else {

                    $css_variable = str_replace( '_', '-', $font->prop );
                }
                           
                
                $value = maybe_json_decode( $this->get_theme_mod( 'fonts', $font->prop ) );



                if ( empty( $value ) ) {
                    
                    continue;

                } elseif ( is_object( $value ) ) { //it's a font family

                    printf ( '--%s%s: "%s", %s;', $scss_prefix, $css_variable, $value->font, $value->category );
                
                } else {

                    //Convert the value into rems 
                    if ( is_int( $value ) ) {

                        $value_in_rem = $value / 16;

                        printf ( '--%s%s: %srem;', $scss_prefix, $css_variable, $value_in_rem );

                    } else {
                        
                        printf ( '--%s%s: %s;', $scss_prefix, $css_variable, $value );

                        
                    }

                   
                }
            }

            //Do body
            $body_rules = $this->get_theme_defaults()->body;

            //        color: var( --#{$variable-prefix}link-hover-color );
        //text-decoration: var( --#{$variable-prefix}link-hover-decoration );

            foreach( $body_rules as $body_rule ) {


            }
*/
            ?>
        }
        </style>
            <?php
        
            $styles = ob_get_clean();
        
            return $styles;

            
            



    }






    public function add_google_fonts_to_customizer( $wp_customize ) {


            $wp_customize->add_setting( 'google_font_base', [  
                'default' => '{"font":"Open Sans","regularweight":"regular","italicweight":"italic","boldweight":"700","category":"sans-serif"}',
                'sanitize_callback' => 'skyrocket_google_font_sanitization',
                'type' => 'theme_mod'
            ]);


            
            
            $wp_customize->add_control( new \Skyrocket_Google_Font_Select_Custom_Control( $wp_customize, 'google_font_base',
                [
                    'label' => __( 'Google Font Control' ),
                    'description' => esc_html__( 'Sample custom control description' ),
                    'section' => 'body',
                    'input_attrs' => array(
                        'font_count' => 'all',
                        'orderby' => 'alpha',
                    ),
                ]
            ) );



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