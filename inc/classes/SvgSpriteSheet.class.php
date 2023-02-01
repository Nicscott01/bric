<?php

namespace Bric;

use SVG\SVG;
use SVG\Nodes\Shapes\SVGRect;



class SvgSpriteSheet
{


    public static $instance;
    public $svgs;


    public function __construct()
    {

        add_action( 'wp_footer', [ $this, 'print_sprite_sheet' ], 100 );

    }



    /**
     *  Add svg based on the assumption
     *  that it came from Font Awesome 6.
     *  Others may work.
     * 
     * 
     */

    public function add_svg( $svg, $id ) {

       /* echo '<pre>';
        var_dump( $svg );
        echo '</pre>';
*/

        if ( empty( $svg ) ) {
            return;
        }

        require_once ( get_template_directory(  ) . '/vendor/autoload.php' );

        //var_dump( $svg );


        $svg = SVG::fromString( $svg );

        $svg_doc = $svg->getDocument();
        
        //$symbols = $svg_doc->getElementsByTagName( 'symbol' );
        
        $viewBox = $svg_doc->getAttribute( 'viewBox' );
        
        $maybe_fill = $svg_doc->getAttribute( 'fill' );

        if ( empty( $maybe_fill ) ) {

            $svg_doc->setAttribute( 'fill', 'currentColor' );
        }

        $svg = $svg->toXMLString(false);

        $replace_id = sprintf( '<symbol id="%s"', $id );

        $this->svgs[ $id ] = [
                'svg' => str_replace( ['<svg xmlns="http://www.w3.org/2000/svg"', '</svg>'], [ $replace_id, '</symbol>' ], $svg ),
                'id' => $id,
                'viewBox' => $viewBox
            ];

    }



    public function print_sprite_sheet() {



        if ( !empty( $this->svgs) ) {
        ?>
<div class="d-none svg-sprite-sheet-php">
    <svg xmlns="http://www.w3.org/2000/svg">
        <?php 
        foreach( $this->svgs as $svg ) {
            echo $svg['svg'];
        }
        ?>
    </svg>
</div>
        <?php
        }

    }



    public function get_svg_use( $id ) {


        if ( isset( $this->svgs[$id]) ) {
        
            
            return sprintf( '<svg viewBox="%s"><use xlink:href="#%s"></use></svg>', $this->svgs[$id]['viewBox'], $id );
        }

    }








    public static function getViewBox( $svg ) {

        require_once ( get_template_directory(  ) . '/vendor/autoload.php' );

var_dump( $svg );

        $svg = SVG::fromString( $svg );

        $svg_doc = $svg->getDocument();
        
        //$symbols = $svg_doc->getElementsByTagName( 'symbol' );
        
        $viewBox = $svg_doc->getAttribute( 'viewBox' );

        return $viewBox;


    }







    public static function get_instance()
    {

        if (self::$instance == null) {

            self::$instance = new self;
        }

        return self::$instance;
    }
}





SvgSpriteSheet::get_instance();
