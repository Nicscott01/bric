<?php
/**
 *  As of 11/9/2022 when i first made this file,
 *  I'm not actually using it because we're loading the
 *  baseline form on the page load. ajax loading
 *  didn't work. but i'm going to leave this here
 *  in case we figure out another way later on.
 * 
 * 
 */

if( !$has_access ) {

    echo do_shortcode( sprintf( '[dlm_gf_form download_id="%s" gf_ajax="true" gf_field_values="download_name=%s" gf_description="true"]', $download->get_id(), $download->get_slug() ) );
    //echo gravity_form (1, true, false, false, false, true);


  } else {

    //Get the download link
    $template_handler = new DLM_Template_Handler;


    $template_handler->get_template_part( 'gf-download-link', '', '', [ 'download' => $download ]  );

  }
