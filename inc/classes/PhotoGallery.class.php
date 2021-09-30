<?php


/**
 *		Class to create photo gallery
 *		orignally based off of masonry & photoswipe
 *		but to be expanded in the future as necessary!
 *
 *
 */



class PhotoGallery {
	
	public $gallery = array();
	public $args = array();
	
	
	
	function __construct( $gallery = array(), $args = '' ) {

			
		
		if ( isset( $args ) ) {
			
			$this->args = $args;
			
		}
		
		$this->raw_gallery = $gallery;
		
		//Parse arguments w/ defaults
		$this->parseArgs();
		
		
		//Build gallery
		if ( !empty( $gallery ) ) {
			

			if ( !isset( $gallery[0]['url'] ) ) {
				
				foreach ( $gallery as $g ) {
					
					$this->gallery[] = acf_get_attachment( $g );
					
				}
				
				
			}
			
			else {
				
				$this->gallery = $gallery;
			
			}
			
			
		
			//return $this->buildGallery();
		}	
		
		
		
		
		
		
		
	}
	
	
	
	public function buildGallery() {
				
		if ( !is_admin() ) {
			$this->queueScripts();

		}
		else {
			//scripts need to be queued via shortcode (bottom of this file)
			
		}
		
		$ajax = isset( $this->args['ajax_load'] ) ? $this->args['ajax_load'] : false;
		
		if ( $ajax ) {
		
			$this->prepareAjax();	
			
		} else {
			
			$this->compileGallery();	
			$this->prepareAjax();	
		}
		
		return $this->htmlGallery;
		
	}
	
	
	
	
	
	public function prepareAjax() {
		
		//We need to output the files that are a part of this gallery, 
		//along with the template for rendering on the page
		
		$ajax_data = [];
		$ajax_data['rest_url'] = get_rest_url() . 'bric/v1/photogallery/get-images/';
		$ajax_data['photos_per_load'] = $this->args['photos_per_load'];
		
		if ( is_array( $this->gallery ) ) {
			
			$ajax_data['gallery'] = $this->raw_gallery;
			//$ajax_data['template'] = include locate_template( 'template-parts/components/photo-gallery/image.php' );
			
			
		}
		
		
		ob_start();
		?>
		$BricPhotoGallery.ajax = <?php echo json_encode( $ajax_data ); ?>
		<?php
		
		$script = ob_get_clean();
		
		wp_add_inline_script( 'bric', $script );
		
		
		
		
	}
	
	
	
	
	
	
	
	public function compileGallery() {
		
		
		if ( is_array( $this->gallery ) ) {
			

			$output = '';

			$gallery = $this->gallery;
			$image_large = 'medium';
            $item_class = '';
            
			
		//	$render_limit = isset( $this->args['render_limit'] ) && is_int( $this->args['render_limit'] ) ? $this->args['render_limit'] : 10; 
			$render_limit = $this->args['photos_per_load'];
			
			
            $c = 1;				
			foreach ( $gallery as $key => $image ) {

				if ( $c > $render_limit ) {
					break;
				}
				
				include locate_template( 'template-parts/components/photo-gallery/image.php' );
					
				$c++;
				
			}

			
				
			$output = sprintf( "<div class='gallery-wrapper'><div class='gallery %s %s' itemscope itemtype='http://schema.org/ImageGallery'>\n%s</div></div><div id='after-gallery' style='height:10px;'></div>", 
							  'gallery-'.$this->args['gallery_display'],
							  'gallery-'.$this->args['lightbox'],
							  $output 
							 );

			
			$this->htmlGallery = $output;
			
		}
		
		
		
		
		
	}
	
	
	
	
	
	

	
	
	
	
	
	public function queueScripts() {
		
		
		if ( $this->args['gallery_display'] == 'masonry' ) {
			
			//wp_enqueue_script( 'imagesloaded', get_template_directory_uri(). '/includes/js/imagesloaded.pkgd.min.js', '', '4.1.1', true );
			wp_enqueue_script( 'imagesLoaded' );

			//wp_enqueue_script('masonry', get_template_directory_uri().'/includes/js/masonry.pkgd.min.js', array('jquery'), '3.3.2', true );
			wp_enqueue_script( 'masonry' );
			
			
			add_action( 'wp_footer', array( $this, 'masonry_queue' ), 101 );
			
		}
		
		if ( $this->args['lightbox'] == 'photoswipe' ) {
			
			
			wp_enqueue_script('photoswipe', get_template_directory_uri().'/assets/js/photoswipe.min.js', '', '4.1', true );

			wp_enqueue_script('photoswipe-ui', get_template_directory_uri().'/assets/js/photoswipe-ui-default.min.js', ['photoswipe'], '4.1', true);

			wp_enqueue_script('photoswipe-thumbnail-opener', get_template_directory_uri().'/assets/js/photoswipe-thumbnail-opener.min.js', array( 'photoswipe', 'photoswipe-ui'), '', true);

			
			
			$this->getPhotoSwipeHTML();
			
					
		
			//add_action('wp_enqueue_scripts', array( $this, 'queue_photo_swipe' ) );

			//wp_enqueue_style('photoswipe', get_template_directory_uri().'/includes/resources/photo-swipe/photoswipe.css', '', '4.1');

			//wp_enqueue_style('photoswipe-default-skin', get_template_directory_uri().'/includes/resources/photo-swipe/default-skin/default-skin.css', 'photoswipe');

			
			

		}
		

		
		//Queue up Waypoints for infinite loading
		wp_enqueue_script( 'waypoints', get_template_directory_uri(). '/assets/js/jquery.waypoints.min.js', ['jquery'], '4.0.1', true );
		
		
		
		
	}
	
	
	
