<?php
/**
 *      Output SVG sprite sheet
 *
 *
 */


require ( locate_template( '/vendor/autoload.php' ) );

use SVG\SVG;
use SVG\Nodes\Shapes\SVGRect;

global $svg_icons;

//$bric_child_svgs = get_stylesheet_directory() . '/assets/svgs/bric-child.svg';
$bric_child_social_svgs = locate_template( 'assets/svgs/bric-theme-icons.svg' );


$svg_icons['file'] = $bric_child_social_svgs;

$svg = SVG::fromString( file_get_contents( $bric_child_social_svgs ) );

$svg_doc = $svg->getDocument();

$symbols = $svg_doc->getElementsByTagName( 'symbol' );

foreach ( $symbols as $symbol ) {

    $viewBox = $symbol->getAttribute( 'viewBox' );
    $id = $symbol->getAttribute( 'id' );
    
    //Check for stopwords
    $stopwords = [
        'square-',
    ];

    $key = str_replace( $stopwords, '', $id );

    $svg_icons[ $key ] = [
        'id' => $id,
        'viewBox' => $viewBox
    ];


}






add_action( 'wp_footer', function() {

    global $svg_icons;

    if ( file_exists( $svg_icons['file'] ) ) {
        
        echo '<div class="d-none svg-sprite-sheet">';
        echo file_get_contents( $svg_icons['file'] );
        echo '</div>';

    }

}, 100 );










return;









//$bric_child_svgs = get_stylesheet_directory() . '/assets/svgs/bric-child.svg';
$bric_child_social_svgs = get_stylesheet_directory() . '/assets/svgs/bric-social.svg';

if ( file_exists( $bric_child_social_svgs ) ) {
    
    echo '<div class="d-none svg-sprite-sheet">';
    //echo file_get_contents( $bric_child_svgs );
    echo file_get_contents( $bric_child_social_svgs );
    echo '</div>';

}