<?php
/**
 *		SiteInfo Class for Bric Theme
 *
 *		Controls the businss info fields, options for theme.
 *	
 *	
 *
 */





class SiteInfo {
	
	public $name = '';
	public $description = '';
	public $type = '';
	public $phone = '';
	public $copyright_start = '';
	public $copyright_owner = '';
	public $logo = '';
	public $industry_logos = array();
	public $email = '';
	public $social ='';
	public $options = '';
	var $breadcrumbs = [];
	public $body_classes = array();
	public $carousel = array();
	public $operating_hours = array();
	
	function __construct() {
		
		if ( !defined('DEVELOPER_NAME') )
			define( 'DEVELOPER_NAME', 'Creare Web Solutions' );
		
		if ( !defined( 'DEVELOPER_URL' ) )
		define( 'DEVELOPER_URL', 'https://www.crearewebsolutions.com' );

		
		$this->email = new stdClass();
		//$this->social = new stdClass();
		$this->options = new stdClass();
		$this->options->posts = new stdClass();
		$this->options->excerpts = new stdClass();
		$this->options->main_content = new stdClass();
		$this->options->breadcrumbs = new stdClass();
		$this->navbar = new stdClass();
		
		$this->address = new stdClass();
		$this->phone = new stdClass();
		
		
		$this->phone->main = '123.456.7890';
		$this->name = get_bloginfo( 'title' );
		$this->description = get_bloginfo( 'description' );
		$this->url = get_bloginfo( 'url' );
		$this->copyright_start = '1983';
		$this->copyright_owner = $this->name;
		$this->logo = get_theme_mod( 'custom_logo' );
		$this->industry_logos = array();
		$this->email->main = '';
		$this->social = array();
		
		//Now really get the business info from the DB
		$this->get_business_info();
		
		//init the options for the site
		add_action( 'init', [ $this, 'get_site_options' ] );
		//make sure we init the options for customizer
		add_action( 'customize_preview_init', [ $this, 'get_site_options' ] );
		
		add_action( 'customize_register', [ $this, 'add_options_to_customizer'] );
		
		add_action( 'customize_preview_init', [ $this, 'customizer_preview_scripts' ] );

		
		
		
		//ACF Stuff
		
		//Pre-fill Business Info Name if not already loaded
		add_filter( 'acf/load_value/key=field_59f08a93e4c97', array( $this, 'pre_load_business_name'), 10, 3 );
		
		//Pre-fill Copyright Holder as Business Name if not already loaded
		add_filter( 'acf/load_value/key=field_59f0ac650a2fe', array( $this, 'pre_load_business_name'), 10, 3 );
		
		//Pre-fill year Copyright Starts
		add_filter( 'acf/load_value/key=field_59f0ac9b0a2ff', array( $this, 'pre_load_copyright_year'), 10, 3 );
		
		
		
		//Shortcode
		add_shortcode( 'site_info', array( $this, 'site_info_sc' ));
		
		
		
		
		//Structured Data
		add_action( 'wp_footer', array( $this, 'structured_data'), 30 );
		
	}
	
	
	
	
	
