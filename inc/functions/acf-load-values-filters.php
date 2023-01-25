<?php
/**
 *  Pre-load some ACF fields
 * 
 * 
 * 
 */

use function Bric\Customizer;

add_filter( 'acf/load_field', function( $field ) {


    if ( strpos( $field['name'], 'theme_color' ) ) {

        $field['choices']['default'] = 'Default';

        $colors = Customizer()->get_theme_default_colors();

        //error_log( json_encode( $colors ) );

        if ( !empty( $colors ) && is_array( $colors ) ) {

            foreach( $colors as $color ) {

                $field['choices'][$color->prop] = $color->label;

            }

        }

        $field['choices']['transparent'] = 'Transparent';


    }


    return $field;

}, 10, 1 );