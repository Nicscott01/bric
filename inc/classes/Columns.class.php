<?php
/**
 * Plugin Name: Columns
 * Plugin URI: https://wordpress.org/extend/plugins/columns/
 * Description: Use a [column] shortcode inside a [column-group] to create magic.
 * Author: Konstantin Kovshenin
 * Author URI: https://kovshenin.com
 * License: GPLv2 or later
 * Version: 0.7.3
 */

class Columns_Plugin {

	public $current_group = 0;
	public $span = array();

	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		//add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		//add_action( 'wp', array( $this, 'wp') );
		
	}

	function init() {
		//add_shortcode( 'column', array( $this, 'column' ) );
		add_shortcode( 'columns', array( $this, 'group' ) );
		add_shortcode( 'columnbreak', array( $this, 'columnbreak' ) );
		
				//move wpautop filter to AFTER shortcode is processed
		remove_filter( 'the_content', 'wpautop' );
		remove_filter( 'the_content', 'do_shortcode', 11 );
		
		add_filter( 'the_content', 'wpautop', 100 );
		//add_filter( 'the_content', 'shortcode_unautop', 105);
		add_filter( 'the_content', 'bric_fix_shortcodes', 110 );		
		add_filter( 'the_content', 'do_shortcode', 110 );		
		
	}
	
	
	
	
	function wp() {
		
		global $post;
		
		$this->columns_start = strpos( $post->post_content, '[columns' );
		
		//var_dump( $has_columns );
		
		if ( $this->columns_start !== false ) {
			
			remove_filter( 'the_content', 'wpautop' );
			add_filter( 'the_content', array( $this, 'the_content_with_columns') );
		}
		
	}
	
	
	function the_content_with_columns( $the_content ) {
		
		$this->columns_end = strpos( $the_content, '[/columns]');
		
		var_dump( substr( $the_content, $this->columns_start, $this->columns_end - $this->column_start ) );		
		
		var_dump( $this->columns_end );
		
		
	}
	
	
	

	function column( $attr, $content ) {
		$attr = shortcode_atts( array(
			'span' => 1,
		), $attr );

		$attr['span'] = absint( $attr['span'] );
		$this->span[ $this->current_group ] += $attr['span'];

		$content = wpautop( $content );

		// Allow other shortcodes inside the column content.
		if ( false !== strpos( $content, '[' ) )
			$content = do_shortcode( shortcode_unautop( $content ) );

		return sprintf( '<div class="column column-number-%d column-span-%d">%s</div>', $this->span[ $this->current_group ], $attr['span'], $content );
	}

	
	
	
	
	
	function group( $attr, $content ) {
		
		$this->current_group++;
		$this->span[ $this->current_group ] = 0;
		
		$attr = shortcode_atts( array( 
			'num' => 2,
		), $attr );
		
		// Convent and count columns.
		//$content = wpautop( do_shortcode( $content ), false );
		//$content = wpautop( $content );

		$content = shortcode_unautop( do_shortcode( $content ) );

		//var_dump( $content );
		
		//wp_die();
		
		// Allow other shortcodes inside the column content.
		if ( false !== strpos( $content, '[' ) )
			//$content = do_shortcode( shortcode_unautop(  $content ) );


		//$count = $this->span[ $this->current_group ];
		//$content = str_replace( 'class="column column-number-' . $count, 'class="column column-number-' . $count . ' last', $content );
		
		$count = (string) $attr['num'];
		
		return sprintf( '<div class="column-group columns-%d"><div class="column">%s</div></div>', $count, $content );
	}
	
	
	
	
	function columnbreak( $attr, $content ) {
		
		
		return '</div><div class="column">';
		

	}
	
	
	
}


new Columns_Plugin;