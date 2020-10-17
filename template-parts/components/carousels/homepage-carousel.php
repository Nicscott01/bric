<?php
/**
 *      Homepage Carousel Template
 *
 *      var $gallery has the images
 */


if( empty( $gallery ) ) {
    return;
}
?>
<div id="homepage-carousel" class="carousel slide col-12 px-0" data-ride="carousel">
    <?php
    
    /**
     *      Indicators
     */
    
    include locate_template( 'template-parts/components/carousels/indicators.php' );
    
    
    /**
     *      Inner
     *
     */
    
    include locate_template( 'template-parts/components/carousels/inner.php' );

    
    
    /**
     *      Controls
     */
    include locate_template( 'template-parts/components/carousels/controls.php' );
    ?>
</div>
