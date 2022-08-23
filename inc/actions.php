<?php

global $Navbar;

add_action( 'bric_header', [$Navbar, 'get_upper_header'], 10 ); 
    
add_action( 'bric_header', array( $Navbar, 'get_navbar' ), 10 );


//Do the loop
global $BricLoop;

//Add in the archive header
add_action( 'bric_before_loop', array( $BricLoop, 'get_archive_header' ), 5 );


//add_action( 'bric_before_loop', array( $BricLoop, 'get_sidebar'), 5 );
add_action( 'bric_before_loop', array( $BricLoop, 'get_before_loop_posts'), 10 );
add_action( 'bric_before_loop', array( $BricLoop, 'get_before_loop'), 10 ); 

add_action( 'bric_loop', array( $BricLoop, 'get_content'), 10 );
//add_action( 'bric_after_loop', array( $BricLoop, 'get_after_loop') );

add_action( 'bric_no_posts', array( $BricLoop, 'get_no_posts'), 10 );


add_action( 'bric_footer', array( $BricLoop, 'get_footer'), 10 );





/**
 *		Comments
 *
 */

add_action( 'bric_after_loop_posts', array( $BricLoop, 'get_post_comments') ); 


/**
 *		Pagination
 *
 */

add_action( 'bric_after_loop_posts', array( $BricLoop, 'get_post_pagination') ); 




		//Carousel for homepage
add_action( 'wp', array( $BricLoop, 'home_carousel'), 10 );




//SVG output
add_action( 'wp_footer', function() {
   
    get_template_part( 'template-parts/svg/sprite-sheet' );
    
}, 100 );





/**
 *  Cookie Consent
 * 
 * 
 */


add_action( 'init', function() {

    $output_cookie_consent = apply_filters( 'bric_enable_cookie_consent', true );


    if ( $output_cookie_consent ) {

        //Load the template before the script. It matters.
        add_action( 'wp_footer', function() {

            get_template_part( 'template-parts/cookie-consent' );

        }, 10 );



        wp_enqueue_script( 'cookie-consent', get_template_directory_uri() . '/assets/js/cookie-banner.min.js', null, null, true );


    }

});
