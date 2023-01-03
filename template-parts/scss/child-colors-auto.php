<?php
/**
 *  Template for Auto-Generated SCSS variables
 * 
 * 
 * 
 */

 //We pass along $theme_colors from Customizer.class.php



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

 if ( $write_file ) {
 
    printf( "//Auto-generated SCSS color variables for theme %s at %s.\r\n", get_stylesheet(), date( "Y-m-d H:i:s" ) );

 }

 if ( $write_inline ) {
   
    $scss_prefix = $this->get_theme_defaults()->scss->prefix;
    
 }

//Get the theme colors
$colors = $this->get_theme_default_colors();

$theme_colors_map = [];

foreach ( $colors as $color ) {


    if ( $write_inline ) {
    
        $value = $this->get_theme_mod( 'theme_colors', $color->prop );
        
        printf ( "--%s%s: %s;\r\n", $scss_prefix, $color->prop, $value );
        printf ( "--%s%s-rgb: %s;\r\n", $scss_prefix, $color->prop, hex2rgb( $value ) );    

    } elseif ( $write_file ) {

        $value = isset( $theme_mods[ 'theme_colors__' . $color->prop ] ) ? $theme_mods[ 'theme_colors__' . $color->prop ] : $color->val;
     
        if ( !empty( $value ) ) {

            printf( "$%s: %s;\r\n", $color->prop, $value );

            $theme_colors_map[$color->prop] = sprintf( '$%s', $color->prop );
        }

    }

    
   

}

return;

/**
 *  This is experimental and might not work here
 * 
 * 
 */
/*
if ( $write_file && !empty( $theme_colors_map )) {

    ?>
$theme-colors: (
    <?php
    foreach( $theme_colors_map as $key => $val ) {
    
        printf('"%s": %s,
', $key, $val );
    }
    ?>
);
    <?php

}

*/


