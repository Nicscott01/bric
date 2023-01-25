<?php



class BricFilters {
	
	public static $instance;

	public $wpseo_map_id = 0;
	
	
	
	function __construct() {
		
		$this->add_filters();
		
	}
		
	
	function add_filters() {
		
		//Add classes based on settings
		add_filter( 'body_class', array( $this, 'body_class' ), 10, 2 );

		//Wrap youtube links in responsive embed
		add_filter( 'embed_oembed_html', array( $this, 'embed_wrap'), 10, 4 );

		//prevent column shortcode plugin from outputting stylesheet
		add_filter( 'cpsh_load_styles', '__return_false', 100 );
		
		//Exclude BRIC Assets that are minified to be sent to WP Offload minifying
		add_filter( 'as3cf_minify_exclude_files', array( $this, 'as3cf_minify_exclude_files' ) ); 

		
		// Force Gravity Forms to init scripts in the footer and ensure that the DOM is loaded before scripts are executed
		add_filter( 'gform_init_scripts_footer', '__return_true' );
		
        
        /** Turned these off since it causes issues w/ v2.5 **/
        //add_filter( 'gform_cdata_open', array( $this, 'wrap_gform_cdata_open' ), 1 );
		//add_filter( 'gform_cdata_close', array( $this, 'wrap_gform_cdata_close' ), 100 );
	
	
	
		//Auto-populate ALT tags with title/caption text if not available
		//apply_filters( 'wp_get_attachment_image_attributes', $attr, $attachment, $size );
		add_filter( 'wp_get_attachment_image_attributes', [ $this, 'image_alt_tag'], 10, 3 );
	
	
	
		/**
		 * Make the Yoast SEO Local Maps load lazy
		 *
		 *
		 */
		add_filter( 'wpseo_local_location_route_title_name', [ $this, 'wpseo_local_location_route_title_name' ] );

		/**
		 * 	Make Gform Submit a button so we can add btn classes
		 */
		add_filter( 'gform_submit_button', [ $this, 'gform_submit_button'], 10, 2 );
	


		/**
		 * 	Filter the Archive Page Title
		 */
		add_filter( 'get_the_archive_title', [ $this, 'get_the_archive_title'], 10, 3 );
	
		/**
		 * 
		 * 	Filters the Archive Page Image
		 */
		//add_filter( 'post_thumbnail_id', [ $this, 'post_thumbnail_id' ], 10, 3 );


	}




	/**
	 * 	Filter the archive title
	 * 
	 * 
	 */
	
	public function get_the_archive_title( $title, $original_title, $prefix ) {

		if ( has_landing_page() || is_home() || is_single() ) {

			$page = get_landing_page();
			
			$title = $page->post_title;
			
		}

		return $title;

	}





	/**
	 *		Filter body classes for site sizes
	 *		and use CSS to control
	 *
	 */
	
	function body_class( $classes, $additional_classes ) {
		
		global $SiteInfo;
		
		
		if ( $SiteInfo->options->main_content->container ) {
			
			$classes[] = 'main-content-container';
		
		}
		
		if ( !empty( $SiteInfo->body_classes ) ) {
			
			foreach ( $SiteInfo->body_classes as $class ) {
				
				$classes[] = $class;
				
			}
			
		
		}
		
				
		
		
		//full-width image
		if ( has_post_thumbnail() ) {
			
			$classes[] = 'has-header-image';
		}
				
		return $classes;
		
	}
	
	
	
	
	/*
	 *		Wrap embed code that is auto-generated by WP for responsive YouTube
	 *		Ensure that no related videos are shown at the end.
	 *
	 *		NOTE: This only will happen on the new save of a URL from the content editor. It then will cache the iFrame markup
	 *		apply_filters( 'embed_oembed_html', mixed $cache, string $url, array $attr, int $post_ID )
	 *		
	 */

	function embed_wrap( $cache, $url, $attr, $post_id ) {


		//Find the src string in the iframe HTML
		preg_match('/src="(.+?)"/', $cache, $matches);

		
		$youtube = strpos( $matches[1], 'youtube' );
		
		if ( $youtube !== false ) {

			//Add the rel=0 parameter
			$params = array(
				'controls'    => 1,
				'hd'        => 1,
				'autohide'    => 1,
				'rel' => 0,
			);
			
		

			//Add the new parameters to the src string
			$new_url = add_query_arg($params, $matches[1] );

			//replace old src string with new one
			$cache = str_replace( $matches[1], $new_url, $cache );

			//add responsive div and return new iframe HTML
			return '<div class="ratio ratio-16x9">'.$cache.'</div>';
		
		}
		
		else {
			
			return $cache;
		}
		
	}


	/**
	 *		Prevent Bric theme CSS (that's already minified)
	 *		to be sent to the WP Offload Minifier
	 *
	 */
	
