<?php
/**
 *  Blocks for Testimonials
 * 
 */



acf_register_block_type([
    'name' => 'testimonials-display',
    'title' => 'Testimonials Display',
    'description' => 'Display all the testimonials entered in the website',
    'render_template' => 'template-parts/blocks/testimonials-display.php',
    'category' => 'formatting',
    'icon' => 'format-quote',
    
]);