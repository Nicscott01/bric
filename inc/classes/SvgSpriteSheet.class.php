<?php

namespace Bric;


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

        $replace_id = sprintf( '<symbol id="%s"', $id );

        $this->svgs[ $id ] = str_replace( ['<svg xmlns="http://www.w3.org/2000/svg"', '</svg>'], [ $replace_id, '</symbol>' ], $svg );

    }



    public function print_sprite_sheet() {

        ?>
<div class="d-none svg-sprite-sheet-php">
    <svg xmlns="http://www.w3.org/2000/svg">
        <?php echo implode( '', $this->svgs ); ?>
    </svg>
</div>
        <?php


    }




    public static function get_instance()
    {

        if (self::$instance == null) {

            self::$instance = new self;
        }

        return self::$instance;
    }
}




if (!function_exists('BricSvgSpriteSheet')) {

    function BricSvgSpriteSheet()
    {

        return \Bric\SvgSpriteSheet::get_instance();
    }
}


BricSvgSpriteSheet();
