<?php
/**
 *  Template for Auto-Generated SCSS variables
 * 
 * 
 * 
 */

 //We pass along $theme_colors from Customizer.class.php

 printf( "//Auto-generated SCSS color variables for theme %s \r\n", get_stylesheet() );

if ( !empty( $theme_colors ) && is_array( $theme_colors ) ) {

    foreach ( $theme_colors as $slug => $color ) {

        $scss[] = sprintf( '$%s: %s;', $slug, $color );

    }

    $scss_contents = implode( "\r\n", $scss );

    echo $scss_contents;
}