<?php
/**
 * 
 *  REgister these sidebars
 * 
 * 
 */



/**
 *		Header above everything
    *
    */
register_sidebar( array(
    'name'          => 'Upper Header',
    'id'            => 'upper-header',
    'before_widget' => '<div id="%1$s" class="widget mb-0 %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',	
) );


/**
 *		Header above nav
    *
    */
register_sidebar( array(
    'name'          => 'Header',
    'id'            => 'header-cta',
    'before_widget' => '<div id="%1$s" class="widget align-self-end mb-0 p-1 %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',	
) );


/**
 *		Footer
    *
    */		
register_sidebar( array(
    'name'          => 'Footer',
    'id'            => 'footer-content',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
) );
