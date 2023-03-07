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


//check to see if we're on our own or part of an accordion
if ( empty( $id ) ) {

  $collapse_id = $block['id'];

  //Todo: For now we're making the button text the same as the rest of the block
  $text_color = $block['textColor'];
  $text_link_style = !empty( $text_color ) ? sprintf( '--bric-link-color:var(--bric-%s);', $text_color ) : '';


  ?>
<div class="block collapse-block <?php echo get_block_classes( $block ); ?>" style="<?php echo $text_link_style; ?>">
  <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $collapse_id; ?>" aria-expanded="false" aria-controls="<?php echo $collapse_id; ?>"><?php echo $header_copy; ?></button>
  <div class="collapse" id="<?php echo $collapse_id; ?>">
    <InnerBlocks class="<?php echo isset( $block['className'] ) ? $block['className'] : ''; ?>" /> 
  </div>
</div>
  <?php


} else {

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
<?php
}