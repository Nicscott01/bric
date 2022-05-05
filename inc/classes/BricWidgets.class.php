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
	
		include locate_template( 'template-parts/components/sidebar/register-sidebar.php' );

	}
	
	
	
}

new BricWidgets();


