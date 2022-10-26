<?php
/**
 *  The Main Content
 * 
 *  Falls between Header & Footer
 * 
 * 
 */

//See if we have a background image
$style = '';
$bg_image = bric_get_theme_mod( 'backgrounds', 'background_image' );

if ( !empty( $bg_image ) ) {

    $style = " style=\"";

    $repeat = bric_get_theme_mod( 'backgrounds', 'background_repeat' );
    $position = bric_get_theme_mod( 'backgrounds', 'background_position' );
    $size = bric_get_theme_mod( 'backgrounds', 'background_size' );
    $attachment = bric_get_theme_mod( 'backgrounds', 'background_attachment' );
    
    $style .= "background:url('$bg_image') $repeat $position/$size;";

    if ( $attachment == 'fixed' ) {

        $style .= " background-attachment:$attachment;";
    }

    $style .= "\"";
}


?>
<main class="main-content container<?php echo get_post_type() == 'page' ? '-fluid' : ''; ?>" <?php echo $style; ?> role="main">
<div class="row">
<?php

do_action( 'bric_before_loop');

if ( have_posts() ) : 

    do_action( 'bric_before_loop_posts' );
    
while ( have_posts() ) : the_post();
    
    do_action( 'bric_loop' );

endwhile;
    
    do_action( 'bric_after_loop_posts' );

else :
    
do_action( 'bric_no_posts');
    
endif;
    

do_action( 'bric_after_loop');
    
?>
</div>
</main>
<?php