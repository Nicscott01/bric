<?php
/**
 *		FOOTER TEMPLATE for BRIC
 *
 *
 */

global $SiteInfo;

?>
<footer class="site-footer" role="contentinfo">
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