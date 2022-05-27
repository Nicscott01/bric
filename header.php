<!doctype html>
<html lang="en-US">
  <head>
	<meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
   	<?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
	  <?php
	  /**
	   *	Bric Before Header
	   *
	   *
	   */
	  
	  		do_action( 'bric_before_header' );
	  ?>
	<header class="site-header" role="banner">
		<?php 
		
			/**
			 *		Bric Header
			 *
			 *		'navbar' - 10
			 */
			
			do_action( 'bric_header' );
		
		?>
	</header>
<?php 

	/**
	 *		Bric After Header
	 *
	 *		
	 */

	do_action( 'bric_after_header' );

?>
