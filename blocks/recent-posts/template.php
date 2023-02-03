<?php
/**
 *  Recent Posts Block template
 * 
 * 
 * 
 */

$what_to_show = get_field( 'what_to_show' );
$list_style = get_field( 'list_style' );

$color = isset( $block['textColor'] ) ? $block['textColor'] : '';
$block_classes = get_block_dimension_classes( get_block_dimensions( $block ) );


$args = [
'posts_per_page' => get_field( 'number_of_posts'),
'post_status' => 'publish'
];


if ( $what_to_show == 'post_type') {

  
  $args['post_type'] = get_post_type() !== 'page' ? get_post_type() : 'post'; //default to post if on page

} elseif ( $what_to_show == 'any' ) {

  $args['post_type'] = 'any';

} else {

  $args['post_type'] = $what_to_show;

}


$posts = get_posts( $args );

if ( !empty( $posts ) ) {

  ?>
  <ul class="bric-recent-posts wp-block-latest-posts recent-<?php echo $args['post_type']; ?> <?php echo $list_style == 'none' ? 'list-unstyled' : ''; ?>" style="--bric-link-color: var( --bric-<?php echo $color; ?>);">
  <?php

  foreach ( $posts as $post ) {

    ?>
    <li class="<?php echo !empty( $block_classes ) ? implode( ' ', $block_classes ) : ''; ?>"><a href="<?php echo get_permalink( $post ); ?>"><?php echo $post->post_title; ?></a></li>
    <?php

  }

  ?>
  </ul>
  <?php
}