<?php
/**
 *  Plugin loader for Bric theme
 *
 *
 */
   
$include_plugins = apply_filters( 'bric_include_plugins', [ ] );



if ( !empty( $include_plugins ) ) {

    foreach( $include_plugins as $plugin ) {

        $file = get_template_directory() . '/inc/plugins/' . $plugin . '.php';

        if( file_exists( $file ) ) {

            include_once( $file );

            do_action( 'bric_plugin_init_' . $plugin );

        }

    }
}   