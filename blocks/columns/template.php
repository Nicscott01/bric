<?php
/**
 *  Columns Block template
 * 
 * 
 * 
 */

$container = get_field( 'container' );
$bg_color_location = get_field( 'background_color_container' );

$columns_per_row_mobile = get_field( 'columns_per_row_mobile' );
$columns_per_row_tablet = get_field( 'columns_per_row_tablet' );
$columns_per_row_large = get_field( 'columns_per_row_large_screen' );

//var_dump( $block['anchor'] );


if ( isset( $block['backgroundColor'] ) && $bg_color_location == 'outside' ) {


        ?>
<div <?php echo isset( $block['anchor'] ) ? 'id="' . $block['anchor'] . '"' : ''; ?> class="block bric-columns-block bg-<?php echo $block['backgroundColor']; ?> row">
    <div class="col-12">
        <div class="<?php echo $container; ?>">
        <?php
    
} elseif ( ( isset( $block['backgroundColor'] ) && $bg_color_location == 'inside' )) {

        ?>
<div class="block bric-columns-block row">
    <div class="col-12">
    <div class="<?php echo $container; ?> bg-<?php echo $block['backgroundColor']; ?>">
        <?php

} else {
    ?>
<div class="block bric-columns-block row">
    <div class="col-12">
    <div class="no-bg-color <?php echo $container; ?>">
    <?php
}



?>
    <InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( [ 'acf/column-item' ])) ?>" class="row cols-mobile-<?php echo $columns_per_row_mobile; ?> cols-tab-<?php echo $columns_per_row_tablet; ?> cols-large-<?php echo $columns_per_row_large; ?> <?php echo isset( $block['className'] ) ? $block['className'] : '';?>"/>
    </div>
</div>
</div>