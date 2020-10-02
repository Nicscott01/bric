<?php
/**
 *  Job listing single exceprt
 *
 */
?>
<article class="py-5">
    <h1 class="h4 text-uppercase text-primary mb-1"><?php the_title();?></h1>
    <div class="job-meta d-flex text-orange mb-3 text-uppercase">
        <?php
        $location = wp_get_post_terms( get_the_ID(), 'location' );
        
        if( !empty( $location ) ) {
            
            printf( '<span class="mr-3"><strong>Location:</strong>&nbsp;%s</span>', $location[0]->name ); 
        }
        
       
        printf( '<span class="mr-3"><strong>Posted on: </strong>&nbsp;%s</span>', get_the_date() );
        
        ?>
    </div>
    <div class="entry-excerpt">
    <?php the_excerpt(); ?>
    <a class="btn btn-primary" href="<?php the_permalink(); ?>">Learn More</a>
    </div>
</article>