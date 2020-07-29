//Normalize Heights on Carousel

( function($) {
	
	$(document).ready( function() {
        
        //Do this for multiple carousels on the page
        Bric.carousels = $('.carousel');
        Bric.carousels.each(function( index ){
            normalizeHeights( $(this) );
        });
        
        
	});
	
	
	function normalizeHeights( $object ) {
		        
		$object.minHeight = 0;
		
		if ( $object.length ) {
			
			
            
			$object.find('.carousel-item').each( function() {
								                
				if ( $(this).height() > $object.minHeight ) {
					$object.minHeight = $(this).height();
				}
			});
			
			//Set max height of carousel
			$object.find('.carousel-item').css( 'min-height',  $object.minHeight );
			
			//reset the variables
			$object.minHeight = 0;
		}

		
	}
	
	$(window).on( 'resize orientationchange', function(){
		
        Bric.carousels.each(function( index ){
            normalizeHeights( $(this) );
        });
        		
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
