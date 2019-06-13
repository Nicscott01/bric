<?php



class BricShortcodes {
	
	
	
	function __construct() {
		
		//Navigation Menu
		add_shortcode( 'nav_menu', array( $this, 'menu_sc' ) );

		//Business Hours
		add_shortcode( 'hours', array( $this, 'hours_sc') );
		
		//Social Media
		add_shortcode( 'social_media', array( $this, 'social_media_sc') );
		
		//Call to Action
		add_shortcode( 'call_to_action', array( $this, 'call_to_action_sc') );
		
		
		//Recent Posts
		add_shortcode( 'recent_posts', array( $this, 'recent_posts_sc' ) );
		
		
		//Frontend Login Form
		add_shortcode( 'login_form', array( $this, 'login_form_sc') );
		add_filter( 'login_form_bottom', array( $this, 'login_form_bottom'), 10, 2 );
		
		
		
		
		//Page title move around
		add_shortcode( 'page_title', [ $this, 'page_title_sc' ], 10, 2 );
		//add_action( 'wp', [ $this, 'evaluate_page_title_sc' ] );
		
	}
	
	
	
	
	function menu_sc( $atts, $content ) {
		
		$atts = shortcode_atts( array(
			'menu' => '',
			'echo' => false,
		), $atts );
		
		
		return wp_nav_menu( $atts );
		
	}
	
	
	
	
	
	function hours_sc( $atts, $content ) {
		
		
		global $SiteInfo;
		
		
		if( !empty( $SiteInfo->operating_hours )) {
			
			$o = '';
			
			foreach( $SiteInfo->operating_hours as $hour ) {
				
				$o .= sprintf( '<li><span class="day">%s</span> <span class="hours">%s</span></li>', $hour['day'], $hour['hours'] );
				
				
			}
			
			
			return sprintf( '<ul class="list-unstyled operating-hours">%s</ul>', $o );
			
		}
		
		
		
	}


	
	
	
	
	
	
	function social_media_sc( $atts, $content ) {
		
		global $SiteInfo;
		
		if ( !empty( $SiteInfo->social ) ) {
			
			$o = '';
						
			foreach ( $SiteInfo->social as $social ) {
								
				$o .= sprintf( '<li class="social-account list-inline-item"><a href="%s" target="_blank" aria-label="Follow us on %s">%s</a></li>', $social['url'], $social['platform'], $social['icon']->element );
				
				
			}
			
			return sprintf( '<ul class="list-unstyled list-inline social-media">%s</ul>', $o );
			
			
		}
		
		
	}
	
	
	
	
	
	/**
	 *		Shortcode for Call to Action
	 *
	 *
	 */
	
	function call_to_action_sc( $atts, $content ) {
		
		$atts = shortcode_atts( array(
			'title' => '',
			'image' => '',
			'link' => '',
			'class' => '',
			'size' => 'medium',
		), $atts );
		
		
		return sprintf( '<div class="cta %s"><a href="%s"><span class="title">%s</span>%s</a></div>', 
				 $atts['class'],
			   	get_permalink( $atts['link'] ),
				$atts['title'],
				wp_get_attachment_image( $atts['image'], $atts['size'] )
			   );
		
		
		
		
	}
	
	
	
	
	
	/**
	 *		Recent Posts Shortcode
	 *
	 *
	 */
	
	public static function recent_posts_sc( $atts, $content ) {
		
		$atts = shortcode_atts( array(
			'post_type' => 'post',
			'posts_per_page' => 5,
			'content_template' => 'excerpt',
			'taxonomy' => '',
			'terms' => '',
			'order' => 'ASC',
			'orderby' => 'title',
		), $atts );
		
		
		if ( !empty( $atts['taxonomy'] ) ) {
			
			$atts['tax_query'] = array(
				array(
					'taxonomy' => $atts['taxonomy'],
					'field' => 'slug',
					'terms' => $atts['terms'],
				),
			);
		}
		
		
		
		$posts = new WP_Query( $atts );
		
		//var_dump( $posts->posts );
		if ( !empty( $atts['content_template'] ) ) {
			
			global $post;
			
			ob_start();
			
			foreach ( $posts->posts as $post ) {
				
				setup_postdata( $post );
								
				get_template_part( 'content', $atts['content_template'] );
				
			}
			
			$content = ob_get_clean();
			
			wp_reset_postdata();
		}
		
		return $content;
		
		
		
		
		
		
	}
	
	
	
	
	
	
	public function login_form_sc( $args ) {
		
		if ( !is_user_logged_in() ) {
			
			$o = '<div class="login-form-wrapper">';
			
			//$o .= '<div class="alert alert-primary" role="alert">You must login to edit your info.</div>';
			
			$o .= wp_login_form( array(
				'echo' => false,
			));
			
			
			$o .= '</div>';
			
			return $o;
			
			
		}
		
		
		
	}
	
	
	
	public function login_form_bottom( $content, $args ) {
		
		$content .= sprintf( '<p>I <a href="%s">lost my password.</a></p>', wp_lostpassword_url() );

		return $content;
		
	}
	
	
	
	
	
	
	
	
	
	/**
	 *		Page Title
	 *		-Use of shortcode will place the page title where it is!
	 *
	 *
	 */
	
	public function page_title_sc( $content, $args ) {
		
		
		return sprintf( '<h1 class="page-title page-title-sc">%s</h1>', get_the_title() );
		
		
	}

	
	
	/**
	 *		Evaluate Page Title SC
	 *		-see if the sc is in the post_content
	 *		so we can remove the regular title early enough
	 *
	 */
	
	public function evaluate_page_title_sc() {
		
		global $post;
		
		if ( has_shortcode( $post->post_content, 'page_title' ) ) {
		
			
			//Remove the title
			
		}
		
		
		
	}
	
	
}


new BricShortcodes();