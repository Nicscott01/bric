<?php
/**
 *  Template for Auto-Generated SCSS variables
 * 
 * 
 * 
 */

 $scss_vars = [];
 
 $theme_defaults = $this->get_theme_defaults();



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



    if ( $theme_default_section->scss == 'passthrough' ) {

        //Write out these values
        //$settings = $theme_mods[ $section->id ];

        foreach ( $theme_defaults->$section_id as $setting ) {

            $css_var = str_replace( '_', '-', $setting->prop ); //dashes, no underscores
            $val = $theme_mods[ $section_id ][ $setting->prop ];

            if ( $val == null || $val == 'null' ) {
                continue;
            }

            switch( $setting->type ) {

                case "google_font" :

                    $val = maybe_json_decode( $val );

                    $val = sprintf( '"%s", %s', $val->font, $val->category ); //"Montserrat", sans-serif

                    $expression = "$%s: %s;";

                    break;



                case "number" :

                    $units = isset( $setting->units ) ? $setting->units : null;

                    if ( $units == 'px' ) {
                        
                        $val = $val/16;
                        $expression = "$%s: %srem;";
                    
                    } else {

                        $expression = "$%s: %s;";

                    } 



                    

                    break;

                case "select" :
                case "choice" :

                    if ( isset( $setting->facevalue ) && $setting->facevalue ) {

                        $expression = "$%s: %s;";

                    } else {
    
                        //Assume it references another variable
                        $expression = "$%s: $%s;";
    
                    }
    
                    break;

                
                
                default :

                    $expression = "$%s: %s;"; 

                
            }

            $scss_vars[] = sprintf( $expression, $css_var, $val );

        }

    }





}


echo implode( "\r\n", $scss_vars );