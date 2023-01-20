<?php

/**
 *		Slideout Menu
 *
 */

class Slideout {

	function __construct() {
		
		add_action( 'wp_enqueue_scripts', array ( $this, 'enqueue_scripts') );

		
		//@since bric_v1.1 remove this inline script and bundle in bric.js
		//add_action( 'wp_footer', array( $this, 'init_slideout'), 51 );

		
		add_action( 'bric_before_header', [ $this, 'open_page_wrap' ], 1 );
		add_action( 'wp_footer', [ $this, 'close_page_wrap' ], 1 );
		
	}
	
	
	
	public function open_page_wrap() {
        
        get_template_part( 'template-parts/slideout/open-page-wrap' );
				
	}
	
    
    
	public function close_page_wrap() {
	
        get_template_part( 'template-parts/slideout/close-page-wrap' );
        
		
	}
	
	
	
	public function enqueue_scripts() {
		
		if ( is_admin() ) {
			return;
		}


		global $SiteInfo;
		global $Navbar;


		$main_menu = \Bric\Navbar::get_primary_nav_menu_obj();
			


		//var_dump( $main_menu );
        //bail if no menu items
        if ( empty( $main_menu) || $main_menu->count == 0  ) {
            
           return;
        }


		if ( isset( $Navbar->main_nav_menu_obj_left ) ) {

		}


		wp_enqueue_script( 'slideout', get_template_directory_uri().'/assets/js/slideout.min.js', array('jquery', 'bootstrap'), false, true );
		
		wp_localize_script( 'slideout', 'slideout', [
			'side' => $SiteInfo->navbar->slideout['side'],
			'target_id' => 'menu-'. $main_menu->slug,
			'target_id_left' => ( isset( $Navbar->main_nav_menu_obj_left ) ) ? 'menu-' . $Navbar->main_nav_menu_obj_left->slug : '',
			'close_button' => true, //$SiteInfo->navbar->slideout['close_button'],
			'id' => 'slideout-primary-menu'
		] );
		
		
	}
	
	
	
	
	
}

new Slideout();