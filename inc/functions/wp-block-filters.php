<?php
/**
 *  Filters to change behavior of OOTB block content
 * 
 * 
 */

use function PHPSTORM_META\map;

/**
 *  Edit some markup of the blocks
 * 
 * 
 * 
 */





add_filter( 'render_block_context', 'has_parent_block', 5, 3 ); 

function has_parent_block( $context, $parsed_block, $parent_block ) {

    global $has_parent_block;

    //var_dump( $parsed_block );
    //var_dump( $parent_block );


    //Do this only for the bockas I want

    $catch_these = [
        'core/paragraph',
        'core/heading',
        'core/quote',
        'core/group',
        'core/list',
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

    if ( $has_parent_block || is_singular( ['post']) ) {

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

        return '<div class="plain-paragraph">' . $content . '</div>';

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




function has_child_block_type( $block, $type ) {

    if ( isset( $block['innerBlocks'] ) && !empty( $block['innerBlocks'] ) ){
        
        foreach( $block['innerBlocks'] as $innerBlock ) {

            if ( is_block_type( $innerBlock, $type ) ) {
                
                return true;

            } elseif ( has_child_block_type( $innerBlock, $type ) ) {

                return true;
            
            }
        }

    }

    return false;

}






/**
 *  Core/Button Block
 *  filter classes
 * 
 */
 //add_filter( 'render_block_core/button', 'bric_filter_core_button', 10, 3 );
 //add_filter( 'render_block_core/buttons', 'bric_filter_core_button', 10, 3 );

 function bric_filter_core_button( $content, $block, $instance = null ) {

   // var_dump( $block );

    //Baseline Button
    $content = str_replace( [ 'wp-block-button__link', 'wp-block-search__button' ], 'btn', $content );

    $btn_type_class = 'btn-';

    if ( isset( $block['attrs']['className'] ) ) {

        if( strpos( $block['attrs']['className'], 'is-style-outline') !== false ) {
          
            $btn_type_class = 'btn-outline-';

        } elseif( strpos( $block['attrs']['className'], 'is-style-fill') !== false  ) {

            $btn_type_class = 'btn-';
        } 

    }


    //Pick up the colors
    $bg_color = isset( $block['attrs']['backgroundColor'] ) ? $block['attrs']['backgroundColor'] : null;
    $text_color = isset( $block['attrs']['textColor'] ) ? $block['attrs']['textColor'] : null;

    $btn_color =  empty( $bg_color ) ? $text_color : $bg_color;

    $content = str_replace( 'btn', 'btn ' . $btn_type_class . $btn_color, $content );

    
    if ( !($btn_color == $text_color ) ) { 

        $content = str_replace( 'has-' . $text_color . '-color', 'text-' . $text_color, $content );
    
    } else {
        //Remove the wp classes that get extended since it will mess up rollover states
        $content = str_replace( 'has-' . $text_color . '-color', '', $content );
    }

    if ( $btn_color == $bg_color ) {

        $content = str_replace( 'has-' . $bg_color . '-background-color', '', $content );
    }


    $content = str_replace( 'wp-block-button', 'wp-block-button flex-shrink-1', $content );



    return $content;

 }




 function bric_filter_core_search( $content, $block, $instance = null ) {

    $search = [
        'wp-block-search__label',
        'wp-block-search__inside-wrapper',
        'wp-block-search__input',
        'wp-block-search__button-inside',
        'wp-block-search__button '

    ];

    $replace = [
        'wp-block-search--label visually-hidden',
        'wp-block-search--inside-wrapper btn-group',
        'wp-block-search--input form-control',
        'wp-block-search--button-inside', //so the other search/replace doesn't add a btn style
        'wp-block-search--button btn btn-primary ' //so the other search/replace doesn't add a btn style
    ];

    return str_replace( $search, $replace, $content );

 }
 







 add_filter( 'render_block', function( $content, $block ) {

    global $has_parent_block;

    switch( $block['blockName'] ) {

       
        case 'core/group' :


            if ( $has_parent_block ) {
                
                $has_parent_block = false;

                break;
            
            } 

            //var_dump( $block );

            $align = isset( $block['attrs']['align'] ) ? $block['attrs']['align'] : 'none';
            $layout = isset( $block['attrs']['layout']['type'] ) ? $block['attrs']['layout']['type'] : '';

            $search = 'align' . $align;

            switch( $align ) {

                case 'wide':

                    $alignment_class = 'container-xxl';

                    break;

                case 'full' :
                case 'center' :

                    $alignment_class = 'container-fluid';

                    break;


                case 'none' :

                    $search = 'wp-block-group'; //they don't put out a class
                    $alignment_class = 'wp-block-group container';


                    break;
                
            }


            $content = str_replace( $search, $alignment_class, $content );
            
            $content = bric_block_general_css_replacement( $content );


            break;
            

        case 'core/button' :

           $content = bric_filter_core_button( $content, $block );

            break;

        case 'core/search' :  

           $content = bric_filter_core_search( $content, $block );
           //$content = bric_filter_core_button( $content, $block );
           
           break;


        case 'core/list' :

            //Wrap bullets in a container if it doesn't have a parent block
            if ( $has_parent_block ) {
                
                $has_parent_block = false;

                break;
            
            } else {

                $content = '<div class="container-xxl">' . $content . '</div>';

            }
            


            break;

        case 'core/embed' :

//var_dump( $block );

            $has_ratio = strpos( $block['attrs']['className'], 'wp-has-aspect-ratio' );

            if ( $has_ratio !== false ) {

                $re = '/wp-embed-aspect-(\d+)-(\d+)\s?/m';
                $str = $block['attrs']['className'];

                preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);

                // Print the entire match result
                //var_dump($matches);

                if ( !empty( $matches ) ) {

                    $ratio_w = $matches[0][1];
                    $ratio_h = $matches[0][2];


                    $content = str_replace( 'wp-block-embed__wrapper', 'ratio ratio-' . $ratio_w . 'x' . $ratio_h, $content );


                }



            }
            break;

        default :

        $content = bric_block_general_css_replacement( $content );
        //var_dump( $block );


    }


    return $content;

 }, 10, 2 );




function bric_block_general_css_replacement( $content ) {

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
        //'wp-block-button__link',
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
        'wp-block-search__label',
        'wp-block-post-excerpt__more-link',
        'is-layout-flex',
        'is-content-justification-center',
        'has-small-font-size'
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
        //'btn',
        'bg-primary',
        'bg-secondary',
        'bg-tertiary',
        'bg-dark',
        'bg-light',
        'wp-block-columns row',
        'col',
        'input-group',
        'wp-block-search__input form-control',
        'wp-block-search__button btn',
        'wp-block-search__label visually-hidden',
        'wp-block-post-excerpt--more-link fw-bold',
        'd-flex',
        'justify-content-center',
        'small'
    ];




    return str_replace( $search, $replace, $content );

}






/**
 *  Add Block ID to accordion block
 * 
 * 
 * 
 */


add_filter(
    'acf/pre_save_block',
    function( $attributes ) {

        if ( $attributes['name'] == 'acf/accordion' && empty( $attributes['anchor'] ) ) {
            $attributes['anchor'] = 'accordion-' . uniqid();
        }

        return $attributes;
    }
);





add_filter( 'render_block_data', function( $parsed_block, $source_block, $parent_block ) {


    if ( $source_block['blockName'] == 'acf/collapse' ) {
        
         $parsed_block['attrs']['parentAnchor'] = $parent_block->parsed_block['attrs']['anchor'];
  //     var_dump( $parsed_block );

    }


    return $parsed_block;


}, 10, 3 );