	function as3cf_minify_exclude_files( $exclude ) {
		
		//$exclude[] = '/abspath/wp-content/themes/twentyfifteen/genericons/genericons.css';
		
		$exclude[] =  get_stylesheet_directory().'/assets/css/bric-style.css';
		$exclude[] =  get_stylesheet_directory().'/assets/css/bric-style-customizer.css';

		
		return $exclude;
		
	}
	
	
	
	
	function wrap_gform_cdata_open( $content = '' ) {
		
		if ( ( defined('DOING_AJAX') && DOING_AJAX ) || isset( $_POST['gform_ajax'] ) ) {
			return $content;
		}
		
		$content = 'document.addEventListener( "DOMContentLoaded", function() { ';
		
		return $content;
		
	}
	
	

	function wrap_gform_cdata_close( $content = '' ) {
		
		if ( ( defined('DOING_AJAX') && DOING_AJAX ) || isset( $_POST['gform_ajax'] ) ) {
			return $content;
		}
		
		$content = ' }, false );';
		
		return $content;
	}		
	

	
	
	
	
	
	/**
	 *		Filters the image attributes before outputing
	 *		from wp_get_attachment_image
	 *
	 *		@since bric_v1.1
	 *
	 */
	
	function image_alt_tag( $attr, $attachment, $size ) {
		
		//Don't do anything if it's already populated
		if ( !empty( $attr['alt']) ) {
			return $attr;
		}
		
		//var_dump( $attachment );
		
		//Check for other text. First will be caption
		if ( !empty( $attachment->post_excerpt ) ) {
			
			$attr['alt'] = wp_trim_words( $attachment->post_excerpt );
			
		}
		//Fall back on the title, which we probably have
		else {
			
			$attr['alt'] = $attachment->post_title;
			
		}
		
		
		return $attr;
	}
	
	
	
	
	/**
	 * We use this filter to just trigger the 
	 * access of a wp seo local global variable
	 * 
	 * always return what goes in
	 */
	
	public function wpseo_local_location_route_title_name( $name ) {
		

		global $wpseo_map;

		
		// Comment out the JS directives

		$s = [ 	
				'if( window', 
				'else if', 
				'window.addEventListener(', 
				'window.attachEvent(' 
			 ];

		$r = [ 
				'//if( window', 
				'//else if', 
				'//window.addEventListener(', 
				'//window.attachEvent('  
		];


		$wpseo_map = str_replace( $s, $r, $wpseo_map );

		//Get the 
		

		$this->bric_render_wpseo_lazy_script();
		//This is for when autoptimize is turned on
		add_filter( 'autoptimize_filter_js_exclude', [ $this, 'wpseo_autoptimize' ], 10 );
		wp_enqueue_script( 'in-view' );

		
		return $name;



	}
	
	
	
	
	
	
	
	/**
	 *		Exclude In-View and WPSEO frontend scripts
	 *
	 *		wp-content/themes/bric/assets/js/in-view.min.js, 
	 *		wp-content/plugins/wpseo-local/js/dist/wp-seo-local-frontend-1290.js
	 */

	function wpseo_autoptimize( $exclude_js ) {


		$exclude_js .= ',wp-content/themes/bric/assets/js/in-view.min.js';
		$exclude_js .= ',wp-content/plugins/wpseo-local/js/dist/wp-seo-local-frontend-1290.js';

		return $exclude_js;

	}


	function bric_render_wpseo_lazy_script() {

		if ( $this->wpseo_map_id ) {
			$init_function = 'wpseo_map_init_' . $this->wpseo_map_id . '()';
			$map_canvas = '#map_canvas_' . $this->wpseo_map_id;
		}
		else {
			$init_function = 'wpseo_map_init()';
			$map_canvas = '#map_canvas';
		}
		
		
		ob_start();
		?>
		inView('<?php echo $map_canvas; ?>', 1000 ).once( 'enter', function() {
			<?php echo $init_function; ?>;
		});
		<?php
		$this->wpseo_map_id++;
		
		$data = ob_get_clean();

		wp_add_inline_script( 'in-view', $data, 'after' );

	}
	




	public function bric_lower_footer_menu( $menu_items, $args ) {


		if ( !empty( $menu_items ) ) {

			$count = count( $menu_items );
			
			$c = 1;
			
			foreach( $menu_items as $k => $menu_item ) {

				//Add padding
				if ( $count > $c ) {

					$menu_items[$k]->classes[] = 'pe-2';
				}

				$c++;

			}

			
		}


		return $menu_items;

	
	}







		public function gform_submit_button( $button, $form ) {


			//get the option about recaptcha

			$recap_opt = get_option( 'gravityformsaddon_gravityformsrecaptcha_settings' );
			
			//Reset button
			$button = '';

			if ( isset( $recap_opt['disable_badge_v3']) && intval( $recap_opt['disable_badge_v3'] ) && $recap_opt['recaptcha_keys_status_v3'] ) {

				ob_start();

				include locate_template( 'template-parts/components/gravity-forms/google-recaptcha-v3-terms.php' );

				$button .= ob_get_clean();

			}


			ob_start();


			include locate_template( 'template-parts/components/gravity-forms/submit-button.php' );


			$button .= ob_get_clean();

			return $button;
		}

		


	/**
	 * 	Singleton
	 * 
	 */
	public static function get_instance() {

		if ( self::$instance == null ) {
			
			self::$instance = new self;
		}

		return self::$instance;

	}

}


function BricFilters() {

	return BricFilters::get_instance();

}

BricFilters();