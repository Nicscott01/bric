<?php


class BricWidgets {
	
	
	function __construct() {
		
		add_action( 'widgets_init', array( $this, 'init' ));
		
		//Let shortode be processed in widgets
		//add_filter( 'widget_text', 'do_shortcode', 10 );
		
		remove_filter( 'widget_text_content', 'wpautop', 10 );
		//remove_filter( 'the_content', 'do_shortcode', 11 );
		
		//add_filter( 'widget_text', 'wpautop', 100 );
		//add_filter( 'the_content', 'shortcode_unautop', 105);
		//add_filter( 'widget_text', 'bric_fix_shortcodes', 110 );		
		add_filter( 'widget_text_content', 'shortcode_unautop' );
		add_filter( 'widget_text_content', 'do_shortcode', 110 );
		add_filter( 'widget_text', 'do_shortcode');
		
		
	}
	
	
	function init() {
		
		/**
		 *		Header above nav
		 *
		 */
		register_sidebar( array(
			'name'          => 'Header',
			'id'            => 'header-cta',
			'before_widget' => '<div id="%1$s" class="widget align-self-end mb-0 p-1 %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',	
		) );

		
		/**
		 *		Footer
		 *
		 */		
		register_sidebar( array(
			'name'          => 'Footer',
			'id'            => 'footer-content',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
		
	}
	
	
	
}

new BricWidgets();


