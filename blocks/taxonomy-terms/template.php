<?php
/**
 *  Taxonomy Terms Block template
 * 
 * 
 * 
 */

$what_to_show = get_field( 'what_to_show_tax' );
$taxonomy = get_field( 'taxonomy' );
$list_style = get_field( 'list_style' );

$color = isset( $block['textColor'] ) ? $block['textColor'] : '';
$block_classes = get_block_dimension_classes( get_block_dimensions( $block ) );

//var_dump( $taxonomy );
//Filter the taxonomy list based on the "what to show"
$taxonomies = get_taxonomies ([
  'public' => true
], 'objects' );

//var_dump( $taxonomies );


$args = [
  'taxonomy' => $taxonomy,
  'public' => true,
  'hide_empty' => true,
];


$filtered_taxonomies = [];

if ( $what_to_show == 'post_type') {
  
  $post_type = get_post_type();

//  var_dump( $post_type );

  $args['taxonomy'] = []; //reset the argument so we can put together a list.

  if( !empty( $taxonomies ) && is_array( $taxonomies ) ) {

    foreach( $taxonomies as $key => $tax ) {

      if ( in_array( $key, $taxonomy ) ) {

        if ( in_array( $post_type, $tax->object_type ) ) {
          //We have the taxonomy that belongs with this post type
          $filtered_taxonomies[$key] = $tax;
          $args['taxonomy'][] = $tax->name;

        }


      }


    }

  }

 

} 


//Make sure we load the chosen taxonomies if on an archive page
if ( empty( $args['taxonomy']) ) {
  $args['taxonomy'] = $taxonomy;
}


$terms = get_terms( $args );


if ( !empty( $terms ) ) {

  ?>
  <ul class="bric-recent-posts wp-block-latest-posts recent-<?php ?> <?php echo $list_style == 'none' ? 'list-unstyled' : ''; ?>" style="--bric-link-color: var( --bric-<?php echo $color; ?>);">
  <?php

  foreach ( $terms as $term ) {

    ?>
    <li class="<?php echo !empty( $block_classes ) ? implode( ' ', $block_classes ) : ''; ?>"><a href="<?php echo get_term_link( $term ); ?>"><?php echo $term->name; ?></a></li>
    <?php

  }

  ?>
  </ul>
  <?php
}