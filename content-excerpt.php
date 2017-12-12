<?php
/**
 *		Basic Content Template
 *
 *
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'excerpt'); ?>>
	<div class="inner-content">
		<h1 class="entry-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h1>
	
		<div class="entry-content">
			<?php the_excerpt(); ?>
		</div>
		<div class="read-more">
			<a class="arrow" href="<?php the_permalink(); ?>">Read More</a>
		</div>
	</div>
	
	<?php if ( has_post_thumbnail() ) : ?>
	<div class="featured-image">
		<div class="img-wrapper">
		<?php

		the_post_thumbnail( 'medium' );
		?>
		</div>
	</div>
	
	<?php endif; ?>
</article>