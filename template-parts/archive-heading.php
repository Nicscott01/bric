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
	
	do_action( 'bric_before_page_header' );
	
	setup_postdata( $post );

		get_template_part( 'template-parts/heading-header-image' );
		
	wp_reset_postdata();

	do_action( 'bric_after_page_header' );

} elseif ( is_search() ) {

	do_action( 'bric_before_page_header' );
	

		get_template_part( 'template-parts/search-header' );
		

	do_action( 'bric_after_page_header' );


} 



