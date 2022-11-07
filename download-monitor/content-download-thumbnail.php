<?php
/**
 *  Template for content download w/ thumbnail. 
 *  This also adds modal functionality to
 *  the gated form piece.
 * 
 *  Todo: Change the download link if user is already authorized.
 * 
 */

 $download_id = $dlm_download->get_id();
 $form_id = get_post_meta( $download_id, '_dlm-gf-form', true );
 $download_slug = $dlm_download->get_slug();

?>
<div class="row align-items-start my-4"  href="<?php $dlm_download->the_download_link(); ?>">
    <h3>Read The Entire Article</h3>
    <div class="col-12 col-md-auto text-center mb-3">
        <div class="img-wrapper border border-light overflow-hidden" style="max-width: 200px; min-width:150px;" ><?php $dlm_download->the_image(); ?></div>
    </div>
    <div class="col-12 col-md content px-3">
        <div class="name display-2"><?php $dlm_download->the_title(); ?></div>
        <div class="description"><?php echo $dlm_download->get_description(); ?></div>
        <button type="button" class="btn btn-secondary mt-3 text-white" data-bs-toggle="modal" data-bs-target="#form-for-download-id-<?php echo $download_id; ?>">Read Now</button>
    </div>
</div>
<?php

DLM_Modals()->register_modal( $download_id, $form_id, $download_slug );