<?php


define( 'BRIC_GOOGLE_MAPS_API_KEY', 'AIzaSyDUy-vuqQLK4APwNGoJ2MWDn04nTMzeZJ8' );


class Bric {
	
	public $SiteInfo;
	public $errors = array();
	
	
	function __construct() {
		
		
		$this->setup_theme();
		
		add_action( 'wp_enqueue_scripts', array( $this, 'easy_google_fonts_edits'), 999 );
		
	}
	
	
		
	
	
	function setup_theme() {
				
		add_action( 'after_setup_theme', array( $this, 'theme_support') );
		add_action( 'after_setup_theme', array( $this, 'nav_menus') );

		
		
		add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_footer', array( $this, 'enqueue_footer_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) ); // queue early because of jquery in footer
				
		add_action( 'widgets_init', array( $this, 'register_sidebars') );
		
		add_action( 'wp', array( $this, 'get_theme_globals' ));
		
		add_action( 'wp', array( $this, 'site_breadcrumbs') );
		
		add_filter( 'image_size_names_choose', array( $this, 'add_image_size_to_editor' ));

		
		add_filter( 'wpseo_metabox_prio', array( $this, 'yoast_meta_box' ));
		
		//Add redirect for sitemap.xml if yoast is active and we have nginx
		//	add_action( 'init', [$this, 'sitemap_xml'] );
		
	}
	
	
	
	/**
	 *		TODO: If need be, add a rewrite rule so that sitemap.xml goes to sitemap_index.xml if yoast is being used
	 *
	 *
	 */
	
	
	function sitemap_xml() {
		
		//see if nginx is being used
		if ( strpos( $_SERVER['SERVER_SOFTWARE'], 'ginx') ) {
			
			//check if yoast is here
			if ( function_exists( 'yoast_breadcrumb' ) && WPSEO_Options::get( 'enable_xml_sitemap' ) ) {
				
				
				//Add rewrite
				//add_rewrite_rule();
				
				
				if( $_SERVER['REQUEST_URI'] == '/sitemap.xml' ) {
					
					
					
				}
				
				
			}
			
			
		}
		
		
	}
	
	
	
	
	
	
	function easy_google_fonts_edits() {
		
		//remove_action( 'wp_enqueue_scripts', array( 'EGF_Frontend', 'enqueue_stylesheets' ) );
		wp_dequeue_style( 'tt-easy-google-fonts' );
		
		
	}
	
	


	/** 
	 *		Register Theme Styles
	 *
	 */
	
	function register_styles() {
		
		//var_dump(get_stylesheet_directory_uri().'/assets/css/bric-style.css' );
		/*
		if ( is_child_theme() ) {
			wp_register_style( 'bric-child', get_stylesheet_directory_uri().'/assets/css/bric-style.css' );
		} 
		else {
*/			wp_register_style( 'bric', get_stylesheet_directory_uri().'/assets/css/bric-style.css' );
		//}
		
		
		wp_register_style( 'bric-customizer', get_stylesheet_directory_uri().'/assets/css/bric-style-customizer.css' );
		
		
		
		
	}
	
	 
	
	/** 
	 *		Enqueue Styles
	 *
	 *
	 */
	

	function enqueue_styles() {

		if ( is_customize_preview() ) {
		
			wp_enqueue_style( 'bric-customizer' );
			
			
		}
		else {
		
			wp_enqueue_style( 'bric' );
			
		}

	}	

	
	/** 
	 *		Enqueue Styles for Footer
	 *
	 *
	 */
	

	function enqueue_footer_styles() {

			wp_enqueue_style( 'tt-easy-google-fonts' );
					
	}	

	
	/** 
	 *		Register Theme Scripts
	 *
	 */
	
	function register_scripts() {
		
		
		wp_deregister_script( 'jquery' );
		
		//wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js', array(), null, true );
		wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js', array(), null, true );
		
		//wp_register_script( 'popper', get_template_directory_uri().'/assets/js/popper.min.js', array( 'jquery' ), '1.12.5', true );

		wp_register_script( 'bootstrap', get_template_directory_uri().'/assets/js/bootstrap.bundle.min.js', array( 'jquery' ), null, true );
		
		
	}
	
	 
	
	
	
	
	
	/** 
	 *		Enqueue Theme Styles
	 *
	 *
	 */
	

	function enqueue_scripts() {
		
		//wp_enqueue_script( 'popper' );
		wp_enqueue_script( 'bootstrap' );
		

	}	
	
	
	
	
	
	/**
	 *		Theme Support
	 *
	 *
	 *
	 */
	
	function theme_support() {
		
		
		add_theme_support( 'post-thumbnails' );

		add_theme_support( 'custom-logo', array(
			'height'      => 100,
			'width'       => 400,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array( 'site-title', 'site-description' ),
		));		
		
		add_theme_support( 'automatic-feed-links' );
		
		add_theme_support( 'html5', array( 
			'comment-list', 
			'comment-form', 
			'search-form', 
			'gallery', 
			'caption' 
		));
		
		add_theme_support( 'title-tag' );
		
		add_theme_support( 'post-formats',  array ( 
			'aside', 
			'gallery', 
			//'quote', 
			'image', 
			'video',
		));

		
		//Register Nav Menus
		$this->nav_menus();
		
		
	}
	
	
	
	/**
	 *		Nav Menus
	 *
	 *
	 *
	 */
	
	function nav_menus() {

		register_nav_menus( array(
			'primary'   => 'Main Site Menu',
		) );
	
		
	}
	
	
	
	
	/**
	 *  	Get Theme Global Variables		
	 *
	 */
	
	function get_theme_globals() {
		
		global $SiteInfo;
		$this->SiteInfo = $SiteInfo;
		

	}
	
	
	
	
	
	
	
	
	/**
	 *		Breadcrumbs
	 *
	 *
	 */
	
	
	public function site_breadcrumbs() {
		
		//add_action( 'bric_after_header', array( $this, 'print_breadcrumbs'));
		if ( $this->SiteInfo->breadcrumbs['enable'] ) {
			
			if( $this->SiteInfo->breadcrumbs['hide_on_home'] ) {
				
				if ( !is_front_page() ) {
					
					add_action( $this->SiteInfo->breadcrumbs['action'], array( $this, 'print_breadcrumbs') );
					//var_dump($this->SiteInfo->breadcrumbs['action']);
				}
				
			}
			
			
			
		}
	}
	
	
	
	
	public function print_breadcrumbs() {
		
		
		if ( function_exists('yoast_breadcrumb') ) {
						
			
			$classes = implode( ' ', $this->SiteInfo->breadcrumbs['classes'] );
		
			$open_tag = sprintf( '<p id="breadcrumbs" class="%s">', $classes );
			$close_tag = "</p>";
			
			
			yoast_breadcrumb( $open_tag, $close_tag, true );
		}
		else {
			
			echo '<p>Yoast Breadcrumbs not enabled.</p>';
			
		}
		
		
	}
	
	
	
	
	public function register_sidebars() {
		
		$args = array(
			'name' => 'Blog Posts Sidebar',
			'id' => 'posts-sidebar-main',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
				
		
		);
		
		register_sidebar( $args );
		
		
	}
	
	
	
	
	
	public function add_image_size_to_editor( $sizes ) {
		return array_merge( $sizes, array(
			'medium_large' => 'Medium Large',
		) );
	}
	
	
	/**
	 *		Make sure Yoast SEO is below the lower priority ACF content
	 *
	 *
	 */
	
	public function yoast_meta_box( $priority ) {
		
		return 'low';
		
	}

	
	
}


global $Bric;

$Bric = new Bric();
$Bric->theme_support();

