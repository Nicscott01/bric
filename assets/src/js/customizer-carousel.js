// JavaScript Document

jQuery( document ).ready(function($) {
   
	/**
	 *	Slide Transition
	 *
	 */
	wp.customize('bric[carousel][transition]', function(control) {
      control.bind( function( controlValue ) {
		  
         if( controlValue == 'slide' ) {
         	  $('.carousel' ).removeClass( 'carousel-fade' ); 
         }
         else if ( controlValue == 'fade' ) {
         	  $('.carousel' ).addClass( 'carousel-fade' ); 
		 }
      });
   });
	
	/**
	 *	Slide transition speed
	 *
	 */	
	wp.customize('bric[carousel][speed]', function(control) {
      control.bind( function( controlValue ) {
		  
		// var existing_classes = $('.carousel').attr('class');
		  
		 $('.carousel').carousel( 'dispose' );
		  
		  $('.carousel').carousel({
			  interval: controlValue
			}); //.addClass( existing_classes );
		  
		  
       
      });
   });
});	
