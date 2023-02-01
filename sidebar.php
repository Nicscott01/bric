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
<div class="sidebar py-4 py-md-3">
	<?php dynamic_sidebar( 'posts-sidebar-main' ); ?>
</div>
<?php }

?>
 