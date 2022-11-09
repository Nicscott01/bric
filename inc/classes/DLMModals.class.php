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

            /**
             *  Ajax load template part
             *  for modal/gated content
             * 
             * 
             */

            add_action( 'wp_ajax_nopriv_dlm_modal', [ $this, 'ajax_dlm_modal' ] );
            add_action( 'wp_ajax_dlm_modal', [ $this, 'ajax_dlm_modal' ] );

          




        }


        public function register_modal( $download_id, $form_id, $download_slug ) {

            

            $this->dlm_modals[ $download_id ] = [ 
                'download_id' => $download_id, 
                'form_id' => $form_id,
                'download_slug' => $download_slug 
            ];
            
        

        }




        public function get_modals() {


            return $this->dlm_modals;


        }



        public function ajax_dlm_modal() {

            $download = new DLM_Download;
            $post = get_post( $_REQUEST['download_id'] );

            $download->set_id( $post->ID );
            $download->set_slug( $post->post_name );
            

            $has_access = apply_filters( 'dlm_can_download', false, $download );
        
            $result = [];
            $result['has_access'] = $has_access;
            $result['download_id'] = $_REQUEST['download_id'];

            if ( $has_access ) {

                ob_start();
                include locate_template( 'template-parts/modal/dlm/title.php' );
                $result['title'] = ob_get_clean();

                ob_start();
                include locate_template( 'template-parts/modal/dlm/body.php' );
                $result['body'] = ob_get_clean();
                
            }

            echo wp_send_json_success( $result );

            exit();

        }



        public function print_modal() {
            
            get_template_part( 'template-parts/modal/dlm/modal' );
            
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