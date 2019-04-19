<?php

include get_template_directory().'/inc/classes/BootstrapNavwalker.class.php';


include get_template_directory().'/inc/classes/BricNotices.class.php';
include get_template_directory().'/inc/classes/Bric.class.php';
include get_template_directory().'/inc/classes/BricOptions.class.php';
include get_template_directory().'/inc/classes/OptionsPages.class.php';

//ACF Fields
include get_template_directory().'/inc/acf-fields/site_info.php';



include get_template_directory().'/inc/classes/SiteInfo.class.php';
$opts = get_theme_mod( 'bric' ); 
if ( isset( $opts['homepage_slider'] ) && $opts['homepage_slider'] )
include get_template_directory().'/inc/acf-fields/homepage_slider.php';



include get_template_directory().'/inc/classes/LoginLogo.class.php';

include get_template_directory().'/inc/classes/BricChild.class.php';
include get_template_directory().'/inc/classes/Navbar.class.php';
include get_template_directory().'/inc/classes/BricLoop.class.php';
include get_template_directory().'/inc/classes/BricWidgets.class.php';
include get_template_directory().'/inc/classes/Carousel.class.php';
include get_template_directory().'/inc/classes/PhotoGallery.class.php';
include get_template_directory().'/inc/classes/Slideout.class.php';
//include get_template_directory().'/inc/classes/Columns.class.php'; //-- Disabled because of conflict with shortcode filter in Gutenberg
include get_template_directory().'/inc/classes/BricShortcodes.class.php';
include get_template_directory().'/inc/classes/Shortcakes.class.php';
include get_template_directory().'/inc/classes/GoogleMaps.class.php';
include get_template_directory().'/inc/classes/Restaurant.class.php';

//Integrations 
include get_template_directory().'/inc/classes/Integrations.class.php';



//Functions
include get_template_directory().'/inc/functions/utility.php';


//Register Actions
include get_template_directory().'/inc/actions.php';
include get_template_directory().'/inc/filters.php';






/**
 *		Bric Main Content Class
 *
 *		@since v1.1
 *
 *		Output proper class for main content div based on 
 *		Customizer settings.
 */


function bric_main_content_class( $classes = [] ) {
	
	$extra_classes = $classes;
	
	global $SiteInfo;
	
	
	
	
}


/**
 *		Bric Entry Content Class
 *
 *		@since v1.1
 *
 *		Output proper class for entry-content div based on 
 *		Customizer settings.
 */


function entry_content_class( $classes = [] ) {
	
	global $SiteInfo;
	
	//Get the page option
	
	
	
	$classes[] = 'entry-content';
	$classes[] = $SiteInfo->options->entry_content_class;
	
	$classes = array_map( 'trim', $classes );
	
	echo join( ' ', $classes );
	
}
















