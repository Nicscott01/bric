<?php

/**
 * 
 *  Testimonials Plugin for BRIC theme
 * 
 * 
 */





class BricTestimonials
{


    public static $instance;




    public function __construct()
    {



        add_action('init', [$this, 'init']);
        //add_action( 'acf/init', [ $this, 'acf_init' ] );

        add_action('acf/save_post', [$this, 'save_post_title'], 5);
    }






    public function init()
    {

        include_once(__DIR__ . '/testimonials/acf-blocks.php');
        include_once(__DIR__ . '../acf-fields/testimonials.php');

        $this->register_post_type();
    }




    public function acf_init()
    {

        include_once(__DIR__ . '/testimonials/acf-blocks.php');
    }



    // Register Custom Post Type
    public function register_post_type()
    {


        $labels = array(
            'name'                       => _x('Display Groups', 'Taxonomy General Name', 'text_domain'),
            'singular_name'              => _x('Display Group', 'Taxonomy Singular Name', 'text_domain'),
            'menu_name'                  => __('Display Group', 'text_domain'),
            'all_items'                  => __('All Display Groups', 'text_domain'),
            'parent_item'                => __('Parent Item', 'text_domain'),
            'parent_item_colon'          => __('Parent Item:', 'text_domain'),
            'new_item_name'              => __('New Display Group', 'text_domain'),
            'add_new_item'               => __('Add New Display Group', 'text_domain'),
            'edit_item'                  => __('Edit Display Group', 'text_domain'),
            'update_item'                => __('Update Display Group', 'text_domain'),
            'view_item'                  => __('View Display Group', 'text_domain'),
            'separate_items_with_commas' => __('Separate Display Groups with commas', 'text_domain'),
            'add_or_remove_items'        => __('Add or remove Display Groups', 'text_domain'),
            'choose_from_most_used'      => __('Choose from the most used', 'text_domain'),
            'popular_items'              => __('Popular Display Groups', 'text_domain'),
            'search_items'               => __('Search Display Groups', 'text_domain'),
            'not_found'                  => __('Not Found', 'text_domain'),
            'no_terms'                   => __('No Display Groups', 'text_domain'),
            'items_list'                 => __('Display Groups list', 'text_domain'),
            'items_list_navigation'      => __('Display Groups list navigation', 'text_domain'),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => false,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => false,
            'show_tagcloud'              => false,
            'rewrite'                    => false,
            'show_in_rest'               => false,
        );

        register_taxonomy('testimonial_display_group', array('testimonial'), $args);








        $labels = array(
            'name'                  => _x('Testimonials', 'Post Type General Name', 'text_domain'),
            'singular_name'         => _x('Testimonial', 'Post Type Singular Name', 'text_domain'),
            'menu_name'             => __('Testimonials', 'text_domain'),
            'name_admin_bar'        => __('Testimonials', 'text_domain'),
            'archives'              => __('Item Archives', 'text_domain'),
            'attributes'            => __('Item Attributes', 'text_domain'),
            'parent_item_colon'     => __('Parent Item:', 'text_domain'),
            'all_items'             => __('All Items', 'text_domain'),
            'add_new_item'          => __('Add New Item', 'text_domain'),
            'add_new'               => __('Add New', 'text_domain'),
            'new_item'              => __('New Item', 'text_domain'),
            'edit_item'             => __('Edit Item', 'text_domain'),
            'update_item'           => __('Update Item', 'text_domain'),
            'view_item'             => __('View Item', 'text_domain'),
            'view_items'            => __('View Items', 'text_domain'),
            'search_items'          => __('Search Item', 'text_domain'),
            'not_found'             => __('Not found', 'text_domain'),
            'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
            'featured_image'        => __('Featured Image', 'text_domain'),
            'set_featured_image'    => __('Set featured image', 'text_domain'),
            'remove_featured_image' => __('Remove featured image', 'text_domain'),
            'use_featured_image'    => __('Use as featured image', 'text_domain'),
            'insert_into_item'      => __('Insert into item', 'text_domain'),
            'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
            'items_list'            => __('Items list', 'text_domain'),
            'items_list_navigation' => __('Items list navigation', 'text_domain'),
            'filter_items_list'     => __('Filter items list', 'text_domain'),
        );
        $args = array(
            'label'                 => __('Testimonial', 'text_domain'),
            'description'           => __('Testimonials', 'text_domain'),
            'labels'                => $labels,
            'supports'              => array('editor', 'revisions'),
            'hierarchical'          => false,
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 20,
            'menu_icon'             => 'dashicons-format-quote',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => false,
            'capability_type'       => 'page',
            'show_in_rest'          => false,
        );

        register_post_type('testimonial', $args);
    }



    public function save_post_title($post_id)
    {



        if (isset($_POST['acf']['field_61f2cb2fc6257']) && isset($_POST['acf']['field_61f2cb37c6258'])) {

            $title = $_POST['acf']['field_61f2cb2fc6257'] . ', ' . $_POST['acf']['field_61f2cb37c6258'];

            $post = get_post($post_id);

            $post->post_title = $title;

            wp_update_post($post);
        }
    }



    /**
     *  Get Instance
     * 
     */

    public static function get_instance()
    {

        if (self::$instance == null) {

            self::$instance = new self;
        }

        return self::$instance;
    }
}


function BricTestimonials()
{

    return BricTestimonials::get_instance();
}

BricTestimonials();
