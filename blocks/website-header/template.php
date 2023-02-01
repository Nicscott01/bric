<?php

/**
 *  Website Header Block template
 * 
 * 
 * 
 */

$flex_direction = get_field( 'layout' );
$navbar_expand_breakpoint = bric_get_theme_mod('navbar', 'expand_breakpoint');


if ( $flex_direction == 'row' ) {

    $flex_class = 'flex-row-reverse flex-' . $navbar_expand_breakpoint . '-row';

} elseif ( $flex_direction == 'column' ) {

    $flex_class = 'flex-column align-items-end';

}



?>
<nav class="navbar-block navbar navbar-expand-<?php echo $navbar_expand_breakpoint; ?> navbar-<?php echo bric_get_theme_mod('navbar', 'theme'); ?> bg-<?php echo bric_get_theme_mod('navbar', 'bg_color') ?>">
    <div class="container-xxl">
        <?php get_template_part('template-parts/components/navbar/navbar-brand'); ?>
        <InnerBlocks allowedBlocks="<?php //echo esc_attr( wp_json_encode( [ 'acf/column-item' ])) ?>" class="d-flex <?php echo $flex_class; ?> gap-3<?php echo isset($block['className']) ? $block['className'] : ''; ?>" />
    </div>
</nav>