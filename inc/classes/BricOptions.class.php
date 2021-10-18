<?php
/**
 *		CUSTOMIZER SETTINGS for BRIC
 *
 *		@edits 3/9/19
 *		-Remove SCSSS from customizer
 *
 */



class BricOptions {
	
	
	function __construct() {
		
	
			
		add_action( 'customize_register', array( $this, 'create_customizations') );
		
		//add_action( 'wp_head', array( $this, 'output_css') );
		
		
		//add_action( 'theme_mod_custom_scss', array( $this, 'get_scss_file') );
		
		
		//add_action( 'customize_save_after', array( $this, 'write_custom_css') );
		add_action( 'customize_save_after', array( $this, 'run_grunt_task') );
	
		
		//add_filter( 'wp_get_custom_css', array( $this, 'override_wp_custom_css') );
		
		
		//add_action( 'customize_preview_init', array( $this, 'css_preview_js' ) );
		
		//add_action( 'customize_controls_enqueue_scripts', array( $this, 'customizer_code_formatter' ) );
	
		//add_action( 'customize_controls_print_footer_scripts', array( $this, 'behave_init'), 50);
		
		

		
	}
	
	
	
	function create_customizations( WP_Customize_Manager $wp_customize ) {
		
		$wp_customize->remove_section( 'custom_css' );

		
		/*
		$wp_customize->add_section( 'bric_css', array(
			'priority' => 160,
			'title' => 'Bric CSS',
			'description' => 'Description coming soon...',
		
		));
		*/

		/*
		$wp_customize->add_setting( 'custom_scss', array(
				'type' => 'theme_mod',
				'capability' => 'edit_theme_options',
				'default' => '//Enter SCSS Variables here',
				//'validate_callback' => array( $this, 'validate_scss' ),
				'transport' => 'postMessage',
				//'dirty' => true,
		));
		
		$wp_customize->add_setting( 'custom_scss_styles', array(
				'type' => 'theme_mod',
				'capability' => 'edit_theme_options',
				'default' => '//Enter SCSS Styles here',
				//'validate_callback' => array( $this, 'validate_scss' ),
				'transport' => 'postMessage',
				//'dirty' => true,
				'validate_callback' => array( $this, 'scss_validation'),
		));
		
		
		$wp_customize->add_control( 'custom_scss', array(
			'type' => 'textarea',
			'priority' => 5,
			'section' => 'bric_css',
			'label'=> 'Theme Variables',
			'description' => 'Enter variables for child theme',
			'input_attrs' => array(
				'class' => 'theme-variables',
				'placeholder' => '#333',
				'style' => 'min-height:400px; font-family: "SFMono-Regular", Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; font-size:.8rem;'
			),
			'active_callback' => '', //when to show the control
		));
		
		$wp_customize->add_control( 'custom_scss_styles', array(
			'type' => 'textarea',
			'priority' => 5,
			'section' => 'bric_css',
			'label'=> 'SCSS Styles',
			'description' => 'Enter additional custom styles',
			'input_attrs' => array(
				'class' => 'theme-variables',
				'placeholder' => '#333',
				'style' => 'min-height:400px; font-family: "SFMono-Regular", Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; font-size:.8rem;'
			),
			'active_callback' => '', //when to show the control
		));
		
		*/
		/*
		$wp_customize->selective_refresh->add_partial( 'custom_scss', array(
			'settings' => array( 'custom_scss', 'custom_scss_styles' ),
			'selector' => '#bric-customizer-css',
			'container_inclusive' => true,
			'render_callback' => function() {
							
				BricOptions::save_css_files();
				$grunt_task = BricOptions::run_grunt_task('dev');
				
				//$grunt_task = json_encode( $grunt_task );
				
				if ( end( $grunt_task ) == 'Done, without errors.' ) {
					
					return sprintf( '<link id="%s" rel="stylesheet" href="%s">', 'bric-customizer-css', get_stylesheet_directory_uri().'/assets/css/bric-style-customizer.css' );
					
				}
				else {
					
					return false;
				}
				
				
			
			},
			'fallback_refresh' => false,
		));
		*/
		
		//$wp_customize->remove_control( 'custom_css' );
				
		//$wp_customize->get_control('custom_css')->label = 'Additional S/CSS';
		//$wp_customize->get_control('custom_css')->input_attrs['style'] = 'min-height:400px; font-family: "SFMono-Regular", Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; font-size:10px;';
		
		
		
		
		
		
		/*
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_control', array(
			'label' => 'Link Color',
			'section' => 'colors',
			'settings' => 'link_color',
		)));
		
		
		$wp_customize->add_section( 'custom_scss', array(
		  'title' => __( 'Custom SCSS' ),
		  'description' => __( 'Add custom CSS here' ),
		  'priority' => 160,
		  'capability' => 'edit_theme_options',
		) );
		
		*/
		
		
		
		
		
		//$this->maybe_compile_css();
		
		
		
		
		
		
		
		/**
		 *		Add Homepage Setting
		 *		-Enable homepage slider
		 *
		 *
		 *//*
		
		$wp_customize->add_setting( 'bric[homepage_slider]', array(
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'default' => false,
			'transport' => 'refresh',
			'sanitize_callback' => '',
			'sanitize_js_callback' => '',
		));
		
		
		$wp_customize->add_control( 'bric[homepage_slider]', array(
			'type' => 'checkbox',
			'priority' => 10,
			'section' => 'static_front_page',
			'label' => __('Homepage Slider'),
			'description' => __('Enable homepage slide show'),
			'input_attrs' => [
				'class' => 'checkbox'
				
			],
			'active_callback' => 'is_front_page',
		));
		
		*/
		
		
		
		
		
		
		
	}
	
	
	
	
	public function scss_validation( $validity, $value, $setting_object ) {
		
		
		
		
		
		return $validity;
		
	}
	
	
	
	
	
	
	
