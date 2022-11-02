<?php
/**
 *		Basic Content Template
 *
 *
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'excerpt col-12 col-lg-6'); ?>>
	<div class="inner-content">
		<h1 class="entry-title h3">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h1>
	
		<div class="entry-content">
			<?php the_excerpt(); ?>
		</div>
		<div class="read-more">
			<a class="fw-bold" href="<?php the_permalink(); ?>">Read More</a>
		</div>
	</div>
	
	<?php if ( has_post_thumbnail() ) : ?>
	<div class="featured-image">
		<a class="img-wrapper d-block" href="<?php the_permalink(); ?>">
		<?php

		the_post_thumbnail( 'medium' );
		?>
		</a>
	</div>
	
	<?php endif; ?>
</article>