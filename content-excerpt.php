<?php
/**
 *		Basic Content Template
 *
 *
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'excerpt col-12 col-lg-6 mb-5 d-flex flex-column align-content-start justify-content-start'); ?>>
	<div class="inner-content order-1">
		<h1 class="entry-title h3">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h1>
		<p class="date"><?php the_date(); ?></p>
		<div class="entry-content">
			<?php the_excerpt(); ?>
		</div>
		<div class="read-more mb-3">
			<a class="fw-bold" href="<?php the_permalink(); ?>">Read More</a>
		</div>
	</div>
	<?php if ( has_post_thumbnail() ) : ?>
	<div class="featured-image mb-4 order-0">
		<a class="img-wrapper d-block" href="<?php the_permalink(); ?>">
			<div class="ratio ratio-4x3 overflow-hidden">
				<?php

				the_post_thumbnail( 'medium_large', [ 'class' => 'img-fit img-cover' ] );
				?>
			</div>
		</a>
	</div>
	
	<?php endif; ?>
</article>