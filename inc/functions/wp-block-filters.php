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
        'core/heading',
        'core/quote'
    ];

    if ( in_array( $parsed_block['blockName'], $catch_these)  ) {

        if ( !empty( $parent_block ) ) {

            $has_parent_block = true;

        }

    }

    return $context;

}





add_filter( 'render_block_core/heading', 'bric_filter_heading_block', 10, 3 );


add_action( 'dynamic_sidebar_before', function() {

    remove_filter( 'render_block_core/heading', 'bric_filter_heading_block', 10, 3 );

});

add_action( 'dynamic_sidebar_after', function() {

    add_filter( 'render_block_core/heading', 'bric_filter_heading_block', 10, 3 );

});

function bric_filter_heading_block( $content, $parsed_block, $wp_block ) {

   // var_dump( $parsed_block );
   // var_dump( $wp_block );
  //error_log( json_encode( $wp_block ) );




    global $has_parent_block;

    if ( $has_parent_block ) {

        $has_parent_block = false;

        return $content;

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

}








add_filter( 'render_block_core/paragraph', function( $content, $parsed_block, $wp_block ) {

    global $has_parent_block;

    if ( $has_parent_block || get_post_type() == 'post' ) {


        $has_parent_block = false;

        return '<div class="plain-paragraph mb-4">' . $content . '</div>';

    } else {

        return sprintf( '<div class="container-xxl">%s</div>', $content );

    }

},  10, 3 );




add_filter( 'render_block_core/quote', function( $content, $parsed_block, $wp_block ) {

    global $has_parent_block;

    if ( $has_parent_block || get_post_type() == 'post' ) {


        $has_parent_block = false;

        return '<div class="plain-quote mb-4">' . $content . '</div>';

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
        'is-vertically-aligned-top',
        'has-black-color',
        'has-white-color',
        'has-primary-color',
        'has-secondary-color',
        'has-tertiary-color',
        'has-dark-color',
        'has-light-color',
        'wp-block-button__link',
        'has-primary-background-color',
        'has-secondary-background-color',
        'has-tertiary-background-color',
        'has-dark-background-color',
        'has-light-background-color',
        'wp-block-columns',
        'wp-block-column',
        'wp-block-search__inside-wrapper',
        'wp-block-search__input',
        'wp-block-search__button',
        'wp-block-search__label'
    ];

    $replace = [
        'align-items-start',
        'align-self-start',
        'text-black',
        'text-white',
        'text-primary',
        'text-secondary',
        'text-tertiary',
        'text-dark',
        'text-light',
        'btn',
        'bg-primary',
        'bg-secondary',
        'bg-tertiary',
        'bg-dark',
        'bg-light',
        'row',
        'col',
        'input-group',
        'wp-block-search__input form-control',
        'wp-block-search__button btn',
        'wp-block-search__label visually-hidden'
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











