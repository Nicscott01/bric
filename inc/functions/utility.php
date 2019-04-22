<?php
/**
 *		Utility Functions
 *
 */


function get_copyright_text( $builtYear = '' ) {

	
	if ( empty( $builtYear ) ) {

		$builtYear = date( "Y" );
	}
	
	
	//Cast the year as an integer
	$builtYear = (int) $builtYear;


	$currentYear = date( "Y" );
	
	

	if ( $currentYear == $builtYear ) {
		return "Copyright &copy; " . $currentYear;
	}

	if ( $currentYear > $builtYear ) {
		return "Copyright &copy; " . $builtYear . "&ndash;" . $currentYear;
	}

}



function the_copyright_text( $built_year = '' ){
	
	echo get_copyright_text( $built_year );
	
}




function bric_fix_shortcodes($content) {

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
	
	if ( is_archive() ) {

		$page = get_landing_page();

		if ( $page ) {		
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


function get_landing_page() {

	$page = false;
	
	if ( is_home() ) {
		
		$page_id = get_option( 'page_for_posts' );
		
		$page = get_page( $page_id );
		
	}
	elseif ( is_tax() ) {
		
		global $wp_taxonomies;
		$query = get_queried_object();
		
		$tax = $wp_taxonomies[$query->taxonomy];
		
		if ( $tax ) {
			
			$post_type = $tax->object_type[0];
			
			$pt_obj = get_post_type_object( $post_type );
			
			if ( $pt_obj->rewrite ) {
				
				$page = get_page_by_path( $pt_obj->rewrite['slug'] );
			}
			
		}
		
	}
	elseif ( is_archive() ) {
	
		//global $wp_query;
		//See if we have a page with a slug that's an archive
		
		$query = get_queried_object();
		
		//queried object rewrite slug
	//	if ( $wp_query->queried_object->rewrite ) {
		if ( $query->rewrite ) {

			$slug = $query->rewrite['slug'];

			$page = get_page_by_path( $slug );
		}
		
	}

	return $page;
	
}