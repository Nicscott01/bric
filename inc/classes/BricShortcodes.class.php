<?php



class BricShortcodes {
	
	
	
	function __construct() {
		
		//Navigation Menu
		add_shortcode( 'nav_menu', array( $this, 'menu_sc' ) );

		//Business Hours
		add_shortcode( 'hours', array( $this, 'hours_sc') );
		
		//Social Media
		add_shortcode( 'social_media', array( $this, 'social_media_sc') );
		
		//Call to Action
		add_shortcode( 'call_to_action', array( $this, 'call_to_action_sc') );
		
	}
	
	
	
	
	function menu_sc( $atts, $content ) {
		
		$atts = shortcode_atts( array(
			'menu' => '',
			'echo' => false,
		), $atts );
		
		
		return wp_nav_menu( $atts );
		
	}
	
	
	
	
	
	function hours_sc( $atts, $content ) {
		
		
		global $SiteInfo;
		
		
		if( !empty( $SiteInfo->operating_hours )) {
			
			$o = '';
			
			foreach( $SiteInfo->operating_hours as $hour ) {
				
				$o .= sprintf( '<li><span class="day">%s</span> <span class="hours">%s</span></li>', $hour['day'], $hour['hours'] );
				
				
			}
			
			
			return sprintf( '<ul class="list-unstyled operating-hours">%s</ul>', $o );
			
		}
		
		
		
	}


	
	
	
	
	
	
	function social_media_sc( $atts, $content ) {
		
		global $SiteInfo;
		
		if ( !empty( $SiteInfo->social ) ) {
			
			$o = '';
						
			foreach ( $SiteInfo->social as $social ) {
				
				$o .= sprintf( '<li class="social-account list-inline-item"><a href="%s" target="_blank">%s</a></li>', $social['url'], $social['icon']->element );
				
				
			}
			
			return sprintf( '<ul class="list-unstyled list-inline">%s</ul>', $o );
			
			
		}
		
		
	}
	
	
	
	
	
	/**
	 *		Shortcode for Call to Action
	 *
	 *
	 */
	
	function call_to_action_sc( $atts, $content ) {
		
		$atts = shortcode_atts( array(
			'title' => '',
			'image' => '',
			'link' => '',
		), $atts );
		
		
		return sprintf( '<div class="cta"><a href="%s"><span class="title">%s</span>%s</a></div>', 
			   	get_permalink( $atts['link'] ),
				$atts['title'],
				wp_get_attachment_image( $atts['image'], 'medium' )
			   );
		
		
		
		
	}
	

}


new BricShortcodes();