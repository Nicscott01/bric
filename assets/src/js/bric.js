/*! bric 2021-02-11 */
//Initialize the Bric JS object.
var Bric = {};;//Normalize Heights on Carousel

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

		//$('body > *:not(script):not(link)').wrapAll( '<div id="total-page-wrapper" />' );
		
		//$('.navbar-collapse').clone().appendTo('body').attr('id', 'slideout').removeClass('navbar-collapse collapse').find('ul').attr('id','');
		$('#' + slideout.target_id ).parent().clone().appendTo('body').attr('id', slideout.id ).find('ul').attr('id','');

		var $slideout = $('#' + slideout.id );
		
		
		//Change the ID on each li element
		$slideout.find( 'li' ).each(function(i){
			var id = $(this).attr('id');
			$(this).attr('id', 'slideout-' + id );
			
			$(this).find('a').attr('data-toggle', '' );
			
		});
		
		Bric.mainmenu = new Slideout({
			'panel': document.getElementById( 'total-page-wrapper' ),
			'menu': document.getElementById( slideout.id ),
			'side': slideout.side,
		});

		if( parseInt( slideout.close_button ) ) {
			$slideout.prepend('<div class="close-button w-100 text-right"><button class="btn btn-primary" onclick="Bric.mainmenu.close();"><i class="fas fa-times"></i></button></>')
		}
		
		
		//document.querySelector('.navbar-toggler').addEventListener('click')
		$('.navbar-toggler').click( function() {
			Bric.mainmenu.toggle();
		});
		
		$(document).trigger('bricSlideOutComplete');


	});

})(jQuery);

//# sourceMappingURL=bric.js.map