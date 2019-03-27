<?php
/**
 *		FOOTER TEMPLATE for BRIC
 *
 *
 */

global $SiteInfo;

$container_class = ( $SiteInfo->navbar->container ) ? 'container' : 'container-fluid';
//itemscope itemtype="http://schema.org/WPFooter"

?>

<footer class="site-footer <?php echo $container_class; ?>" >
		<?php 
		
			/**
			 *		Bric Header
			 *
			 *		'' - 10
			 */
			
			do_action( 'bric_footer' );
		
		?>
</footer>
<?php

wp_footer();

?>
</body>
</html>