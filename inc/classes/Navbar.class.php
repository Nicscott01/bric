<?php



class Navbar {
	
	public $logo_id = null;
	public $logo = null;
	public $title = '';
	public $tagline = '';
	public $has_logo = false;
	public $navbar_collapse_id = 'collapse-id-not-set';
	public $main_nav_menu = '';
	public $main_nav_menu_obj = array();
	public $main_nav_menu_items_wrap = '';
	public $content_above_nav = '';
	public $navbar_options = array();
	public $navbar_toggler = '';
	public $wp_menus = array();
	
	function __construct() {
		
		add_action( 'wp', array( $this, 'gather_assets' ) );		
		

		
	}
	
	
	
	public function gather_assets() {
		
		//Get the site logo
		$this->has_logo = has_custom_logo();
		
		if ( $this->has_logo ) {
			
			$this->logo_id = get_theme_mod( 'custom_logo' );
			//$this->logo = get_custom_logo( );
			$this->logo = wp_get_attachment_image( $this->logo_id, 'medium' );
			
		}
		
		//Get site info
		$this->title = get_bloginfo( 'name' );
		$this->tagline = get_bloginfo( 'description' );
		$this->url = get_bloginfo( 'url' );
		
		
		//Get main menu
		$this->wp_menus = get_nav_menu_locations();
		
		if ( !empty( $this->wp_menus['primary'] ) ) {
		
			$this->main_nav_menu_obj = wp_get_nav_menu_object( $this->wp_menus['primary'] );
			$this->navbar_collapse_id = $this->main_nav_menu_obj->slug.'-'.$this->main_nav_menu_obj->term_id;

		}
		
		
		
		$this->main_nav_menu_items_wrap = '<ul id="%1$s" class="%2$s">%3$s</ul>';
		$this->main_nav_menu_container_class = 'collapse navbar-collapse';
		
	}
	
	
	
	
	
	public function get_main_nav_menu() {
		
		if ( !empty( $this->wp_menus['primary'] ) ) {
		
				
			$this->main_nav_menu = wp_nav_menu( array(
				'theme_location' => 'primary',
				'echo' => 0,
				'menu_class' => 'navbar-nav ml-auto',
				'container' => 'div',
				'container_class' => $this->main_nav_menu_container_class,
				'container_id' => $this->navbar_collapse_id,
				'walker' => new BootstrapNavwalker(),
				'items_wrap' => $this->main_nav_menu_items_wrap, //<ul id="%1$s" class="%2$s">%3$s</ul>

			));		
			
			return $this->main_nav_menu;

		}
		
	}
	
	
	
	
	
	
	
	public function get_navbar() {
		
		$this->navbar_options = array(
			'container' => false,
			'expand' => 'md',
			'bg_color' => 'light',
			'navbar_color' => 'light',
			'content_before' => array(
				//'html' => '',
				//'above_navbar' => false,
			),
			'navbar_expression' => '%9$s
<nav class="navbar navbar-expand-%4$s navbar-%7$s bg-%8$s">%5$s
%1$s
<div class="right-side">
%10$s
%3$s
%2$s
</div>
%6$s</nav>',
		);
		
		/**
		 *		Filter the options
		 *
		 *
		 */	
		
		$this->navbar_options = apply_filters( 'bric_navbar_options', $this->navbar_options );
		
		

		
		
		if ( !empty( $this->navbar_options['content_before'] )  ) {
			
			/*
			if ( !$this->navbar_options['content_before']['above_navbar'] ) {
				
				$this->main_nav_menu_items_wrap = '<div class="content-above-nav-menu">'.$this->navbar_options['content_before']['html'].'</div>'.$this->main_nav_menu_items_wrap;

				$this->main_nav_menu_container_class = $this->main_nav_menu_container_class.' has-content-above';
				
			}
*/
			//else {
				
				
				
				$this->content_above_nav = sprintf( '<div class="%s"><div class="row">%s</div></div>',
											 ( $this->navbar_options['container'] ) ? 'content-above-navbar container' : 'content-above-navbar container-fluid',											 
											 $this->navbar_options['content_before']['html']
											);
				
			//}
			
			
		}		
		
				
		
		
		
		
		printf( $this->navbar_options['navbar_expression'], 
			   $this->get_navbar_brand(),
			   $this->get_main_nav_menu(),
			   $this->get_navbar_toggler(),
			   $this->navbar_options['expand'],
			   ( $this->navbar_options['container'] ) ? '<div class="container">' : '',
			   ( $this->navbar_options['container'] ) ? '</div>' : '',
			   $this->navbar_options['navbar_color'],
			   $this->navbar_options['bg_color'],
			   $this->content_above_nav,
			   $this->get_header_cta()
			  );
		
		
	}
	
	
	
	
	public function get_header_cta() {
		
		ob_start();
		
		dynamic_sidebar( 'header-cta' );
		
		return ob_get_clean();		
		
	}
	
	
	
	
	
	
	
	
	
	public function get_navbar_brand() {
		
		/**
		 *		Apply filter: bric_navbar_brand_type
		 *
		 *		choices: text, image, textimage
		 *
		 */
		
		
		$navbar_brand_type = apply_filters( 'bric_navbar_brand_type', 'textimage', 10 );
		
		switch ( $navbar_brand_type ) {
				
			case 'text' :
				
				$this->navbar_brand = sprintf( '<a class="navbar-brand" href="%s">%s</a>', $this->url, $this->title );
			
				break;
				
			case 'image' :
				
				$this->navbar_brand = sprintf( '<a class="navbar-brand" href="%s">%s</a>',$this->url, $this->logo );
				break;
				
			case 'textimage' :
				
				$this->navbar_brand = sprintf( '<a class="navbar-brand" href="%s"><div class="tagline">%s</div> %s</a>', 
											  $this->url,
											  '<span class="blogtitle">'.get_bloginfo('title').'</span>',
											  wp_get_attachment_image( $this->logo_id, 'medium' ) );
				break;
		}
			
		
		return $this->navbar_brand;
				
		
	}
	
	
	
	
	
	
	
	public function get_navbar_toggler() {
		
		if ( !empty( $this->main_nav_menu ) ) {
			
			$this->navbar_toggler = sprintf( '
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#%1$s-sidebar" aria-controls="%1$s" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	  </button>', $this->navbar_collapse_id );

		}
		
		
		return $this->navbar_toggler;		
		
		
	}
	
	
	
	
	
}

global $Navbar;

$Navbar = new Navbar;



?>
