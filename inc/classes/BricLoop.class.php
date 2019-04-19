<?php

/**
 *		Class to control the loop
 *		so things don't spin out of control
 *
 */


class BricLoop {
	
	public $contentTemplate = '';
	
	function __construct() {
		
		add_action( 'wp', array( $this, 'setupContent' ));
		
		global $SiteInfo;
		
		$this->SiteInfo = $SiteInfo;
		
		
	}
	
	
	
	public function setupContent() {
		
		if ( empty( $this->contentTemplate ) ) {
			
			$this->contentTemplate = 'basic';
			
		}
		
		
		
		//Check for woocommerce 
		if ( function_exists('is_product') ) {
			
			if ( is_product() ) {
				
				$this->contentTemplate = 'bricproduct';
				
			}
			
		}
		
		
		elseif ( is_search() || is_archive() || is_home() ) {
			
			$this->contentTemplate = 'excerpt';
		}
	
		
	}
	
	
	
	
	public function get_before_loop_posts() {
		
		
		
		//If woocommerce, don't do the sidebar.
		if ( function_exists('is_product') ) {
			
			if ( is_product() ) {
				
				return;
				
			}
		
		}

		
		
		if ( is_archive() || is_home() || is_single() || is_search() || is_404() ) {
			
					
			//$this->get_pusher();
			//echo '<div class="container"><div class="row"><div class="archive-posts-wrapper recent-posts col">';
			echo '<div class="archive-posts-wrapper recent-posts '.$this->get_content_class( 'main' ).'"><div class="row">';
			add_action( 'bric_after_loop', array( $this, 'close_div'), 10 );	//call early so we can slide in the sidebar
			add_action( 'bric_after_loop', array( $this, 'close_div'), 15 );	
			//add_action( 'bric_after_loop_posts', array( $this, 'close_div'), 50 );	
		
		}
		
		
		
	}
	
	
	
	public function get_before_loop() {
		
		if ( is_search() ) {
			
					
			//$this->get_pusher();
			printf( '
					<div class="col-12 mb-2">
						<h1>Search Results for: %s</h1>
						%s
					</div>
				', get_search_query(), get_search_form(0) );
			//add_action( 'bric_after_loop', array( $this, 'close_div'), 20 );	//call early so we can slide in the sidebar
			//add_action( 'bric_after_loop', array( $this, 'close_div'), 50 );	
			//add_action( 'bric_after_loop', array( $this, 'close_div'), 50 );	
		
		}
		elseif ( is_404() ) {
			
					
			//$this->get_pusher();
			echo( '<div class="col-12 mb-2"><h1>Sorry, that page doesn\'t exist here.</h1></div>' );
			
		//	add_action( 'bric_after_loop', array( $this, 'close_div'), 20 );	//call early so we can slide in the sidebar
			//add_action( 'bric_after_loop', array( $this, 'close_div'), 50 );	
			//add_action( 'bric_after_loop', array( $this, 'close_div'), 50 );	
		
		}
		
		
		
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

		
		
		if ( !is_404() ) {
			
			get_template_part( 'content', $this->contentTemplate );
			
		}
		
		
		
	}
	
	
	
	
	public function set_post_class() {
		
		add_filter( 'post_class', array( $this, '_set_post_class'), 10, 3 );
		
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
			?>
<div class="keep-reading col-12 mb-3">
	<h3>Keep Reading</h3>
	<div class="nav-previous alignleft has-btn-primary has-laquo"><?php previous_post_link( '%link'); ?></div> 
	<div class="nav-next alignright has-btn-primary has-raquo"><?php next_post_link( '%link' ); ?></div>
</div>
			<?php
			}
			
		}
		
		if ( is_archive() || is_home() ) {
			
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
		
		if ( (  ( get_post_type() == 'post' ) || ( get_post_type() == 'testimonials-widget' ) && ( is_archive() || is_home() || is_single() ) ) || is_search() || is_404() ) {
		
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
		
		
		$copyright_text = get_copyright_text( $this->SiteInfo->copyright_start );
		
		return sprintf( '<div class="copyright">%s %s</div>', $copyright_text, $this->SiteInfo->copyright_owner ); 
		
		
	}
	
	
	
	
	public function get_developer_credits() {
		
		if ( !empty( DEVELOPER_NAME ) && !empty( DEVELOPER_URL ) ) {
			
			return sprintf( '<div class="developer-credits"><a href="%s" target="_blank">Website by %s</a></div>', DEVELOPER_URL, DEVELOPER_NAME );
			
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

		
		
		$Carousel = new \Bric\Carousel( $gallery );
		
		
		//The transition value returns the class
		$Carousel->wrapperClass[] = $this->SiteInfo->carousel['transition'];
		
		if ( $this->SiteInfo->carousel['edge_to_edge'] ) {
			$Carousel->wrapperClass[] = 'edge-to-edge';
			$Carousel->mainSize = 'full';
			
		}
		
		if ( $this->SiteInfo->carousel['show_caption'] ) {
			$Carousel->includeCaption = true;
		}
		
		
		if ( $this->SiteInfo->carousel['speed'] ) {
			$Carousel->slideSpeed = $this->SiteInfo->carousel['speed'];
		}
		
				
		$Carousel = apply_filters( 'bric_header_carousel', $Carousel );
		
		echo $Carousel->buildGallery();
		
	}
	
	
	
	
}


global $BricLoop;

$BricLoop = new BricLoop();
