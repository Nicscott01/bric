<?php

global $Navbar;

add_action( 'bric_header', array( $Navbar, 'get_navbar' ), 10 );


//Do the loop
global $BricLoop;

//Add in the archive header
add_action( 'bric_before_loop', array( $BricLoop, 'get_archive_header' ), 5 );


add_action( 'bric_before_loop', array( $BricLoop, 'get_sidebar'), 5 );
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



//SVGs
add_action( 'wp_footer', function() {
    
    $child_svg = get_stylesheet_directory() . '/assets/svgs/bric-child.svg';
    
    if ( file_exists( $child_svg ) ) {
        
        echo '<div class="svg-wrapper d-none">';
        include $child_svg;
        echo '</div>';
        
    }
    
}, 100 );