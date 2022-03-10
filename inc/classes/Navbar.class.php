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
		add_action( 'customize_preview_init', array( $this, 'gather_assets' ) );		
		
		add_shortcode( 'navbar_toggler', [ $this, 'get_navbar_toggler'] );

		
	}
	
	
	
	public function logo_is_svg() {
		
		if ( empty( $this->logo_id ) ) {
			return false;
		}
		
		$mime = get_post_mime_type( $this->logo_id );
		
		if ( $mime == 'image/svg+xml' ) {
			
			return true;
		}
		else {
			return false;
		}
		
		
	}
	
	
	
	public static function get_svg_source( $id ) {
		
		if ( empty( $id )) {
			return $id;
		}
		
		//Filter to return something other than the SVG of the id
		$file_override = apply_filters( 'bric_navbar_brand_svg_override', null, $id );
		
		if ( !empty( $file_override )) {
			return $file_override;
		}
		
		$file = get_attached_file( $id );
		
		$svg = file_get_contents( $file );
        
        $svg = preg_replace( '/^\<\?xml.+\?\>/m', '', $svg );
		
		
		//Strip out the comment
		//todo: can't get this to work
		/*
		$comment = '/(?=<!--)([\s\S]*?)-->/m';
		
		$svg = preg_replace( $comment, '', $svg );
	
		//Strip out the comment
		$comment = '/(?=<?)([\s\S]*?)-->/m';
		
		$svg = preg_replace( $comment, '', $svg );
*/
		
		$re = '/viewBox=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?/m';
		preg_match( $re, $svg, $viewBox );
		
		$dimensions = explode( ' ', $viewBox[1] );

		$width = $dimensions[2] - $dimensions[0];
		$height = $dimensions[3] - $dimensions[1];
		
		$dimension_css = sprintf( '<svg style="width:%spx; height:%spx"', $width, $height );
		
		
		
		//$svg = str_replace( '<svg', $dimension_css, $svg );
		
		return $svg;
	}
	
	
	
	
	
	public function gather_assets() {
		
		//Get the site logo
		$this->has_logo = has_custom_logo();
		
		if ( $this->has_logo ) {
			
			$this->logo_id = get_theme_mod( 'custom_logo' );
			//$this->logo = get_custom_logo( );
			
			if ( $this->logo_is_svg() ) {
				
				$this->logo = self::get_svg_source( $this->logo_id );
				
			}
			else {
			
				$this->logo = wp_get_attachment_image( $this->logo_id, 'medium' );
				
			}
			
			
		}
		
		//Get site info
		$this->title = get_bloginfo( 'name' );
		$this->tagline = get_bloginfo( 'description' );
		$this->url = get_bloginfo( 'url' );
		
		
		/**
		 *		filter the navbar menu location
		 *
		 */
		$menu = apply_filters( 'bric_navbar_menu_location', 'primary' );
		
		
		//Get main menu
		$this->wp_menus = get_nav_menu_locations();

		
		if ( !empty( $this->wp_menus[$menu] ) ) {
		
			$this->main_nav_menu_obj = wp_get_nav_menu_object( $this->wp_menus[$menu] );
			$this->navbar_collapse_id = $this->main_nav_menu_obj->slug.'-'.$this->main_nav_menu_obj->term_id;

		}
			
		
		$this->main_nav_menu_items_wrap = '<ul id="%1$s" class="%2$s">%3$s</ul>';
		$this->main_nav_menu_container_class = 'collapse navbar-collapse';
		

		//Check for a "right side" menu
		$menu_left = apply_filters( 'bric_navbar_menu_location_left', 'primary_l' );
			
		
		if ( !empty( $this->wp_menus[$menu_left] ) ) {
		
			$this->main_nav_menu_obj_left = wp_get_nav_menu_object( $this->wp_menus[$menu_left] );
			$this->navbar_collapse_id_left= $this->main_nav_menu_obj_left->slug.'-'.$this->main_nav_menu_obj_left->term_id;

		}
			
		
		$this->main_nav_menu_items_wrap = '<ul id="%1$s" class="%2$s">%3$s</ul>';
		$this->main_nav_menu_container_class = 'collapse navbar-collapse';




	}
	
	
	
	
	
	public function get_main_nav_menu( $side = 'right') {
		

		if ( !empty( $this->wp_menus )  ) {
		
			$nav_menu = '';

			if ( $side == 'right' ) {

				/**
				 *		filter the navbar menu location
				*
				*/
				$location = apply_filters( 'bric_navbar_menu_location', 'primary' );


					
				$this->main_nav_menu = wp_nav_menu( array(
					'theme_location' => $location,
					'echo' => 0,
					'menu_class' => 'navbar-nav ml-auto',
					'container' => 'div',
					'container_class' => $this->main_nav_menu_container_class,
					'container_id' => $this->navbar_collapse_id,
					'walker' => new BootstrapNavwalker(),
					'items_wrap' => $this->main_nav_menu_items_wrap, //<ul id="%1$s" class="%2$s">%3$s</ul>

				));	
				
				$nav_menu = $this->main_nav_menu;
			
			} elseif ( $side == 'left' ) {

				if ( isset( $this->main_nav_menu_obj_left ) ) {

					$this->main_nav_menu_left = wp_nav_menu([
						'menu' => $this->main_nav_menu_obj_left,
						'echo' => 0,
						'menu_class' => 'navbar-nav ml-auto',
						'container' => 'div',
						'container_class' => $this->main_nav_menu_container_class,
						'container_id' => $this->navbar_collapse_id_left,
						'walker' => new BootstrapNavwalker(),
						'items_wrap' => $this->main_nav_menu_items_wrap, //<ul id="%1$s" class="%2$s">%3$s</ul>
						]);

					$nav_menu = $this->main_nav_menu_left;

				}
			}

			
			return $nav_menu;

		}
		
	}
	
	
	
	
	
	
	
	public function get_navbar() {
		
		global $SiteInfo;
		
		
		
		$navbar_expression = include locate_template( 'template-parts/components/navbar/navbar-expression.php' );

		$this->navbar_options = array(
			'container' => $SiteInfo->navbar->container,
			'expand' => $SiteInfo->navbar->expand,
			'bg_color' => $SiteInfo->navbar->bg_color,
			'navbar_color' => $SiteInfo->navbar->navbar_color,
			'content_before' => array(
				//'html' => '',
				//'above_navbar' => false,
			),
			'navbar_expression' => $navbar_expression,
		);
		
		/**
		 *		Filter the options
		 *
		 *
		 */	
		
		
		
		$this->navbar_options = apply_filters( 'bric_navbar_options', $this->navbar_options, $this );
		
		

		
		
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
			   $this->get_navbar_brand(), //1
			   $this->get_main_nav_menu(), //2
			   $this->get_navbar_toggler(), //3 
			   $this->navbar_options['expand'], //4
			   ( $this->navbar_options['container'] ) ? '<div class="container navbar-inner-wrapper">' : '', //5
			   ( $this->navbar_options['container'] ) ? '</div>' : '', //6
			   $this->navbar_options['navbar_color'], //7
			   $this->navbar_options['bg_color'], //8
			   $this->content_above_nav, //9
			   $this->get_header_cta(), //10
			   $this->get_main_nav_menu( 'left' ) //11
			  );
		
		
	}
	
	
	
	
	public function get_header_cta() {
		
		ob_start();
		
		dynamic_sidebar( 'header-cta' );
		
		return ob_get_clean();		
		
	}
	
	
	
	
	
	
	
	
	
	public function get_navbar_brand() {
		
		global $SiteInfo;
		
			
		// Get the option for brand type
		$navbar_brand_type = $SiteInfo->navbar->brand_type;
		
		
		include locate_template( 'template-parts/components/navbar/navbar-brand.php' );

		return $this->navbar_brand;
				
		
	}
	
	
	
	
	
	
	
	public function get_navbar_toggler() {
		


        if ( !isset( $this->navbar_collapse_id ) ){
            $this->gather_assets();
        }
			

        ob_start();
        
        include( locate_template( 'template-parts/components/navbar/navbar-toggler.php' ) );        
        
		$this->navbar_toggler = ob_get_clean();

		
		return $this->navbar_toggler;		
		
		
	}
	
	
	
	/**
	 *		Get the menu object for Primary Menu
	 *
	 *
	 */
	
	public static function get_primary_nav_menu_obj() {
		
		$nav_menu_locations = get_nav_menu_locations();
			
		$primary_nav = $nav_menu_locations['primary'];
			
		return wp_get_nav_menu_object( $primary_nav );

	}
	
	



	public function get_upper_header() {
		//Outout top navbar
		
		include locate_template( 'template-parts/components/navbar/upper-header.php' );
				
	}
	
	
	
	
	
	
}

global $Navbar;

$Navbar = new Navbar;



?>
