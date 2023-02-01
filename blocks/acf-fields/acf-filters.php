<?php

/**
 *  Pre load some values
 * 
 * 
 */


 add_filter( 'acf/load_field/name=wp_nav_menu', function( $field ) {

    $field['choices'] = [];

    $nav_menus = get_terms( 'nav_menu' );


    if ( !empty( $nav_menus ) && is_array( $nav_menus ) ) {

        foreach( $nav_menus as $nav_menu ) {

            $field['choices'][$nav_menu->term_id] = $nav_menu->name;

        } 
    }


    return $field;

 });