<?php
/**
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
	public $breadcrumbs = '';
	public $body_classes = array();
	public $carousel = array();
	public $operating_hours = array();
	
	function __construct() {
		
		define( 'DEVELOPER_NAME', 'Creare Web Solutions' );
		define( 'DEVELOPER_URL', 'https://www.crearewebsolutions.com' );

		
		$this->email = new stdClass();
		//$this->social = new stdClass();
		$this->options = new stdClass();
		$this->options->posts = new stdClass();
		$this->options->excerpts = new stdClass();
		$this->options->main_content = new stdClass();
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
		
		
		$this->get_site_options();
		
		
		$this->breadcrumbs = array(
			'enable' => false,
			'action' => 'bric_after_header',
			'priority' => '10',
			'hide_on_home' => true,
			'classes' => array(
				'container',
			),
		);
		
		
		$this->carousel = array(
			'edge_to_edge' => true,  //force out of container for edge to edge
			'show_caption' => false,
		);
		
		
		
		
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
		
		$this->options->posts->show_post_date = true;
		$this->options->posts->show_post_author = true;
		
		$this->options->excerpts->show_post_date = true;
		$this->options->excerpts->show_post_author = true;
		
		
		$this->options->main_content->container = true;
		$this->options->article_class = 'col';
		$this->options->article_class_excerpt = 'col-12 col-md-6';
		
		
		
		
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
		<?
		
		
	}
	
	
	
	
	
	
	/**
	 *		Format Address and Markup
	 *
	 */
	
	function format_address() {
		
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
	
	
	/**
	 *		Format Email 
	 *
	 *
	 */
	
	function format_email( $label = '' ) {
		
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
	
	function format_phone( $label = '' ) {
		
		
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
	
	function format_fax( $label = '' ) {
		
		
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
	
	function get_href( $item, $label ) {
		
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
	
	
	
	function is_email( $thing ) {
		
		if ( filter_var( $thing, FILTER_VALIDATE_EMAIL ) ) {
			
			return true;
		}		
		else {
			
			return false;
			
		}
		
	}
	
	
	function is_url( $thing ) {
		
		if ( filter_var( $thing, FILTER_VALIDATE_URL ) ) {
			
			return true;
		}		
		else {
			
			return false;
			
		}
		
	}
	
	
	
	
	
	function site_info_sc( $attr, $content ) {
		
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
				
				$o .= call_user_func( array( $this, 'format_'.$v ), $label ).$br;
			
				
				$c++;
			}
			
			
			$o = sprintf( '<div name="%s" class="biz-info mb-3">%s</div>', $this->name, $o );
			
		}
		
		
	
		return $o;
		
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



