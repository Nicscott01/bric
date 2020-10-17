<?php
/**
 *      Indicator Template
 *
   <ol class="carousel-indicators">
    <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
    <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
  </ol> 
 
 */
?>


<ol class="carousel-indicators">
    <?php
    $c = 0;
    foreach( $gallery as $slide ) {
        
        printf( '<li data-target="#homepage-carousel" data-slide-to="%s" class="%s"></li>', $c, ( $c == 0 ) ? 'active' : '' );
        
        $c++;
    }
    
    ?>
</ol>
