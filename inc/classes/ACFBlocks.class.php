<?php


class ACFBlocks {
	
	
	
	public function __construct() {
		

		//add_action( 'init', [ $this, 'init'] );
		add_action( 'init', [ $this, 'register_acf_block_types' ] );
	
	}
	
	
	
	
	public function init() {
		
		// Check if function exists and hook into setup.
		if( function_exists('acf_register_block_type') ) {
				
			add_action('acf/init', [ $this, 'register_acf_block_types' ] );
			
		}
		
		
	}
	
	
	
	
	public function register_acf_block_types() {

		
		// register a testimonial block.
		acf_register_block_type( array(
			'name'              => 'google-map',
			'title'             => __('Google Map'),
			'description'       => __('Display custom Google map.'),
			'render_template'   => 'template-parts/blocks/google-map.php',
			'category'          => 'embed',
			'icon'              => 'dashicons-location-alt',
			'keywords'          => array( 'map', 'google' ),
			'mode'				=> 'preview',
			''
		));
		
	}

	
	
	
}

new ACFBlocks();