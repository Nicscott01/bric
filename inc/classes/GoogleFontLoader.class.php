<?php

namespace Bric;


class GoogleFontLoader {

    public static $instance;


    public function __construct()
    {
        //add_action( 'wp_head', [$this, 'temp'] );

        add_action( 'wp_head',  [ $this, 'preconnect' ] );
        add_action( 'admin_head', [ $this, 'preconnect' ] );
        add_action( 'wp_head', [ $this, 'enqueue_stylesheets' ] );
        add_action( 'admin_head', [ $this, 'enqueue_stylesheets' ] );
                


    }


    /**
     *  Google Preconnects
     * 
     * 
     */
    public function preconnect() {

        ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<?php

    }



 /**
  *     Google Fonts
  *
  *
  * <link rel="preconnect" href="https://fonts.googleapis.com">
  *   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  *   <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
  
  *   <link rel="preconnect" href="https://fonts.googleapis.com">
  *   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  *   <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Open+Sans:wght@800&display=swap" rel="stylesheet">
  */

   public function temp() {

    $fonts = [];
    
    //Get Fonts
    $fonts[] = bric_get_theme_mod( 'fonts', 'font_family_base' );
    $fonts[] = bric_get_theme_mod( 'fonts', 'font_family_alt' );

    var_dump( $fonts );

    

    }





    public function map_fonts_for_url_encoding( $font ) {

        $new_font = [];

        foreach( $font as $key => $value ) {
            
            if( $key == 'font' && $value == 'None' ) {
              
                continue;

            } elseif ( strpos( $key, 'weight' ) > 0 ) {

                if ( $value == 'regular' ) {

                    $new_value = 400;
                
                } else {

                    $new_value = $value;
                }




                if ( !empty( $new_value ) ) {

                    $new_font[$key] = $new_value;

                }
               

            } 

        }


        return $new_font;
        
    }




    /**
     * Enqueue Stylesheets
     */
    public function enqueue_stylesheets() {


        //Get Fonts
        $fonts[] = maybe_json_decode( bric_get_theme_mod( 'fonts', 'font_family_base' ) );
        $fonts[] = maybe_json_decode( bric_get_theme_mod( 'fonts', 'font_family_alt' ) );
      
        $fonts = apply_filters( 'bric_fonts', $fonts );

        //var_dump( $fonts );

        foreach( $fonts as $key => $font ) {

            if ( empty( $font ) ) {
                continue;
            }

      
            $font_families[ $font->font ] = $this->map_fonts_for_url_encoding( $font );

        }




        $base_url    = 'https://fonts.googleapis.com/css2?display=swap';
        $request_url = $base_url;

        foreach ( $font_families as $family => $variants ) {

            if ( empty( $family ) || $family == 'None' ) {
                continue;
            }

            // Sort variant tuples.
            usort(
                $variants,
                function( $a, $b ) {
                    $a_is_italic = \strpos( $a, 'italic' ) !== false;
                    $b_is_italic = \strpos( $b, 'italic' ) !== false;

                    $a = \str_replace( 'italic', '', $a );
                    $b = \str_replace( 'italic', '', $b );

                    $font_weight_a = empty( $a ) ? '400' : $a;
                    $font_weight_b = empty( $b ) ? '400' : $b;

                    if ( $a_is_italic && ! $b_is_italic ) {
                        return 1;
                    }

                    if ( ! $a_is_italic && $b_is_italic ) {
                        return -1;
                    }

                    if ( $font_weight_a === $font_weight_b ) {
                        return 0;
                    }

                    return ( $font_weight_a < $font_weight_b ) ? -1 : 1;
                }
            );

            // Determine variants to load.
            $has_italic               = false;
            $load_additional_variants = false;

         

            foreach ( $variants as $variant ) {


                if ( 400 !== $variant && 'regular' !== $variant ) {
                    $load_additional_variants = true;
                }

                if ( false !== \strpos( $variant, 'italic' ) ) {
                    $has_italic = true;
                }
            }

            // Construct url fragments.
            $url_fragment = "&family={$family}";

            // Regular only (no italic).
            if ( ! $has_italic && ! $load_additional_variants ) {
                $request_url .= $url_fragment;
                continue;
            }

            // Regular only (italic).
            if ( $has_italic && ! $load_additional_variants ) {
                $request_url .= "{$url_fragment}:ital@1";
                continue;
            }

            // Additional variants (no italic).
            if ( ! $has_italic && $load_additional_variants ) {
                $request_url .= "{$url_fragment}:wght@" . \implode( ';', array_unique( $variants ) );
                continue;
            }

            // Additional variants (some italic).
            $additional_variants = array_map(
                function( $variant ) {
                    $is_italic   = \strpos( $variant, 'italic' ) !== false;
                    $font_weight = \str_replace( 'italic', '', $variant );
                    $font_weight = empty( $font_weight ) ? '400' : $font_weight;

                    return $is_italic ? "1,{$font_weight}" : "0,{$font_weight}";
                },
                $variants
            );

            if ( $has_italic && $load_additional_variants ) {
                $request_url .= "{$url_fragment}:ital,wght@" . \implode( ';', array_unique( $additional_variants ) );
            }
        }

        if ( $request_url === $base_url ) {
            return;
        }

        echo "<link href='{$request_url}' rel='stylesheet'>\r\n"; // @codingStandardsIgnoreLine
    }








    public static function get_instance() {

        if ( self::$instance == null ) {

            self::$instance = new self;
        }

        return self::$instance;
    }


}



GoogleFontLoader::get_instance();