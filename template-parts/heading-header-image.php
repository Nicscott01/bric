<?php
/**
 *	Template part for Article Header with Header Image
 *
 */

//Get the option for conforming the 

 $extra_header_class = get_post_type() == 'post' ? '' : '';

?>
<header <?php article_header_class( ['page-header', 'row', $extra_header_class ] ); ?>>
	<div class="col-12">
	<h1 class="entry-title container-xxl <?php echo entry_title_class(); ?> text-white"><?php 

		if ( is_archive() ) {

			the_archive_title(  );

		} else {

			the_title(); 
		
		}
	
	?></h1>
	<div class="header-image-wrapper p-0">
	<?php

$img_overlay_color = bric_get_theme_mod( 'body', 'page_header_background_image_overlay');

if ( $img_overlay_color != 'none' ) {

	$img_overlay_opacity = bric_get_theme_mod( 'body', 'page_header_background_overlay_opacity');

	?>
	<div class="overlay bg-<?php echo $img_overlay_color; ?> position-absolute w-100 h-100" style="--bric-bg-opacity: <?php echo $img_overlay_opacity / 100; ?>; z-index:0;">

	</div>
	<?php

}

	?>
		<div class="header-image ratio ratio-9x2 bg-<?php echo is_search() ? 'light' : 'primary'; ?>" style="z-index:-1;">
			<?php 

	
			//Check if we're on a post, and load the category featured image; fallback on blog featured image
			if ( is_singular( 'post' ) ) {

			

				//Get the Post Categories
				$has_featured_image = false; //set flag
				$terms = wp_get_post_terms( get_the_id(), 'category' );

				if ( !empty( $terms ) ) {

					//Stick with the first one in the list if it has a featured image
					foreach ( $terms as $term ) {

						//Get the term featued image field
						$featured_image = get_field( 'featured_image', $term->taxonomy . '_' . $term->term_id );

						if ( !empty( $featured_image ) ) {

							echo wp_get_attachment_image( $featured_image['id'], 'full', false, [] );

							$has_featured_image = true;

							continue;

						}

					}



				} 
				
				if( !$has_featured_image || empty( $terms ) ) { 

					//Now we'll look to the page for posts
					$page_for_posts = get_option( 'page_for_posts' );

					//var_dump( $page_for_posts );

					$pfp_image = get_the_post_thumbnail( $page_for_posts, 'full', [] );

					if ( !empty( $pfp_image ) ) {

						echo $pfp_image;

						$has_featured_image = true;

					}

				
				} 	


			} elseif ( is_archive() ) {

				//$post_type = get_post_type();
			
				$queried_obj = get_queried_object();

				$fi = false;			

				if ( isset( $queried_obj->taxonomy ) ) {
			
					//Maybe get the featuerd image from a taxonomy term
					$fi = get_field( 'featured_image', $queried_obj->taxonomy . '_' . $queried_obj->term_id );
					


					//Fall back on the object's landing page
					if ( empty( $fi ) ) {
						
						$queried_taxonomy = get_taxonomy( $queried_obj->taxonomy );

						if ( isset( $queried_taxonomy->object_type ) ) {

							//Post type for quereid object
							$queried_pt = $queried_taxonomy->object_type;

							//var_dump( $queried_pt );

							$post_types = get_post_types([
								'public' => true
							], 'objects');

							//var_dump( $post_types );

							//Take the first of the queried post types and see if we have a slug for a landing page
							$maybe_slug = isset( $post_types[$queried_pt[0]]->rewrite['slug'] ) ? $post_types[$queried_pt[0]]->rewrite['slug'] : $post_types[$queried_pt[0]]->name;


							//maybe get the landing page for this 
							$maybe_landing_page = get_page_by_path( $maybe_slug );

							if ( !empty( $maybe_landing_page ) ) {

								$landing_page_thumbnail = get_the_post_thumbnail( $maybe_landing_page, 'full' );						

							}

						}

					}
				} 

				if ( !empty( $fi ) ) {
					
					echo wp_get_attachment_image( $fi['id'], 'full', false, [] );

				} elseif ( isset( $landing_page_thumbnail ) ) {

					echo $landing_page_thumbnail;

				} else { //Fallback on the page for posts

					echo wp_get_attachment_image( get_option( 'page_for_posts'), 'full', false, [] );

				}	
			
			
			
			} elseif ( !is_404() ) {
				
				the_post_thumbnail( 'full' ); 

			}
			?>
		</div>
	</div>
	</div>
</header>