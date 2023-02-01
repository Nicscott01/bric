<?php
/**
 *  Accordion Block template
 * 
 * 
 * 
 */

global $accordion_block;

$header_copy = get_field( 'header' );

$id = $block['parentAnchor'];

$collapse_id = sanitize_title( $header_copy );
$heading_id = 'heading-' . $collapse_id;

//var_dump( $block );

?>
<div class="accordion-item">
    <h3 class="accordion-header" id="<?php echo $heading_id; ?>">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $collapse_id; ?>" aria-expanded="false" aria-controls="<?php echo $collapse_id; ?>"><?php echo $header_copy; ?></button>
    </h3>
    <div id="<?php echo $collapse_id; ?>" class="accordion-collapse collapse <?php echo is_admin() ? 'show' : ''; ?>" aria-labelledby="<?php echo $heading_id; ?>" data-bs-parent="#<?php echo $id; ?>">
      <div class="accordion-body">
        <InnerBlocks parent="<?php echo esc_attr( wp_json_encode( [ 'acf/accordion' ])) ?>" class="<?php echo isset( $block['className'] ) ? $block['className'] : ''; ?>" /> 
      </div>
    </div>
</div>