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
$height = get_field( 'height' );
$width = get_field( 'width' );

$id = isset( $block['anchor'] ) ? $block['anchor'] : $block['id'];
$classes = get_block_classes( $block );

$svg_file = file_get_contents( get_attached_file( $svg['ID'] ) );

$hw_dims = [];

if ( !empty( $height ) ) {

    $hw_dims['h'] = sprintf( 'height:%s;', $height );

} 

if ( !empty( $width ) ) {

    $hw_dims['w'] = sprintf( 'width:%s;', $width );

}

$height_width_style = implode( '', $hw_dims );



if ( !is_admin() && !empty( $svg_file ) ) {

    BricSvgSpriteSheet()->add_svg( $svg_file, $id );

    echo '<div class="svg-block '.$classes.'" style="'. $height_width_style .'">' . BricSvgSpriteSheet()->get_svg_use( $id ) . '</div>';

    //var_dump( BricSvgSpriteSheet()->svgs );


} else {

    echo '<div class="'.$classes.'">' . $svg_file . '</div>';
}
