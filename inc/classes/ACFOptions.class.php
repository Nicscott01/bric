<?php

namespace Bric;


class ACFOptions {

    public static $instance;


    public function __construct( )
    {

        add_action( 'acf/init', [ $this, 'add_options_pages' ] );
        
    }





    public function add_options_pages() {

        acf_add_options_sub_page([
            'page_title' => __( 'Social Media' ),
            'menu_title' => __( 'Social Media' ),
            'menu_slug' => 'social-media',
            'capability' => 'edit_posts',
            'position' => 10,
            'parent_slug' => 'options-general.php',
            'icon_url' => 'dashicons-share'
        ]);

    }









    public static function get_instance() {

        if( self::$instance == null ) {

            self::$instance = new self;

        }

        return self::$instance;
    }


}


function ACFOptions() {

    return ACFOptions::get_instance();

}

ACFOptions();