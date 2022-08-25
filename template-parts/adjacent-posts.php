<?php
/**
 * Template for Post Pagination / Keep Reading / Other Posts
 * 
 * 
 * 
 */


/*
<div class="keep-reading col-12 mb-3">
	<h3>Keep Reading</h3>
	<div class="d-flex flex-row justify-content-between">
		<div class="nav-previous has-btn-primary has-laquo"><?php previous_post_link('%link'); ?></div>
		<div class="nav-next has-btn-primary has-raquo"><?php next_post_link('%link'); ?></div>
	</div>
</div>
*/

global $post;


//Get some other posts besides the one we're on
$other_posts = get_posts([
	'post_type' => 'post',
	'posts_per_page' => 3,
	'exclude' => $post->ID,
] );

if( empty( $other_posts ) ) {
	return;
}


//Filter the class of the excerpt
add_filter( 'post_class', function($classes) {

	if ( get_post_type() == 'post' ) {

		$classes = str_replace( 'col-12', 'col', $classes );

	}


	return $classes;

});


?>
<div class="recent-posts other-posts col-12 mb-5">
	<div class="row">
	<?php
	foreach( $other_posts as $post ) {
		setup_postdata( $post );

		get_template_part( 'content-excerpt' );



	}

	wp_reset_postdata(  );
	?>
	</div>
</div>	
