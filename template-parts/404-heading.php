<?php

global $post;

if ( !isset( $post->post_title ) ) {
    $post = new stdClass();
}

$post->post_title = "404: This Doesn't exist";


setup_postdata( $post );

    get_template_part( 'template-parts/heading-header-image' );

wp_reset_postdata(  );
