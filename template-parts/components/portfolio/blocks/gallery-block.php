<?php
/**
 *  Render the Gallery Block in masonry
 * 
 * 
 * 
 * 
 */

 $Gallery = new \PhotoGallery(  get_field( 'gallery' ) );

echo $Gallery->buildGallery();