	function get_site_options() {
		
		//Default Options Model
		$this->defaults = [
			'options' => [
				[ 'section' => 'posts',
				  'label' => 'For Full Post Pages',
				  'items' => [
						[	'name' => 'show_post_date',
							'label' => 'Show Post Date',
							'value' => true,
							'type' => 'checkbox', 
						],
						[	'name' => 'show_post_author',
							'label' => 'Show Post Author',
							'value' => true,
							'type' => 'checkbox',
						],						
					],
				],
				[ 'section' => 'excerpts',
				  'label' => 'For Excerpt of Post Page',
				  'items' => [
						[	'name' => 'show_post_date',
							'label' => 'Show Post Date',
							'value' => true,
							'type' => 'checkbox',
						],						
						[	'name' => 'show_post_author',
							'label' => 'Show Post Author',
							'value' => true,
							'type' => 'checkbox',
						],						
						
					],
				],
				[ 'section' => 'layout',
				  'label' => 'Layout Setting',
				  'items' => [
						[	'name' => 'main_content_container',
							'label' => 'Main Content Container',
							'value' => true,
							'type' => 'checkbox',
						],						
						[	'name' => 'article_class',
							'label' => 'Article Class',
							'value' => 'col',
							'type' => 'text',
						],						
						[	'name' => 'article_class_excerpt',
							'label' => 'Excerpt Article Class',
							'value' => 'col-12 col-md-6',
							'type' => 'text',
						],						
					],
				],
				[ 'section' => 'homepage_slider',
				  'label' => 'Homepage Slider',
				  'items' => [
					  [
						  'name' => 'enable',
						  'label' => 'Enable Homepage Slider',
						  'value' => false,
						  'type' => 'checkbox',
					  ],
					  [
						  'name' => 'edge_to_edge',
						  'label' => 'Slider should take up full width of browser window',
						  'value' => false,
						  'type' => 'checkbox',
					  ],
					  [
						  'name' => 'caption',
						  'label' => 'Show caption',
						  'value' => false,
						  'type' => 'checkbox',
					  ],
				  ]
						
				],
			],
			
		];
		
		
		
		

		
		//Set the theme mod defaults
		
		$defaults = array (
			'homepage_slider' => array (
				'enable' => true,
				'edge_to_edge' => false,
				'caption' => true,
			  ),
			'main_content_container' => true,
			'article_class_excerpt' => 'col-12 col-md-6',
			'show_post_date' => true,
			'show_post_author' => true,
			'layout' => array (
				'main_content_container' => true,
				'article_class' => 'col-12',
				'article_class_excerpt' => 'col-12 col-md-6',
			  ),
			'posts' => array (
				'show_post_date' => true,
				'show_post_author' => true,
			  ),
			'excerpts' => array (
				'show_post_date' => true,
				'show_post_author' => true,
			  ),
			'carousel' => array (
				'transition' => 'slide',
				'speed' => '3000',
			  ),
			'navbar' => array(
				'brand_type' => 'image',
				'container' => true,
				'expand' => 'md',
				'bg_color' => 'light',
				'navbar_color' => 'light',
			),
			'breadcrumbs' => array(
				'enable' => WPSEO_Options::get( 'breadcrumbs-enable' ),
				'action' => 'bric_after_header',
				'priority' => '10',
				'hide_on_home' => true,
				'in_container' => false,
				'classes' => array(
					'container-fluid',
				),
			)
		);

		
		
		
		//get the theme mods
		$user_settings = get_theme_mod( 'bric' );
		
		$theme_settings = wp_parse_args( $user_settings, $defaults );
		
		//Parse the args 2 levels deep
		foreach ( $defaults as $k => $default ) {
			
			if ( is_array( $default ) ) {
				
				if ( isset( $user_settings[$k] ) ) {

					$theme_settings[$k] = wp_parse_args( $user_settings[$k], $default );				
				}
				

				
			}
			
		}
		
		
		
		
		//var_dump( $user_settings );
		//var_dump( $theme_settings );
		//exit();
		
		$this->options->posts->show_post_date = $theme_settings['posts']['show_post_date'];
		$this->options->posts->show_post_author = $theme_settings['posts']['show_post_author'];
		
		$this->options->excerpts->show_post_date = $theme_settings['excerpts']['show_post_date'];
		$this->options->excerpts->show_post_author = $theme_settings['excerpts']['show_post_author'];
		
		
		$this->options->main_content->container = $theme_settings['layout']['main_content_container'];
		$this->options->article_class = $theme_settings['layout']['article_class'];
		$this->options->article_class_excerpt = $theme_settings['layout']['article_class_excerpt'];
		
		
		$this->breadcrumbs = $theme_settings['breadcrumbs'];
		
		/**
		 *		Set the wrapper class based on customizer selection
		 *
		 */
		if ( $theme_settings['breadcrumbs']['in_container'] ) {
			$this->breadcrumbs['classes'] = ['container'];
		}
		else {
			$this->breadcrumbs['classes'] = ['container-fluid'];
		}
				
		
		$this->carousel = array(
			'enable' => $theme_settings['homepage_slider']['enable'],
			'edge_to_edge' => $theme_settings['homepage_slider']['edge_to_edge'],  //force out of container for edge to edge
			'show_caption' => $theme_settings['homepage_slider']['caption'],
			'transition' => $theme_settings['carousel']['transition'],
			'speed' => $theme_settings['carousel']['speed'],
		);
		
		
		
		//Navbar Options
		
		$this->navbar->brand_type = $theme_settings['navbar']['brand_type'];
		$this->navbar->container = $theme_settings['navbar']['container'];
		$this->navbar->expand = $theme_settings['navbar']['expand'];
		$this->navbar->bg_color = $theme_settings['navbar']['bg_color'];
		$this->navbar->navbar_color = $theme_settings['navbar']['navbar_color'];
		
		
		
		/*
		echo '<pre>';
		var_dump( $options );
		var_dump( $this->defaults );
		var_dump( $this->options );
		echo '</pre>';
		*/
		
	}
	
	
	/**
	 *		Add SiteInfo Options to Customizer
	 *
	 *		@date 3/9/19
	 *
	 *
	 */
	
	
	public function add_options_to_customizer( $wp_customize ) {
		
		
		
		
		/**
		 *		Add Global Settings to customizer
		 *
		 *		@edit 3/9/19
		 *
		 *
		 */
		
		//Add Section for Global Options
		$wp_customize->add_section( 'bric_options', [
			'priority' => 90,
			'title' => __('Theme Options'),
			'description' => __('Edit these options to control how your website looks.')
		] );
		
		
		
		//Loop through defaults, [registering panels,] settings/controls
		foreach ( $this->defaults['options'] as $option ) {
		
			
			foreach ( $option['items'] as $item ) {
				
				$wp_customize->add_setting( 'bric['.$option['section'].']['.$item['name'].']', array(
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'theme_supports' => '',
					'default' => $item['value'],
					'transport' => 'refresh',
					'sanitize_callback' => '',
					'sanitize_js_callback' => '',
				));


				$wp_customize->add_control( 'bric['.$option['section'].']['.$item['name'].']', [
					'type' => $item['type'],
					'setting' => $item['value'],
					'priority' => 10,
					'section' => 'bric_options',
					'label' => __($item['label']),
					'description' => $option['label'],
					'input_attrs' => [
						'class' => sanitize_title($item['name'])
					],
					'active_callback' => '',
				]);

				
			}
			
			
			
		}
		
		
		/**
		 *		Carousel Settings
		 *
		 *
		 */
		
		//Controls
		
		
		
		
		//Transition
		$wp_customize->add_setting( 'bric[carousel][transition]', array(
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ['BricCustomizer','carousel_transition_cb'],
			'sanitize_js_callback' => '',
		));


		$wp_customize->add_control( 'bric[carousel][transition]', [
			'type' => 'select',
			'setting' => 'slide',
			'priority' => 10,
			'section' => 'static_front_page',
			'label' => __('Select Transition'),
			'description' => '',
			'choices' => [
				'slide' => __('Slide'),
				'fade' => __('Fade'),
			],
			'active_callback' => '',
		]);
		
		
		//Speed
		$wp_customize->add_setting( 'bric[carousel][speed]', array(
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'default' => '3000',
			'transport' => 'refresh',
			'sanitize_callback' => [ 'BricCustomizer', 'carousel_speed_cb' ],
			'sanitize_js_callback' => '',
		));


		$wp_customize->add_control( 'bric[carousel][speed]', [
			'type' => 'text',
			'setting' => '3000',
			'priority' => 10,
			'section' => 'static_front_page',
			'label' => __('Transition Speed'),
			'description' => '',
			'attr' => [
				'placeholder' => __('Transition speed in milliseconds'),
				
			],
			'active_callback' => '',
		]);
		
		



		
		
		//
		//		NAVBAR
		//
		
		//Brand type
		$wp_customize->add_setting( 'bric[navbar][brand_type]', array(
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'default' => 'image',
			'transport' => 'refresh',
			'sanitize_callback' => ['BricCustomizer','navbar_brand_type_cb'],
			'sanitize_js_callback' => '',
		));


		$wp_customize->add_control( 'bric[navbar][brand_type]', [
			'type' => 'select',
			'setting' => 'image',
			'priority' => 10,
			'section' => 'title_tagline',
			'label' => __('Select how to display your brand'),
			'description' => '',
			'choices' => [
				'image' => __('Image'),
				'text' => __('Text'),
				'textimage' => __('Image & Text'),
			],
			'active_callback' => '',
		]);
		

		
		
		
		//Navbar Container
		$wp_customize->add_setting( 'bric[navbar][container]', array(
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'default' => false,
			'transport' => 'refresh',
			'sanitize_callback' => [ 'BricCustomizer', 'checkbox_cb' ],
			'sanitize_js_callback' => '',
		));


		$wp_customize->add_control( 'bric[navbar][container]', [
			'type' => 'checkbox',
			'setting' => false,
			'priority' => 10,
			'section' => 'bric_options',
			'label' => __('Put Navbar in a container'),
			'description' => '',
			'active_callback' => '',
		]);
		

		//Navbar bg color
		$wp_customize->add_setting( 'bric[navbar][bg_color]', array(
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'default' => 'light',
			'transport' => 'refresh',
			'sanitize_callback' => '', //['BricCustomizer','navbar_brand_type_cb'],
			'sanitize_js_callback' => '',
		));


		$wp_customize->add_control( 'bric[navbar][bg_color]', [
			'type' => 'select',
			'setting' => 'light',
			'priority' => 10,
			'section' => 'title_tagline',
			'label' => __('Navbar Background Color'),
			'description' => '',
			'choices' => [
				'light' => __('Light Gray'),
				'dark' => __('Dark Gray'),
				'white' => __('White'),
			],
			'active_callback' => '',
		]);
		
		
		
		
		
		
		
		//Breadcrumbs
		$wp_customize->add_setting( 'bric[breadcrumbs][in_container]', [
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'default' => false,
			'transport' => 'refresh',
			'sanitize_callback' => ['BricCustomizer','checkbox_cb'],
			'sanitize_js_callback' => '',
		]);
		
		$wp_customize->add_control( 'bric[breadcrumbs][in_container]', [
			'type' => 'checkbox',
			'setting' => false,
			'priority' => 10,
			'section' => 'wpseo_breadcrumbs_customizer_section',
			'label' => __('Put Breadcrumbs in a container'),
			'description' => '',
			'active_callback' => '',
		]);
		
		//$wp_customize->add_setting( 'bric[breadcrumbs]');
		
		
		
		
		/*
		//Post Options
		foreach ( $this->options->posts as $k => $option ) {
			
			$wp_customize->add_setting( 'bric['.$k.']', array(
				'type' => 'theme_mod',
				'capability' => 'edit_theme_options',
				'theme_supports' => '',
				'default' => $option,
				'transport' => 'refresh',
				'sanitize_callback' => '',
				'sanitize_js_callback' => '',
			));
			
			
			$wp_customize->add_control( 'bric['.$k.']', [
				'type' => 'checkbox',
				'priority' => 10,
				'section' => 'bric_options',
				'label' => __($k),
				'description' => '',
				'input_attrs' => [
					'class' => 'checkbox'
				],
				'active_callback' => '',
			]);
			
		}
		
		*/
		
	}
	
	
	
	
	/**
	 * 		Customizer JS 
	 *
	 *
	 *
	 */
	 
	
	public function customizer_preview_scripts() {
		
		wp_enqueue_script( 'customizer-bric-carousel', get_template_directory_uri().'/assets/src/js/customizer-carousel.js', array('customize-preview', 'jquery'), null, true);

	}
	 
	
	
	
	
	
	
	
	