	public function getPhotoSwipeHTML() {
		
		//include the photoswipe template in footer; need to call new instance of class because it was printing twice before
		add_action( 'wp_footer', array( 'PhotoGallery', 'photoswipe_queue'), 10 );
		//add_action( 'wp_footer', array( $this, 'photoswipe_queue'), 90 );

	}
	
	
		
	
	public static function photoswipe_queue() {
			

	?>
<!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true" data-slideout-ignore>

    <!-- Background of PhotoSwipe. 
         It's a separate element as animating opacity is faster than rgba(). -->
    <div class="pswp__bg"></div>

    <!-- Slides wrapper with overflow:hidden. -->
    <div class="pswp__scroll-wrap">

        <!-- Container that holds slides. 
            PhotoSwipe keeps only 3 of them in the DOM to save memory.
            Don't modify these 3 pswp__item elements, data is added later on. -->
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <!--  Controls are self-explanatory. Order can be changed. -->

                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)" aria-label="Close"></button>

                <button class="pswp__button pswp__button--share" title="Share" aria-label="Share"></button>

                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen" aria-label="Toggle fullscreen"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out" aria-label="Zoom in/out"></button>

                <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                <!-- element will get class pswp__preloader--active when preloader is running -->
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                      <div class="pswp__preloader__cut">
                        <div class="pswp__preloader__donut"></div>
                      </div>
                    </div>
                </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div> 
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)" aria-label="Previous image">
            </button>

            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)" aria-label="Next image">
            </button>

            <div class="pswp__caption">
                <div class="pswp__caption__center text-center"></div>
            </div>

        </div>

    </div>

</div>	
	<?php
		
	add_action( 'wp_footer', array( 'PhotoGallery', 'add_ps_class'), 50 );
			
		
	}
	
	
	
	

	public static function add_ps_class() {
		
		?>
<script>jQuery('body').addClass('has-photoswipe');</script>		
		<?php
	}
	
	
	
	
	
	public function masonry_queue() {
	?>
	<script>			
	( function($){

		$(document).ready( function() {
			
			/*
			$BricPhotoGallery.photos = $('.gallery-masonry').masonry({
				  itemSelector: '.image-item',
				  percentPosition: true,
				  columnWidth: '.grid-sizer',
				  gutter:0,
				});


			$BricPhotoGallery.photos.imagesLoaded().progress( function() {
					$BricPhotoGallery.photos.masonry('layout');
					console.log( 'imagesLoaded ran' );
				});
			
			
			$BricPhotoGallery.bottomOfGallery = $BricPhotoGallery.photos.waypoint( {
				handler: function() { 
					$BricPhotoGallery.loadMore() 
					this.destroy();
				},
				offset: 'bottom-in-view'
			});
	*/
			
			
			
			
			

		});

	})(jQuery);

	</script>


		<?php

	}
	
	
	
	
	
	
	
	
	function parseArgs() {
		
		$def = array(
			'gallery_display' => 'masonry',
			'lightbox' => 'photoswipe',
			'photos_per_load' => 10,
		);
		
		$this->args = wp_parse_args( $this->args, $def );
		
		return $this;
		
	}
	
	
	
	
	
	
	
}




//add_shortcode( 'cc_photogallery_scripts', 'cc_photogallery_scripts' );

function cc_photogallery_scripts() {
	
	$gallery = new PhotoGallery;
	
	$gallery->queueScripts();
	
}




add_filter( 'post_gallery', 'bric_post_gallery', 10, 3 );

function bric_post_gallery( $output = '', $atts, $instance ) {
		
	$images = explode( ',', $atts['ids'] );
	
	$Gallery = new PhotoGallery( $images );
		
	return $Gallery->buildGallery();
	
}





class PhotoGalleryRest {
	
	public $args;
	
	public function __construct() {
		
		//Register our rest route
		add_action( 'rest_api_init', [ $this, 'register_rest_route' ] );
		
		$this->args = [];
		$this->args['gallery_display'] = 'masonry';
		
		
	}
	
	
	public function register_rest_route() {

        register_rest_route( 'bric/v1', '/photogallery/image/(?P<id>\d+)', array(
            'methods'  => 'GET',
            'callback' => [ $this, 'get_image_markup' ],
       ) );
		
        register_rest_route( 'bric/v1', '/photogallery/get-images', array(
            'methods'  => 'GET',
            'callback' => [ $this, 'get_images_markup' ],
       ) );
		
	

    } 
	
	
	public function get_images_markup( $data ) {
		
		if ( isset( $data['imgs'] ) ) {
			
			$images = explode( ',', $data['imgs' ] );
			
		}
		
		//$html = [];
		
		$output = '';

		$c = 1;
		
		foreach( $images as $img ) {
			

			if ( function_exists( 'acf_get_attachment' ) ) {

				$image = acf_get_attachment( $img );

			}

			include locate_template( 'template-parts/components/photo-gallery/image.php' );
			
			//$html[] = $output;
			
			$c++;
					
		}
				
		
		return array( 'html' => $output );
			

		
	}
	
	
	public function get_image_markup( $data ) {
		
		
		
		if ( function_exists( 'acf_get_attachment' ) ) {
			
			$image = acf_get_attachment( $data['id'] );
			
		}
		
		/*ob_start();
		
	 	var_dump( $data['id'] );
		
		$log = ob_get_clean();
		
		error_log( $log );
		*/
		$output = '';
		
		//ob_start();
		
		//Get the markup
		include locate_template( 'template-parts/components/photo-gallery/image.php' );
		
		
		//$markup = ob_get_clean();
		
		

		
		return $output; //$data['id'];
		
		
	}
	
	
	
	
	
	
}

new PhotoGalleryRest();

