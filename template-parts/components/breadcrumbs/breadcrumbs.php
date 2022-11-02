<?php
/**
 *  Template for displaying breadcrumbs
 *  
 * 
 */		

$classes = get_theme_mod( 'bric_bc_container', 'container-xxl' );

$open_tag = sprintf( '<p id="breadcrumbs" class="mt-3 small %s">', $classes );
$close_tag = "</p>";


echo yoast_breadcrumb( $open_tag, $close_tag, true );

