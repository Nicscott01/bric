<?php

/**
 *		Class to handle Google Maps
 *
 *
 */


class GoogleMaps {
		
	
	function __construct() {
		
		//add_action( 'init', array( $this, 'add_shorcodes') );
		add_shortcode( 'bric_google_map', array( $this, 'google_maps_sc' ) );
		
        add_filter( 'script_loader_tag', [$this, 'load_async' ], 10, 3 );	
        
        
		if ( !defined( 'BRIC_GOOGLE_MAPS_API_KEY') ) {
			define( 'BRIC_GOOGLE_MAPS_API_KEY', 'AIzaSyDc778GGrKeGmDI5qIl1H-nFQUt4We6ZkA' );// //AIzaSyAHiR6Mb0pnjvQs0FoUn6gsk3_TZxRgkOU
		}
		
	}
	
	
	
	
	
	
	function google_maps_sc( $attr, $content ) {
		
		$this->enqueue_scripts();
		

		$attr = shortcode_atts( array(
			'coordinates' => '',
		), $attr );
		
		if ( !empty( $attr['coordinates']) ) {
			
			
			$coordinates = explode( ',', $attr['coordinates'] );
			
			ob_start();
			?>
<div class="acf-map">	
	<div class="marker" data-lat="<?php echo $coordinates[0]; ?>" data-lng="<?php echo $coordinates[1]; ?>"></div>
</div>			
			<?php
			
			$o = ob_get_clean();
			
			if ( is_admin() ) {
				
				$o .= '
<style>.acf-map {
	width: 100%;
	height: 400px;
	border: #ccc solid 1px;
	margin: 20px 0;
}
</style>';
				
				$o .= sprintf( '<script type="text/javascript" src="%s"></script>', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js' );
				$o .= sprintf( '<script type="text/javascript" src="%s"></script>', 'https://maps.googleapis.com/maps/api/js?key='.BRIC_GOOGLE_MAPS_API_KEY );
				$o .= sprintf( '<script type="text/javascript" src="%s"></script>', get_template_directory_uri().'/assets/js/google-maps-render.min.js' );
					
			}
			
			return $o;
			
			
		}
		
		
		
		
	}
	
	
	
	
	
	function enqueue_scripts() {
		

		wp_enqueue_script( 'jQuery-inView' );
		wp_register_script( 'google-maps-render', get_template_directory_uri().'/assets/js/google-maps-render.min.js', array('jquery', 'google-maps-api'), null, true );
		wp_enqueue_script( 'google-maps-render' );
				
        //This needs to go after the render script since we're deferring it (see load_async() )
		wp_register_script( 'google-maps-api', 'https://maps.googleapis.com/maps/api/js?key='.BRIC_GOOGLE_MAPS_API_KEY, array('jquery'), null, true );
		wp_enqueue_script( 'google-maps-api' );
        
		
	}
	
	
	
	public function load_async( $tag, $handle, $src ) {
        
        if ( $handle == 'google-maps-api' ) {
            
            $tag = str_replace( '><', ' async defer><', $tag );
            
        }
        
        
        return $tag;
    }
	
	
	
	
	
}

new GoogleMaps();