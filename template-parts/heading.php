<?php
/**
 *	Picks the header template file
 *
 *
 *
 */

if ( has_shortcode( get_the_content(), 'page_title' ) ) {
	//Don't get a template for heading.
}
elseif ( has_post_thumbnail() ) {
	get_template_part( 'template-parts/heading', 'header-image' );
}
else {
	get_template_part( 'template-parts/heading', 'basic' );
}