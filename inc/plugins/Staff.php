<?php
/**
 *      Plugin Name: BRIC Staff
 *      Description: Easily add/remove staff members and display on website
 *      Version: 0.1
 *     
 */







class BricStaff {
    
    
    /**
     *  Instance
     */
    public static $instance = null;
    
    
    /**
     *   Variables
     */
    public $post_type = 'staff';
    public $post_type_name = 'Staff';
    public $post_type_name_plural = 'Staff Members';
    public $post_type_slug = 'staff';
    public $archive_page = null;
    public $has_archive = true;
    public $public = true;
    
    
    
    /**
     *  Construct
     */
    
    public function __construct() {
        
        add_action( 'init', [ $this, 'init' ] );
        
        add_shortcode( 'bric_staff', [ $this, 'bric_staff_sc'] );
        

        
        
        /**
         *      CUSTOM:
         *
         *
         */
        
        //$this->archive_page = get_page_by_path( 'about' );
        
        //Filter staff landing pages back to the about page
       /* add_filter( 'post_type_link', function( $permalink, $post ) {
            
            if ( is_admin() ){
                
                //return $permalink;
                
            }
            
            
            if ( $post->post_type == $this->post_type ) {
                
                
                $permalink = get_permalink( $this->archive_page ) . '#' . sanitize_title( $post->post_title );
                
            }

            
            return $permalink;
            
        }, 10, 2 );
        */
        
        
        
    }
        
        
    
    public function init() {
        
        
        //Filter the args
        do_action( 'bric_staff_args', $this );
        
    
        
        
        
        $this->register_taxonomy_1();

        $this->register_post_type();
        
        
        
        
    }
    
    
    
    
    
    
    
    

    // Register Custom Post Type
    public function register_post_type() {

        $labels = array(
            'name'                  => _x( $this->post_type_name_plural, 'Post Type General Name', 'text_domain' ),
            'singular_name'         => _x( $this->post_type_name, 'Post Type Singular Name', 'text_domain' ),
            'menu_name'             => __( $this->post_type_name, 'text_domain' ),
            'name_admin_bar'        => __( $this->post_type_name, 'text_domain' ),
            'archives'              => __( $this->post_type_name . ' Archives', 'text_domain' ),
            'attributes'            => __( $this->post_type_name . ' Attributes', 'text_domain' ),
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
            'featured_image'        => __( 'Headshot', 'text_domain' ),
            'set_featured_image'    => __( 'Set headshot', 'text_domain' ),
            'remove_featured_image' => __( 'Remove headshot', 'text_domain' ),
            'use_featured_image'    => __( 'Use as headshot', 'text_domain' ),
            'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
            'items_list'            => __( 'Items list', 'text_domain' ),
            'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
            'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
        );
        
        $args = array(
            'label'                 => __( $this->post_type_name, 'text_domain' ),
            'description'           => __( 'Listing of staff members', 'text_domain' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes' ),
            'hierarchical'          => false,
            'public'                => $this->public,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 20,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => $this->has_archive,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
            'menu_icon'             => 'dashicons-businessperson',
            'rewrite'               => [
                'slug'  => $this->post_type_slug,
                'with_front' => false,
                'pages' => true,
                'feeds' => true,
            ]
            
        );
        
        register_post_type( $this->post_type, apply_filters( 'bric_staff_pt_args', $args ) );

    }

    

    // Register Custom Taxonomy
    function register_taxonomy_1() {

        $labels = array(
            'name'                       => _x( 'Position Types', 'Taxonomy General Name', 'text_domain' ),
            'singular_name'              => _x( 'Position Type', 'Taxonomy Singular Name', 'text_domain' ),
            'menu_name'                  => __( 'Position Type', 'text_domain' ),
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
            'public'                     => $this->public,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
        );
        
        register_taxonomy( 'position_class', array( 'staff' ), $args );

    }


    
    
    /**
     *      BRIC Staff Shortcode handler
     *
     *
     */
    
    public function bric_staff_sc( $atts ) {
        
        $atts = shortcode_atts( [
            'template' => 'headshot-bio',
            'posts_per_page' => -1,
            'orderby'   => 'menu_order',
            'order' => 'ASC',
            'tax_query' => false,
            'taxonomy' => null,
            'term' => null,
            'btn' => 'btn-primary'
        ], $atts );
        
        
        $tax_query = null;
        
        if ( $atts['tax_query'] ) {
            
            $tax_query = [
                [
                    'taxonomy' => $atts['taxonomy'],
                    'terms' => $atts['term'],
                    'field' => 'slug'
                ]
            ];
            
        }
        
        
        
        
        //Do the query
        $staff_members = get_posts([
            'post_type' => $this->post_type,
            'posts_per_page' => $atts['posts_per_page'],
            'orderby'   => $atts['orderby'],
            'order' => $atts['order'],
            'tax_query' => $tax_query
        ]);
        
        
        $staff_members = $this->add_custom_fields_to_obj( $staff_members );
        
        
        
        ob_start();
                
        include locate_template( 'template-parts/components/staff/' . $atts['template'] . '.php' );
        
        return ob_get_clean();
        
        
    }
    
    
    
    
    
    
    /**
     *  Add custom fields as pieces to the Staff object
     *
     *
     */
    
    public function add_custom_fields_to_obj( $posts ) {
        
        
        if( empty( $posts )  ) {
            
            return $posts;
        
        }
       
        
        foreach( $posts as $key => $obj ) {
            
            if( function_exists( 'get_field' ) ) {

                $fields = get_fields( $obj->ID );
                
                
                if ( !empty( $fields ) ) {
                    
                    foreach( $fields as $name => $value ) {
                    
                        $posts[$key]->$name = $value;
                        
                    }

                }
            }

            
            //Maybe get the featured image
            $posts[$key]->featured_image = get_post_thumbnail_id( $obj );
            
        }
        
                
        return $posts;
        
        
        
        
        
    }
    
    
    
    
    
    
    
    /**
     *  Get Instance
     */
    public static function get_instance() {
        
        if ( self::$instance == null ) {
            
            self::$instance = new self;
            
        }
        
        return self::$instance;
    
    }
    
    
    
    
   

    
    
}



function BricStaff() {


    if ( class_exists( 'BricStaff' ) ) {
    
        return BricStaff::get_instance();
    
    } else {

        return false;
    }
    
}

BricStaff();