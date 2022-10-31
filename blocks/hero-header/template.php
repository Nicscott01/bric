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

?>
<div class="block hero-header-block bg-dark bg-opacity-75 row py-5 <?php echo isset( $attributes['className'] ) ? $attributes['className'] : ''; ?>">
    <div class="col-12 position-relative">
        <div class="container-xxl">
            <div class="row position-relative">
                <div class="col-12 col-lg-8">
                    <div class="bg-secondary bg-opacity-75 px-5 py-4">
                        <h1 class="text-white mb-4"><?php echo $post->post_title; ?></h1>
                        <?php
                        if (!empty($cta)) {

                            $title = trp_registered_tm($cta['title']);

                            printf('<a class="btn btn-primary" href="%s" target="%s">%s</a>', $cta['url'], $cta['target'], $title);
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-7 featured-image p-3">
                        <?php

                        echo wp_get_attachment_image($featured_image['id'], 'medium_large', false, [ 'class' => 'img-fit img-contain']);
                        ?>
                </div>

    </div>
</div>
