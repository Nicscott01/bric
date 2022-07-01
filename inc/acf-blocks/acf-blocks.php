<?php





    acf_register_block_type( array(
        'name'              => 'google-map',
        'title'             => __('Google Map'),
        'description'       => __('Display custom Google map.'),
        'render_template'   => locate_template( 'template-parts/blocks/google-map.php' ),
        'category'          => 'embed',
        'icon'              => 'dashicons-location-alt',
        'keywords'          => array( 'map', 'google' ),
        'mode'				=> 'preview',
        ''
    ));



    acf_register_block_type( [
        'name'              => 'company-info',
        'title'             => __( 'Company Contact Info' ),
        'description'       => __( 'Display company contact information in a few ways.'),
        'render_template'   => locate_template( 'template-parts/blocks/company-info.php' ),
        'category'          => 'layout',
        'icon'              => '',
        'keywords'          => [ 'business', 'info', 'address', 'phone', 'contact' ],
        'mode'              => 'preview'
    ]);