	/*
	public function write_custom_css() {
		
		$custom_css = wp_get_custom_css();
		
		file_put_contents( get_stylesheet_directory().'/assets/src/css/_custom-css.scss', $custom_css );
		
		
	}*/
	
	
	
	public function save_css_files() {
				
		$scss_variables = get_theme_mod( 'custom_scss' );
		
		file_put_contents( get_stylesheet_directory().'/assets/src/css/_child-variables-customizer.scss', $scss_variables );
			
		
		$scss_styles = get_theme_mod( 'custom_scss_styles' );
		
		file_put_contents( get_stylesheet_directory().'/assets/src/css/_custom-css.scss', $scss_styles );
			
		
	}
	
	
	
	
	
	public function maybe_compile_css() {
		
		$customizer_css_file = get_stylesheet_directory().'/assets/css/bric-style-customizer.css';
		
		if ( !file_exists( $customizer_css_file )) {
			
			$this->compile_css();
			
			
		}
		
		
		
		
		
	}
	
	
	
	
	
	
	
	public function compile_css() {
		
		
		if ( is_customize_preview() ) {
			
			return $this->run_grunt_task( 'dev' );
						
		}
		
		else {
			
			return $this->run_grunt_task();
		
		}

	}
	
	
	
	
	
	
	
	
	
	function get_scss_file( $default ) {
		
		
		return file_get_contents( get_stylesheet_directory().'/assets/src/css/_child-variables.scss' );
		
		
	}
	
	
	
	
	
	
	public function run_grunt_task( $commit = '' ) {

		if ( !function_exists( 'exec' ) ) {
			return;
		}

		$env = array(
			array( 'PATH', PATH_NODE ),
			array( 'PATH', PATH_SASS ),
			array( 'GEM_HOME', GEM_HOME),
			array( 'RUBYLIB', RUBYLIB ),			
		);



		if ( $commit == 'dev' ) {

			$command = sprintf( 'cd %s && grunt sass:dev --no-color', get_stylesheet_directory() );

		}
		else {

			$command = sprintf( 'cd %s && grunt --no-color', get_stylesheet_directory() );

		}


		foreach ( $env as $putenv ) {

			$ext = ( $putenv[0] == 'PATH' ) ? ':'.getenv('PATH') : '';

			$put = $putenv[0] .'='. $putenv[1] . $ext;

			putenv( $put );

		}

		$output = [];

		exec( $command, $output );


		return $output;


	}

	
	
	
	
	public function customizer_code_formatter() {
		
		
		wp_enqueue_script( 'behave', get_template_directory_uri().'/assets/js/behave.js', array('jquery'), null, true);
		
	}
	
	
	public function behave_init() {
		
		if ( is_customize_preview() ) {
	
		?>
<script>
window.onload = function() {
	var editor_scss = new Behave({
		textarea: document.getElementById('customize-control-custom_scss_styles').querySelector('textarea'),
		replaceTab: 	true,
		softTabs: 		true,
		tabSize: 		4,
		autoOpen: 		true,
		overwrite: 		true,
		autoStrip: 		true,
		autoIndent: 	true
	});
	
	var editor_variables = new Behave({
		textarea: document.getElementById('customize-control-custom_scss').querySelector('textarea'),
		replaceTab: 	true,
		softTabs: 		true,
		tabSize: 		4,
		autoOpen: 		true,
		overwrite: 		true,
		autoStrip: 		true,
		autoIndent: 	true
	});
	
};		
</script>
		<?php
		
		}
		
	}
	
	
	/**
 	 * Load our Script of when to tell the customizer to run scss compile
	 *
	 */
	
	public function css_preview_js() {
		
		 wp_enqueue_script( 'bric_scss_preview', get_template_directory_uri().'/assets/js/bric_scss_preview.js', array( 'customize-preview', 'jquery' ) );		
	}
	
	
	
}

global $BricOptions;

$BricOptions = new BricOptions();