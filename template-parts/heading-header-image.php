<?php
/**
 *	Template part for Article Header with Header Image
 *
 */

//Get the option for conforming the 

?>
<header <?php article_header_class(['page-header', 'row' ]); ?>>
	<h1 class="entry-title <?php echo entry_title_class(); ?> text-center text-primary text-uppercase"><?php the_title(); ?></h1>
	<div class="header-image-wrapper p-0">		
		<div class="header-image ratio ratio-9x2">
			<?php the_post_thumbnail( 'full' ); ?>
		</div>
	</div>
</header>