<?php

/**
 *		Class for restaurant type businesses
 *
 *
 */



class Restaurant {
	
	public $current_post = '';
	
	
	function __construct() {
		
		
		add_action( 'wp', array( $this, 'init' ) );

		
		add_filter( 'the_content', array( $this, 'inject_menu'), 200 ); //hook after our un/autop filter
		
		
	}
	
	
	function init() {

		$this->current_post = get_post();

		return $this;
		
	}
	
	
	
	
	function inject_menu( $the_content ) {
		
		
		if ( $this->has_restaurant_menu() == 'parent' ) {
			
			$the_content .= '<div class="restaurant-menu parent-menu-page">'.implode( '', $this->get_menu_pages() ).'</div>';
			
		}
		
		elseif ( $this->has_restaurant_menu() == 'section' ) {
			
			$the_content .= '<div class="restaurant-menu child-menu-page">'.implode( '', $this->get_restaurant_menu() ).'</div>';
			
		}
		
		
		return $the_content;
	}
	
	
	
	
	function has_restaurant_menu() {
		
		if ( have_rows( 'section' ) ) {
			
			$has_section = false;
			$parent = false;
			
			while ( have_rows( 'section' )) { the_row();
				
				if ( get_row_layout() == 'section' ) {
					
					$has_section = true;
				}
											 
				if ( get_row_layout() == 'menu_parent_page' ) {

					$parent = get_sub_field( 'set_as_menu_parent' );

				}
											 
											 
				
			}
			

			if ( $parent ) {

				return 'parent';

			}
			elseif ( $has_section ) {
				
				return 'section';
				
			}
			
		}
				
	}
	
	
	
	
	
	
	
	
	function get_menu_pages() {
		
		//		<div id="<?php echo $id; " class="restaurant-menu" role="tablist" itemscope itemtype="http://schema.org/MenuSection">
		//$parent = 0;
		
		if ( $this->has_restaurant_menu() == 'section' ) {
			$parent = $this->current_post->post_parent;
		}
		else {
			//This is the parent, so get the ID
			$parent = get_the_ID();
		}
		
		//var_dump( $parent );
		
		//Get all the children
		$children = new WP_Query( array( 
			'post_parent' => $parent, 
			'post_type' => 'page',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'posts_per_page' => -1,
			
		));
		
		//var_dump( $children->posts );
		
		foreach ( $children->posts as $child ) {
			
			ob_start();
			?>
				<div class="card menu-section">	
					<div class="card-header" role="tab">
						<h5><a href="<?php echo get_permalink( $child->ID ) ?>#heading-1" ><?php echo $child->post_title; ?></a></h5>
					</div>
				</div>
			
			<?php
			
			$menu_sections[$child->post_name] = ob_get_clean();
			
		
		}
		
		
		return $menu_sections;
		
	}
	
	
	
	
	
	function get_restaurant_menu() {
		
		
		/**
	 	 * 		Begin Restaurant Menu Code Here
		 *
		 *
		 */
		
		if ( have_rows( 'section' ) ) {
			
			$c = 1;
			$id = 'menu-accordian';
			
			
			
			ob_start();
			
			while ( have_rows( 'section' ) ) : the_row();
			
			
				?>
				<div class="card menu-section">	
					<div class="card-header" role="tab" id="heading-<?php echo $c; ?>">
						<h5><a data-toggle="collapse" href="#collapse-<?php echo $c; ?>" aria-controls="collapse-<?php echo $c; ?>" itemprop="name"><?php the_title(); ?></a></h5>
					</div>
				<div id="collapse-<?php echo $c; ?>" class="collapse <?php echo ($c == 1) ? 'show' : ''; ?>" role="tabpanel" aria-labelledby="heading-<?php echo $c?>" data-parent="#<?php echo $id; ?>">
					<div class="card-body">
						<?php 
							if ( get_sub_field('notes') ) {
								printf( '<div class="notes">%s</div>', get_sub_field('notes'));
							}
			
							if ( have_rows( 'items') ) {
								
								echo '<ul class="list-unstyled menu-items">';
								
								while ( have_rows( 'items') ) : the_row();
								
									$prices = get_sub_field( 'prices' );
									$sizes = '';
								
									
								
									if ( !empty( $prices[0]['price'] )) {
								
										foreach ( $prices as $price ) {

											$sizes .= sprintf( '<span class="size-price-group"><span class="size-name">%s</span><span class="size-price">%s</span></span>', $price['size'], $price['price'] );

										}
									}

									$description = '';
																	
									if ( !empty( get_sub_field('description') ) ) {
										
										$description = sprintf( '<span itemprop="description" class="description col-12 col-md-5 order-2">%s</span>',  get_sub_field('description') );
									} 
								
								
									printf( '
<li class="menu-item row justify-content-between align-items-center mb-3 mb-md-1" itemtype="http://schema.org/MenuItem">
	<span class="name col-12 col-md-3" itemprop="name">%s</span> 
	 %s
	<span class="prices col-12 col-md-auto order-3">%s</span>
</li>', get_sub_field('name'), $description, $sizes  );
								
								endwhile;
								
								echo '</ul>';
								
							}
						?>
					</div>
				</div>
				</div>
				<?php
			
				$c++;
			
			endwhile;
			
			$output = ob_get_clean();
						
		}
		
		//var_dump( $this->current_post );
		
		if ( $this->has_restaurant_menu() == 'section' && ( $this->current_post->post_parent == 0 ) ) {
			
			return $output = array( $output );		
		
		}
		else {
		

			$menu = $this->get_menu_pages();

			$menu[ $this->current_post->post_name] = $output;

			return $menu;
			
		}
		
		
	}
	
	
	
	
	
	
}



new Restaurant();