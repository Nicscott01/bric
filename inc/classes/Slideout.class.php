<?php

/**
 *		Slideout Menu
 *
 */

class Slideout {

	function __construct() {
		
		add_action( 'wp_enqueue_scripts', array ( $this, 'enqueue_scripts') );

		
		//@since bric_v1.1 remove this inline script and bundle in bric.js
		//add_action( 'wp_footer', array( $this, 'init_slideout'), 51 );

	}
	
	
	public function enqueue_scripts() {
		
		wp_enqueue_script( 'slideout', get_template_directory_uri().'/assets/js/slideout.min.js', array('jquery', 'bootstrap'), false, true );
		
	}
	
	
	public function init_slideout() {
		
		?>
<script>
	var mainmenu = {};
	
	( function($){
		$(document).ready( function(e) { 
			
			//if ( $(window).width() < 768 ) {
			
				$('body > *').wrapAll('<div id="total-page-wrapper" />');
				$('.navbar-collapse').clone().appendTo('body').attr('id', 'slideout').removeClass('navbar-collapse collapse');

				$('#slideout a').each(function(i) {
					$(this).attr('data-toggle', '');
				});

				mainmenu = new Slideout({
					'panel': document.getElementById( 'total-page-wrapper' ),
					'menu': document.getElementById( 'slideout' ),
					'side': 'right'
				});

				//document.querySelector('.navbar-toggler').addEventListener('click')
				$('.navbar-toggler').click( function() {
					mainmenu.toggle();
				});
				$(document).trigger('bricSlideOutComplete');
				
			//}
			
		});
		
	})(jQuery);
	
	/**
	 *		Dropdown Behavior
	 *
	 *		perform on doc ready to wait for any other 
	 *		DOM manipulation by other plugins.
	 *
	 */
	(function($){
		$(document).ready( function(){ 

			$('.dropdown-toggle').on( 'click', function(e) {
				if ( $(window).width() > 768 ) {
					e.stopPropagation();
				}
			});	
			
			$('.nav-item.dropdown').hover( function(e) {
				$(this).addClass('hover').find('.dropdown-menu').addClass('show');	
			},
			function(e) {
				$(this).removeClass('hover').find('.dropdown-menu').removeClass('show');
			});
			
		})
		
	})(jQuery);
	
</script>
		<?php
		
	}
	
	
	
}

new Slideout();
