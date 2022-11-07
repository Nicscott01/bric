<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

?>
<div class="dlm-nf-download-link row align-items-center my-3">
	<a href="<?php echo $download->get_the_download_link(); ?>" class="col-3 d-flex flex-column justify-content-center align-items-center">
	<?php 

	$download->the_image();
	?><span class="mt-2"><?php echo apply_filters( 'dlm_nf_download_link_label', __( 'Download', 'dlm-gravity-forms' ) ); ?></span> 
	</a>
</div>