	/**
	 *		Set Business Name to site name 
	 *		as default action
	 *
	 */	
	
	function pre_load_business_name( $value, $post_id, $field ) {
		
		if ( empty( $value ) ) {
			
			$value = get_bloginfo( 'title' );
			
		}
		
		return $value;
		
	}
	
	/**
	 *		Set Copyright Year to this year if not already filled
	 *		as default action
	 *
	 */	
	
	function pre_load_copyright_year( $value, $post_id, $field ) {
		
		if ( empty( $value ) ) {
			
			$value = date("Y");
			
		}
		
		return $value;
		
	}
	
	
	
	
	
	
	
	/**
	 *		Get Business Name
	 *
	 */
	
	
	function get_business_info() {
		


		//Copyright
		if ( $copy_year = get_field( 'copyright_year', 'options' ) )
			$this->copyright_start = $copy_year;
		
		if ( $copy_holder = get_field( 'copyright_holder', 'options' ) )
		$this->copyright_owner = $copy_holder;
		
		
		//Address
		$address = get_field( 'address', 'options' );
		$city_state = get_field( 'city_state', 'options' );
		
		$this->address->line_1 = $address['address_1'];
		$this->address->line_2 = $address['address_2'];
		$this->address->city = $city_state['city'];
		$this->address->state = $city_state['state'];
		$this->address->zip = $city_state['zip'];
		
				
		
		
		//Contact
		$contact = get_field( 'contact', 'options');
		
		$this->phone->main = $contact['phone'];
		$this->phone->fax = $contact['fax'];
		$this->email->main = $contact['email'];
		
		
		
		//Hours
		$this->operating_hours = get_field( 'hours_of_operation', 'options' );
		
		
		//Social Media
		$this->social = get_field( 'social_media_accounts', 'options' );
		
		//Type
		$this->type = get_field( 'business_type', 'options' );
		
		
		//Location (Geo)
		$this->location = get_field( 'location', 'options' );
		
		
	}
	
	
	
	
	function print_all_business_info() {

		ob_start();
		?>
<div>
	<?php echo $this->format_address(); ?><br>
	<?php echo $this->format_phone(); ?><br>
	<?php echo $this->format_email(); ?><br>
</div>
		<?php
		
		
	}
	
	
	
	
	public static function format_address_2() {
		
		return 'Hi!';
		
	}
	
	
	
