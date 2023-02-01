<?php
/**
 *		UL Element for Social Media
 *
 *
 */

$block_class = isset($block['className']) ? $block['className'] : '';


if ( isset( $align ) && !empty( $align ) ) {

    switch( $align ) {


        case "center" : 

            $justify = 'center';

            break;

        case "right" :

            $justify = 'end';

            break;

        default :  
        case "left" :

            $justify = 'start';

            break;
    
    }

}

printf( '<ul class="list-unstyled list-inline d-flex flex-row justify-content-top align-items-center flex-nowrap social-media-accounts %s" style="--bric-social-icon-size: %s;">%s</ul>', $block_class, $icon_size, $o );
