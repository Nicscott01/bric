<?php
/**
 *      Homepage Carousel Template
 *
 *      var $gallery has the images
 */


$Carousel = new \Bric\Carousel( $gallery );


//The transition value returns the class
$Carousel->wrapperClass[] = $this->SiteInfo->carousel['transition'];

if ( $this->SiteInfo->carousel['edge_to_edge'] ) {
    $Carousel->wrapperClass[] = 'edge-to-edge';
    $Carousel->mainSize = 'full';

}

if ( $this->SiteInfo->carousel['show_caption'] ) {
    $Carousel->includeCaption = true;
}


if ( $this->SiteInfo->carousel['speed'] ) {
    $Carousel->slideSpeed = $this->SiteInfo->carousel['speed'];
}


$Carousel = apply_filters( 'bric_header_carousel', $Carousel );

echo $Carousel->buildGallery();