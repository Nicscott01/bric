<?php

/**
 *	Class to handle admin notices
 *
 */


class BricNotices {
	
	
	public function __construct() {
		
		//Start the session
		add_action( 'admin_init', [ $this, 'start_session' ] );
		
		add_action( 'admin_notices', [ $this, 'display_notices' ] );
		
	}
	
	
	
	
	
	
	
	
	
	/**
	 *		Add a notice
	 *
	 *
	 */
	
	
	public function add_notice( $text = '', $type = 'success' ) {
		
		
		
		$_SESSION['bric_notices'][] = [
			'type' => $type,
			'text' => $text,
		];
		
		
		
	}
		
	
	
	
	/**
	 *		Display the notices
	 *
	 *
	 */
	
	public function display_notices() {
		
		
		if ( !isset( $_SESSION['bric_notices'] ) &&  empty( $_SESSION['bric_notices'] ) ) {
			
			return;
			
		}
		
		
		if ( is_array( $_SESSION['bric_notices'] ) ) {
			
			foreach ( $_SESSION['bric_notices'] as $this->notice ) {
				
				$this->print_notice();
				
				
			}
		
				
			
		}
		
		
		
		//Delete the notices
		unset( $_SESSION[ 'bric_notices' ] );
		
	}
	
	
	
	
	
	
	private function print_notice() {
		
		
		//notice types = error, warning, success, info
		
		
		printf( '<div class="notice notice-%s is-dismissible"><p>%s</p></div>', $this->notice['type'], $this->notice['text'] );

				
		
	}
	
	
	
	
	
	
	/**
	 *		Enable the use of the global $_SESSION variable
	 *
	 *
	 *
	 */	
		
		
	public function start_session() {
		
		if ( !isset( $_SESSION )) {
			session_start();			
		}
		

			
	}
	
		
		
		
		
		
		
		
		
	
	
	
	
	
}

global $BricNotices;
$BricNotices = new BricNotices();







function add_bric_notice( $text = '', $type = 'success' ) {
	
	global $BricNotices;
		
	$BricNotices->add_notice( $text, $type );
	
}
