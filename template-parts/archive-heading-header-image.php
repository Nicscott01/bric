<?php
//v_dump( get_queried_object() );
?>

<header class="page-header archive-header">
	<h1 class="entry-title"><?php the_archive_title(); ?></h1>
	<div class="header-image-wrapper">
		<div class="header-image">
			<?php the_post_thumbnail( 'full' ); ?>
		</div>
	</div>
</header>