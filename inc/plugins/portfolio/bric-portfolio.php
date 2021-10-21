<?php
/*
Plugin Name: BRIC Portfolio
Description: Enables portfolio section on BRIC themes
Version: 0.1.0
Author: Creare Web Solutions
Author URI: https://www.crearewebsolutions.com/
*/


class BricPortfolio {

	
	public $name = '';
	public $landing_page = '';
	public $post_type_object = array();
	public $slug = '';
	public $template_part = '';
	public $template_directory = '';
	public $includes = '';
	public $options = array();
	public static $instance = null;


	function __construct() {
		
		$this->name = 'portfolio';	
		$this->slug = 'portfolio';
		$this->template_part = 'portfolio';
		$this->includes = __DIR__.'/includes';
		
		//Setup the options
		add_action( 'init', array( $this, 'get_options') );
		
		//Register the ACF fields
		if ( function_exists( 'get_field') ) {
			include_once( __DIR__.'/includes/acf-fields.php' );
			include_once( __DIR__.'/includes/acf-blocks.php' );
		}
		
		
		//register post type
		add_action( 'init', array( $this, 'register_post_type') , 0 );
		add_action( 'init', array( $this, 'register_tax_industry'), 0 );
		
		//Set page layout
		add_action( 'wp', array( $this, 'set_page_layout'), 10 );


		
		//Set content template file
		add_filter( 'bric_content_template', array( $this, 'set_content_template'), 10, 2 );
		
	
	
		
		//Label the page set as this post type's archive
		add_filter( 'display_post_states', array( $this, 'archive_page_label'), 10, 2 );
				
		
		//Set landing page
		add_action( 'wp', array( $this, 'set_landing_page') );
		
		
		//Add Admin column for featured image
		add_filter( 'manage_posts_columns', array( $this, 'portfolio_columns_head' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'portfolio_columns_content' ), 10, 2);

		
		//Sort by menu_order
		add_action( 'pre_get_posts', array( $this, 'query_options' ) );
		
		
		
		
		//Set the template directory
		$this->template_directory = __DIR__.'/templates';
		
		
		
		//Options for post type
		add_action( 'init', [ $this, 'options_page' ] );
		
		
	}
				   
	
	
	public function options_page() {
		
		
		if ( function_exists( 'get_field' ) ) {
			

			acf_add_options_page( [ 
				'page_title' => 'Settings',
				'parent_slug' => 'edit.php?post_type=portfolio',
			]);
	
		}

	
	}
	
	
	
	
	
	
	
	/**
	 *		Admin Column Header
	 *
	 *
	 */
	
	public function portfolio_columns_head( $defaults ) {
		
		$cb = array_slice( $defaults, 0, 1 );
		
		$the_rest = array_slice( $defaults, 1 );
		
		$defaults = $cb + ['featured_image' => 'Featured Image'] + $the_rest;
		
		
		//var_dump( $defaults );
		return $defaults;
		
	}
	
		
	
	/**
	 *		Admin Column Content
	 *
	 *
	 */
	
	public function portfolio_columns_content( $column_name, $post_id ) {
		
		
		
		if ( $column_name == 'featured_image' ) {
			
			$post_thumbnail_id = get_post_thumbnail_id( $post );
			
			echo wp_get_attachment_image( $post_thumbnail_id );
			
		} 
		
		
		
	}
	
	
	
	
	
	/**
	 *		Sort by option
	 *
	 *
	 */
	
	public function query_options( $query ) {
		
		if ( !is_admin() && !empty( $this->options['query_options'] ) && is_array( $this->options['query_options'] ) ) {
			
			foreach ( $this->options['query_options'] as $k => $option ) {
				
				$query->set( $k, $option );
				
			}
			
			
			
		}
		
		if ( !is_admin() && $query->is_main_query() && is_archive() && $query->query_vars['post_type'] == $this->name ) {

			$query->set( 'posts_per_page', get_field( 'portfolio_posts_per_page', 'options' ));

		}

		
	}
	
	
	
	
	
	/**
	 *		Get Options
	 *
	 *
	 */
	
