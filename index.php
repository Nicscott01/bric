<?php

get_header();

?>
<div class="main-content">
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
</div>
<?php

get_footer();

?>