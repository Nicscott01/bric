<?php
/**
 *		Template Part: Footer Basic
 *
 *
 */

global $BricLoop;

?>

<div class="footer-inner bg-<?php echo bric_get_theme_mod( 'footer', 'background_color' ); ?> text-<?php  echo bric_get_theme_mod( 'footer', 'text_color' ); ?>">
	<?php 

	ob_start();
	get_sidebar('footer'); 

	$sidebar = ob_get_clean();

	if ( !empty( $sidebar )) {

		echo '<div class="footer-widgets">'.$sidebar.'</div>';

	}

	//get_template_part( 'template-parts/footer-lower' );

	?>	
</div>