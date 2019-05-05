/*! bric 2019-05-01 */
//Initialize the Bric JS object.
var Bric = {};;//Normalize Heights on Carousel

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
;(function($){
	$(document).ready( function(){ 

		//Prevent the dropdown from opening on click
		$('.dropdown-toggle').on( 'click', function(e) {
				if ( $(window).width() > 768 ) {
					e.stopPropagation();
				}
			});	
		
		//Instead, open the dropown on hover
		$('.nav-item.dropdown').hover( function(e) {
				$(this).addClass('hover').find('.dropdown-menu').addClass('show');	
			},
			function(e) {
				$(this).removeClass('hover').find('.dropdown-menu').removeClass('show');
			});

	});
})(jQuery);
;( function($){
	$(document).ready( function(e) { 

		$('body > *:not(script):not(link)').wrapAll( '<div id="total-page-wrapper" />' );
		
		$('.navbar-collapse').clone().appendTo('body').attr('id', 'slideout').removeClass('navbar-collapse collapse').find('ul').attr('id','');

		//Change the ID on each li element
		$('#slideout li').each(function(i){
			var id = $(this).attr('id');
			$(this).attr('id', 'slideout-' + id );
		});
		
		$('#slideout a').each(function(i) {
			$(this).attr('data-toggle', '');
		});
		

		Bric.mainmenu = new Slideout({
			'panel': document.getElementById( 'total-page-wrapper' ),
			'menu': document.getElementById( 'slideout' ),
			'side': 'right'
		});

		//document.querySelector('.navbar-toggler').addEventListener('click')
		$('.navbar-toggler').click( function() {
			Bric.mainmenu.toggle();
		});
		$(document).trigger('bricSlideOutComplete');


	});

})(jQuery);

//# sourceMappingURL=bric.js.map