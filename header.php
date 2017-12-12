<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   	<?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
	<header class="site-header">
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