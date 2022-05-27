<?php
/**
 *		Basic Content Template
 *
 *
 */


?>
<div class="px-3 my-3"><?php do_action( 'acs_breadcrumbs' ); ?></div>
<div class="row">

    <article id="post-<?php the_ID(); ?>" <?php post_class( 'col-12 col-md entry-content-container' ); ?>>	
        <div class="<?php entry_content_class(); ?> row">
            <div class="col-12 order-1">
                <h1><?php the_title();?></h1>
                <p class="h3"><?php the_field( 'position' ); ?></p>
                <?php the_content(); ?>
            </div>
            <div class="col-12 order-0 mb-3">
                <div class="col-md-6 p-0"><?php the_post_thumbnail( 'medium_large' ); ?></div>
            </div>
        </div>
    </article>
    <div class="col-12 col-md-auto">
        <?php acs_get_inner_sidebar( true ) ?>
    </div>
</div>
