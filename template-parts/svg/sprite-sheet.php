<?php
/**
 *      Output SVG sprite sheet
 *
 *
 */

$bric_child_svgs = get_stylesheet_directory() . '/assets/svgs/bric-child.svg';

if ( file_exists( $bric_child_svgs ) ) {
    
    echo '<div class="d-none svg-sprite-sheet">';
    echo file_get_contents( $bric_child_svgs );
    echo '</div>';

}