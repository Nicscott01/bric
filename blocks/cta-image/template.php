<?php
/**
 *  CTA Image Block template
 * 
 * 
 * 
 */

$image = get_field( 'image' );
$cta = get_field( 'cta' );
$overlay_opacity = get_field( 'overlay_opacity' );
$ratio = get_field( 'ratio' );

$block_css = get_block_classes( $block, 'column' );


if ( !empty( $ratio ) ) {

    $ratio_values = explode( ':', trim( $ratio ) );

} else {

    $ratio_values = [
        '16',
        '9'
    ];
}

?>
<div class="block cta-image-block <?php echo isset( $block['className'] ) ? $block['className'] : '';?>">
    <div class="ratio position-relative d-flex justify-content-center bg-size-cover bg-position-center bg-image-no-repeat" style="--bric-aspect-ratio: calc(<?php echo $ratio_values[1]; ?> / <?php echo $ratio_values[0]; ?> * 100%); background-image:url('<?php echo $image['url']; ?>');">
        <a href="<?php echo $cta['url']; ?>" target="<?php echo $cta['target']; ?>" class="cta-btn d-flex <?php echo $block_css; ?> text-decoration-none" style="--bric-bg-opacity: <?php echo $overlay_opacity / 100; ?>">    
            <span class=""><?php echo $cta['title']; ?></span>
        </a>
    </div>    
</div>

