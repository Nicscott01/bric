<?php
/**
 *		Class to control the CSS classes of the page
 *
 *		@since bric_v1.1
 *
 *		TODO: Work in progress. May or may not actually be needed!
 *
 */
namespace Bric;

class CSSClasses {
	
	
	var $SiteInfo;
    private static $instance;
	
	
	
	
	public function __construct() {
	
		global $SiteInfo;
		
		$this->SiteInfo = $SiteInfo;
		//->options->main_content->container
		
		
		add_action( 'init', [ $this, 'init'] );
	}
	
	
	
	
	
	
	public function init() {
		
		//Carousel class if edge-to-edge
		
		
	}
	
	
	
	
	
	
	
	
    /**
     * Singleton
     */
    public static function instance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

	
} 







function CSS() {

	return CSSClasses::instance();
	
}

CSS();