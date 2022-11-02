<?php

/**
 * Hero Header Block template
 * 
 * 
 * 
 */
global $post;

$featured_image = get_field('image');
$cta = get_field('call_to_action');

$min_height = get_field( 'minimum_height' );

$min_height_str = $min_height['value'] . $min_height['units'];

?>
<header class="block hero-header-block page-header row py-5 bg-size-cover bg-position-center bg-image-no-repeat <?php echo isset( $attributes['className'] ) ? $attributes['className'] : ''; ?>" style="background-image:url( <?php the_post_thumbnail_url( 'full' ); ?>); min-height: <?php echo $min_height_str; ?>">
    <div class="col-12 position-relative">
        <div class="container-xxl">
            <div class="row position-relative">
                <div class="col-12 col-lg-auto">
                    <div class="bg-secondary bg-opacity-62 px-5 py-4">
                        <h1 class="text-white mb-4 entry-title"><?php echo $post->post_title; ?></h1>
                        <?php
                        if (!empty($cta)) {

                            printf('<a class="btn btn-primary" href="%s" target="%s">%s</a>', $cta['url'], $cta['target'], $cta['title'] );

                        }
                        ?>
                        <InnerBlocks />
                    </div>
                </div>
            </div>
        </div>
        <?php if ( !empty( $featured_image ) ) { ?>
        <div class="col-12 col-lg-7 featured-image p-3">
                        <?php   

                        echo wp_get_attachment_image($featured_image['id'], 'medium_large', false, [ 'class' => 'img-fit img-contain']);
                        ?>
                </div>
        <?php
        }
        ?>
    </div>
    </header>
