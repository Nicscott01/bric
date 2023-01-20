<?php
/**
 *  NAVBAR Template
 *  Get the navbar in all its beauty
 * 
 */


$main_nav_menu = wp_nav_menu( array(
	'theme_location' => 'primary',
	'echo' => 0,
	'menu_class' => 'navbar-nav ml-auto text-' . bric_get_theme_mod( 'navbar', 'text_transform' ),
	'container' => 'div',
	'container_class' => 'collapse navbar-collapse justify-content-end active-style-' . 
	bric_get_theme_mod( 'navbar', 'active_style' ) . ' active-color-' . bric_get_theme_mod( 'navbar', 'active_color' ),
	'container_id' => 'primary-theme-menu',
	'walker' => new BootstrapNavwalker(),
	'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>'
));	


?>
<nav class="navbar navbar-expand-<?php echo bric_get_theme_mod( 'navbar', 'expand_breakpoint'); ?> navbar-<?php echo bric_get_theme_mod( 'navbar', 'theme' ); ?> bg-<?php echo bric_get_theme_mod( 'navbar', 'bg_color' ) ?>">
  <div class="container-xxl">
	<?php
	
		get_template_part( 'template-parts/components/navbar/navbar-brand' );


		include locate_template( 'template-parts/components/navbar/navbar-toggler.php' );


		echo $main_nav_menu;
		
	?>   
  </div>
</nav>