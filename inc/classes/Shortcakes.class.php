<?php


/**
 * 		Shortcode UI Setup
 *
 *
 */


class BricShortcake {
	
	
	
	function __construct() {
		
		//add_action( 'init', array( $this, 'columns' ));
		add_action( 'init', array( $this, 'site_info' ));
		add_action( 'init', array( $this, 'google_maps' ));
		add_action( 'init', array( $this, 'nav_menus2') );
		add_action( 'init', array( $this, 'call_to_action') );
		
	}
	
	
	
	function columns() {
		
		/**
		 * Register a UI for the Shortcode.
		 * Pass the shortcode tag (string)
		 * and an array or args.
		 */
		
		shortcode_ui_register_for_shortcode(
			'columns',
			array(
				// Display label. String. Required.
				'label' => 'Columns',
				// Icon/image for shortcode. Optional. src or dashicons-$icon. Defaults to carrot.
				'listItemImage' => 'dashicons-media-spreadsheet',
				// Available shortcode attributes and default values. Required. Array.
				// Attribute model expects 'attr', 'type' and 'label'
				// Supported field types: text, checkbox, textarea, radio, select, email, url, number, and date.
				'attrs' => array(
					array(
						'label' => 'Force Number of Columns',
						'attr'  => 'num',
						'type'  => 'number',
					),
					
					
				),
				'inner_content' => array(
					'label' => 'Column Content',
					'description' => 'Separate each column with #columnbreak',
				), 
				
			)
		);		
		
	}
	
	
	
	
	
	function site_info() {
		
		$args = array(
			'label' => 'Site Info',
			'listItemImage' => 'dashicons-location-alt',
			'attrs' => array(
				array(
					'label' => 'Include the following',
					'description' => 'Pick the items you want to show',
					'attr' => 'include',
					'type' => 'select',
					'meta' => array( 'multiple' => true ),
					'options' => array(
						array( 'value' => 'address', 'label' => 'Address'  ),
						array( 'value' => 'phone', 'label' => 'Phone' ),
						array( 'value' => 'email', 'label' => 'Email' ),
					),
				),
				array(
					'label' => 'Label for Phone',
					'description' => 'Optional label to prefix the phone number, like "phone: 123.456.7890"',
					'attr' => 'phone_label',
					'type' => 'text',
				),
				array(
					'label' => 'Label for Email',
					'description' => 'Optional label to prefix the email, like "email: info@example.com"',
					'attr' => 'email_label',
					'type' => 'text',
				),
			),
			
		);
		
		
		shortcode_ui_register_for_shortcode( 'site_info', $args );
		
		
	}
	
	
	
	function google_maps() {
		
		//Get the main google map
		$map = get_field( 'location', 'options' );
		
		$args = array(
			'label' => 'Google Map',
			'listItemImage' => 'dashicons-location',
			'attrs' => array(
				array(
					'label' => 'Select Map',
					'type' => 'select',
					'attr' => 'coordinates',
					'options' => array(
						array( 'value' => $map['lat'].','.$map['lng'], 'label' => $map['address'] ),
					),
				)
			),
			
		);
		
		shortcode_ui_register_for_shortcode( 'bric_google_map', $args );
		
		
	}
	
	
	
	
	
	
	function nav_menus2() {
		
		$menus = wp_get_nav_menus();
		
		
		if ( !empty( $menus ) ) {
			
			$options = array();
			
			foreach ( $menus as $menu ) {
				
				$options[] = array( 
						'value' => (string) $menu->term_id, 
						'label' => $menu->name, 
					);
				
			}
		}
		
		//var_dump( $options );
		//$options = 	array( array( 'value' => 'test', 'label' => 'TEST' ) );
	
		
		
		$args = array(
			'label' => 'Nav Menu',
			'listItemImage' => 'dashicons-menu',
			'attrs' => array(
				array(
					'label' => 'Choose Menu',
					'type' => 'select',
					'attr' => 'menu',
					'options' => $options,
				),
			),
		);
		
		
		shortcode_ui_register_for_shortcode( 'nav_menu', $args );
		
		
	}
		
	
	
	function call_to_action() {
		
		
		$post_types = get_post_types(array(
			'public' => true, 
		));
		
		unset( $post_types['attachment'] );
		
						

		$fields = array(
			array(
				'label'       => 'Image',
				'attr'        => 'image',
				'description' => 'Choose One Image',
				'type'        => 'attachment',
				'libraryType' => array( 'image' ),
				'multiple'    => false,
				'addButton'   => 'Select Image',
				'frameTitle'  => 'Select Image',
			),
			array(
				'label' => 'Link',
				'attr' => 'link',
				'description' => 'Pick a post to link to',
				'type' => 'post_select',
				'query'    => array( 'post_type' => $post_types ),
				'multiple' => false,
			),
			array(
				'label'  => 'Title',
				'attr'   => 'title',
				'type'   => 'text',
				'encode' => true,
				'meta'   => array(
					'placeholder' => 'Learn More',
				),
			),
			array(
				'label'  => 'Background Color',
				'attr'   => 'bg-color',
				'description' => 'Use this in case of no image.',
				'type'   => 'color',
				'encode' => false,
				'meta'   => array(
					'placeholder' => 'Hex Color Code',
				),
			),
		);
		
		
		$args = array(
			'label' => 'Call to Action',
			'listItemImage' => 'dashicons-format-image',
			'attrs' => 	$fields
		);
		
		
		shortcode_ui_register_for_shortcode( 'call_to_action', $args );
		
		
	}
	
	
	
	
	
	
	
}


if ( function_exists('shortcode_ui_register_for_shortcode') ) 
	new BricShortcake();