	public function get_options() {
		
		$defaults = array(
			'archive_page' => true,
			'landing_page_content' => true,
			'query_options' => array(
			)
			
		);
		
		//TODO: Make an options page under the post type menu
		
		
			
		$this->options = apply_filters( 'bric_portfolio_options', $this->options );
		
		
		$options = wp_parse_args( $this->options, $defaults );
		
		
		
		
		
		if ( $options['archive_page'] === false ) {
			//change rewrite for archive base
			add_filter('rewrite_rules_array', array( $this, 'rewrites') );
		
			//TODO:
			//add_action( 'acf/save_post -- for options page', flush rewrite rules);
		}
		
		
		
		if ( $options['landing_page_content'] == true && is_archive() ) {
			
			//Output the landing page content above the archive page
			add_action( 'bric_before_loop', array( $this, 'output_landing_page_content' ), 5 );
			
		}
		
		
		
	}
	
	
	
	/**
	 *		Get Landing Page Content
	 *		
	 *
	 */
	
	public function output_landing_page_content() {
		
		
		$this->get_landing_page_content();
		
		
		
	}
	
	
	
	
	
	public function get_landing_page_content() {
		
		global $post;

		//Get the page post
		$lp = get_posts( [ 
			'post_type' => 'page',
			'pagename' => $this->slug,
		]);
		
		
		if ( !empty( $lp ) && ( count( $lp ) == 1 ) ) {
			
			$post = $lp[0];
			
		}
		
		
		
		if ( !empty( $post ) ) {
			
			setup_postdata( $post );
			
			echo '<div class="archive-page-content col-12">';

			get_template_part( 'header', 'page' );
			get_template_part( 'content', 'page' );
			
			echo '</div>';
			
			wp_reset_postdata();
		}
		
		
		
		
		
	}
	
	
	
	
	
	
	
	/**
	 *		Sets the page named the same as the slug for this post type as the 
 	 *		as the "archive" landing page. This keeps the slug structure w/ the base
	 * 		but lets us use the page object to make the post listing
	 *
	 */
	
	function rewrites( $rules ) {
		
			
		$rules[$this->slug.'/?$'] = 'index.php?pagename='.$this->slug; 	
		
		return $rules;
		
	}
	
	
	
	
	function archive_page_label( $post_states, $post ) {
		
		
		if ( $post->post_name == $this->slug ) {
			
			$this->get_post_type_object();
			
			$post_states[] = $this->post_type_object->label.' Landing Page';
			
		}
		
		return $post_states;
		
	}
	
	
	
