<?php
/**
 *	Picks the header template file
 *
 *
 *
 */


do_action( 'bric_before_page_header' );


if ( has_shortcode( get_the_content(), 'page_title' ) ||  has_block( 'acf/hero-header' ) ) {
	//Don't get a template for heading.
}
else { //if ( has_post_thumbnail() || is_singular( 'post' ) ) {

	get_template_part( 'template-parts/heading', 'header-image' );
}

/*
else {
	get_template_part( 'template-parts/heading', 'basic' );
}*/

do_action( 'bric_after_page_header' );