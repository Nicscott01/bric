<?php
/**
 *		Template Part: Footer Basic
 *
 *
 */

global $BricLoop;

?>

<div class="footer-inner">
	<?php 

	ob_start();
	get_sidebar('footer'); 

	$sidebar = ob_get_clean();

	if ( !empty( $sidebar )) {

		echo '<div class="footer-widgets">'.$sidebar.'</div>';

	}

	?>
	<div class="copyright-credits-wrapper">
	<?php echo $BricLoop->get_copyright(); ?>
	<?php echo $BricLoop->get_developer_credits(); ?>
	</div>
</div>