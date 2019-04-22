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
	
	get_template_part( 'template-parts/heading' );
	
	do_action( 'bric_after_page_header');
	?>
	
	<div class="entry-content">
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