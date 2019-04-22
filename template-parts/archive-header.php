<?php
/**
 *		Template to render Header of Archive Page
 *
 *		pulls content from page with same name
 
 		
		if has_landing_page or get_home 
 
 
 */


if ( has_landing_page() || is_home() ) {
	
	$page = get_landing_page();
	
	get_template_part( 'template-parts/archive', 'heading' );
	
}
		/*
		global $post;
	
		global $LSTABreadcrumbs;
		
		//Make sure we're on the landing page and we don't have a term "archive" loaded (not really a term archive, we're using FacetWP filters to fake the term archives)
		$child = $LSTABreadcrumbs->get_deepest_family_member();
				
		if ( !empty( $child ) ) {
			
			$page->post_title = $child->name;
		}
		
		$post = $page;
		setup_postdata( $post );		
		//Get the featured image
		
		if ( has_post_thumbnail() ) {
			
			get_template_part( 'template-parts/heading', 'header-image' );
			
		}
		else {
			
			get_template_part( 'template-parts/heading', 'basic' );
		}
		
		
		wp_reset_postdata();
		
		
		*/


do_action( 'bric_after_page_header' );

?>
