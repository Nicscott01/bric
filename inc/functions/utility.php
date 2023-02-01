<?php

/**
 *		Utility Functions
 *
 */


function get_copyright_text($builtYear = '')
{


	if (empty($builtYear)) {

		$builtYear = date("Y");
	}


	//Cast the year as an integer
	$builtYear = (int) $builtYear;


	$currentYear = date("Y");



	if ($currentYear == $builtYear) {
		return "Copyright &copy; " . $currentYear;
	}

	if ($currentYear > $builtYear) {
		return "Copyright &copy; " . $builtYear . "&ndash;" . $currentYear;
	}
}



function the_copyright_text($built_year = '')
{

	echo get_copyright_text($built_year);
}




function bric_fix_shortcodes($content)
{

	return strtr($content, [
		'<p>[' => '[',
		']</p>' => ']',
		']<br />' => ']'
	]);
}






/**
 * Copy a file, or recursively copy a folder and its contents
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.1
 * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
 * @param       string   $source    Source path
 * @param       string   $dest      Destination path
 * @param       int      $permissions New folder creation permissions
 * @return      bool     Returns true on success, false on failure
 */
function xcopy($source, $dest, $permissions = 0755)
{
	// Check for symlinks
	if (is_link($source)) {
		return symlink(readlink($source), $dest);
	}

	// Simple copy for a file
	if (is_file($source)) {
		return copy($source, $dest);
	}

	// Make destination directory
	if (!is_dir($dest)) {
		mkdir($dest, $permissions);
	}

	// Loop through the folder
	$dir = dir($source);
	while (false !== $entry = $dir->read()) {
		// Skip pointers
		if ($entry == '.' || $entry == '..') {
			continue;
		}

		// Deep copy directories
		xcopy("$source/$entry", "$dest/$entry", $permissions);
	}

	// Clean up
	$dir->close();
	return true;
}





/**
 *		Has Landing Page
 *		checks for page w/ slug
 *		same as the current archive slug
 *
 */

function has_landing_page() {

	if ( is_archive() || is_tax() || is_category() || is_tag()) {

		if ( is_date() ) {
			return false;
		}

		$page = get_landing_page();

		if ($page) {
			return true;
		}
	}


	return false;
}



/**
 *		Returns a page with the same slug as 
 *		the wp query object's rewrite slug
 *
 */


function get_landing_page()
{

	//var_dump( $_SERVER );

	$page = false;



	if (is_home() || is_single() ) {

		$page_id = get_option('page_for_posts');

		$page = get_page($page_id);

	} elseif (is_tax()) {

		global $wp_taxonomies;
		$query = get_queried_object();

		$tax = $wp_taxonomies[$query->taxonomy];


		if ($tax) {

			$post_type = $tax->object_type[0];

			$pt_obj = get_post_type_object($post_type);



			if ($pt_obj->rewrite) {

				$page = get_page_by_path($pt_obj->rewrite['slug']);
			}
		}

	} elseif ( is_category() || is_tag() && isset($_SERVER['REQUEST_URI']) ) {

		$path = trim($_SERVER['REQUEST_URI'], '/');

		$page = get_page_by_path( $path );
		


	} elseif (is_archive()) {

		//global $wp_query;
		//See if we have a page with a slug that's an archive

		$query = get_queried_object();

		//queried object rewrite slug
		//	if ( $wp_query->queried_object->rewrite ) {
		if ($query->rewrite) {

			$slug = $query->rewrite['slug'];

			$page = get_page_by_path($slug);
		}
	}


	return $page;
}






if ( !function_exists( 'maybe_json_decode' ) ) {

	function maybe_json_decode( $thing ) {

		//var_dump( strpos( $thing, '{' ) );
		//var_dump( strpos( $thing, '{' ) === 0  );

			
		if ( is_object( $thing ) ) {
		
			return $thing;
		
		} elseif ( strpos( trim( $thing ), '{' ) === 0 ) {

			$new_thing = json_decode( trim( $thing ) );

		} else {

			return $thing;
		}



		if ( is_object( $new_thing ) ) {
			
			return $new_thing;
		
		} else {

			return $thing;
		}

	}

}


