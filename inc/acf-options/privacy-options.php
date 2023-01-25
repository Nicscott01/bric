<?php

/**
 *  Add options page for more privacy
 * 
 * 
 */
add_action( 'acf/init', function() {

    if ( !function_exists( 'acf_add_options_page' ) ) {
        return;
    }


    $options_page = acf_add_options_page([
        'page_title' => __( 'Enhanced Privacy Settings'),
        'menu_title' => __( 'Cookie Policy/Consent' ),
        'parent_slug' => 'options-general.php',
        'position' => '7.0'
    ]);

});



/**
 *  Call the Cookie Consent code template
 * 
 */
add_action( 'bric_head_first', function() {

    get_template_part( 'template-parts/components/privacy/cookie-consent-loader' );

});