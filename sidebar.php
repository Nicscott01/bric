<?php
/**
 *		Sidebar Template for BRIC
 *
 *
 */

global $BricLoop;

?>
<?php 

if ( is_active_sidebar('posts-sidebar-main') ) { ?>
<div class="sidebar <?php echo $BricLoop->get_content_class('sidebar'); ?>">
	<?php dynamic_sidebar( 'posts-sidebar-main' ); ?>
</div>
<?php }

?>
 