if ( !function_exists( 'is_svg' ) ) {

	function is_svg( $file = null ) {
			
		if ( empty( $file ) ) {
			return false;
		}
		
		$mime = get_post_mime_type( $file );
		
		if ( $mime == 'image/svg+xml' ) {
			
			return true;
		}
		else {
			return false;
		}
		
		
	}

}





if ( ! function_exists( 'get_svg_source' ) ) {


	function get_svg_source( $id = null ) {
		
		if ( empty( $id )) {
			return false;
		}
		
		//Filter to return something other than the SVG of the id
		$file_override = apply_filters( 'bric_navbar_brand_svg_override', null, $id );
		
		if ( !empty( $file_override )) {
			return $file_override;
		}
		
		$file = get_attached_file( $id );
		
		if ( empty( $file ) ) {
			
			return false;

		}


		$svg = file_get_contents( $file );
        
        $svg = preg_replace( '/^\<\?xml.+\?\>/m', '', $svg );
		
		$re = '/viewBox=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?/m';
		preg_match( $re, $svg, $viewBox );
		/*
		$dimensions = explode( ' ', $viewBox[1] );
		$width = $dimensions[2] - $dimensions[0];
		$height = $dimensions[3] - $dimensions[1];
		
		$dimension_css = sprintf( '<svg style="width:%spx; height:%spx"', $width, $height );
		*/
		
		
		//$svg = str_replace( '<svg', $dimension_css, $svg );
		
		return $svg;
	}
	
}






/**
 * 	Helper for translating WP Block Speak 
 *  into Bootstrap
 * 
 * 
 * 
 */

 function get_bootstrap_flex_classes( $block, $flex_direction ) {


	$direction = !empty( $flex_direction ) ? $flex_direction : 'column';
	$align_x = isset( $block['align'] ) && !empty( $block['align'] ) ? $block['align'] : 'start';
	$align_y = isset( $block['align_content'] ) && !empty( $block['align_content'] ) ? $block['align_content'] : 'top';


	//var_dump( $direction );
	//var_dump( $align_x );
	//var_dump( $align_y );

	$decoder = [
		'left' => 'start',
		'right' => 'end',
		'center' => 'center'
	];


	$classes = [];

	if( $direction == 'column' ) {

		$classes[] = 'flex-column';

		if ( isset( $decoder[$align_x] ) ) {
			$classes[] = 'align-items-' . $decoder[$align_x];
		}

		if ( isset( $decoder[$align_y] ) ) {
			$classes[] = 'justify-content-' . $decoder[$align_y];
		}


	} elseif ( $direction == 'row' ) {

		$classes[] = 'flex-row';

		
		if ( isset( $decoder[$align_x] ) ) {
			$classes[] = 'justify-content-' . $decoder[$align_x];
		}

		if ( isset( $decoder[$align_y] ) ) {
			$classes[] = 'align-items-' . $decoder[$align_y];
		}
	
	
	} elseif ( $direction == 'grid-row' ) {

		if ( isset( $decoder[$align_x] ) ) {
			$classes[] = 'justify-content-' . $decoder[$align_x];
		}

		if ( isset( $decoder[$align_y] ) ) {
			$classes[] = 'align-items-' . $decoder[$align_y];
		}

	}

	return implode( ' ', $classes );

 }







