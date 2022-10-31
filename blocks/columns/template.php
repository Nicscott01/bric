<?php
/**
 *  Columns Block template
 * 
 * 
 * 
 */

$container = get_field( 'container' );

$columns_per_row_mobile = get_field( 'columns_per_row_mobile' );
$columns_per_row_tablet = get_field( 'columns_per_row_tablet' );
$columns_per_row_large = get_field( 'columns_per_row_large_screen' );


?>
<div class="block bric-columns-block <?php echo $container; ?>">
    <InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( [ 'acf/column-item' ])) ?>" class="row cols-mobile-<?php echo $columns_per_row_mobile; ?> cols-tab-<?php echo $columns_per_row_tablet; ?> cols-large-<?php echo $columns_per_row_large; ?> <?php echo isset( $attributes['className'] ) ? $attributes['className'] : '';?>"/>
</div>