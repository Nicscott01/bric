<?php
/**
 *  Register all blocks for this site
 * 
 * 
 */

//register_block_type( get_stylesheet_directory( ) . '/blocks/bg-image-group/'  );
//register_block_type( get_stylesheet_directory( ) . '/blocks/footer/'  );
//register_block_type( get_stylesheet_directory( ) . '/blocks/content-image-row/'  );
register_block_type( locate_template( 'blocks/hero-header/' ) );
//register_block_type( get_stylesheet_directory( ) . '/blocks/icon-card/'  );
register_block_type( locate_template(  'blocks/columns/'  ) );
register_block_type( locate_template(  'blocks/column-item/'  ) );
//register_block_type( get_stylesheet_directory( ) . '/blocks/term-carousel/'  );
//register_block_type( get_stylesheet_directory( ) . '/blocks/cta-squares/'  );
register_block_type( locate_template(  'blocks/site-identity/'  ) );
//register_block_type( get_stylesheet_directory( ) . '/blocks/cta-row/'  );
//register_block_type( get_stylesheet_directory( ) . '/blocks/shop-nav/'  );
//register_block_type( get_stylesheet_directory( ) . '/blocks/taxonomy-term-list/'  );

