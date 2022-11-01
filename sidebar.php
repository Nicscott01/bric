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
<div class="sidebar sticky-top">
	<?php dynamic_sidebar( 'posts-sidebar-main' ); ?>
</div>
<?php }

?>
 