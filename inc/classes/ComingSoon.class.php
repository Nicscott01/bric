<?php
/**
 *		Coming Soon functionality
 *
 *
 */

if ( !defined( 'COMING_SOON' ) ) {

	define( 'COMING_SOON', false );

}

class ComingSoon {
	
	/**
	 *	Instance
	 */
	public static $instance = null;
	
	public static function get_instance() {
		
		if ( self::$instance == null ) {
			
			self::$instance = new self;
			
		}
		
		return self::$instance;
		
	}
	
	
	
	
	
	public function __construct() {
		
		if ( COMING_SOON ) {
			
			add_action( 'wp_enqueue_scripts', [ $this, 'dequeue_scripts'] );
			add_action( 'template_redirect', [ $this, 'coming_soon_redirect' ] );
		
		}
		
		
	}
	
	
	



	public function coming_soon_redirect() {


		if ( !is_admin() ) {

			//Get the requested page and see if it is home
			if ( $_SERVER['REQUEST_URI'] !== '/') {

				wp_redirect( '/' );
				die();
			}


			//Load our template
			//require( __DIR__ . '/templates/index.php' );
			include_once( locate_template( 'coming-soon.php' ) );
			
			
			//don't do anything else
			die();

		}


	}


	
	
	
	public function dequeue_scripts() {
		
		wp_dequeue_style( 'bric' );
		wp_dequeue_style( 'fonts' );
		
		
	}
	
	
	
	
}


ComingSoon::get_instance();