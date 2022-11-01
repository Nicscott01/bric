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
	
	get_template_part( 'template-parts/heading' );
	
	

	if ( is_singular() && !is_page() ) {

		?>
		<div class="container-xxl">
			<div class="row">
				<div class="col-12 col-md-8">
		<?php
	}


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

	if ( is_singular() && !is_page() ) {

		?>
		</div>
		<div class="col-12 col-md-4">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>
		<?php
	}
	

	
	if ( $wp_query->max_num_pages > 1 ) {
?>		
	<div class="pagination">
	<?php wp_link_pages( array(
			'next_or_number' => 'next'
		)); ?>
	</div>
<?php
	}
	

?>
</article>