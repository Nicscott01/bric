<?php
/*
Plugin Name: Job Listings
Plugin URI: https://www.ondemandcmo.com/
Description: Simple way to post jobs to website
Version: 0.1.0
Author: Nicolas Scott / onDemandCMO
Author URI: https://www.ondemandcmo.com/
*/


class JobListings {

	
	public $name = '';
    public $post_type;
	public $landing_page = '';
	public $post_type_object = array();
	public $slug = '';
	public $template_part = '';
	public $template_directory = '';
	public $includes = '';
	public $options = array();
	
	function __construct() {
		
		$this->name = 'job_listing';
        $this->post_type = $this->name;
		$this->slug = 'careers';
		$this->template_part = 'basic';
		$this->includes = __DIR__.'/includes';
		
		//Setup the options
		add_action( 'init', array( $this, 'get_options') );
		
		//Register the ACF fields
		if ( function_exists( 'get_field') ) {
			include_once( get_template_directory().'/inc/acf-fields/job-listings.php' );
		}
		
		
		//register taxonomies
		add_action( 'init', array( $this, 'register_taxonomies' ), 0 );
		
		//register post type
		add_action( 'init', array( $this, 'register_post_type'), 0 );
		
		//Set page layout
		add_action( 'wp', array( $this, 'set_page_layout'), 10 );


		
		//Set content template file
	//	add_action( 'wp', array( $this, 'set_content_template') );
		
	
		
		//Label the page set as this post type's archive
		add_filter( 'display_post_states', array( $this, 'archive_page_label'), 10, 2 );
				
		
		//Set landing page
		add_action( 'wp', array( $this, 'set_landing_page') );
		
		
		//Add Admin column for featured image
		//add_filter( 'manage_posts_columns', array( $this, 'columns_head' ) );
		//add_action( 'manage_posts_custom_column', array( $this, 'columns_content' ), 10, 2);

        
		
		//Sort by menu_order
		add_action( 'pre_get_posts', array( $this, 'query_options' ) );
		//Put sticky up front
		add_filter( 'the_posts', array( $this, 'sticky_goes_first' ) );
		
		
        //Shortcode for listing page
        add_shortcode( 'job_listings', [ $this, 'job_listings_sc'] );
        
        
		
		//Set the template directory
		$this->template_directory = get_template_directory();
		
		
		
		
	}
				   
	
	
	/**
	 *		Admin Column Header
	 *
	 *
	 */
	
	public function columns_head( $defaults ) {
		
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
	
	public function columns_content( $column_name, $post_id ) {
		
		
		
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
		
			
		
		if ( $query->is_main_query() && 
		!empty( $this->options['query_options'] ) && 
		is_array( $this->options['query_options'] ) &&
		is_archive() && 
		( $query->query_vars['post_type'] == $this->post_type )
		) {


			foreach ( $this->options['query_options'] as $k => $option ) {

				$query->set( $k, $option );

			}


		}

		
	}
	
	
	/**
	 *		Put Sticky Posts First
	 *
	 *
	 */
	
	public function sticky_goes_first( $posts ) {
		
		
		$sticky = get_option('sticky_posts');
		//var_dump( $sticky );
		$stickies = [];
		
		foreach ( $posts as $k => $post ) {
			
			if ( in_array( $post->ID, $sticky ) ) {
				
				$stickies[] = $post; 
				
				unset($posts[$k]);
			}
						
		}
		
		//var_dump( $stickies );
		//var_dump( $posts );
		
		$posts = array_merge( $stickies, $posts );
		
		return $posts;
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
				'posts_per_page' => -1,
			)
			
		);
		
		//TODO: Make an options page under the post type menu
		
		$options = wp_parse_args( $this->options, $defaults );

		$this->options = apply_filters( $this->name.'_options', $options );
		
		
		
		
		
		
		
		if ( $options['archive_page'] === false ) {
			//change rewrite for archive base
			add_filter('rewrite_rules_array', array( $this, 'rewrites') );
		
			//TODO:
			//add_action( 'acf/save_post -- for options page', flush rewrite rules);
		}
		
		add_filter('rewrite_rules_array', array( $this, 'rewrite_apply_page') );

		
		
