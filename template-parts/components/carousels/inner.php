<?php
/**
 *      Carousel Inner Stuff
 *
 *
   <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="..." class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>First slide label</h5>
        <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="..." class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Second slide label</h5>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="..." class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Third slide label</h5>
        <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p>
      </div>
    </div>
  </div>
 
 */

?>
<div class="carousel-inner">
<?php
    $c = 0;
    foreach ( $gallery as $slide ) {
        
    ?>
    <div class="carousel-item <?php echo ( $c == 0 ) ? 'active' : ''; ?>" style="background-image:url(<?php echo $slide['image']['sizes']['large']; ?>)">
        <div class="content-wrapper d-flex justify-content-stretch h-100">
            <div class="content w-100 w-md-75 h-100 d-block p-5 mx-auto" >
                <?php
            $title = explode( '/', $slide['title'] );

            echo '<p class="h2 text-white text-center">';

            $l = 0;
            foreach( $title as $line ) {

                if( empty( $line ) ) {
                    continue;
                }

               printf( '<span class="d-block %s">%s</span>', ( $l == 1 ) ? 'font-weight-heavy text-orange h1 my-1' : 'font-weight-bold', $line );


                $l++;
            }

            echo '</p>';

                ?>
            </div>
        </div>
    </div>
    <?php
        
        $c++;
    }
    
    ?>
</div>