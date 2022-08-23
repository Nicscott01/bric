<?php
/**
 *      Template for Navbar Brand 
 * 
 *      Added to make class tweaking easier
 * 
 * 
 *      @date 12/8/21
 *      
 */




$navbar_brand_width = bric_get_theme_mod( 'navbar', 'brand_width' );
$site_logo = get_theme_mod( 'custom_logo' );

//Get site info
$title = get_bloginfo( 'name' );
//$tagline = get_bloginfo( 'description' );





?>
  <a class="navbar-brand" href="<?php echo get_home_url( ); ?>" style="max-width:<?php echo $navbar_brand_width; ?>px";>
		<?php

		if ( !empty( $site_logo ) ) {
			
			if ( is_svg( $site_logo ) ) {
				
				$logo = get_svg_source( $site_logo );
				
			} else {
			
				$logo = wp_get_attachment_image( $site_logo, 'medium' );
				
			}
           
            //Print the logo
            echo $logo;
			
		} else {

            //There's no logo, so we'll use the site title and tag
            echo $title;

        }
		?>
  </a>
