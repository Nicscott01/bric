<?php
/**
 *  Lower Footer Block template
 * 
 * 
 * 
 */

// var_dump( $block['style'] );

 $dimensions = get_block_dimensions( $block );

 extract( $dimensions );

 var_dump( $padding );
 var_dump( $margin );

 get_template_part( 'template-parts/footer-lower' ); 

?>
 <InnerBlocks class="<?php echo isset( $block['className'] ) ? $block['className'] : ''; ?>" /> 