	/**
	 *		Format Email 
	 *
	 *
	 */
	
	public function format_email( $label = '' ) {
		
		if ( !empty( $this->email->main )) {
			
			return $this->get_href( $this->email->main, $label );		
			
		}
		
		return '';
		
	}
	
	
	/**
	 *		Format Phone 
	 *
	 *
	 */
	
	public function format_phone( $label = '' ) {
		
		
		if ( !empty( $this->phone->main ) ) {
		
		return sprintf( '<span class="tel-wrapper">%2$s<a href="tel:%1$s">%1$s</a></span>', $this->phone->main, 
					   ( !empty( $label) ? $label.'&nbsp;' : '' )
					  );	
			
		}
		
		return '';
		
	}
	
	
	/**
	 *		Format Fax 
	 *
	 *
	 */
	
	public function format_fax( $label = '' ) {
		
		
		if ( !empty( $this->phone->fax ) ) {
		
		return sprintf( '<span class="fax-wrapper">%2$s<a href="fax:%1$s">%1$s</a></span>', $this->phone->fax, 
					   ( !empty( $label) ? $label.'&nbsp;' : '' )
					  );	
			
		}
		
		return '';
		
	}
	
	
	
	
	
	
	
	/**
	 *		Format Href
	 *
	 */
	
	public function get_href( $item, $label ) {
		
		if ( $this->is_email( $item ) ) {
			
			$class = 'email';
			$output = sprintf( '%2$s<a href="mailto:%1$s">%1$s</a>', $item, 
						   ( !empty( $label) ? $label.'&nbsp;' : '' )
						  );
			
		}
		
		elseif ( $this->is_url( $item ) ) {
			
			$class = 'email';
			$output = sprintf( '%2$s<a href="mailto:%1$s" target="_blank">%1$s</a>', $item, 
						   ( !empty( $label) ? $label.'&nbsp;' : '' )
						  );
			
		}
		
		else {
			
			$class = 'url';
			$output = sprintf( '%2$s<a href="%1$s">%1$s</a>', $item, 
						   ( !empty( $label) ? $label.'&nbsp;' : '' )
						  );
			
		}
		
		
		return sprintf( '<span class="%s-wrapper">%s</span>', $class, $output );
		
	}
	
	
	
