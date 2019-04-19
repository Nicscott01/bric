/**
 *		Create the slideout div based on the 
 *		menu navbar item output on the page
 *
 */
( function($){
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
