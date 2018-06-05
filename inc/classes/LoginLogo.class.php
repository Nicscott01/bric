<?php

class LoginLogo {
	
	function __construct() {
		
		
		//Customize Login Screen Logo
		add_action( 'login_enqueue_scripts', array( $this, 'login_logo' ) );

		add_action( 'password_protected_login_head', array( $this, 'maintenance_mode_scripts') );	
		
		add_action( 'password_protected_before_login_form', array( $this, 'maintenance_mode_message') );
		add_action( 'password_protected_after_login_form', array( $this, 'maintenance_mode_message_after') );
		
		
		//ACF ACtion
		add_action( 'acf/save_post', array( $this, 'maybe_maintenance_mode'), 20, 1 ); 
		
	}
	
	
	
	function login_logo() { 
		
		global $SiteInfo;
		
		$logo = wp_get_attachment_image_url( $SiteInfo->logo, 'medium' );
		
		$logo_data = pathinfo( $logo );
				
		$bg_size = 'contain';
		
		if ( $logo_data['extension'] == 'svg' ) {
			
			$bg_size = '200px auto';
		}
		
		
	?>
		<style type="text/css">
			#login h1 a, .login h1 a {
			background-image: url(<?php echo $logo; ?>);
			width:100%;
			background-size: <?php echo $bg_size; ?>;
			background-repeat: no-repeat;
			padding-bottom: 30px;
			}
		</style>
	<?php }
	
	
	
	
	function maintenance_mode_scripts() {
		
		printf( '<link type="text/css" rel="stylesheet" media="all" href="%s">', get_stylesheet_directory_uri().'/assets/css/bric-style.css' );
		
		printf( '<script src="%s"></script>', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js' );
		printf( '<script src="%s"></script>', get_template_directory_uri().'/assets/js/popper.min.js' );
		printf( '<script src="%s"></script>', get_template_directory_uri().'/assets/js/bootstrap.min.js' );
		
		
	}
	
	
	
	function maintenance_mode_message() {
		
		?>
	<div class="col-12 text-center">
		<?php
		global $SiteInfo;
		
		printf( '<a href="%s">%s</a>', get_bloginfo('url'), wp_get_attachment_image( $SiteInfo->logo, 'medium' ) );
		
		?>
	</div>
	<div class="col-12">
		<p class="text-center h4 mb-5">We're currently undergoing some maintenance. Please check back soon.</p>
		<div class="text-center mt-5"><?php echo $SiteInfo->print_all_business_info(); ?></div>
	</div>
		<?php
	}
	
	
	
	function maintenance_mode_message_after() {
		
		//var_dump( get_option('password_protected_status'));
		
		?>
		<?php
		
	}
	
	
	
	
	function maybe_maintenance_mode( $post_id ) {
		
		
		if ( $post_id == 'options' && get_current_screen()->id == 'toplevel_page_acf-options-site-info' ) {
			
			
			$maintenance_mode = get_field( 'enable_maintenance_mode', 'options' );
			
			if ( $maintenance_mode ) {
				
				$password_plugin = WP_PLUGIN_DIR .'/'. 'password-protected/password-protected.php' ;
				
				//var_dump( WP_PLUGIN_DIR .'/'. $password_plugin );
				
				if ( file_exists( $password_plugin ) ) {
				
					//Activate plugin
					activate_plugins( $password_plugin, '/wp-admin/options-general.php?page=password-protected' );


					//Make sure the option for password protect is enabled
					update_option( 'password_protected_status', 1 );
					
					
					//Check to see if password is set
					$pwd = get_option( 'password_protected_password' );
					
					if ( empty( $pwd )) {

						wp_redirect( '/wp-admin/options-general.php?page=password-protected'  );
						exit;
						
					}
				
				}
				
			}
			
			else {
				
				deactivate_plugins( plugin_basename( 'password-protected/password-protected.php')  );
				
			}
			
		} 
		
		
	}
	
	
	
}

new LoginLogo();