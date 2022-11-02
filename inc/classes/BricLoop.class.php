<?php

/**
 *		Class to control the loop
 *		so things don't spin out of control
 *
 */


class BricLoop {
	
	var $contentTemplate = 'basic';
	
	/**
	 *	Instnace
	 */
	public static $instance = null;
	
	
	
	function __construct() {
		
		add_action( 'wp', array( $this, 'setupContent' ));
		
		global $SiteInfo;
		
		$this->SiteInfo = $SiteInfo;
		
		
	}
	
	
	
	public function setupContent() {
		
		
		
		$pt = get_post_type();
       	 	$template_string_archive = 'content-excerpt-' . $pt . '.php';
        	$template_string = 'content-' . $pt . '.php';
        
        	$template_file_archive = locate_template( $template_string_archive ); 
        	$template_file = locate_template( $template_string ); 
            

		$look_for_another = false;


		if ( is_search() ) {

			$search_template = locate_template( 'content-search-result.php' );

			if ( !empty( $search_template ) ) {
				$this->contentTemplate = 'search-result';
			} else {
				$look_for_another = true;
			}


		} elseif ( is_archive() || is_home() || $look_for_another ) {

		    if ( !empty( $template_file_archive ) ) {

			$this->contentTemplate = 'excerpt-' . $pt;

		    } else {

			$this->contentTemplate = 'excerpt';

		    }

			} elseif ( !empty( $template_file ) ) {

		    $this->contentTemplate =  $pt;

		} 
		
		
		/**
	 	 *		Filter: bric_content_template
		 *
		 *		@since 5/23/19
		 *
		 */
		
		$this->contentTemplate = apply_filters( 'bric_content_template', $this->contentTemplate, $this );
		
	
		
	}
	
	
	
	
	public function get_before_loop_posts() {
		
		
		
		//If woocommerce, don't do the sidebar.
		if ( function_exists('is_product') ) {
			
			if ( is_product() ) {
				
				return;
				
			}
		
		}

		
		if ( is_archive() || is_home() || is_search() || is_404() ) {
			
            
			get_template_part( 'template-parts/archive-start' );
			
			add_action( 'bric_after_loop', [$this, 'archive_end' ], 10 );
			
		//	echo '<div class="archive-posts-wrapper recent-posts '.$this->get_content_class( 'main' ).'"><div class="row">';
			
			
			//add_action( 'bric_after_loop', array( $this, 'close_div'), 10 );	//call early so we can slide in the sidebar
			//add_action( 'bric_after_loop', array( $this, 'close_div'), 15 );	

			
		}
		
		
		
	}
	
	
	public function archive_end() {
		
		get_template_part( 'template-parts/archive-end' );
		
	}
	
	
	
	
	/**
	 *		Retrieve the archive header if an archive page
	 *
	 *		@since v1.1
	 */
	
	public function get_archive_header() {
		
		get_template_part( 'template-parts/archive-heading' );
		
	}
	
	
	
	
	
	
	
	
	
	
	
	public function get_no_posts() {
		
		
		//$this->_get_sidebar();
		
			
		echo '<div class="col"><p>That yielded no results.</p></div>';
		
		/*
		if ( is_search() || is_404() ) {
			
			printf( '<h2>Want to try a search?</h2>%s', get_search_form(0) );
			
		} 
		*/
	}
	
	
	
	
	
	
	public function close_div() {
		
		echo '</div>';
		
	}
	
	
	
	public function get_pusher() {
		
		echo '<div class="pusher col"></div>';
		
	}
	
	
	
	
	/**
	 *		Main function to call loop templates
	 *
	 */
	
	public function get_content() {
		
		$this->set_post_class();

		
		if ( is_search() ) {

			get_template_part( 'template-parts/search-result' );

		} elseif ( !is_404() ) {

			get_template_part( 'content', $this->contentTemplate );
		}
		
		
		
	}
	
	
	
	
	public function set_post_class() {
		
		//add_filter( 'post_class', array( $this, '_set_post_class'), 10, 3 );
		
	}
	
	
	public function _set_post_class( $classes, $class, $post_id ) {
		
		global $SiteInfo;
		
		
		if ( is_main_query() ) {

			
			
			if ( is_archive() || is_home() || is_search() ) {
				
				$classes[] = $SiteInfo->options->article_class_excerpt;
				
			}
			else {
				
				$classes[] = $SiteInfo->options->article_class;

			}
			/*
			if ( !empty( $SiteInfo->article_class ) ) {

				$classes[] = $SiteInfo->article_class;

			}
			else {

				$classes[] = 'col';
			}
			*/
			
		}
		
		
		
		
		return $classes;
		
	}
	
	
	
