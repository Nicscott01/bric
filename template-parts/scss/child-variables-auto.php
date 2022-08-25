<?php
/**
 *  Template for Auto-Generated SCSS variables
 *  
 *  This will write both the inline stylesheet which is used
 *  mainly in the customizer to "fake" changes (without re-compiling the scss)
 *  and for doing the scss file writing. We kept it together
 *  as to not repeat ourselves.
 * 
 *  This file is included in the Customizer.class.php so it
 *  has access to the class plus maybe the varibales
 *  that are passed from the hook it 
 * 
 * 
 */

 $css_lines = [];

 $write_file = isset( $write_file ) ? $write_file : false;
 $write_inline = isset( $write_inline ) ? $write_inline : false;

//If they're both true, it can't be.
 if ( $write_file && $write_inline ) {
    $write_file = false;
 }

 //Somebody forgot to set a flag.
 if ( !$write_file && !$write_inline ) {
    return;
 }


 //We're coming from the save customizer action and want to pass the mods values along
 //and merge theme with the defaults, that way we 
 // 1) don't make extra database calls and
 // 2) we print out the default values in our SCSS which would save us a step of defining them in a .scss file AND writing the theme-defaults.json file
 if ( isset( $theme_mods ) ) {

    $theme_mods = $this->get_merged_theme_mods( $theme_mods );
 
} 
/*    
ob_start();

var_dump( $theme_mods );

$log = ob_get_clean();
 error_log( $log );
*/
 $theme_defaults = $this->get_theme_defaults();

 $scss_prefix = $theme_defaults->scss->prefix;










 if ( $write_file ) {
 
    printf( "//Auto-generated SCSS variables for theme %s at %s.\r\n", get_stylesheet(), date( "Y-m-d H:i:s" ) );

 }


foreach( $theme_defaults->sections as $theme_default_section ) {


    //set the current section id
    $section_id = $theme_default_section->id;




    //Skip stuff
    $skip = [
        'theme_colors'
    ];


    if ( in_array( $theme_default_section->id, $skip ) ) {
        
        continue;

    }


    $inline_css = [];

    if ( true ) { //} $theme_default_section->scss == 'passthrough' ) {

        //Write out these values
        //$settings = $theme_mods[ $section->id ];

        foreach ( $theme_defaults->$section_id as $setting ) {

/*
            ob_start();

            var_dump($theme_mods[$section_id][$setting->prop]);

            $log = ob_get_clean();
            error_log($log);
        
*/

            if ( $write_inline && isset( $setting->css_var ) && !empty( $setting->css_var ) ) {

                $css_var = $setting->css_var;

            } elseif( $write_file && isset ( $setting->scss_var ) && !empty( $setting->scss_var ) ) {

                $css_var = $setting->scss_var;

            } else {
                
                $css_var = str_replace( '_', '-', $setting->prop ); //dashes, no underscores
                
            }

          
            if ( $write_file ) {

                $val = str_replace( '_', '-', $theme_mods[ $section_id ][ $setting->prop ] );

            }

            if ( $write_inline ) {

                $val = str_replace( '_', '-', bric_get_theme_mod( $section_id, $setting->prop ) );

               

            }

            //All the reasons to skip this for file
            if ( $val == null 
            || $val == 'null' 
            || ( isset( $setting->css_var ) && $setting->css_var == false ) 
            ) {
                continue;
            }

         


            switch( $setting->type ) {

                case "google_font" :

                    $val = maybe_json_decode( $val );

                    $val = sprintf( '"%s", %s', $val->font, $val->category ); //"Montserrat", sans-serif

                    $expression = "$%s: %s;";

                    $expression_inline = "--%s%s: %s;";

                    break;



                case "number" :

                    $units = isset( $setting->units ) ? $setting->units : null;

                    if ( $units == 'px' && $val >= 10 ) {
                        
                        $val = $val/16 . 'rem';
                    
                    } elseif( !empty( $units ) ) {

                        $val = $val . $units;
                    
                    } 


                    $expression = "$%s: %s;";
                    $expression_inline = "--%s%s: %s;";
                  
                    

                    break;

                case "select" :
                case "choice" :

                    //Overrides

                    if ( $write_inline && $setting->prop == 'headings_font_family' ) {

                        //var_dump( $setting );
                        //var_dump( $val );
                       // var_dump( $theme_defaults->$section_id );

                        $has_override_val = false;

                        foreach( $theme_defaults->$section_id as $override_section ) {
                            
                            //var_dump( $override_section );

                            if ( $override_section->prop == str_replace( '-', '_', $val ) ) {
                                $val = $override_section->css_var;

                               

                                $has_override_val = true;
                            }

                            if ( $has_override_val ) {
                                break;
                            }
                        }

                    
                    }


                    if ( isset( $setting->facevalue ) && $setting->facevalue ) {

                        $expression = "$%s: %s;";
                        $expression_inline =  "--%s%s: %s;";

                    } else {

                        //Assume it references another variable
                        $expression = "$%s: $%s;";

                        if ( $write_inline ) {
                        
                            /**
                             *  Override our value & expression if we're 
                             *  trying to reference another real value
                             * 
                             * 
                             */
                            if ( isset( $setting->find_val ) && !empty( $setting->find_val ) ) {


                                //Get our actual value
                                $theme_colors = [
                                    'primary',
                                    'secondary',
                                    'light',
                                    'dark',
                                    'white',
                                    'black'
                                ];


                                if ( in_array( $val, $theme_colors ) ) {

                                    $actual_value = bric_get_theme_mod( 'theme_colors', $val );
                                
                                } else {

                                    //This probably won't work because we don't know the section it is in, so we might have to write some search routine.
                                    $actual_value = bric_get_theme_mod( $val );

                                } 

                                $val = $actual_value;

                                $expression_inline = "--%s%s: %s;";


                            } else {
                            
                                $val = sprintf( 'var( --%s%s )', $scss_prefix, $val );
                                $expression_inline = "--%s%s: --%s;";
                            }
                            

                        }

                       
    

                       

                    }
    
                    break;

                
                
                default :

                    $expression = "$%s: %s;"; 

                    $expression_inline = "--%s%s: %s;";

                
            }





            if ( $write_file ) {
                
                $css_lines[] = sprintf( $expression, $css_var,  $val );

            } elseif ( $write_inline ) {

                $css_lines[] = sprintf( $expression_inline, $scss_prefix, $css_var, $val );

            }
           

        }

    }





}


echo implode( "\r\n", $css_lines );