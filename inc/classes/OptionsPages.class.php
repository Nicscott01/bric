<?php





class OptionsPages {
	
	
	function __construct() {
		
		add_action( 'acf/init', array( $this, 'add_options_page') );
		
		//Google maps API Key
		add_filter( 'acf/fields/google_map/api', array( $this, 'google_maps_api_key'), 10, 1 );
		
	}
	
	
	function add_options_page() {
		
		if ( function_exists( 'acf_add_options_page') ) {
			
			acf_add_options_page( array(
				'page_title' => 'Site Info',
				'position' => 50,
				'icon_url' => 'dashicons-location-alt'
			));
			
		}
		
		
	}
	
	
	
	
	function google_maps_api_key( $api ) {
		
		$api['key'] = BRIC_GOOGLE_MAPS_API_KEY; //'AIzaSyDUy-vuqQLK4APwNGoJ2MWDn04nTMzeZJ8';
		
		return $api;
		
	}
	
	
	
}


new OptionsPages();