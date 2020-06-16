<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<?php wp_head(); ?>
<style>
	html {
		min-height: 100vh;
	}
	html, body {
		display: -webkit-box;
		display: -webkit-flex;
		display: -ms-flexbox;
		display: flex;
		-webkit-box-orient: vertical;
		-webkit-box-direction: normal;
		-webkit-flex-direction: column;
		    -ms-flex-direction: column;
		        flex-direction: column;
		-webkit-align-content: center;
		    -ms-flex-line-pack: center;
		        align-content: center;
		-webkit-box-pack: center;
		-webkit-justify-content: center;
		    -ms-flex-pack: center;
		        justify-content: center;
		-webkit-box-align: center;
		-webkit-align-items: center;
		    -ms-flex-align: center;
		        align-items: center;
	}
	
	body {
		font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
		-webkit-box-orient: vertical;
		-webkit-box-direction: normal;
		-webkit-flex-direction: column;
		    -ms-flex-direction: column;
		        flex-direction: column;
	}	
	
	h1 {
		text-align: center;
		font-size: 2em;
	}
	.image-container {
		-webkit-box-flex: 1;
		-webkit-flex: 1 1 auto;
		    -ms-flex: 1 1 auto;
		        flex: 1 1 auto;
		max-width: 100%;
	}
	img {
		max-width: 100%;
		height: auto;
	}
</style>
</head>
<body>
	<div class="image-container">
		<?php //echo wp_get_attachment_image( 5, 'medium_large' ); 
			the_custom_logo();
		?>
	</div>
	<h1>The new <?php bloginfo( 'name' ); ?> website is coming soon&hellip;</h1>	
</body>
</html>