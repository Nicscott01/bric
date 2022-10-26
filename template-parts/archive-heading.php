<?php
/**
 *	Picks the header template file on Home/Archive pages
 *
 *
 *
 */



global $post;

if ( has_landing_page() || is_home() ) {
	
	$page = get_landing_page();

	$post = $page;	
		

}



if ( is_archive() || is_home() || is_tax() ) {
	
	//v_dump( $post );
	setup_postdata( $post );

	
		if ( has_post_thumbnail() ) {

			get_template_part( 'template-parts/archive-heading', 'header-image' );

		} else {

			get_template_part( 'template-parts/archive-heading', 'basic' );

		}

	wp_reset_postdata();

	
}




do_action( 'bric_after_page_header' );