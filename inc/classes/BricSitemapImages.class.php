<?php
/**
 *  Helper to add images to sitemap that may not be in featured image meta
 * 
 * 
 */


 if ( !class_exists( 'YoastSEOSitemapImages' ) ) {


    class YoastSEOSitemapImages {

        public static $instance;
        private $registered_blocks;
        private $current_block;

        public function __construct() {
            
            $this->registered_blocks = [];
        

            add_filter( 'wpseo_sitemap_urlimages', [ $this, 'add_sitemap_images'], 10, 2 );

        }




        public function register_block_images( $block_name = '', $field_name = '', $field_type = 'image' ) {


            $this->registered_blocks[$block_name] = [
                'block_name' => $block_name,
                'field_name' => $field_name,
                'field_type' => $field_type,
            ];
            

        }


        public function is_registered_block( $block_name ) {

            $has_block = false;

            foreach ( $this->registered_blocks as $registered_block ) {

                if( $registered_block['block_name'] == $block_name ) {
                
                

                $this->current_block = $registered_block;

                $has_block = true;


                } 
                        

            }


            return $has_block;

        }




        
        public function add_sitemap_images( $images, $post_id ) {

        
           

            $post = get_post( $post_id );
            $images_to_add = [];
            
            if ( has_blocks( $post ) ) {
                
                $blocks = parse_blocks( $post->post_content );
                            

                foreach( $blocks as $block ) {

                   // var_dump( $block );

                    if ( $this->is_registered_block( $block['blockName'] ) ) {

                        //var_dump( $block );

                        switch ( $this->current_block['field_type'] ) { 

                            case 'gallery' :
                            case 'relationship' :
                            
                                $image_ids = $block['attrs']['data'][$this->current_block['field_name']];

                                foreach( $image_ids as $imid ) {
                                    $images_to_add[] = $imid;
                                }

                            break;

                            case 'repeater' :
                                
                                foreach( $block['attrs']['data'] as $key => $value ) {

                                

                                    if ( strpos( $key, $this->current_block['field_name'] ) !== false ) {

                                        $images_to_add[] = $value;

                                    }
                                }

                        

                            case 'image' :
                            default :

                                

                                //Now grab the data from the attrs
                                $images_to_add[] = $block['attrs']['data'][$this->current_block['field_name']];

                            break;
                        }


                    }
        
                }


            }

          


            foreach ( $images_to_add as $img_to_add ) {



                $images[] = [
                    'src' => wp_get_attachment_image_url( $img_to_add, 'large', false),
                    'title' => get_the_title( $img_to_add ),
                    'alt' => get_post_meta( $img_to_add, '_wp_attachment_image_alt', true )
                ];

            }


    

            return $images;


        }




        public static function get_instance() {

            if ( self::$instance == null ) {
                self::$instance = new self;
            }

            return self::$instance;

        }

    }

    function BricSitemapImages() {

        return YoastSEOSitemapImages::get_instance();

    }

    BricSitemapImages();

 }