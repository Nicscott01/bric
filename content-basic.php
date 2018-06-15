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
		?>
	<header class="page-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<div class="header-image-wrapper">
			<div class="header-image">
				<?php the_post_thumbnail( 'full' ); ?>
			</div>
		</div>
	</header>
	
		<?php
	}
	else {
		?>
	<h1 class="entry-title">
		<?php the_title(); ?>
	</h1>
		<?php
		
	}
	
	do_action( 'bric_after_page_header');
	?>
	
	<div class="entry-content">
		<?php the_content(); ?>
	</div>
	<div class="pagination">
	<?php wp_link_pages( array(
			'next_or_number' => 'next'
		)); ?>
	</div>
</article>