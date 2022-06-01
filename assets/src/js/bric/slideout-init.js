/**
 *		Create the slideout div based on the 
 *		menu navbar item output on the page
 *
 */
( function($){
	$(document).ready( function(e) { 


		if ( typeof( slideout ) == 'undefined' ) {
			return;
		}
		
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
