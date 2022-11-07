<?php
/**
 *		Basic Content Template
 *
 *
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'excerpt-adjacent col-12 col-lg-4 mb-5'); ?>>
	<div class="row">
	<div class="inner-content col-12 col-sm-7 col-lg-12 order-1">
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
	<div class="featured-image col-12 col-sm-5 col-lg-12 order-0 mb-3">
		<a class="img-wrapper d-block" href="<?php the_permalink(); ?>">
		<?php

		the_post_thumbnail( 'medium_large' );
		?>
		</a>
	</div>
	
	<?php endif; ?>
	</div>
</article>