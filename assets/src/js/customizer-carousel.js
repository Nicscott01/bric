// JavaScript Document

jQuery( document ).ready(function($) {
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
});	
