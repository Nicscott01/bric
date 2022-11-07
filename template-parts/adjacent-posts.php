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



//Get the title of the page for posts and use that for this heading
$page_for_posts = get_option( 'page_for_posts' );

if ( !empty( $page_for_posts ) ) {

	$pfp = get_post( $page_for_posts );

}


?>
<div class="recent-posts other-posts container-xxl col-12 mb-5">
	<h3 class="text-center h2 mb-4" style="color:revert;">Additional <?php echo $pfp->post_title; ?></h3>
	<div class="row">
	<?php
	foreach( $other_posts as $post ) {
		setup_postdata( $post );

		get_template_part( 'content-excerpt-adjacent' );

	}

	wp_reset_postdata(  );

	?>
	</div>
</div>	
