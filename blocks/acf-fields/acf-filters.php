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





 /**
  *     Add post types to selection
  */

add_filter( 'acf/load_field/name=what_to_show', function( $field ) {

    //Public post types
    $post_types = get_post_types([
        'public' => true
    ], 'objects' );


   //error_log( json_encode( $post_types ) );

    if ( !empty( $post_types ) && is_array( $post_types ) ) {
        foreach( $post_types as $post_type ) {
            $field['choices'][$post_type->name] = $post_type->label;
        }
    }

    return $field;


}, 10, 1 );




 /**
  *     Add taxonomies to selection
  */

  add_filter( 'acf/load_field/name=taxonomy', function( $field ) {

    //Get taxonomies
    $taxonomies = get_taxonomies( [
        'public' => true
    ], 'objects' );

    //Clear choices
    $field['choices'] = [];

    //error_log( json_encode( $taxonomies ) );

    if( !empty( $taxonomies ) && is_array( $taxonomies ) ) {

        foreach( $taxonomies as $taxonomy ) {

            $field['choices'][$taxonomy->name] = $taxonomy->label;
         
        }


    }


    return $field;


  }, 10, 1 );