/**
 *		Dropdown Behavior
 *
 *		perform on doc ready to wait for any other 
 *		DOM manipulation by other plugins.
 *
 */

(function($){
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