	public function set_content_template( $content_part, $BricLoop ) {
		
		
		if ( get_post_type() == 'portfolio' && is_single() ) {
		


			$this->template_location = locate_template( 'content-' . $this->template_part .'.php', false );
			
						
			//We don't have a template so, lets load the plugin's
			if ( empty( $this->template_location ) ) {

				add_filter( 'the_content', [ $this, 'get_content' ] );


			}
			else {
				
				$content_part = $this->template_part;
				
			}

		}
		
		return $content_part;
		
	}
	
	
	
	
	public function get_content( $the_content ) {
		
		
	
		
		ob_start();
		
		include( $this->template_directory . '/content-portfolio.php' );
		
		
		$o = ob_get_clean();
	
		return $the_content . $o;
		
	}
	
	
	
	
	
	
	// Register Custom Post Type
	function register_post_type() {
		
		
		//
		// 	Get Options
		//
		
		$label = get_field( 'portfolio_label', 'option' );
		$label_singular = get_field( 'portfolio_label_singular', 'option' );
		$slug = get_field( 'portfolio_slug', 'option' );
		
		
		$label = ( empty( $label ) ) ? 'Portfolio' : $label;
		$slug = ( empty( $slug )) ? $this->slug : $slug;
		
		
		

		$labels = array(
			'name'                  => _x( $label, 'Post Type General Name', 'text_domain' ),
			'singular_name'         => _x( $label_singular, 'Post Type Singular Name', 'text_domain' ),
			'menu_name'             => __( $label, 'text_domain' ),
			'name_admin_bar'        => __( $label, 'text_domain' ),
			'archives'              => __( $label, 'text_domain' ),
			'attributes'            => __( 'Item Attributes', 'text_domain' ),
			'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
			'all_items'             => __( 'All Items', 'text_domain' ),
			'add_new_item'          => __( 'Add New Item', 'text_domain' ),
			'add_new'               => __( 'Add New', 'text_domain' ),
			'new_item'              => __( 'New Item', 'text_domain' ),
			'edit_item'             => __( 'Edit Item', 'text_domain' ),
			'update_item'           => __( 'Update Item', 'text_domain' ),
			'view_item'             => __( 'View Item', 'text_domain' ),
			'view_items'            => __( 'View Items', 'text_domain' ),
			'search_items'          => __( 'Search Item', 'text_domain' ),
			'not_found'             => __( 'Not found', 'text_domain' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
			'featured_image'        => __( 'Featured Image', 'text_domain' ),
			'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
			'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
			'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
			'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
			'items_list'            => __( 'Items list', 'text_domain' ),
			'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
			'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
		);
		$rewrite = array(
			'slug'                  => $slug,
			'with_front'            => false,
			'pages'                 => true,
			'feeds'                 => true,
		);
		$args = array(
			'label'                 => __( $label, 'text_domain' ),
			'description'           => __( $label . ' area', 'text_domain' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions','page-attributes' ),
			'taxonomies'            => array( 'category', 'post_tag' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-portfolio',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'rewrite'               => $rewrite,
			'capability_type'       => 'post',
			'show_in_rest'			=> true,
		);
		register_post_type( $this->name, $args );

	}







	// Register Custom Taxonomy
	function register_tax_industry() {

		$labels = array(
			'name'                       => _x( 'Industries', 'Taxonomy General Name', 'text_domain' ),
			'singular_name'              => _x( 'Industry', 'Taxonomy Singular Name', 'text_domain' ),
			'menu_name'                  => __( 'Industry', 'text_domain' ),
			'all_items'                  => __( 'All Items', 'text_domain' ),
			'parent_item'                => __( 'Parent Item', 'text_domain' ),
			'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
			'new_item_name'              => __( 'New Item Name', 'text_domain' ),
			'add_new_item'               => __( 'Add New Item', 'text_domain' ),
			'edit_item'                  => __( 'Edit Item', 'text_domain' ),
			'update_item'                => __( 'Update Item', 'text_domain' ),
			'view_item'                  => __( 'View Item', 'text_domain' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
			'popular_items'              => __( 'Popular Items', 'text_domain' ),
			'search_items'               => __( 'Search Items', 'text_domain' ),
			'not_found'                  => __( 'Not Found', 'text_domain' ),
			'no_terms'                   => __( 'No items', 'text_domain' ),
			'items_list'                 => __( 'Items list', 'text_domain' ),
			'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
		);
		$rewrite = array(
			'slug'                       => 'industry',
			'with_front'                 => true,
			'hierarchical'               => false,
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'rewrite'                    => $rewrite,
		);
		//register_taxonomy( 'industry', array( $this->name ), $args );

	}

	

	
	

	
	
	
	
	
	
	/**
	 *		Define the landing page for this "archive"
	 *
	 */
	
	function set_landing_page() {
		
		$this->get_post_type_object();
		
		$this->landing_page = get_page_by_path( $this->post_type_object->rewrite['slug'] );
	
		
		return $this->landing_page;
		
	}

	
	
	
	function get_post_type_object() {
		
		$this->post_type_object = get_post_type_object( $this->name );
		
		return $this;
		
	}
	
	
	
	
	
	
	
	function set_page_layout() {
		
		global $BricLoop;
		
		if ( get_post_type() == $this->name ) {
			
			remove_action( 'bric_before_loop', array( $BricLoop, 'get_before_loop_posts'), 10 );
			
			remove_action( 'bric_after_loop_posts', array( $BricLoop, 'get_post_pagination') ); 
		}
		
		
	}
	
	
	
	static public function get_instance() {

		if ( self::$instance == null ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
	
	
	
	
	
	
	
}

//global $BricPortfolio;

$BricPortfolio = BricPortfolio::get_instance();