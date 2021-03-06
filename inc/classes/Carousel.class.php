<?php
//
//		Image Slider Class
//
//

namespace Bric;



class Carousel {
	
	public $gallery = array();
	public $mainSize = 'large';
	public $includeCaption = false;
	public $includeIndicators = '';
	public $indicatorItems = '';
	public $includeControls = false;
	public $wrapperClass = [];
	public $id = 'generic-carousel-1';
	public $autoPlay = true;
	public $linkCaption = false;
	public $bsCarousel = true;
	public $wrapImage = false;
	public $pauseOnHover = true;
	public $wrapItemInner = false;
	public $itemInnerClass = '';
	public $slideSpeed = '3000';
	public $isGallery = false;

	/**
	 *		Enable entire slide to be hyperlink
	 */
	public $link_entire_slide = false;

	
	
	function __construct( $gallery ) {
		
		if ( isset( $gallery ) ) {
			
			$this->setGallery( $gallery );
			
		}
		
	}
	
	
	
	
	function setGallery( $gallery ) {
		
		if ( is_array( $gallery )) {
			
			$this->gallery = $gallery;
			
		}
		
		else {
			
			$this->gallery = false;
		}
		
		
		return $this->gallery;
		
		
	}
	
	
	
	
	
	function buildGallery() {
		
		if ( $this->gallery ) {
			
			//$o = '<div class="background-images slide carousel" data-ride="carousel">';
			
			$this->counter = 0;
			$this->imageItems = '';
			
			foreach ( $this->gallery as $this->currentItem ) {
				
				$this->buildCarouselInner();
				
				$this->buildCarouselIndicators();
				
				$this->counter++;
				
			}
			
			
			$this->buildCarouselControls();
						
			
			$this->wrapIndicators();
			
			$this->wrapInner();
			
			$this->htmlGallery = $this->wrapCarousel();
			
			
			//Output JS for object-fit fallback
			$this->printScripts();
			
			return $this->htmlGallery;
		
		}
		
		
		
		
	}
	
	
	
	
	
	function buildCarouselInner() {
		
		$item = '';
		$caption = '';
		

		/*
		if ( $this->includeCaption ) {
			
			if ( $this->linkCaption ) {
				
				$caption = sprintf( '<div class="carousel-caption"><a href="%s">%s</a></div>', get_field( 'link_to', $this->currentItem['id']), $this->currentItem['caption'] );
				
			}
			
			else {
				
				$caption = sprintf( '<div class="carousel-caption">%s</div>', $this->currentItem['caption'] );
				
			}
			
			
		}
		*/
		
		/**
		 *		Prepare the Copy
		 *
		 */
		
		$title = '';
		$description = '';
		$cta = '';
		
		if ( !empty( $this->currentItem['title']) )
			
			$title = sprintf( '<h1>%s</h1>', $this->currentItem['title'] );
			
		if ( !empty( $this->currentItem['description']) )
			
			$description = sprintf( '<div class="description">%s</div>', $this->currentItem['description'] );
			
		
		if ( !empty( $this->currentItem['link'] ) && !$this->link_entire_slide  ) 
			
			$cta = sprintf( '<a class="btn btn-cta" href="%s">%s</a>', $this->currentItem['link']['url'], $this->currentItem['link']['title'] );
			
		
		
		$img = wp_get_attachment_image( $this->currentItem['image']['id'], $this->mainSize );
		
		
		if ( $this->wrapImage || $title || $description || $cta ) {
			
			if (  $title || $description || $cta ) {
				
				
				$img = sprintf( '<div class="img-wrapper has-copy"><div class="copy">%s %s %s</div>%s</div>', $title, $description, $cta, $img );
			
				
			}
			else {
				
				$img = '<div class="img-wrapper">'.$img.'</div>';

			}
			
		
			
			
		}
		
		
		if ( $this->link_entire_slide ){

			$img = sprintf( '<a class="d-block" href="%s">%s</a>', $this->currentItem['link']['url'], $img );

		}

		
		
		

		$item = sprintf( '<div class="carousel-item %s" role="listitem" style="background-image:url(%s)">%s%s%s%s</div>',
							( $this->counter == 0 ? 'active' : '' ),
                            ( $this->currentItem['image']['sizes']['large'] ),
							( $this->wrapItemInner ) ? '<div class="carousel-item-inner '.$this->itemInnerClass.'">' : '',
							$img,
							$caption,
							( $this->wrapItemInner ) ? '</div>' : ''
						);



		$this->imageItems .= $item;
		
			
		
	}
	
	
	
	
	
