<?php
/**
 *  Register all blocks for this site
 * 
 * 
 */

 include_once( __DIR__ . '/acf-fields/acf-fields.php' );
 include_once( __DIR__ . '/acf-fields/acf-filters.php' );


 add_action( 'init', function() {
    
   register_block_type( locate_template(  'blocks/accordion/'  ) );
   register_block_type( locate_template(  'blocks/button/'  ) );
   register_block_type( locate_template(  'blocks/collapse/'  ) );
   register_block_type( locate_template(  'blocks/column-item/'  ) );
   register_block_type( locate_template(  'blocks/columns/'  ) );
   register_block_type( locate_template(  'blocks/gallery/' ) );
   register_block_type( locate_template(  'blocks/hero-header/' ) );
   register_block_type( locate_template(  'blocks/menu/' ) );
   register_block_type( locate_template(  'blocks/nav-menu/' ) );
   register_block_type( locate_template(  'blocks/recent-posts/' ) );
   register_block_type( locate_template(  'blocks/site-copyright/' ) );
   register_block_type( locate_template(  'blocks/site-identity/'  ) );
   register_block_type( locate_template(  'blocks/svg-image/'  ) );
   register_block_type( locate_template(  'blocks/social-media/'  ) );
   register_block_type( locate_template(  'blocks/taxonomy-terms/'  ) );
   register_block_type( locate_template(  'blocks/website-credits/' ) );
   register_block_type( locate_template(  'blocks/website-header/'  ) );

 });



