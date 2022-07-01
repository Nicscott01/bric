<?php

namespace Bric;


/**
 *		Initialize the BRIC Theme
 *
 *
 */

class BricInit {
	
	public $startuptheme = null;
	
	
	public function __construct() {
		
		$this->init();
		
	}
	
	
	public function init() {
		
		add_action( 'after_switch_theme', array( $this, 'theme_dependencies'), 5, 2 );		
		add_action( 'after_setup_theme', array ($this, 'startup_theme') );
		add_action( 'admin_notices', array( $this, 'admin_notices') );
		
	}
	
	/**
	 *		Check for theme dependencies upon switching
	 *
	 *
	 */
	
	public function check_dependencies() {
		
		$this->startuptheme = true;

		//Check for ACF
		if ( !function_exists('get_field')) {
			
			$this->errors[] = 'ACF Required to use this theme.';
			$this->startuptheme = false;
			
			wp_redirect( admin_url( 'plugin-install.php?s=Advanced%20Custom%20Fields%20PRO') );
			
			
			
		}
		
		/*
		if ( ! class_exists( 'acf_plugin_font_awesome' ) ) {
			
			$this->errors[] = sprintf( 'ACF Font Awesome required for social icons. <a href="%s">Click here to get it</a>', admin_url( 'plugin-install.php?s=Advanced+Custom+Fields+Font+Awesome') );
			
			
		}
		
		*/
		
		
		
		
		return $this;
		
		
	}
	
	
	
	
		
	public function theme_dependencies( $oldthemename, $oldtheme ) {
		
		$this->check_dependencies();
		
		
		if ( $this->startuptheme == false ) {

			switch_theme( $oldtheme->stylesheet );
		
		}
					
			
		return false;		
		
		
	}
	
	
	
	
	public function admin_notices() {
		
		if ( !empty( $this->errors ) ) {
			
			foreach ( $this->errors as $error ) {
				
				printf( '<div class="notice notice-error"><p>%s</p></div>', $error );
				
			}
			
		}
		
	}
	
	
	
	
	
	public function startup_theme() {
		
		if ( $this->check_dependencies()->startuptheme ) {
			
			include_once( __DIR__ . '/functions/bric_functions.php' );
		
		}
		
		
		
	}
	
	
	
	
	
	
	
}

new BricInit();



