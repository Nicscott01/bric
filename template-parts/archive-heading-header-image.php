<?php
/**
 * 		Page header for Archive with Image
 * 
 * 
 */
 
 global $post;








//Header Image
//Grab the "landing page" object. This should include any page 
//that has the same slug as the archive
$page = get_landing_page();


if ( empty( $page ) ) {

	$page = $post;

	//If this comes up blank, we will default to the posts page image
	if ( is_tax() || is_category() || is_archive() ) {

		$post_page_id = get_option( 'page_for_posts' );

		$page = get_post( $post_page_id );

	}

} 





?>
<header class="page-header archive-header">
	<div class="container">
		<h1 class="entry-title text-white"><?php the_archive_title(); ?></h1>
	</div>
	<div class="header-image-wrapper">
		<div class="header-image">
			<?php echo get_the_post_thumbnail( $page, 'full' ); ?>
		</div>
	</div>
</header>