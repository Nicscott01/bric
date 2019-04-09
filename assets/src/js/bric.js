/*! bric 2019-04-09 */
//Normalize Heights on Carousel
var carousel;


( function($) {
	
	$(document).ready( function() {
		carousel = $('.carousel-item');
		normalizeHeights();
	});
	
	
	function normalizeHeights() {
		
		carousel.minHeight = 0;
		
		if ( carousel.length ) {
			
			
			$(carousel).each( function() {
								
				if ( $(this).height() > carousel.minHeight ) {
					carousel.minHeight = $(this).height();
				}
			});
			
			//Set max height of carousel
			$('.carousel-item').css( 'min-height',  carousel.minHeight );
			
			//reset the variables
			carousel.minHeight = 0;
		}

		
	}
	
	$(window).on( 'resize orientationchange', function(){
		
		$('.carousel-item').css( 'min-height',  carousel.minHeight );
		normalizeHeights();
		
	});
	
	
})( jQuery );


( function($) {
	if ( ( 'objectFit' in document.documentElement.style ) === false ) {
		$(carousel).each( function(i) {

			var img = $(this).find('img').attr('src');

			$(this).css('background-image', 'url('+img+')').addClass('object-fit-fallback');

		});
	}

})(jQuery);
