<?php
/**
 *  Template for a modal form
 * 
 * 
 */


$modals = DLM_Modals()->get_modals();


if ( !empty( $modals ) && is_array( $modals ) ) {
  foreach ( $modals as $modal ) {


    $download = new DLM_Download;

    $download->set_id( $modal['download_id'] );

    $has_access = apply_filters( 'dlm_can_download', false, $download );




?>
<div class="modal fade" id="form-for-download-id-<?php echo $modal['download_id']; ?>" tabindex="-1" aria-labelledby="gated-content-modal-id-<?php echo $modal['download_id']; ?>-label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header px-4">
        <h5 class="modal-title text-primary" id="gated-content-modal-id-<?php echo $modal['download_id']; ?>-label"><?php echo $has_access ? 'Your Download Is Ready' : 'Please Tell Us About Yourself'; ?></h5>
      </div>
      <div class="modal-body px-4">
       <?php

        if( !$has_access ) {

          echo do_shortcode( sprintf( '[dlm_gf_form download_id="%s" gf_ajax="true" gf_field_values="download_name=%s" gf_description="true"]', $modal['download_id'], $modal['download_slug'] ) );
        
        } else {

          //Get the download link
          $template_handler = new DLM_Template_Handler;

          $template_handler->get_template_part( 'gf-download-link', '', '', [ 'download' => $download ]  );

        }
       ?>
      </div>
    </div>
  </div>
</div>
<?php
  }
}