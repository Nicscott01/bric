<?php


global $BricLoop;


?>
<div class="footer-lower d-flex flex-wrap justify-content-center align-items-center p-2 small copyright-credits-wrapper text-<?php echo bric_get_theme_mod('lower_footer', 'text_color'); ?> bg-<?php echo get_theme_mod('lower_footer__background_color'); ?>">
    <?php echo $BricLoop->get_copyright(); ?><span class="sep px-2">|</span>
    <?php

    //Get the lower nav menu
    $menu = get_nav_menu_locations();

    if (isset($menu['lower_footer']) && !empty($menu['lower_footer'])) {

        $menu = $menu['lower_footer'];
    } else {

        $menu = bric_get_theme_mod('lower_footer', 'menu');
    }


    if ($menu) {


        add_filter( 'wp_nav_menu_objects', [ BricFilters(), 'bric_lower_footer_menu' ], 10, 2 ); 


        add_filter( 'wp_nav_menu_items', function( $items, $args ) {
			
            //Get the cookie consent tool code
			$uc_code = get_field( 'cc_code', 'option' ); 
            $cookie_consent_type = get_field( 'cookie_consent_type', 'option' );


            if ( $cookie_consent_type == 'uc' && !empty( $uc_code ) && get_field( 'privacy_settings_link', 'option' ) ) {
            $items .= sprintf('
                <li class="px-2 menu-item"><a href="javascript:UC_UI.showSecondLayer();" id="usercentrics-psl">Privacy Settings</a></li>
            ');
            }

            return $items;

        }, 10, 2);



        
        wp_nav_menu([
            'menu' => $menu,
            'menu_class' => 'menu d-flex m-0 list-unstyled text-' . bric_get_theme_mod('lower_footer', 'text_color')
        ]);


        remove_filter( 'wp_nav_menu_objects', [ BricFilters(), 'bric_lower_footer_menu' ], 10, 2 ); 
        

    ?><span class="sep px-2">|</span><?php

    }
        
        echo $BricLoop->get_developer_credits(); 
        
    ?>
</div>
<?php