	/**
	 *		Pagination
	 *
	 *
	 */
	
	public function get_post_pagination() {
		
		if ( function_exists('is_product') ) {
			
			if ( is_product() ) {
				return;		
			}
		}
		
		
		
		if ( is_single() ) {
			
			$adjacents = [];
					
			$adjacents[] = get_adjacent_post();
			$adjacents[] = get_adjacent_post( false, '', false );
			
			
			//var_dump( $adjacents );
			
			if ( !empty( $adjacents )) {
				
				get_template_part( 'template-parts/adjacent-posts' );

			}
			
		}

		
		if ( is_archive() || is_home() || is_post_type_archive() || is_search() ) {
			
			the_posts_pagination( array( 'mid_size' => 1, 'type' => 'list' ) );

		}
		
		
		
		
		
		
	}
	
	
	
	
	
	
	/**
	 *		COMMENTS
	 *
	 *
	 *
	 */
	
	
	public function get_post_comments() {

		// If comments are open or we have at least one comment, load up the comment template.
		 if ( comments_open() || get_comments_number() ) :
			 comments_template();
		 endif;		
		
	}
	
	
	
	
	
	
	
	
	public function get_sidebar() {
		
		$pts = apply_filters( 'bric_sidebar_post_types', [ 'post', 'testimonials-widget' ] );
		
        
		/*if ( (  ( get_post_type() == 'post' ) || ( get_post_type() == 'testimonials-widget' ) || is_home() && ( is_archive() || is_home() || is_single() ) ) || is_search() || is_404() ) {
		*/	
		if ( is_home() || is_singular( $pts ) || ( in_array( get_post_type(), $pts ) && is_archive() ) ) {
		
			//$this->get_pusher(); 

			add_action( 'bric_after_loop', array( $this, '_get_sidebar'), 30 );
			
		}
		
	}
	
	
	
	public function _get_sidebar() {
		
		get_sidebar();
	
	}
	
	
	
	
	
	
	
	public function get_footer() {
		
		get_template_part( 'template-parts/footer', 'basic' );
				
	}
	
	
	
	
	public function get_copyright() {
		
		$copyright_text = get_copyright_text( bric_get_theme_mod( 'lower_footer', 'copyright_year' ) );

		include( locate_template( 'template-parts/copyright.php' ) ); 
		
			
	}
	
	
	
	
	public function get_developer_credits() {
		
		if ( !empty( DEVELOPER_NAME ) && !empty( DEVELOPER_URL ) ) {
			
			get_template_part( 'template-parts/developer-credits' );
						
		}
		
		
		
	}
	
	
	
	
	
	
	public function get_content_class( $what ) {
		
		$defaults = array(
			'xs' => array( 12, 12 ),
			'sm' => array( 12, 12 ),
			'md' => array( 8, 4 ),
			'lg' => array( 9, 3 ),
		);
		
		
		$args = $defaults;
		


		foreach ( $defaults as $name => $size ) {

			$main[] = 'col-'.$name.'-'.$size[0];
			$sidebar[] = 'col-'.$name.'-'.$size[1];

		}

		
		return ( $what == 'main' ) ? implode( ' ', $main) : implode( ' ', $sidebar );
				
		
	}
	
	
	
	
	
	
	/**
	 *		Home carousel
	 *
	 *
	 */
	
	public function home_carousel() {
		
		
		
		if ( is_front_page() ) {
			
			if ( have_rows('slides') ) {
				
				//set the body class to "has-header=carousel'
				//global $SiteInfo;
				
				$this->SiteInfo->body_classes[] = 'has-header-carousel';
					
				add_action( 'bric_before_loop', array( $this, 'header_carousel' ) );
				
				
			}
			
			
		}
		
		
	}
	
	
	
	function header_carousel() {

		
		$gallery = get_field('slides');
        
        include locate_template( 'template-parts/components/carousels/homepage-carousel.php' );
        
		
		
	}
	
	
	
	
	
	
	public static function get_instance() {
		
		if ( self::$instance == null ) {
			
			self::$instance = new self;
			
		}
		
		return self::$instance;
		
	}
	
	
}


global $BricLoop;


function BricLoop() {
	
	return BricLoop::get_instance();
}

$BricLoop = BricLoop();