		if ( $options['landing_page_content'] == true ) {
			
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
		
		if ( is_archive() && get_post_type() == $this->name )
		
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

			get_template_part( 'content', 'page-header' );
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
	
	/**
	 *		Reserve the slug $this->slug/apply for the page named that
	 *
	 */
	
	function rewrite_apply_page( $rules ) {
		
		$my_rule = [];
			
		$my_rule[$this->slug.'/apply/?$'] = 'index.php?pagename='.$this->slug.'/apply'; 	
		
		//Put our rule first since its pretty specific and important
		$rules = $my_rule + $rules;

		return $rules;
		
	}
	
	
	
	
	function archive_page_label( $post_states, $post ) {
		
		
		if ( $post->post_name == $this->slug ) {
			
			$this->get_post_type_object();
			
			$post_states[] = $this->post_type_object->label.' Landing Page';
			
		}
		
		return $post_states;
		
	}
	
	
	
	public function set_content_template() {
		
		global $BricLoop;
		
		if ( get_post_type() === $this->name && is_single() ) {
		

			$this->template_location = locate_template( 'template-parts/' . $this->template_part, false );


			//We don't have a template so, lets load the plugin's
			if ( empty( $this->template_location ) ) {

				add_filter( 'the_content', [ $this, 'get_content' ] );

				//remove_action( 'bric_loop', [$BricLoop, 'get_content'], 10 );
				//add_action( 'bric_loop', [ $this, 'get_content'], 10 );


			}
			else {
				
				$BricLoop->contentTemplate = $this->template_part;

			}
			
			

		}
		
	}
	
	
	
	
	public function get_content( $the_content ) {
		
		
		
		ob_start();
		
		include( $this->template_directory . '/content-'.$this->slug.'.php' );
		
		
		$o = ob_get_clean();
	
		return $the_content . $o;
		
	}
	
	
	
	
	
	
	
	
	
	
	// Register Custom Post Type
	public function register_post_type() {

		$labels = array(
			'name'                  => _x( 'Job Listings', 'Post Type General Name', 'text_domain' ),
			'singular_name'         => _x( 'Job Listing', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'             => __( 'Job Listings', 'text_domain' ),
			'name_admin_bar'        => __( 'Job Listings', 'text_domain' ),
			'archives'              => __( 'Item Archives', 'text_domain' ),
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
			'slug'                  => $this->slug,
			'with_front'            => false,
			'pages'                 => true,
			'feeds'                 => true,
		);
		$args = array(
			'label'                 => __( 'Job Listings', 'text_domain' ),
			'description'           => __( 'Job Listings', 'text_domain' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'revisions', 'page-attributes' ),
			'taxonomies'            => array( 'location', 'type' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-nametag',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'rewrite'               => $rewrite,
			'capability_type'       => 'post',
		);
        
        $args = apply_filters( 'bric_job_listings_args', $args );
        
		register_post_type( $this->name, $args );

	}





	
	
	
	/**
	 *		Register Taxonomy: Location
	 *
	 *
	 */
	
	
	public function register_taxonomies() {

		$labels = array(
			'name'                       => _x( 'Locations', 'Taxonomy General Name', 'text_domain' ),
			'singular_name'              => _x( 'Location', 'Taxonomy Singular Name', 'text_domain' ),
			'menu_name'                  => __( 'Location', 'text_domain' ),
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
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => false,
		);
		
		
		register_taxonomy( 'location', array( 'job_listing' ), $args );

		
		

		$labels = array(
			'name'                       => _x( 'Type', 'Taxonomy General Name', 'text_domain' ),
			'singular_name'              => _x( 'Type', 'Taxonomy Singular Name', 'text_domain' ),
			'menu_name'                  => __( 'Type', 'text_domain' ),
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
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => false,
		);
		
		
		register_taxonomy( 'type', array( $this->post_type ), $args );

		

		
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
		
		if ( get_post_type() === $this->name ) {
			
			remove_action( 'bric_before_loop', array( $BricLoop, 'get_before_loop_posts'), 10 );
			
			remove_action( 'bric_after_loop_posts', array( $BricLoop, 'get_post_pagination') ); 
		}
		
		
	}
	
	
	
    
    
    
    
    public function job_listings_sc( $atts ) {
        
        $atts = shortcode_atts( [
            'template' => 'job-listings',
            'posts_per_page' => -1,
            'orderby'   => 'date',
            'order' => 'DESC',
        ], $atts );
        
        
        
        //Do the query
        $jobs = get_posts([
            'post_type' => $this->post_type,
            'posts_per_page' => $atts['posts_per_page'],
            'orderby'   => $atts['orderby'],
            'order' => $atts['order'],
        ]);
        
        

        
        ob_start();
        
        include locate_template( 'template-parts/components/job-listings/' . $atts['template'] . '.php' );
        
        return ob_get_clean();
        
    }
	
	
	
	
	
	
	
	
	
}

global $JobListings;

$JobListings = new JobListings();