<?php
/**
 *		Basic Content Template
 *
 *
 */
 
 $extra_classes = [];

 $extra_classes[] = get_post_type() == 'post' ? 'mb-5' : ''; 

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $extra_classes ); ?>>
	<?php 
	if ( get_post_type() == 'post' ) {

		echo '<div class="card mt-0 p-5">';

	}


	do_action( 'bric_before_page_header');
	
	get_template_part( 'template-parts/heading' );

	do_action( 'bric_after_page_header');
	
	
	$extra_entry_content_classes[] = get_post_type() == 'post' ? 'pt-3' : '';

 	?>
	<div class="<?php entry_content_class( $extra_entry_content_classes ); ?>">
		<?php 
		
		if ( is_singular( 'post' ) ) {

			if ( has_post_thumbnail() ) {

				echo '<div class="mb-4 featured-image">';

				the_post_thumbnail( 'full' );

				echo '</div>';

			}

		}


		the_content(); 
		
		?>
	</div>
<?php 
	
	if ( $wp_query->max_num_pages > 1 ) {
?>		
	<div class="pagination">
	<?php wp_link_pages( array(
			'next_or_number' => 'next'
		)); ?>
	</div>
<?php
	}
	
	
	if ( get_post_type() == 'post' ) {
		echo '</div>';
	}

?>
</article>