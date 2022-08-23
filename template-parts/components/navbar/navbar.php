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
<nav class="navbar navbar-expand-lg navbar-<?php echo bric_get_theme_mod( 'navbar', 'theme' ); ?> bg-<?php echo bric_get_theme_mod( 'navbar', 'bg_color' ) ?>">
  <div class="container-xxl">
	<?php
	
		get_template_part( 'template-parts/components/navbar/navbar-brand' );


		include locate_template( 'template-parts/components/navbar/navbar-toggler.php' );


		echo $main_nav_menu;
		
	?>   
  </div>
</nav>


<?php
return;


		global $SiteInfo;
		
		
		$navbar_expression = include locate_template( 'template-parts/components/navbar/navbar-expression.php' );

		$this->navbar_options = array(
			'container' => $SiteInfo->navbar->container,
			'expand' => $SiteInfo->navbar->expand,
			'bg_color' => bric_get_theme_mod( 'navbar', 'bg_color'),
			'navbar_color' => bric_get_theme_mod('navbar', 'theme' ),
			'content_before' => array(
				//'html' => '',
				//'above_navbar' => false,
			),
			'navbar_expression' => $navbar_expression,
		);
		
		/**
		 *		Filter the options
		 *
		 *
		 */	
		
		
		
		$this->navbar_options = apply_filters( 'bric_navbar_options', $this->navbar_options, $this );
		
		

		
		
		if ( !empty( $this->navbar_options['content_before'] )  ) {
			
			/*
			if ( !$this->navbar_options['content_before']['above_navbar'] ) {
				
				$this->main_nav_menu_items_wrap = '<div class="content-above-nav-menu">'.$this->navbar_options['content_before']['html'].'</div>'.$this->main_nav_menu_items_wrap;

				$this->main_nav_menu_container_class = $this->main_nav_menu_container_class.' has-content-above';
				
			}
*/
			//else {
				
				
				
				$this->content_above_nav = sprintf( '<div class="%s"><div class="row">%s</div></div>',
											 ( $this->navbar_options['container'] ) ? 'content-above-navbar container' : 'content-above-navbar container-fluid',											 
											 $this->navbar_options['content_before']['html']
											);
				
			//}
			
			
		}		
		
				
		
		
		
		
		printf( $this->navbar_options['navbar_expression'], 
			   $this->get_navbar_brand(), //1
			   $this->get_main_nav_menu(), //2
			   $this->get_navbar_toggler(), //3 
			   $this->navbar_options['expand'], //4
			   '<div class="container-xxl navbar-inner-wrapper">', //5
			   '</div>', //6
			   $this->navbar_options['navbar_color'], //7
			   $this->navbar_options['bg_color'], //8
			   $this->content_above_nav, //9
			   $this->get_header_cta(), //10
			   $this->get_main_nav_menu( 'left' ) //11
			  );
		