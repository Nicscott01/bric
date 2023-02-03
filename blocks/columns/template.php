<?php
/**
 *  Columns Block template
 * 
 * 
 * 
 */
//var_dump( $block );
//var_dump( $context );

$is_child = false;

//Find out if we're in a nested column item
if ( isset( $context['bric/column-item'] ) ) {

    $is_child = true;

}


$container = get_field( 'container' );
$bg_color_location = get_field( 'background_color_container' );
$bg_image = get_field( 'background_image' );


$bg_classes = '';
$bg_style = '';

if ( !empty( $bg_image ) ) {

    $bg_size = get_field( 'background_size' );
    $bg_position = get_field( 'background_position' );
    $bg_repeat= get_field( 'background_repeat' );
    $overlay_percentage = get_field( 'overlay_percentage' );
    
    //var_dump( $overlay_percentage );

    $bg_style = sprintf( 'background-image: url(%s); --bric-overlay-opacity: %s;', $bg_image['url'], intval( $overlay_percentage ) /100 );

    $bg_classes = sprintf( 'has-bg-img bg-size-%s bg-position-%s %s', $bg_size, $bg_position, $bg_repeat );

}


$columns_per_row_mobile = get_field( 'columns_per_row_mobile' );
$columns_per_row_tablet = get_field( 'columns_per_row_tablet' );
$columns_per_row_large = get_field( 'columns_per_row_large_screen' );

//var_dump( $block['anchor'] );


if ( ( isset( $block['backgroundColor'] ) || !empty( $bg_image ) ) && $bg_color_location == 'outside' ) {


        ?>
<div <?php echo isset( $block['anchor'] ) ? 'id="' . $block['anchor'] . '"' : ''; ?> class="block bric-columns-block <?php echo get_block_classes( $block ); ?> row <?php echo $bg_classes; ?> <?php echo $is_child ? 'w-100' : '';?>" style="<?php echo $bg_style; ?>">
    <div class="col-12 <?php echo $is_child ? '' : 'p-0';?>" style="z-index:1;">
        <div class="<?php echo $container; ?>">
        <?php
    
} elseif ( ( ( isset( $block['backgroundColor'] )  || !empty( $bg_image ) ) && $bg_color_location == 'inside' )) {

        ?>
<div class="block bric-columns-block row <?php echo $is_child ? 'w-100' : '';?>">
    <div class="col-12 <?php echo $is_child ? '' : 'p-0';?>" style="z-index:1;">
    <div class="<?php echo $container; ?> <?php echo get_block_classes( $block ); ?> <?php echo $bg_classes; ?>" style="<?php  echo $bg_style; ?>">
        <?php

} else {
    ?>
<div class="block bric-columns-block row <?php echo $is_child ? 'w-100' : '';?>">
    <div class="col-12 <?php echo $is_child ? '' : 'p-0';?>">
    <div class="no-bg-color <?php echo $container; ?> <?php echo get_block_classes( $block ); ?>">
    <?php
}



?>
    <InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( [ 'acf/column-item' ])) ?>" class="row <?php echo get_bootstrap_flex_classes( $block, 'grid-row' ); ?> cols-mobile-<?php echo $columns_per_row_mobile; ?> cols-tab-<?php echo $columns_per_row_tablet; ?> cols-large-<?php echo $columns_per_row_large; ?> <?php echo isset( $block['className'] ) ? $block['className'] : '';?>"/>
    </div>
</div>
</div>