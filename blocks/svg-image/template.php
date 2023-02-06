<?php
/**
 *  SVG Image Block template
 * 
 * 
 *  
 * 
 * 
 */

$svg = get_field( 'svg' );
$id = isset( $block['anchor'] ) ? $block['anchor'] : $block['id'];


$svg_file = file_get_contents( $svg['url'] );


if ( !is_admin() && !empty( $svg_file ) ) {

    BricSvgSpriteSheet()->add_svg( $svg_file, $id );

    echo BricSvgSpriteSheet()->get_svg_use( $id );

    //var_dump( BricSvgSpriteSheet()->svgs );


} else {

    echo $svg_file; 
}
