<?php
/**
 *		Integrations w/ various plugins we use a lot
 *
 */
	
class BricIntegrations {



	public function __construct() {

		/**
		 *		Integrate GA w/ Facet WP
		 *
		 *		Only load the GA JS on FacetWP pages
		 *
		 */

		add_action( 'wp_footer', [ $this, 'facetwp_ga'], 30 );


	}




	/**
	 *		JS if FacetWP is turned on
	 *		Records facet pages in GA
	 *
	 */


	public function facetwp_ga() {


		if ( function_exists( 'FWP' ) && FWP()->display->load_assets ) {

?>
<script>
(function($) {
$(document).on('facetwp-loaded', function() {
	if (FWP.loaded && (typeof ga == 'function') ) {
		ga('send', 'pageview', window.location.pathname + window.location.search);
	}
});
})(jQuery);
</script>
<?php

		}	

	}




}	

new BricIntegrations();
