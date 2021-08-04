<?php
/*
Plugin Name:  WP Quick Notifications
Plugin URI:   https://ondemandcmo.com
Description:  Simple notification system.
Version:      0.0.2
Author:       Nic Scott
Author URI:   https://ondemandcmo.com/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wporg
Domain Path:  /languages
*/



if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if ( !class_exists( 'WPQuickNotifications')) :
	

class WPQuickNotifications {

    public static $instance = null;
    
	public $errors = array();
	public $current_notification = null;
	public $has_notification = false;
	public $already_checked = false;
	
	function __construct() {

		add_action( 'init', array( $this, 'init') );

        
        //Clear entire page cache when post expires
        add_action( 'wp_insert_post', [ $this, 'clear_page_cache' ], 10, 2 );
        
        
	}





	function init() {

		$this->check_dependencies();

		$this->register_post_type();

		$this->register_acf_fields();

	}
	
	
	function check_dependencies() {
		
		$has_errors = false;
		
		
		if ( !function_exists('get_field') ) {
			
			$this->errors[] = 'WP Quick Notifications plugin requires ACF Pro plugin. Please install before trying to use this.';
			
			$has_errors = true;
		}
		
		
		if ( !shortcode_exists('postexpirator') ) {
			
			$this->errors[] = 'WP Quick Notifications plugin requires Expirator plugin. Please install before trying to use this.';
			
			$has_errors = true;
		}
		
		
		
		if ( $has_errors ) {
			
			add_action('admin_notices', array( $this, 'errors' ) );
			
		}	

		
	}


	
	function errors() {
		
		if ( !empty( $this->errors ) ) {
			
			echo '<div class="notice notice-error">';
			
			foreach ( $this->errors as $error ) {
				
				printf( '<p>%s</p>', $error );
				
			}
			
			echo '</div>';
			
		}
		
	}
	

		
		// Register Custom Post Type
	function register_post_type() {

		$labels = array(
			'name'                  => _x( 'Notifications', 'Post Type General Name', 'text_domain' ),
			'singular_name'         => _x( 'Notification', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'             => __( 'Notifications', 'text_domain' ),
			'name_admin_bar'        => __( 'Notifications', 'text_domain' ),
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
			'slug'                  => 'notifications',
			'with_front'            => true,
			'pages'                 => true,
			'feeds'                 => true,
		);
		$args = array(
			'label'                 => __( 'Notification', 'text_domain' ),
			'description'           => __( 'Easily set timed messages across your website.', 'text_domain' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'revisions' ),
			'hierarchical'          => false,
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-megaphone',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'rewrite'               => $rewrite,
			'capability_type'       => 'page',
		);
		
		register_post_type( 'notifications', $args );

		
	}
	
	
	
	
	function register_acf_fields() {
		
		
		
		
	}

	
	
	function has_notification() {
		
		$this->get_notification();
		
		return $this->has_notification;
		
	}
	
	
	
	
	function get_notification() {
		
		if ( !$this->already_checked ) {
		
			$notifications = new WP_Query(array(
				'post_type' => 'notifications',
				'order' => 'DESC',
				'orderby' => 'date',
				'status' => 'publish',
			));

			//var_dump( $notifications->posts );
			//var_dump( $this->has_notification );

			if ( !empty( $notifications->posts ) ) {

				$this->current_notification = $notifications->posts[0];

				$this->has_notification = true;

				$this->setup_current_notification();
			}

			$this->already_checked = true;		

		}
		
		
		return $this;
		
		
	}
	
	
	
	
	function setup_current_notification() {
		
		$this->current_notification->bg_color = get_field( 'background_color', $this->current_notification->ID );
		
		$this->current_notification->expiration_date = get_field( 'expiration_date', $this->current_notification->ID );
		
        $this->current_notification->homepage_ribbon = get_field( 'homepage_ribbon', $this->current_notification->ID );
        
        $this->current_notification->alt_slide = get_field( 'alternate_slide', $this->current_notification->ID );
        
        
        
		$this->get_expiration_date_object();
		
		
		$this->current_notification->cta = get_field( 'cta', $this->current_notification->ID );
		
		
		return $this;
		
	}
	
	
	function get_expiration_date_object() {
		
		$this->current_notification->expiration_date = new DateTime( $this->current_notification->expiration_date, new DateTimeZone( get_option('timezone_string') ) );
	
		return $This;
	
	}
	
	
	
	
	
	function format_notification() {
		
        
        if ( $this->has_notification && $this->has_alternate_slide() ) {
            
			$bg_color = 'style="min-height:0;';
			
			if ( isset( $this->current_notification->bg_color ) && !empty( $this->current_notification->bg_color )) {
				$bg_color .= sprintf( 'background-color:%s"', $this->current_notification->bg_color );
			}

			?>
<div class="carousel slide" data-ride="" data-pause="hover">
	<div class="carousel-inner" role="listbox">
		<div class="carousel-item active notification">
			<div class="carousel-item-inner bg-maroon" <?php echo $bg_color; ?>>
				<?php echo $this->current_notification->post_content; ?>
			</div>
		</div>
	</div>
</div>			
			<?php

            
            
        } elseif ( $this->has_notification ) {
			
			$bg_color = '';
			
			if ( isset( $this->current_notification->bg_color ) && !empty( $this->current_notification->bg_color )) {
				$bg_color = sprintf( 'style="background-color:%s"', $this->current_notification->bg_color );
			}
			
			?>
<div class="carousel slide" data-ride="" data-pause="hover">
	<div class="carousel-inner" role="listbox">
		<div class="carousel-item active notification">
			<div class="carousel-item-inner bg-maroon" <?php echo $bg_color; ?>>
				<div class="message">
					<h3 class="h1"><?php echo $this->current_notification->post_title; ?></h3>
					<div style="font-size:1.8em;"><?php echo $this->current_notification->post_content; ?></div>
				</div>
			</div>
		</div>
	</div>
</div>			
			<?php
		
			
			
			
		} 
		
		
	}
	
	
	
	function get_notification_title() {
		
		$this->get_notification();
		
		return $this->current_notification->post_title;
		
	}
	
	
    
    
    
    public function is_notification_ribbon() {
        
        
        return $this->current_notification->homepage_ribbon;
        
        
    }
	
    public function has_alternate_slide() {
        
        
        return $this->current_notification->alt_slide;
        
        
    }
	
    
    
    
    
    public function is_current_page_cta_page() {
        
        $notification_url = parse_url( $this->current_notification->cta['url'] );
        
        
        
        
        //Current paage url
        $request = $_SERVER['REQUEST_URI'];
        
        $request = parse_url( $request );
        
        
       // var_dump( $request );
        //var_dump( $notification_url );
        
        return ( $request['path'] == $notification_url['path'] );
        
        
    }
    
    
    
    
    
    
    
    /**
     *      Clear Page Cache
     *
     *
     *
     */
    public function clear_page_cache( $post_id, $post ) {
        
        
        if ( $post->post_type == 'notifications' ) {
            
            if ( function_exists( 'spinupwp_purge_site' ) ) {
          
                spinupwp_purge_site(); 
                
            }
            
        }
        
        
    }
    
    
    
    
    
    
    
    
    
    
    /**
     *      Singleton
     *
     */
    
    public static function get_instance() {
        
        if ( self::$instance == null ) {
            
            self::$instance = new self;
        
        }
        
        return self::$instance;
        
    }
	
	
		
		
}



//global $WPQuickNotifications;



endif; //class_exists




/**
 *		Functions
 *
 *
 */

function WPQuickNotifications() {
    
    return WPQuickNotifications::get_instance();

}

WPQuickNotifications();




/**
 *		Return a notification if it is current
 *
 *
 */

function wp_quick_notification() {

//	global $WPQuickNotifications;


	WPQuickNotifications()->get_notification()->format_notification();

	//var_dump( $WPQuickNotifications );


	
}






function has_quick_notification() {

	
	return WPQuickNotifications()->has_notification();
	
}


function get_notification_title() {
		
	return WPQuickNotifications()->get_notification_title();
	
}


function get_current_notification() {


	return WPQuickNotifications()->get_notification();
	
}


function is_notification_ribbon() {
    
    return WPQuickNotifications()->is_notification_ribbon();
}

function has_alternate_slide() {
    
    return WPQuickNotifications()->has_alternate_slide();
}



?>