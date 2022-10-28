<?php
/**
 *  Write our Theme.json colors
 * 
 * 
 */

use function Bric\Customizer;

 //Get the theme.json
 $theme_json_path = get_stylesheet_directory(  ) . '/theme.json';

 if ( !file_exists( $theme_json_path ) ) {

    return;

 }

 $theme_json = file_get_contents( $theme_json_path );

 if ( !empty( $theme_json ) ) {

    $theme_json = json_decode( $theme_json );

 }



//Get the theme colors
$colors = $this->get_theme_default_colors();


$colors_palette = [];

foreach ( $colors as $color ) {

    $value = isset( $theme_mods[ 'theme_colors__' . $color->prop ] ) ? $theme_mods[ 'theme_colors__' . $color->prop ] : $color->val;

    if ( !empty( $value ) ) {

        $colors_palette[] = [
            'name' => ucfirst( $color->prop ),
            'slug' => $color->prop,
            'color' => $value
        ];
    
    }

/*
    if ( $write_inline ) {
    
        $value = $this->get_theme_mod( 'theme_colors', $color->prop );
        
        printf ( "--%s%s: %s;\r\n", $scss_prefix, $color->prop, $value );
        printf ( "--%s%s-rgb: %s;\r\n", $scss_prefix, $color->prop, hex2rgb( $value ) );    

    } elseif ( $write_file ) {

        $value = isset( $theme_mods[ 'theme_colors__' . $color->prop ] ) ? $theme_mods[ 'theme_colors__' . $color->prop ] : $color->val;
     
        if ( !empty( $value ) ) {

            printf( "$%s: %s;\r\n", $color->prop, $value );

        }

    }
*/
    
   

}



//Put the color array into the palette
if ( isset( $theme_json->settings ) ) {

    $theme_json->settings->color = [
        'custom' => false,
        'defaultPalette' => false,
        'customGradient' => false,
        'gradients' => [],
        'defaultGradients' => false,
        'palette' => $colors_palette
    ];


} else {

    $theme_json->settings = [
        'color' => [
            'custom' => false,
            'defaultPalette' => false,
            'customGradient' => false,
            'gradients' => [],
            'defaultGradients' => false,
            'palette' => $colors_palette
        ]
    ];

}


echo json_encode( $theme_json );