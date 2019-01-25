/**
 *		Bric SCSS Preview
 *
 *		Runs in customizer to tell it when to refresh/compile scss.
 *
 */


( function( $ ) {
  wp.customize( 'custom_scss_styles', function( value ) {
	 // console.log( value );
    value.bind( function( to ) {
		
		console.log( to );
     // $( '#custom-theme-css' ).html( to );
    } );
  } );
 /* wp.customize( 'custom_plugin_css', function( value ) {
    value.bind( function( to ) {
      $( '#custom-plugin-css' ).html( to );
    } );
  } );*/
} )( jQuery );