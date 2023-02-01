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

$class = [];

$class[] = column_classes( $columns_per_row_mobile );
$class[] = column_classes( $columns_per_row_tablet, 'md' );
$class[] = column_classes( $columns_per_row_large, 'lg' );

/*
$class .= !empty( $columns_per_row_mobile ) && $columns_per_row_mobile > 0 ? 'col-' . $columns_per_row_mobile : $columns_per_row_mobile == -1 ? 'col-auto' : 'col';
$class .= !empty( $columns_per_row_tablet ) ? ' col-md-' . $columns_per_row_tablet : '';
$class .= !empty( $columns_per_row_large ) ? ' col-lg-' . $columns_per_row_large : '';
*/


$class = implode( ' ', $class );

if ( empty( trim( $class) ) ) {
    $class = 'col';
}


//var_dump( $class );

//var_dump( get_block_classes( $block, get_field( 'flex_direction') ) );
//var_dump(  get_field( 'flex_direction') );

//Get the bg color
//$bg_color = isset( $block['backgroundColor'] ) ? 'bg-' . $block['backgroundColor'] : '';

//Get the text color
//$text_color = isset( $block['textColor']) ? 'text-' . $block['textColor'] : '';


?>
<InnerBlocks parent="<?php echo esc_attr( wp_json_encode( [ 'acf/columns' ])) ?>" class="d-flex <?php echo get_block_classes( $block, get_field( 'flex_direction' ) ); ?> <?php echo $class; ?> <?php echo isset( $block['className'] ) ? $block['className'] : ''; ?>" /> 