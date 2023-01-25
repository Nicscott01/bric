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

    $cookie_consent_type = get_field( 'cookie_consent_type', 'option' );

    if ( $cookie_consent_type == 'uc' ) {

        get_template_part( 'template-parts/components/privacy/cookie-consent-loader' );
        
    }

});




/**
 *  Basic Cookie Consent
 * 
 * 
 */


 add_action( 'init', function() {


    $cookie_consent_type = get_field( 'cookie_consent_type', 'option' );


    $output_cookie_consent = apply_filters( 'bric_enable_cookie_consent', $cookie_consent_type == 'basic' ? true : false );


    if ( !is_admin() && $output_cookie_consent ) {

        //Load the template before the script. It matters.
        add_action( 'wp_footer', function() {

            get_template_part( 'template-parts/cookie-consent' );

        }, 10 );



        wp_enqueue_script( 'cookie-consent', get_template_directory_uri() . '/assets/js/cookie-banner.min.js', null, null, true );


    }

});

