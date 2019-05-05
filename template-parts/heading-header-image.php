<?php
/**
 *	Template part for Article Header with Header Image
 *
 */

//Get the option for conforming the 

?>
<header <?php article_header_class(['page-header', 'has-header-image']); ?>>
	<h1 class="entry-title <?php echo entry_title_class(); ?>"><?php the_title(); ?></h1>
	<div class="header-image-wrapper">		
		<?php
		/**
		 *		Look at header image options
		 *
		 *		-fixed background image
		 *
		 */
		$options = get_field( 'featured_image_options' );
				
		if ( !empty( $options ) && in_array( 'fixed_background_image', $options ) ) {
			//Get some data about the image
			$header_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			
			//var_dump( $header_image );
			
			$min_height = '50vh';
			
			//Set a baseline min height
			if ( $header_image[2] ) {
				
				$min_height = $header_image[2] * .8;
				
				$min_height = (string) $min_height .'px';
			}
			
			//Do the parallax markup
			?>
		<div class="header-image parallax-window" data-parallax="scroll" data-image-src="<?php the_post_thumbnail_url( 'full' );?>" style="min-height: <?php echo $min_height; ?>; background:transparent;">
			<?php
		}
		else {
			?>
		<div class="header-image">
			<?php the_post_thumbnail( 'full' ); ?>
		</div>
			<?php
		}
		?>		
	</div>
</header>