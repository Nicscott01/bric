<?php

/**
 *  CALLBACK Functions for Customizer Refresh
 *
 * 
 * 
 */


 function get_template_part_footer_inner() {

    return get_template_part( 'template-parts/footer-basic' );

 }


 function get_template_part_footer_lower() {

    return get_template_part( 'template-parts/footer-lower' );

 }


 function write_css_vars(){ 

    return \Bric\Customizer()->write_css_vars();

 }