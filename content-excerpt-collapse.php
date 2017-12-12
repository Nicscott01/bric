<?php
/**
 *		Basic Content Template
 *
 *
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('excerpt'); ?>>
	<div class="inner-content">
		<h1 class="entry-title">
			<a data-toggle="collapse" href="#collapse-<?php the_ID(); ?>" aria-expanded="false" aria-controls="collapse-<?php the_ID(); ?>"><?php the_title(); ?></a>

		</h1>
		<div class="collapse" id="collapse-<?php the_ID(); ?>">
			<div class="entry-content">
				<?php the_excerpt(); ?>
			</div>
			<div class="read-more">
				<a class="arrow" href="<?php the_permalink(); ?>">Read More</a>
			</div>
		</div>
	</div>
	<div class="background-image">
		<?php
			
			the_post_thumbnail( 'full' );
		?>
	</div>
</article>