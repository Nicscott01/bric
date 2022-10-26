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