<?php
/**
 *  Social Media Block template
 * 
 * 
 * 
 */

use function Bric\BricSocialMedia;


$icons = get_field( 'social_accounts' );
$icon_size = get_field( 'icon_size' );
$margin = get_field( 'margin' );



if ( !empty( $icon_size ) && intval( $icon_size  ) > 0 ) {

    $icon_size = intval( $icon_size )  / 16 . 'rem';

}



if ( is_admin() ) {

    $social_icons = $icons;

} else {

    $social_icons = BricSocialMedia()->social_icons;

}



ob_start(); 

foreach( $social_icons as $social ) {


    include locate_template( 'template-parts/social-media-item.php' );


}


$o = ob_get_clean();

include locate_template( 'template-parts/social-media-wrapper.php' );