	public function is_email( $thing ) {
		
		if ( filter_var( $thing, FILTER_VALIDATE_EMAIL ) ) {
			
			return true;
		}		
		else {
			
			return false;
			
		}
		
	}
	
	
	public function is_url( $thing ) {
		
		if ( filter_var( $thing, FILTER_VALIDATE_URL ) ) {
			
			return true;
		}		
		else {
			
			return false;
			
		}
		
	}
	
	
	
	
	
	public function site_info_sc( $attr, $content ) {
		
		$attr = shortcode_atts( array(
			'include' => 'address',
			'phone_label' => '',
			'email_label' => '',
			'fax_label' => '',
			'address_label' => '',
		), $attr );
		
		
		$include = explode( ',', $attr['include'] );
		
		$o = '';
		
		$num_of_items = count( $include );
		$c = 1;
		
		if ( $num_of_items > 0 ) {
			
			foreach ( $include as $k => $v ) {
				
				$br = '';//'<br>';
				
				if ( $num_of_items == $c ) {
					$br = '';
				}
							
				$label = $attr[$v.'_label'];
				
				//var_dump( $v.'_label' );
				//var_dump( $this->format_phone( $label ) );
				//global $SiteInfo;
				
				
				$o .= call_user_func( 'SiteInfo::format_'.$v, $label ).$br;
			
				
				$c++;
			}
			
			
			$o = sprintf( '<div name="%s" class="biz-info mb-3">%s</div>', $this->name, $o );
			
		}
		
		
	
		return $o;
		
	}
	
	
	
