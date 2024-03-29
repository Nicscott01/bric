<?php

/**
 *		Class to initialize a Bric Child theme
 *
 *
 */


class BricChild {
	
	/**
	 *		Instance
	 */
	static public $instance;
	
	/**
	 *		Get Instance
	 */
	public static function get_instance() {
		
		if ( self::$instance == null ) {
			
			self::$instance = new self;
		}
		
		return self::$instance;
	}
	
	
	
	/**
	 *		Fonts
	 *
	 */
	public $google_fonts = [];
	public $google_font_url = '';
	
	

	
	
	
	
	
	
	function __construct() {
		
		add_action( 'after_switch_theme', array( $this, 'setup_child') );
		
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts_styles' ] );
		
	}
	
	
	
	
	public function setup_child() {
		
		//Get child name
		$this->get_child_folder();
		
		
		if ( ! file_exists(  get_theme_root() . '/' . $this->folder_name ) ) {

			//copy child model folder
			$this->copy_child_model();

			if ( $this->copied ) {

				//edit the style.css header
				$this->edit_style_header();


				//create symlink to node_modules
				$this->create_node_modules_symlink();

				
			}
			
		}

		
		//Set the child theme as the stylesheet
		update_option( 'stylesheet', $this->folder_name );
		
						//set base variables in theme mod
				//$this->set_child_variables_defaults();
		
	}
	
	
	
	
	
	public function get_child_folder() {
		
		$this->site_name = get_bloginfo( 'name' );
		
		$this->folder_name = sanitize_title( $this->site_name ) . '-' . ( string )get_current_blog_id();
		
		return $this;
	}
	
	
	
	
	public function copy_child_model() {
		
		$this->folder_path = get_theme_root().'/'.$this->folder_name;
		
		$this->copied = xcopy( get_template_directory().'/inc/bric-child/', $this->folder_path );
				
		return $this;
		
	}
	
	
	
	
	public function edit_style_header() {
		
		$style = file_get_contents( $this->folder_path.'/style.css' );
		
		$style = str_replace( 'child_theme_name', $this->site_name, $style );
		$style = str_replace( 'child_theme_uri', get_bloginfo('url'), $style );
		
		
		file_put_contents( $this->folder_path.'/style.css', $style );
		
		
		
	}
	
	
	
	
	public function create_node_modules_symlink() {
		
		symlink( get_template_directory().'/node_modules', $this->folder_path.'/node_modules' );
				
	}
	
	
	
	
	public function set_child_variables_defaults() {
		
		ob_start();
		
		?>
//
//		VARIABLES FOR <?php echo $this->site_name; ?>
//

$c1: #000;
$c2: #222;
$c3: #000;
$c4: #959595;
$c5: #95989A;

$custom-logo-max-width: 200px;

//
// Color system
//

$white:  #fff;
$gray-100: #f8f9fa;
$gray-200: #e9ecef;
$gray-300: #dee2e6;
$gray-400: #ced4da;
$gray-500: #adb5bd;
$gray-600: #868e96;
$gray-700: #495057;
$gray-800: #343a40;
$gray-900: $c1;
$black:  #000;


//
//	THEME COLOR PALATE
//

$blue:    #007bff;
$indigo:  #6610f2;
$purple:  #6f42c1;
$pink:    #e83e8c;
$red:     #dc3545;
$orange:  #fd7e14;
$yellow:  #ffc107;
$green:   #28a745;
$teal:    #20c997;
$cyan:    #17a2b8;

$theme-colors: (
  primary: $c1,
  secondary: $c2,
  success: $green,
  info: $cyan,
  warning: $yellow,
  danger: $red,
  light: $gray-100,
  dark: $gray-800
);


//
//		TYPOGRAPHY
//

$font-family-sans-serif: 'Lato', sans-serif;



// Links
//
// Style anchor elements.

$link-color:      		$c4;
$link-hover-color:      $c5;



//
//		HEADINGS
//
$headings-color: $c2;
		<?php
		
		$variables = ob_get_clean();
		
		set_theme_mod( 'custom_scss', $variables );
		
	}
	
	
	
	
	
	
	
	public function define_google_fonts( $fonts = [] ) {
		
		$this->google_fonts = $fonts;
		
		return $this;
	}
	
	
	
	
	public function enqueue_scripts_styles() {
		
		
		
		
		if ( !empty( $this->google_fonts ) ) {
			
			$c = count( $this->google_fonts );
			
			$font_string = [];
			
			foreach( $this->google_fonts as $font ) {
				
                //var_dump( $font );
                
				$font_string[] = http_build_query( ['family' => $font ] );
				
            
                
                
				if ( $c > 1 ) {
					
				    //$font_string .= "\&";
				
				} else {
				
					$font_string[] = http_build_query( ['display' => 'swap']);
					
				}
				
				$c--;
			}
			
            
			

			$this->google_font_url = urldecode( 'https://fonts.googleapis.com/css2?' . implode( '&', $font_string ) );
			
		
            if ( !is_admin() ) {
                add_action( 'wp_head', [ $this, 'enqueue_google_fonts_v2' ], 11 );
    			//add_action( 'wp_enqueue_style', [ $this, 'enqueue_google_fonts_v2' ], 11 );
            } else {
                
                wp_enqueue_style( 'bric-fonts', $this->google_font_url );

            }
			
            
		}
		

		
	}
	
	
	
	public function enqueue_google_fonts_v2() {
        
        ?>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link rel="preload" as="style" href="<?php echo $this->google_font_url; ?>" />
<link rel="stylesheet" href="<?php echo $this->google_font_url; ?>" media="print" onload="this.media='all'" />
<noscript>
    <link rel="stylesheet" href="<?php echo $this->google_font_url; ?>">
</noscript>
        <?php

        
        add_action( 'wp_footer_d', function() {
            
            printf( '<link href="%s" rel="stylesheet" media="print" onload="this.media=\'all\'">', $this->google_font_url );
            
        }, 1 );
		
		
		
	}
	
	
	
	
	
	
}



function BricChild() {
	
	return BricChild::get_instance();
	
}


BricChild();