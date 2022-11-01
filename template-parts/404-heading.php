<?php

global $post;

$post->post_title = "404: This Doesn't exist";

setup_postdata( $post );

    get_template_part( 'template-parts/heading-header-image' );

wp_reset_postdata(  );
