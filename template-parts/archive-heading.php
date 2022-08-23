<?php
/**
 *	Picks the header template file on Home/Archive pages
 *
 *
 *
 */

if ( has_post_thumbnail() ) {

	get_template_part( 'template-parts/archive-heading', 'header-image' );

} else {

	get_template_part( 'template-parts/archive-heading', 'basic' );

}

do_action( 'bric_after_page_header' );