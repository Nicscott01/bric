<?php


/**
 *		Class to create photo gallery
 *		orignally based off of masonry & photoswipe
 *		but to be expanded in the future as necessary!
 *
 *
 */



class PhotoGallery {
	
	public static $gallery = array();
	public static $args = array();
	
	
	
	function __construct( $gallery = array(), $args = '' ) {

			
		
		if ( isset( $args ) ) {
			
			$this->args = $args;
			
		}
		
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
	
	
	
	function buildGallery() {
				
		if ( !is_admin() ) {
			$this->queueScripts();

		}
		else {
			//scripts need to be queued via shortcode (bottom of this file)
			
		}
		
		$this->compileGallery();	
		
		return $this->htmlGallery;
		
	}
	
	
	
	
	function compileGallery() {
		
		
		if ( is_array( $this->gallery ) ) {
			

			$output = '';

			$gallery = $this->gallery;
			$image_large = 'medium';

			$c = 1;				
			foreach ( $gallery as $key => $image ) {

				if ( $c == 1 ) {
					$item_class = " grid-sizer";
				}

				elseif ( $c % 3 == 0) {
					$item_class = " image-width-2 image-height-2";
					$image_large = 'medium_large';
				}

				else {
					$item_class= ' ';
					$image_large = 'medium';
				}

				
				$output .= "\t<figure class='image-item".$item_class."' itemprop='associatedMedia' itemscope itemtype='http://schema.org/ImageObject'>\n";


				$img = wp_get_attachment_image( $image['id'], $image_large, false, array('itemprop' => 'thumbnail') );


				$output .= sprintf( "\t\t<a href='%s' itemprop='contentUrl' data-size='%s'>%s</a>\n",
								apply_filters( 'bric_photo_gallery_full_size', $image['url'], $image ),
								$image['width'].'x'.$image['height'],
								$img );


				//Check for caption text, if not, check for alt text, if not, then no caption.
				if ( $image['caption'] !== '' ) {

					$img_cap = '<span class="image-caption">'.$image['caption'].'</span>';

				}

				else {

					$img_cap = '';

				}



				$output .= sprintf("\t\t<figcaption itemprop='caption description'>%s</figcaption>\n",
								$img_cap
							);

				$output .= "\t</figure>\n";


					
			$c++;
				
			}

			
				
			$output = sprintf( "<div class='gallery %s %s row' itemscope itemtype='http://schema.org/ImageGallery'>\n%s</div>", 
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

			wp_enqueue_script('photoswipe-ui', get_template_directory_uri().'/assets/js/photoswipe-ui-default.min.js', 'photoswipe', '4.1', true);

			wp_enqueue_script('photoswipe-thumbnail-opener', get_template_directory_uri().'/assets/js/photoswipe-thumbnail-opener.min.js', 'photoswipe-ui', '', true);

			
			
			$this->getPhotoSwipeHTML();
			
					
		
			//add_action('wp_enqueue_scripts', array( $this, 'queue_photo_swipe' ) );

			//wp_enqueue_style('photoswipe', get_template_directory_uri().'/includes/resources/photo-swipe/photoswipe.css', '', '4.1');

			//wp_enqueue_style('photoswipe-default-skin', get_template_directory_uri().'/includes/resources/photo-swipe/default-skin/default-skin.css', 'photoswipe');

			
			

		}
		

		
		
	}
	
	
	
	public function getPhotoSwipeHTML() {
		
		//include the photoswipe template in footer; need to call new instance of class because it was printing twice before
		add_action( 'wp_footer', array( 'PhotoGallery', 'photoswipe_queue'), 10 );
		//add_action( 'wp_footer', array( $this, 'photoswipe_queue'), 90 );

	}
	
	
		
	
	public function photoswipe_queue() {
			

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

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--share" title="Share"></button>

                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

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

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>

            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>

        </div>

    </div>

</div>	
	<?php
		
	add_action( 'wp_footer', array( 'PhotoGallery', 'add_ps_class'), 50 );
			
		
	}
	
	
	
	

	public function add_ps_class() {
		
		?>
<script>jQuery('body').addClass('has-photoswipe');</script>		
		<?php
	}
	
	
	
	
	
	function masonry_queue() {
	?>
	<script>	

	( function($){

		var $masonry = $('.gallery-masonry').masonry({
			  // options...
			  itemSelector: '.image-item',
			  percentPosition: true,
			  columnWidth: '.grid-sizer',
			  gutter:0,
			});
			//Horizontal centering of large header img


		$masonry.imagesLoaded().progress( function() {
		  $masonry.masonry('layout');
		});
		

	})(jQuery);

	</script>


		<?php

	}
	
	
	
	
	
	
	
	
	function parseArgs() {
		
		$def = array(
			'gallery_display' => 'masonry',
			'lightbox' => 'photoswipe',
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