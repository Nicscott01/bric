<?php
/**
 *	Inner Archive Template
 *
 */

?>
<div class="archive-posts-wrapper recent-posts <?php echo BricLoop()->get_content_class( 'main' ); ?> ">
	<div class="row">
		<?php 
		if ( have_posts() ) {
			while ( have_posts() ) {
				
				the_post();
				
				get_template_part( 'content', 'excerpt' );
				
			}
		}
		?>
		
	</div>
</div>