	/**
	 *		Format Address and Markup
	 *
	 */
	
	public function format_address() {
		
		ob_start();
		?>
<div class="address">
	
	
	
	<?php 
		if ( !empty ( $this->address->line_1 ) ) : ?>
	
	<span><?php echo $this->address->line_1; 
		if ( !empty( $this->address->line_2 ) ) {
			echo '<br>'.$this->address->line_2.'<br>';
		}
		else {
			echo '<br>';
		}
		?></span>
		
	<?php endif; 
	
	if ( !empty( $this->address->city ) ) :
	?>	
	<span><?php echo $this->address->city; ?></span>,
	<?php endif; 
		
	if ( !empty( $this->address->state ) ) : 
	?>
	<span><?php echo $this->address->state; ?></span>
	<?php endif;
	
	if ( !empty( $this->address->zip ) ) : 
	?>
	<span><?php echo $this->address->zip; ?></span>
	<?php endif; ?>
	
</div>		
		
		<?php
		
		return ob_get_clean();
		
		
	}
	
		
	
	function structured_data() {
		
		//Create structured data object in php
		
		//$sdata = new stdClass();
		
		$sdata = array(
			'@context' => 'http://schema.org',
			'@type' => $this->type,
			'name' => $this->name,
			'description' => $this->description,
			'openingHours' => $this->get_opening_hours(),
			'telephone' => $this->phone->main,
			'url' => $this->url,
			'address' => array(
				'@type' => 'PostalAddress',
				'addressLocality' => $this->address->city,
				'addressRegion' => $this->address->state,
				'postalCode' => $this->address->zip,
				'streetAddress' => $this->address->line_1.$this->address->line_2
			),
			'geo' => array(
				'@type' => 'GeoCoordinates',
				'latitude' => $this->location['lat'],
				'longitude' => $this->location['lng'],
			),
			'logo' => $this->get_logo_data()->url,
			'image' => $this->get_logo_data()->url,
			 
		);
		
		
		$sdata = apply_filters( 'bric_structured_data', $sdata, $this );		
		
		
		?>
<script type="application/ld+json">
	<?php echo json_encode( $sdata ); ?>
</script>
		<?php
		
	}
	
	
	
	
	
	
	
