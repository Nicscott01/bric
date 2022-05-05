<?php
/**
 *  NAVBAR Template
 *  Get the navbar in all its beauty
 * 
 */

		global $SiteInfo;
		
		
		$navbar_expression = include locate_template( 'template-parts/components/navbar/navbar-expression.php' );

		$this->navbar_options = array(
			'container' => $SiteInfo->navbar->container,
			'expand' => $SiteInfo->navbar->expand,
			'bg_color' => $SiteInfo->navbar->bg_color,
			'navbar_color' => $SiteInfo->navbar->navbar_color,
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
			   ( $this->navbar_options['container'] ) ? '<div class="container navbar-inner-wrapper">' : '', //5
			   ( $this->navbar_options['container'] ) ? '</div>' : '', //6
			   $this->navbar_options['navbar_color'], //7
			   $this->navbar_options['bg_color'], //8
			   $this->content_above_nav, //9
			   $this->get_header_cta(), //10
			   $this->get_main_nav_menu( 'left' ) //11
			  );
		