function decode_block_dimensions( $dimensions ) {

	if ( !empty( $dimensions ) && is_array( $dimensions )) {

		$dim = [];

		foreach( $dimensions as $att => $val ) {
			
			$dim[$att] = str_replace( 'var:preset|spacing|', '', $val );
		}
	
	}

	return $dim;

}

 /**
  *		Returns usable dimensions from a block
  *
  */

 function get_block_dimensions( $block ) {


	$block_dimensions = [];

	$atts_to_look_for = [
		'padding',
		'margin',
		'blockGap'
	];



	if ( isset( $block['style']['spacing'] ) && !empty( $block['style']['spacing'] ) ) {
		
		

		foreach( $atts_to_look_for as $att ) {
			

			if ( isset( $block['style']['spacing'][$att] ) ) {


				if ( $att == 'blockGap' ) {

					
					$block_dimensions[$att] = str_replace( 'var:preset|spacing|', '', $block['style']['spacing']['blockGap'] );

				} else {

					$block_dimensions[$att] = decode_block_dimensions( $block['style']['spacing'][$att] );

				}
			
			} 

		}
		

	}

	return $block_dimensions;

 }





 function decode_block_dimension_classes( $prefix, $vals ) {


	$decoder_ring = [
		'left' => 's',
		'right' => 'e',
		'top' => 't',
		'bottom' => 'b'
	];


	if ( is_array( $vals ) && !empty( $vals ) ) {
						
		foreach( $vals as $attr => $val ) {

			$classes[] = sprintf( '%s%s-%s', $prefix, $decoder_ring[$attr], $val );

		}
	}


	return $classes;



}






 function get_block_dimension_classes( $dimensions ) {

	$classes = [];

	foreach( $dimensions as $attr => $vals ) {

		switch( $attr ) {

			case "padding" :

				$classes[] = decode_block_dimension_classes( 'p', $vals );

				break;


			case "margin" :


				$classes[] = decode_block_dimension_classes( 'm', $vals );

				break;

			case "blockGap" :

				$classes[] = 'gap-' . $vals;

				break;
		}

	}

	$class_list = [];


	//Flatten the array
	foreach( $classes as $section ) {

		if ( is_array( $section ) ) {

			foreach( $section as $val ) {
			
				$class_list[] = $val;

			}
		} else {

			$class_list[] = $section;

		}

	}


	return $class_list;


 }






 function decode_bs_text_align( $data ) {

	if ( isset( $data ) && !empty( $data ) ) {

		$decoder_ring = [
			'left' => 'text-start',
			'center' => 'text-center',
			'right' => 'text-end'
		];

		return $decoder_ring[$data];

	} else {

		return '';
	}


 }




 /**
  * 	Take the block from ACF
  * 	and output a string of classes
  *		for the block
  * 
  */

 function get_block_classes( $block, $flex_direction = '' ) {

	if ( empty( $block ) ) {
		
		return '';

	}


	//var_dump( $block );

	$classes = [];

	//Array of padding/margin classes
	$dim_classes = get_block_dimension_classes( get_block_dimensions( $block ) );

	$classes[] = implode( ' ', $dim_classes );

	//Get flex classes if we have a direction
	if ( !empty( $flex_direction ) ) {
		
		//Flex direction classes
		$classes[] = get_bootstrap_flex_classes( $block, $flex_direction );
		
	}


	//Text alignment, string
	if ( isset( $block['alignText'] ) && !empty( $block['alignText'] )) {
	
		$classes[] = decode_bs_text_align( $block['alignText'] );

	}

	if ( isset( $block['textColor'] ) && !empty(  $block['textColor'] ) ) {

		$classes[] = 'text-' . $block['textColor'];
	}

	if ( isset( $block['backgroundColor'] ) && !empty( $block['backgroundColor'] ) ) {

		$classes[] = 'bg-' . $block['backgroundColor'];
	
	}



	/**
	 * Exceptions
	 * 
	 */

	 //Paragraph margin bottom on the copyright
	if ( $block['name'] == 'acf/site-copyright' && empty( $block['styling']['spacing']['margin'] ) ) {

		$classes[] = 'mb-0';
	}


	return implode( ' ', $classes );


 }







 function column_classes( $col_val, $size = '' ) {

    $size = isset( $size ) && !empty( $size ) ? $size : '0';

    $prefix_map = [
        '0' => 'col',
        'sm' => 'col-sm',
        'md' => 'col-md',
        'lg' => 'col-lg',
        'xl' => 'col-xl', 
        'xxl' => 'col-xxl'
    ];


    if ( !empty( $col_val ) && $col_val > 0 ) {

        $class = $prefix_map[$size] . '-' . $col_val;
    
    } elseif ( $col_val == -1 ) {

        $class = $prefix_map[$size] . '-auto';
    
    } else {

        $class = ''; //$prefix_map[$size];
    }

    return $class;

 }
