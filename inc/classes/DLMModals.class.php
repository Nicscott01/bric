<?php
/**
 *  DLM Modal for gated content
 * 
 * 
 */





 /**
  *  Class to handle modals for Download Monitor
  *
  */


 if ( !class_exists( 'DLM_Modals') ) {

    class DLM_Modals {


        public static $instance;

        public $dlm_modals;



        public function __construct() {


            add_action( 'wp_footer', [ $this, 'print_modal' ], 2 ); 


        }


        public function register_modal( $download_id, $form_id, $download_slug ) {

            

            $this->dlm_modals[] = [ 
                'download_id' => $download_id, 
                'form_id' => $form_id,
                'download_slug' => $download_slug 
            ];
            
        

        }




        public function get_modals() {


            return $this->dlm_modals;


        }



        public function print_modal() {
            
            get_template_part( 'template-parts/modal-form' );
            
        }




        public static function get_instance() {

            if ( self::$instance == null ) {

                self::$instance = new self;
            }

            return self::$instance;

        }


    }



    function DLM_Modals() {

        return DLM_Modals::get_instance();

    }


    DLM_Modals();


 }
