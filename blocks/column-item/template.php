<?php
/**
 *  Column Block template
 * 
 * 
 * 
 */

$columns_per_row_mobile = get_field( 'columns_per_row_mobile' );
$columns_per_row_tablet = get_field( 'columns_per_row_tablet' );
$columns_per_row_large = get_field( 'columns_per_row_large_screen' );

$class = '';

$class .= !empty( $columns_per_row_mobile ) ? 'col-' . $columns_per_row_mobile : '';
$class .= !empty( $columns_per_row_tablet ) ? ' col-md-' . $columns_per_row_tablet : '';
$class .= !empty( $columns_per_row_large ) ? ' col-lg-' . $columns_per_row_large : '';


$class = empty( $class ) ? 'col' : $class;

//var_dump( $block );

//Get the bg color
$bg_color = isset( $block['backgroundColor'] ) ? 'bg-' . $block['backgroundColor'] : '';

//Get the text color
$text_color = isset( $block['textColor']) ? 'text-' . $block['textColor'] : '';


?>
<InnerBlocks parent="<?php echo esc_attr( wp_json_encode( [ 'acf/columns' ])) ?>" class="d-flex <?php echo $class; ?> <?php echo $bg_color; ?> <?php echo $text_color; ?> <?php echo isset( $block['className'] ) ? $block['className'] : ''; ?>" /> 