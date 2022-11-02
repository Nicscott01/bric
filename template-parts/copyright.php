<?php


$copyright_holder = bric_get_theme_mod( 'lower_footer', 'copyright_holder' );

if ( empty( $copyright_holder ) ) {

    $copyright_holder = get_option( 'blogname' );
}


//printf( '<div class="copyright">%s %s</div>', $copyright_text, $this->SiteInfo->copyright_owner ); 
printf( '<div class="copyright text-center">%s %s</div>', $copyright_text, $copyright_holder ); 