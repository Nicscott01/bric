//Normalize Heights on Carousel

( function($) {
	
	$(document).ready( function() {
		Bric.carousel = $('.carousel-item');
		normalizeHeights();
	});
	
	
	function normalizeHeights() {
		
		Bric.carousel.minHeight = 0;
		
		if ( Bric.carousel.length ) {
			
			
			$(Bric.carousel).each( function() {
								
				if ( $(this).height() > Bric.carousel.minHeight ) {
					Bric.carousel.minHeight = $(this).height();
				}
			});
			
			//Set max height of carousel
			$('.carousel-item').css( 'min-height',  Bric.carousel.minHeight );
			
			//reset the variables
			Bric.carousel.minHeight = 0;
		}

		
	}
	
	$(window).on( 'resize orientationchange', function(){
		
		$('.carousel-item').css( 'min-height',  Bric.carousel.minHeight );
		normalizeHeights();
		
	});
	
	
})( jQuery );


( function($) {
	if ( ( 'objectFit' in document.documentElement.style ) === false ) {
		$(Bric.carousel).each( function(i) {

			var img = $(this).find('img').attr('src');

			$(this).css('background-image', 'url('+img+')').addClass('object-fit-fallback');

		});
	}

})(jQuery);
