<?php
/**
 *	Template part for Article Header with Header Image
 *
 */

//Get the option for conforming the 

?>
<header <?php article_header_class(['page-header', 'row' ]); ?>>
	<div class="col-12">
	<h1 class="entry-title container-xxl <?php echo entry_title_class(); ?> text-white"><?php the_title(); ?></h1>
	<div class="header-image-wrapper p-0">		
		<div class="header-image ratio ratio-9x2">
			<?php the_post_thumbnail( 'full' ); ?>
		</div>
	</div>
	</div>
</header>