	function get_opening_hours() {
		
		$hours = $this->operating_hours;
		
		if ( !empty ($hours) ) {
			
			$o = array();
			
			foreach ( $hours as $hour ) {
				
				$day = explode( '-', $hour['day'] );
				
				$day = $this->remove_whitespace( $day );
				
				$time = $hour['hours'];
				
				//Create abbreviations from first 2 letters
				$day_start = $day[0][0] . $day[0][1];

				
				if ( !empty( $time ) ) {
				
					if ( ( strtolower( $time ) !== 'closed' ) ) {

						$time = explode( '-', $time );

						$time = $this->remove_whitespace( $time );

						$time = $this->get_military_time( $time );


						if ( ( count( $day ) > 1 )) {
							//Multiple days in one row

							//Create the day end
							$day_end = $day[1][0] . $day[1][1];


							if ( $this->is_valid_day( $day_start ) && $this->is_valid_day( $day_end ) ) {

								$o[] = sprintf( '%s-%s %s-%s', $day_start, $day_end, $time[0], $time[1] );

							}


						}
						else {
							//One day in the row

							if ( $this->is_valid_day( $day_start ) ) {

								$o[] = sprintf( '%s %s-%s', $day_start, $time[0], $time[1] );

							}


						}

					}

				}
				
				
			}
			
			
			return $o;
			
		}
		
	}
	
	
	
	function is_valid_day( $day ) {
		
		$valid_days = array(
			'Su',
			'Mo',
			'Tu',
			'We',
			'Th',
			'Fr',
			'Sa',
		);
		
		
		if ( in_array( $day, $valid_days ) ) {
			return true;
		}
		else {
			return false;
		}
		
	}
	
	
	
	
	
	
	function remove_whitespace( $thing ) {
		
		//Remove any whitespace
		foreach ( $thing as $k => $v ) {
			$thing[$k] = trim( $v );
		}

		
		return $thing;
		
	}
	
	
	
	
	function get_military_time( $time ) {
		
		
		if( !empty( $time ) ) {
			foreach ( $time as $k => $v ) {

				$t = new DateTime( $v );

				$time[$k] = $t->format( 'H:i' );



			}
		}
		
		return $time;
		
		
	}
	
	
	
	
	
	
	
	
	function get_logo_data( $size = 'medium' ) {
				
		$img = wp_get_attachment_image_src( $this->logo, $size );
		
		$obj = new StdClass();
		
		$obj->type = '@ImageObject';
		$obj->url = $img[0];
		$obj->width = $img[1];
		$obj->height = $img[2];
		
		return $obj;
		
	}
	
	
	
	
	
	
	
	
}



global $SiteInfo;

$SiteInfo = new SiteInfo();











class BricCustomizer {
	
	
	
	static function carousel_transition_cb( $value ) {
		
		if ( $value == 'fade' ) {
			
			return 'carousel-fade';
		}
		elseif ( $value == 'slide' ) {
			
			return 'slide';
		}
		
	}
	
	
	
	static function carousel_speed_cb( $value ) {
		
		if ( is_numeric( $value ) ) {
			
			return $value;
			
		}
		else {
			
			return null;
			
		}
		
		
	}
	
	
	
	
	
	
	
	
	static function navbar_brand_type_cb( $value ) {
		
		$value = sanitize_title( $value );
		
		if ( in_array( $value, [ 'text', 'image', 'textimage' ] ) ) {
			
			return $value;
			
		}
		
		else {
			
			return null;
		}
		
		
		
	}
	
	
	
	static function breadcrumb_classes_cb( $value ) {
		
		
		if ( $value === true ) {
			
			return [ 'container' ];
			
		}
		else {
			return [ 'container-fluid' ];
		}
		
		
	}
	
	
	
	
	
	
	static function checkbox_cb( $value ) {
		
		if ( $value === true ) {
			return true;
		}
		else {
			return false;
		}
		
	}
	
	
	
	
}
