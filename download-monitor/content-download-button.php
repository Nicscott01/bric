<?php
/**
 *  Template for content download w/ thumbnail. 
 *  This also adds modal functionality to
 *  the gated form piece.
 * 
 *  Todo: Change the download link if user is already authorized.
 * 
 */

global $bric_button;


$base_button_class = '';
$button_text = '';

if ( isset( $bric_button) && !empty( $bric_button ) ) {

    $base_button_class = $bric_button['class'];
    $button_text = $bric_button[ 'text' ];
    
}


 $download_id = $dlm_download->get_id();
 $form_id = get_post_meta( $download_id, '_dlm-gf-form', true );
 $download_slug = $dlm_download->get_slug();

?>

<button type="button" class="<?php echo !empty( $base_button_class ) ? $base_button_class : 'btn btn-primary mt-3 text-white'; ?>" data-bs-toggle="modal" data-bs-target="#form-for-download-id-<?php echo $download_id; ?>"><?php echo !empty( $button_text ) ? $button_text : 'Download  Now'; ?></button>

<?php

DLM_Modals()->register_modal( $download_id, $form_id, $download_slug );