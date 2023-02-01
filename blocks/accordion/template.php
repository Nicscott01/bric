<?php
/**
 *  Accordion Block template
 * 
 * 
 * 
 */

//var_dump( $block );

$block_css = [];

//var_dump( $block['backgroundColor']);

if( isset( $block['backgroundColor'] ) && !empty( $block['backgroundColor'] ) ) {

    $block_css[] = '--bric-accordion-bg:var( --bric-' . $block['backgroundColor'] .')';

} else {

    $block_css[] = '--bric-accordion-bg:transparent;';
}


if( isset( $block['textColor'] ) && !empty( $block['textColor'] ) ) {

    $block_css[] = '--bric-accordion-color:var( --bric-' . $block['textColor'] .')';

} 


//Accordion button background
$accordion_button_bg = get_field( 'accordion_button_background_theme_color' );

if ( !empty( $accordion_button_bg ) && $accordion_button_bg != 'default' ) {

    if ( $accordion_button_bg == 'transparent' ) {

        $block_css[] = '--bric-accordion-btn-bg:transparent';
        $block_css[] = '--bric-accordion-border-color:transparent';


    } else {

        $block_css[] = '--bric-accordion-btn-bg:var( --bric-' . $accordion_button_bg . ')';
        $block_css[] = '--bric-accordion-border-color:var( --bric-' . $accordion_button_bg . ')';

    }


}



//Accordion button background
$accordion_button_color = get_field( 'accordion_button_color_theme_color' );

if ( !empty( $accordion_button_color ) && $accordion_button_color != 'default' ) {

    if ( $accordion_button_color == 'transparent' ) {

        $block_css[] = '--bric-accordion-btn-color:transparent';


    } else {

        $block_css[] = '--bric-accordion-btn-color:var( --bric-' . $accordion_button_color . ')';

    }


}



?>
<div id="<?php echo isset( $block['anchor'] ) ? $block['anchor'] : ''; ?>"  class="block accordion-block accordion" style="<?php echo implode( '; ', $block_css ); ?>">
    <InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( [ 'acf/collapse' ])) ?>" class="inner-accordion <?php echo isset( $block['className'] ) ? $block['className'] : '';?>"/>
</div>
