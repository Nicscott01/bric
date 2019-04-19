<?php
/**
 *		Basic Content Template
 *
 *
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php 
	
	do_action( 'bric_before_page_header');

	
	if ( has_post_thumbnail() ) {
		get_template_part( 'template-parts/heading', 'header-image' );
	}
	else {
		get_template_part( 'template-parts/heading', 'basic' );
	}
	
	do_action( 'bric_after_page_header');
	?>
	
	<div class="<?php entry_content_class(); ?>">
		<?php the_content(); ?>
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
?>
</article>