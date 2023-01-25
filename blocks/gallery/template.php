<?php
/**
 *  Gallery Block template
 * 
 * 
 *  TODO: Add option for photoswipe
 * 
 * 
 */

$images = get_field( 'images' );
$aspect_ratio = get_field('aspect_ratio');
$col_mobile = get_field('columns_mobile');
$col_tab = get_field('columns_tablet');
$col_lg = get_field('columns_large');
$padding = get_field( 'padding' );
$v_align = get_field( 'vertical_align' );
$h_align = get_field( 'horizontal_align' );
$crop = get_field( 'crop_images' );

$layout = get_field( 'layout' );



if ( empty( $images ) ) {
    return;
}

?>
<div class="block gallery-block <?php echo isset( $block['className'] ) ? $block['className'] : '';?>">
    <div id="<?php echo $block['id']; ?>" class="images grid row justify-content-center">
        <?php

        foreach( $images as $img ) {


            ?>
            <div class="grid-item col-<?php echo $col_mobile == 0 ? 'auto' : $col_mobile;  ?> col-md-<?php echo $col_tab == 0 ? 'auto' : $col_tab;   ?> col-lg-<?php echo $col_lg== 0 ? 'auto' : $col_lg;  ?> p-<?php echo $padding; ?> d-flex justify-content-<?php echo $h_align; ?> align-items-<?php echo $v_align; ?>">
                <?php 
                switch ( $aspect_ratio ) {

                    case "original" :

                        echo wp_get_attachment_image( $img['id'], 'large' );

                        break;

                    case "1x1" :
                    case "4x3" :
                    case "16x9" :

                        ?>
                    <div class="ratio ratio-<?php echo $aspect_ratio; ?>">
                        <?php 

                        $classes = $crop == false ? 'img-fit img-contain' : 'img-fit img-cover';

                        echo wp_get_attachment_image( $img['id'], 'large', false, [ 'class' => $classes ] ); 
                        
                        ?>
                    </div>
                        <?php
                        

                        break;
                }
                ?>
            </div>
            <?php

        }


        ?>
    </div>
</div>
<?php



if ( $layout == 'masonry' ) {

    //wp_enqueue_script( 'isotope' );
    wp_enqueue_script( 'masonry' );


    ob_start();

    ?>
(function($) {
    $('#<?php echo $block['id']; ?>').masonry({
        itemSelector: '.grid-item'
    });
})(jQuery);
    <?php

    $script = ob_get_clean();

    wp_add_inline_script( 'masonry', $script, 'after' );

}
