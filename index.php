<?php

get_header();

?>
<main class="main-content container<?php echo get_post_type() == 'page' ? '-fluid' : ''; ?>" role="main">
	<div class="row">
<?php

do_action( 'bric_before_loop');

if ( have_posts() ) : 

		do_action( 'bric_before_loop_posts' );
		
    while ( have_posts() ) : the_post();
		
		do_action( 'bric_loop' );

	endwhile;
		
        do_action( 'bric_after_loop_posts' );

else :
		
	do_action( 'bric_no_posts');
		
endif;
		

do_action( 'bric_after_loop');
		
	?>
	</div>
</main>
<?php

get_footer();

?>