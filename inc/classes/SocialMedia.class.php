<?php

namespace Bric;

use SVG\SVG;
use SVG\Nodes\Shapes\SVGRect;


class SocialMedia {


    public static $instance;
    public $social_accounts;
    public $social_icons;


    public function __construct()
    {

        add_filter( 'acf/load_value/name=social_accounts', [ $this, 'populate_social_accounts'], 10, 3 );
        add_filter( 'acf/load_value/name=social_accounts', [ $this, 'preload_svgs_front'], 20, 3 );
        
    }




    /**
     *  Load the Yoast SEO social fields
     *  into the repeater field only on admin screens
     *  
     *  
     *  platform field = field_63c999429ebba
     *  icon field = field_63c999749ebbb
     * 
     */

    public function populate_social_accounts( $value, $post_id, $field ) {

    
        $social_accounts = $this->get_social_accounts();

        if ( is_admin() ) {


            //First time, load them up
            if ( empty( $value ) ) {

                //Loop through the social accounts and
                foreach ( $social_accounts as $acct ) {
                    $value[] = [
                        'field_63c999429ebba' => $acct['platform']
                    ];
                }
        
        


            } else {
                //We'll build upon what's there

                $c = 0;

                $new_value = [];
                
                //Look through the fields and make sure we covered all the ones in yoast
                //var_dump( $value );

                //var_dump( $social_accounts );

                foreach( $value as $icon_field ) {

                    foreach( $social_accounts as $sa_key => $social_acct ) {

                        if ( $icon_field['field_63c999429ebba'] == $social_acct['platform'] ) {
                            unset( $social_accounts[$sa_key] );
                        }

                    }


                }

                $new_value = $value;

                //var_dump( $social_accounts );

                if( count( $social_accounts) ) {

                    foreach( $social_accounts as $new_acct ) {
                        $new_value[] = [
                            'field_63c999429ebba' => $new_acct['platform']
                        ];
                    }
                }

                /*
                foreach( $social_accounts as $acct  ) {

                    $new_value[] = [
                        'field_63c999429ebba' => $acct['platform'],
                        'field_63c999749ebbb' => $value[$c]['field_63c999749ebbb']   
                    ];

                    $c++;

                }
                */

                return $new_value;


            }
        
        }


        return $value;

    }




    /**
     *  ACF Filter
     * 
     *  platform field = field_63c999429ebba
     *  icon field = field_63c999749ebbb
     *
     */

     public function preload_svgs_front( $value, $post_id, $field ) {

        if ( !is_admin() && !empty( $value ) ) {


 //           var_dump( $value );

            foreach( $value as $social_account ) {

                $social_account_info = json_decode( $social_account['field_63c999749ebbb'] );

                $svg = $this->get_svg( $social_account_info->id, $social_account['field_63c999429ebba'] );



                if ( !empty( $svg ) ) {
                    //Add icon to global sprite sheet
                    BricSvgSpriteSheet()->add_svg( $svg, $social_account_info->id ); //strtolower( $social_account['field_63c999429ebba'] ) );
                }

            }

        
        }

        return $value;
    }





    public function get_svg( $id, $platform ) {

        //Look for the svg in our folder(s)
        $maybe_svg = locate_template( '/assets/src/svgs/social/' . $id . '.svg' );

        if( !empty( $maybe_svg ) ) {

            $svg = file_get_contents( $maybe_svg );

            if ( !empty( $svg ) ) {

                $svg = self::svg_fill( $svg );

                $this->add_icon( $id, $svg, $platform );

                return $svg; 

            }

        }

    }



    public function add_icon( $id, $svg, $platform ) {

        $social_account = $this->get_social_account( strtolower( $platform ) );

        if ( isset( $social_account['url'] ) && !empty( $social_account['url'] ) ) {

            $this->social_icons[ $id ] = [
                'svg' => $svg,
                'id'  => $id,
                'viewBox' => self::getViewBox( $svg ),
                'platform' => $platform,
                'url' => $social_account['url']
            ];
        }

        return $this;
    }







    public static function getViewBox( $svg ) {

        require_once ( get_template_directory(  ) . '/vendor/autoload.php' );

        $svg = SVG::fromString( $svg );

        $svg_doc = $svg->getDocument();
        
        //$symbols = $svg_doc->getElementsByTagName( 'symbol' );
        
        $viewBox = $svg_doc->getAttribute( 'viewBox' );

        return $viewBox;


    }



    /**
     *  Add fill="currentColor" for those 
     *  that don't have fill defined.
     * 
     * 
     */

    public static function svg_fill( $svg ) {

        require_once ( get_template_directory(  ) . '/vendor/autoload.php' );
        
        $svg = SVG::fromString( $svg );

        $svg_doc = $svg->getDocument();

        $svg_fill = $svg_doc->setAttribute( 'fill', 'currentColor' );

        return $svg->toXMLString(false);
        
    }




    /**
     *  Retrieve a social account
     *  
     */

    public function get_social_account( $id ) {

        $accounts = $this->get_social_accounts();

        if ( isset( $accounts[$id] ) && !empty( $accounts[$id] ) ) {

            return $accounts[$id];

        } else {
         
            return false;
        
        }

    }






    /**
     *  Get Social Accounts
     *  
     * 
     * 
     */

    public function get_social_accounts() {

        //Don't do the query if we alreay have it
      /*  if ( !empty( $this->social_accounts ) ) {

            return $this->social_accounts;
        }
*/

        //Get the social links
		$social_urls = get_option( 'wpseo_social' );

        if ( !empty( $social_urls ) && is_array( $social_urls ) ) {

            foreach( $social_urls as $service => $url ) {

             

                if ( is_string( $url ) ) {

            
                    switch( $service ) {
            
                        case 'facebook_site' :

                            if ( !empty( $url ) ) {

                                $this->social_accounts['facebook'] = [
                                    'url' => $url,
                                    'platform' => 'Facebook',
                                ];

                            }
            
                            break;
            
                        case 'twitter_site' : 
            

                            if ( !empty( $url ) ) {

                                $this->social_accounts['twitter'] = [
                                    'url' => 'https://twitter.com/' . $url,
                                    'platform' => 'Twitter',
                                ];
                
                            }
            
                            break;
            
            
                    }
            
            
                    //$social['url'] = 
            
                } elseif ( is_array( $url ) && $service == 'other_social_urls' ) {
            

                    //Other Social URLs
                    foreach( $url as $other_url ) {
            
                        $url_parts = parse_url( $other_url );
            
                        if ( isset( $url_parts['host'] ) && !empty( $url_parts['host'] ) ) {
                        
                            $host_platform = str_replace( [ 'www.', '.com' ], '', $url_parts['host'] );
            
            
                            $this->social_accounts[$host_platform] = [
                                'url' => $other_url,
                                'platform' => ucfirst( $host_platform ),
                            ];
                        }
            
                     
            
            
                    }
            
                }
        

            }
        }


        return $this->social_accounts;

    }





    public static function get_instance() {

        if ( self::$instance == null ) {

            self::$instance = new self;

        }

        return self::$instance;

    }



}




if ( !function_exists( 'BricSocialMedia' ) ) {

    function BricSocialMedia() {

        return \Bric\SocialMedia::get_instance();
    }

}


BricSocialMedia();