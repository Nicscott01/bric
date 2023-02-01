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

$write_file = isset($write_file) ? $write_file : false;
$write_inline = isset($write_inline) ? $write_inline : false;

//If they're both true, it can't be.
if ($write_file && $write_inline) {
    $write_file = false;
}

//Somebody forgot to set a flag.
if (!$write_file && !$write_inline) {
    return;
}


//We're coming from the save customizer action and want to pass the mods values along
//and merge theme with the defaults, that way we 
// 1) don't make extra database calls and
// 2) we print out the default values in our SCSS which would save us a step of defining them in a .scss file AND writing the theme-defaults.json file
if (isset($theme_mods)) {

    $theme_mods = $this->get_merged_theme_mods($theme_mods);
}
/*    
ob_start();

var_dump( $theme_mods );

$log = ob_get_clean();
 error_log( $log );
*/
$theme_defaults = $this->get_theme_defaults();

$scss_prefix = $theme_defaults->scss->prefix;










if ($write_file) {

    printf("//Auto-generated SCSS variables for theme %s at %s.\r\n", get_stylesheet(), date("Y-m-d H:i:s"));
}


foreach ($theme_defaults->sections as $theme_default_section) {


    //set the current section id
    $section_id = $theme_default_section->id;




    //Skip stuff
    $skip = [
        'theme_colors'
    ];


    if (in_array($theme_default_section->id, $skip)) {

        continue;
    }


    $inline_css = [];


    //Write out these values
    //$settings = $theme_mods[ $section->id ];

    foreach ($theme_defaults->$section_id as $setting) {

       /* ob_start();
        var_dump( $setting );
        $log = ob_get_clean();
        error_log( $log );
*/



        if ($write_inline && isset($setting->css_var) && !empty($setting->css_var)) {

            $css_var = $setting->css_var;
        } elseif ($write_file && isset($setting->scss_var) && !empty($setting->scss_var)) {

            $css_var = $setting->scss_var;
        } elseif (isset($setting->prop) && is_string($setting->prop)) {

            $css_var = str_replace('_', '-', $setting->prop); //dashes, no underscores

        }




        if ($write_file && 
            is_string( $theme_mods[$section_id][$setting->prop] ) ||
            ( isset( $theme_mods[$section_id][$setting->prop])  && is_numeric( $theme_mods[$section_id][$setting->prop] ) )
            )  {

            $val = str_replace('_', '-', $theme_mods[$section_id][$setting->prop]);
        }

        if ($write_inline && 
            is_string( bric_get_theme_mod($section_id, $setting->prop) ) ||
            is_numeric( bric_get_theme_mod($section_id, $setting->prop) )
            ) {

            $val = str_replace('_', '-', bric_get_theme_mod($section_id, $setting->prop));
        }

        /*ob_start();

        var_dump( $css_var );
        var_dump( $val );
        var_dump( bric_get_theme_mod($section_id, $setting->prop) );

        $log = ob_get_clean();*/
        //error_log($log);



        //All the reasons to skip this for file
        if (
            !isset($val)
            || $val == null
            || $val == 'null'
            || (isset($setting->css_var) && $setting->css_var == false)
        ) {
            continue;
        }




        switch ($setting->type) {

            case "google_font":



                $val = maybe_json_decode($val);
/*
                ob_start();
                var_dump( $val );
                $log = ob_get_clean();
                error_log( $log );
*/

              if (isset($val->font) && $val->font != 'None' ) {

                    $val = sprintf('"%s", %s', $val->font, $val->category); //"Montserrat", sans-serif

                    $expression = "$%s: %s;";

                    $expression_inline = "--%s%s: %s;";

                } else {

                    if ( $val->font == 'None') {

                        $val = 'null';
                    

                    } else {

                        $val = 'inherit';
                    
                    }

                    $expression = "$%s: %s;";

                    $expression_inline = "--%s%s: %s;";
                }


                /**
                 *  Write the font weights based on selections
                 * 
                 */

                $fonts_base = bric_get_theme_mod( 'fonts', 'font_family_base' );
/*
                ob_start();
                var_dump( json_decode( $fonts_base ) );
                $log = ob_get_clean();
                error_log( $log );
  */           

  
                if ( !empty( $fonts_base ) ) {

                    $fonts_base = json_decode( $fonts_base );

                    if ( is_object( $fonts_base ) ) {

                        foreach( $fonts_base as $attr => $_val ) {


                            //error_log( 'before: ' . $_val );

                            if ( strpos( $_val, 'regular' ) === 0 || strpos( $_val, 'italic' ) === 0 ) {

                                $_val = '400';
                            
                            } elseif(  strpos( $_val, 'italic' ) > 0  ) {

                                $_val = str_replace( 'italic', '', $_val );
                            
                            } elseif ( strpos( $_val, 'bold' ) === 0 ) {

                                $_val = '700';
                            }

                            //error_log( 'after: ' . $_val );


                            switch( $attr ) {

                                case 'regularweight' :
                               

                                    if ( $write_file ) {

                                        $css_lines['font_base_normal'] = sprintf( '$font-weight-normal: %s;', $_val );


                                    } elseif ( $write_inline ) {

                                        $css_lines['font_base_normal'] = sprintf('--%sbody-font-weight: %s;', $scss_prefix, $_val);

                                    }


                                    break;


                                case 'italicweight' :

                                    //$css_lines['font_base_'] = sprintf( '$font-weight-normal: %s', $val );

                                    break;


                                case 'boldweight' :


                                    if ( $write_file ) {

                                        $css_lines['font_base_bold'] = sprintf( '$font-weight-bold: %s;', $_val );

                                    } elseif( $write_inline ) {

                                        $css_lines['font_base_bold'] = sprintf('--%sbody-font-weight-bold: %s;', $scss_prefix, $_val);
                                    
                                    }


                                    break;
                            }

                        }

                    }
                    

                }


                break;



            case "number":

                $units = isset($setting->units) ? $setting->units : null;

                if ($units == 'px' && $val >= 10) {

                    $val = $val / 16 . 'rem';
                } elseif (!empty($units)) {

                    $val = $val . $units;
                }


                $expression = "$%s: %s;";
                $expression_inline = "--%s%s: %s;";



                break;

            case "select":
            case "choice":

                //Overrides

                if ($write_inline && $setting->prop == 'headings_font_family') {

                    //var_dump( $setting );
                    //var_dump( $val );
                    // var_dump( $theme_defaults->$section_id );

                    $has_override_val = false;

                    foreach ($theme_defaults->$section_id as $override_section) {

                        //var_dump( $override_section );

                        if ($override_section->prop == str_replace('-', '_', $val)) {
                            $val = $override_section->css_var;



                            $has_override_val = true;
                        }

                        if ($has_override_val) {
                            break;
                        }
                    }
                }


                if (isset($setting->facevalue) && $setting->facevalue) {

                    $expression = "$%s: %s;";
                    $expression_inline =  "--%s%s: %s;";
                } else {

                    //Assume it references another variable
                    $expression = "$%s: $%s;";

                    if ($write_inline) {

                        /**
                         *  Override our value & expression if we're 
                         *  trying to reference another real value
                         * 
                         * 
                         */
                        if (isset($setting->find_val) && !empty($setting->find_val)) {


                            //Get our actual value
                            $theme_colors = [
                                'primary',
                                'secondary',
                                'light',
                                'dark',
                                'white',
                                'black'
                            ];


                            if (in_array($val, $theme_colors)) {

                                $actual_value = bric_get_theme_mod('theme_colors', $val);
                            } else {

                                //This probably won't work because we don't know the section it is in, so we might have to write some search routine.
                                $actual_value = bric_get_theme_mod($val);
                            }

                            $val = $actual_value;

                            $expression_inline = "--%s%s: %s;";
                        } else {

                            $val = sprintf('var( --%s%s )', $scss_prefix, $val);
                            $expression_inline = "--%s%s: --%s;";
                        }
                    }
                }

                break;



            default:

                $expression = "$%s: %s;";

                $expression_inline = "--%s%s: %s;";
        }





        if ($write_file) {

            $css_lines[] = sprintf($expression, $css_var,  $val);
        } elseif ($write_inline) {

            $css_lines[] = sprintf($expression_inline, $scss_prefix, $css_var, $val);
        }
    }

   /* ob_start();
    var_dump( $css_lines );
    $log = ob_get_clean();
    error_log( $log );
*/
}


echo implode("\r\n", $css_lines);
