<?php
/**
 *  Menu Block template
 * 
 * 
 * 
 */

 $flex_direction = get_field( 'flex_direction' );
 $gap = get_field( 'gap' );
 $menu = get_field( 'wp_nav_menu' );
 $sep = get_field( 'separator' );
 $text_color = $block['textColor'];

 $style = [];

 if ( !empty( $sep )  ) {
  
    $style[] = '--bric-separator: ' . $sep . ';';
 
  }

  if ( !empty( $text_color ) ) {

    $style[] = ' --bric-link-color: --bric-' . $text_color . ';';

  }

  if ( !empty( $gap ) ) {

    $style[] = ' --bric-separator-padding-x: var(--wp--preset--spacing--'. $gap . ');'; 
  }


  //Put the att in the filter class
  BricFilters()->nav_menu_link_att = [
    'att' => 'data-separator',
    'val' => $sep 
  ];

  add_filter( 'nav_menu_link_attributes', [ BricFilters(), 'add_menu_data_attribute' ], 10, 4 );

/**
 *  Get the menu
 * 
 * 
 */
?>
<div class="block menu-block <?php echo !empty( $sep ) ? 'has-separator' : ''; ?>" style="<?php echo implode( ' ', $style );?>">
<?php
 $main_nav_menu = wp_nav_menu( [
    'menu' => $menu,
    'menu_class' => 'd-flex flex-' . $flex_direction . ' list-unstyled m-0 gap-' . $gap,
    'container_class' => 'menu-container ' . get_block_classes( $block ),
 ]);
 ?>
</div>