<?php
/**
 *      Headshot/Bio Template
 *
 *      $staff_members variable available with all posts
 */

//var_dump( $staff_members );    

if ( empty( $staff_members ) ) {
    
    return;
    
}

?>
<div class="staff-members">
<?php

global $post;

foreach ( $staff_members as $post ) {
    
    setup_postdata( $post );
    
    if ( !empty( $post->post_content ) ) {
    ?>
    <section id="<?php echo sanitize_title( get_the_title() ); ?>" class="row mb-5 py-5">
        <?php if ( has_post_thumbnail() ) : ?>
        <div class="col-12 col-md-4 col-xl-3 headshot mb-3">
            <div class="embed-responsive embed-responsive-4by3">
                <?php the_post_thumbnail( 'medium', [ 'class' => 'embed-responsive-item'] ); ?>
            </div>
        </div>
        <?php endif; ?>
        <div class="col-12 col-md-8 col-xl-9 bio">
            <h3 class="mt-0 mb-1"><?php the_title(); ?></h3>
            <div class="title h5">
            <?php
            $title = get_field( 'title' );
            
            if ( !empty( $title ) ) {
                
                echo '<p>' . $title . '</p>';
            
            } else {
                
                //See if we have a term
                $position_type = get_the_terms( $post, 'position_class' );
                
                if ( !is_wp_error( $position_type ) || !empty( $position_type ) ) {
                    
                    echo '<p>' . $position_type[0]->name . '</p>';
                    
                }
                
            }
            
            ?>
            </div>
            <?php the_content(); ?>
        </div>
    </section>
    <?php
    }
}

wp_reset_postdata();
?>
</div>
