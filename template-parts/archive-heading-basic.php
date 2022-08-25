<?php
/**
 * 	Display the title above the home/archive pages
 * 
 * 
 */

add_filter( 'get_the_archive_title', function( $title, $original_title, $prefix ) {

	return BricFilters()->get_the_archive_title( $title, $original_title, $prefix );
	
}, 10, 3 );


?>
<div class="archive-header col-12">
	<h1 class="entry-title">
		<?php the_archive_title(); ?>
	</h1>
</div>