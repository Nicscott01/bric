<?php
/**
 *  Lower Footer Block template
 * 
 * 
 * 
 */

 //var_dump( $block['textColor'] );


$copyright_text = get_copyright_text( bric_get_theme_mod( 'lower_footer', 'copyright_year' ) );
$copyright_holder = bric_get_theme_mod( 'lower_footer', 'copyright_holder' );

if ( empty( $copyright_holder ) ) {

    $copyright_holder = get_option( 'blogname' );
}


?>
<p class="copyright block <?php echo get_block_classes( $block ); ?>"><?php echo $copyright_text ?>, <?php echo $copyright_holder?></p>
