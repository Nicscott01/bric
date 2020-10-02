<?php
/**
 *      Template for job listings
 *
 */

//var $jobs;


?>
<div class="job-listings">
    
<?php

global $post;

foreach ( $jobs as $post ) {
    
    setup_postdata( $post );
    
    
    get_template_part( 'template-parts/components/job-listings/job-listing' );
    
    
}
wp_reset_postdata();
?>
</div>
