//Normalize Heights on Carousel

( function($) {
	$(document).ready( function(){
		
		var carousel;
		
		carousel = $('.carousel-item');
		
		if ( carousel.length ) {
			
			var minHeight = 0;
			
			$(carousel).each( function() {
				if ( $(this).height() > minHeight ) {
					minHeight = $(this).height();
				}
			});
			
			//Set max height of carousel
			$('.carousel-item').height( minHeight );
		}
		
	});
	
})( jQuery );