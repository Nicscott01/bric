<?php
/**
 *	Picks the header template file
 *
 *
 *
 */

if ( has_post_thumbnail() ) {
	get_template_part( 'template-parts/heading', 'header-image' );
}
else {
	get_template_part( 'template-parts/heading', 'basic' );
}