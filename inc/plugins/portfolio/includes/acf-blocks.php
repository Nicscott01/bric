<?php
/**
 *  ACF Blocks
 * 
 * 
 * 
 */



if ( function_exists( 'acf_register_block_type' ) ) {
       
    

    acf_register_block_type( [
        'name' => 'portfolio-gallery',
        'title' => 'Gallery for Portfolio',
        'description' => 'Renders image gallery in masonry',
        'render_template' => 'template-parts/components/portfolio/blocks/gallery-block.php',
        'category' => 'formatting',
        'icon' => 'images-alt2',
        'keywords' => [ 'gallery' ],
        'align' => 'wide',
    ]);

}
 