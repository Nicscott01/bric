<?php
/**
 *  Template for a modal form
 * 
 * 
 */


$modals = DLM_Modals()->get_modals();


if ( !empty( $modals ) && is_array( $modals ) ) {

  ?>
<script>
  var bricAjaxUrl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
</script>
  <?php
 


  foreach ( $modals as $modal ) {



?>
<div class="modal fade" id="form-for-download-id-<?php echo $modal['download_id']; ?>" tabindex="-1" aria-labelledby="gated-content-modal-id-<?php echo $modal['download_id']; ?>-label" aria-hidden="true" data-dlm-id="<?php echo $modal['download_id']; ?>">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header px-4">
        <h5 class="modal-title text-primary" id="gated-content-modal-id-<?php echo $modal['download_id']; ?>-label">Please Tell Us About Yourself</h5>
      </div>
      <div id="gated-content-modal-id-<?php echo $modal['download_id']; ?>-body" class="modal-body px-4">
        <div class="form-container-test">
          <?php echo do_shortcode( sprintf( '[dlm_gf_form download_id="%s" gf_ajax="true" gf_field_values="download_name=%s" gf_description="true"]', $modal['download_id'], $modal['download_slug'] ) ); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  const modalDLM<?php echo $modal['download_id']; ?> = document.getElementById( 'form-for-download-id-<?php echo $modal['download_id']; ?>' );
  modalDLM<?php echo $modal['download_id']; ?>.addEventListener( 'show.bs.modal', event => {
    
    var data = {
      "action" : "dlm_modal",
      "download_id": event.srcElement.getAttribute( 'data-dlm-id' )
    };
    jQuery.post( bricAjaxUrl, data, function( resp ) {
      if ( resp.data.has_access ) {
        document.getElementById( "gated-content-modal-id-" + resp.data.download_id + "-label" ).innerHTML = resp.data.title;
        document.getElementById('gated-content-modal-id-' + resp.data.download_id + '-body' ).innerHTML = resp.data.body;
      }

    });


  });
</script>
<?php



  }
}