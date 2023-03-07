<?php
/**
 *  Nav Menu Block template
 * 
 * 
 * 
 */


 $main_nav_menu = wp_nav_menu( array(
  'theme_location' => 'primary',
  'echo' => 0,
  'menu_class' => 'navbar-nav ml-auto text-' . bric_get_theme_mod( 'navbar', 'text_transform' ),
  'container' => 'div',
  'container_class' => 'justify-content-end active-style-' . 
  bric_get_theme_mod( 'navbar', 'active_style' ) . ' active-color-' . bric_get_theme_mod( 'navbar', 'active_color' ),
  'container_id' => 'primary-theme-menu',
  'walker' => new Bootstrap5Navwalker(),
  'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>'
));	

include locate_template( 'template-parts/components/navbar/navbar-toggler.php' ); 

//echo $main_nav_menu;
//return;
?>
<div class="offcanvas offcanvas-end" tabindex="-1" id="navbar-collapse-slideout" aria-labelledby="offcanvasNavbarLabel">
  <div class="inner offcanvas-body ">  
    <div class="text-end">
      <button type="button" class="btn-close text-end d-lg-none mb-1" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
    <?php echo $main_nav_menu; ?>
  </div>
</div>


