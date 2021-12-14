<?php

/**
 *      Template for Navbar Brand 
 * 
 *      Added to make class tweaking easier
 * 
 * 
 *      @date 12/8/21
 *      
 */


/**
 *		Apply filter: bric_navbar_brand_type
 *
 *		choices: text, image, textimage
 *
 */


$navbar_brand_type = apply_filters('bric_navbar_brand_type', $navbar_brand_type);


$navbar_brand_width = apply_filters('bric_navbar_brand_width', $SiteInfo->navbar->width . 'px');


switch ($navbar_brand_type) {

    case 'text':

        $this->navbar_brand = sprintf('<a class="navbar-brand" href="%s" style="width:%s">%s</a>', $this->url, $navbar_brand_width, $this->title);

        break;

    case 'image':

        $this->navbar_brand = sprintf('<a class="navbar-brand" href="%s" style="width:%s">%s</a>', $this->url, $navbar_brand_width, $this->logo);
        break;

    case 'textimage':

        $this->navbar_brand = sprintf(
            '<a class="navbar-brand" href="%s" style="width:%s"><div class="tagline">%s</div> %s</a>',
            $this->url,
            $navbar_brand_width,
            '<span class="blogtitle">' . get_bloginfo('blogname') . '</span>',
            $this->logo
        );
        break;
}