	function wrapInner() {
		
		( $this->bsCarousel ? $class = 'carousel-inner' : $class = '' );
			
			
		
		$this->carouselInner = '<div class="'.$class.'" role="list">'.$this->imageItems.'</div>';
		
		return $this->carouselInner;		
		
		
	}
	
	
	
	
	
	function buildCarouselIndicators() {
		
		
		if ( $this->includeIndicators == 'thumbnails') {
			
			$this->indicatorItems .= sprintf( '<li data-target="#%s" data-slide-to="%s" class="%s">%s</li>',
												$this->id,
											 	$this->counter,
											 	( $this->counter == 0 ? 'active' : ''),
											 	wp_get_attachment_image( $this->currentItem['id'], 'favicon')
											);
			
		}
				
		elseif ( $this->includeIndicators == 'dots' ) {
			
			$this->indicatorItems .= sprintf( '<li data-target="#%s" data-slide-to="%s" class="%s"></li>',
												$this->id,
											 	$this->counter,
											 	( $this->counter == 0 ? 'active' : '')
											);
			
		}
		
		else {
			
			$this->indicatorItems = '';
		}
		
		
	}
	
	
	
	
	function wrapIndicators() {
		
		if ( $this->includeIndicators == 'thumbnails') {
		
			$this->carouselIndicators = '<ol class="carousel-indicators thumbs">'.$this->indicatorItems.'</ol>';
		
		}
		
		elseif ( $this->includeIndicators == 'dots' ) {
			
			$this->carouselIndicators = '<ol class="carousel-indicators">'.$this->indicatorItems.'</ol>';
			
		}
		
		else {
			
			$this->carouselIndicators = '';
		}
		
		
		return $this->carouselIndicators;
		
	}
	
	
	
	
	
	function buildCarouselControls() {
		
		if ( $this->includeControls ) {
			
			ob_start();
			
			?>
			
			<a class="left carousel-control" href="#<?php echo $this->id; ?>" role="button" data-slide="prev">
				<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="right carousel-control" href="#<?php echo $this->id; ?>" role="button" data-slide="next">
				<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			</a>			
			
			
			<?php
			
			
			$this->carouselControls = ob_get_clean();
			
			
		}
		
		else {
			
			$this->carouselControls = '';
			
		}
		
		
		
	}
	
	
	
	
	
	
	
	function wrapCarousel() {
		
		$class = join( ' ', $this->wrapperClass );
				
		
		$this->carousel = sprintf( '<div id="%s" class="%s" %s %s data-interval="%s">%s</div>',
								 	$this->id,
								  	( $this->bsCarousel ? 'carousel slide ' : '').$class,
								  	( $this->autoPlay ? 'data-ride="carousel"' : '' ),
								    ( $this->pauseOnHover  ? 'data-pause="hover"' : 'data-pause="null"'),
								    $this->slideSpeed,
								  	$this->carouselInner.$this->carouselIndicators.$this->carouselControls
								 );
		
		return $this->carousel;
		
		
	}
	
	
	
	
	
	
	
	function printScripts() {
		
		//add_action( 'wp_footer', array( $this, '_printScripts'), 20 ); 
	}
	
	
	
	
	function _printScripts() {
		
		?>
<script>
	( function($) {
		if ( ( 'objectFit' in document.documentElement.style ) === false ) {
			$('.carousel-item').each( function(i) {

				var img = $(this).find('img').attr('src');

				$(this).css('background-image', 'url('+img+')').addClass('object-fit-fallback');

			});
		}
		
	})(jQuery);
</script>
		<?php
	}
	
	
	
	
	
	
	
	
	
	
	
	
}
