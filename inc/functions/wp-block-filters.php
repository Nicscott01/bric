<?php
/**
 *  Filters to change behavior of OOTB block content
 * 
 * 
 */



/**
 *  Edit some markup of the blocks
 * 
 * 
 * 
 */





add_filter( 'render_block_context', 'has_parent_block', 10, 3 ); 

function has_parent_block( $context, $parsed_block, $parent_block ) {

    global $has_parent_block;

    //var_dump( $parsed_block );
    //var_dump( $parent_block );


    //Do this only for the bockas I want

    $catch_these = [
        'core/paragraph',
        'core/heading'
    ];

    if ( in_array( $parsed_block['blockName'], $catch_these)  ) {

        if ( !empty( $parent_block ) ) {

            $has_parent_block = true;

        }

    }

    return $context;

}





add_filter( 'render_block_core/heading', function( $content, $parsed_block, $wp_block ) {

    //var_dump( $parsed_block );
    //var_dump( $wp_block );



    global $has_parent_block;

    if ( $has_parent_block ) {

        $has_parent_block = false;

        return  $content;

    } else {

        $flex_classes = [];

        //Get the classes
        if ( isset( $parsed_block['attrs']['className'] ) ) {

            $classes = $parsed_block['attrs']['className'];

            $classes = explode( ' ', $classes );
            

            foreach( $classes as $class ) {

                if ( $class == 'right-side' ) {
                    $flex_classes[] = 'justify-content-end';
                }

            }
        
        }


        return sprintf( '<div class="container-xxl heading-wrapper d-flex %s">%s</div>', implode( ' ' , $flex_classes ), $content );
        
    }

},  10, 3 );








add_filter( 'render_block_core/paragraph', function( $content, $parsed_block, $wp_block ) {

    //var_dump( $parsed_block );
    //var_dump( $wp_block );

    //Get the classes
  /*  $classes = $parsed_block['attrs']['className'];

    $classes = explode( ' ', $classes );

    $flex_classes = [];

    foreach( $classes as $class ) {

        if ( $class == 'right-side' ) {
            $flex_classes[] = 'justify-content-end';
        }

    }
*/

    global $has_parent_block;

    if ( $has_parent_block || get_post_type() == 'post' ) {


        $has_parent_block = false;

        return '<div class="plain-paragraph mb-4">' . $content . '</div>';

    } else {

        return sprintf( '<div class="container-xxl">%s</div>', $content );

    }

},  10, 3 );




//I got the following 2 functions from https://gist.github.com/plasticmind/1509d93f9dbcb3186332ee8dced5b265
/**
 * inject_class_column_count
 *
 * @param string 	$content 		The block content about to be appended.
 * @param array 	$block 			The full block, including name and attributes
 * @return $content;
 */
function inject_class_column_count( $content, $block ) {

	if ( ! is_block_type( $block, "core/columns" ) ) {

		return $content;

	} else {

		$column_count = array_column($block['innerBlocks'],'blockName');

		$modified_content = str_replace( 'wp-block-columns', 'wp-block-columns has-'.count($column_count).'-columns', $content );

		return $modified_content;

	}
}

add_filter( 'render_block', 'inject_class_column_count', 10, 2 );

/**
 * is_block_type
 *
 * @param array $block 		A WordPress block array
 * @param string $type 		The block name being queried
 * @return bool;
 */
function is_block_type( $block, $type ) {
	if ( $type === $block['blockName'] ) {
		return true;
	}
	return false;
}








/**
 *  Replace WP classes with BS equivalents
 * 
 * 
 */

 add_filter( 'render_block', function( $content, $block ) {


    $search = [
        'are-vertically-aligned-top',
        'has-black-color',
        'has-white-color',
        'has-primary-color',
        'has-secondary-color',
        'has-dark-color',
        'has-light-color',
        'wp-block-button__link'
    ];

    $replace = [
        'align-items-start',
        'text-black',
        'text-white',
        'text-primary',
        'text-secondary',
        'text-dark',
        'text-light',
        'btn'
    ];



/*
    if ( is_block_type( $block, 'core/button' ) ) {

        if( isset( $block['attrs']['className'] ) ) {

            $button_classes = explode( ' ', $block['attrs']['className'] );

            foreach( $button_classes as $button_class ) {

                if ( $button_class == 'is-style-outline' ) {

                    $search[] = 'btn';
                    $replace[] = 'btn btn-outline';
                }
            }
        }
        
    }
*/





    //var_dump( $content );

    return str_replace( $search, $replace, $content );

 }, 10, 2 );











