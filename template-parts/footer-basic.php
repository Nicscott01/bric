<?php
/**
 *		Template Part: Footer Basic
 *
 *
 */

global $BricLoop;

?>

<div class="footer-inner">
	<div class="footer-widgets">	
		<?php get_sidebar('footer'); ?>
	</div>
	<div class="copyright-credits-wrapper">
	<?php echo $BricLoop->get_copyright(); ?>
	<?php echo $BricLoop->get_developer_credits(); ?>
	</div>
</div>