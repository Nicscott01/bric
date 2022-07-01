<?php


global $BricLoop;


?>
<div class="footer-lower d-flex justify-content-center align-items-center p-2 small copyright-credits-wrapper text-<?php echo bric_get_theme_mod( 'lower_footer', 'text_color' ); ?> bg-<?php echo get_theme_mod( 'lower_footer__background_color' ); ?>">
	<?php echo $BricLoop->get_copyright(); ?><span class="px-2">|</span>
    <?php 
    //Get the lower nav menu
    wp_nav_menu( [
        'menu' => bric_get_theme_mod( 'lower_footer', 'menu' ),
        'menu_class' => 'menu d-flex m-0 list-unstyled text-' . bric_get_theme_mod( 'lower_footer', 'text_color' )
    ] );

    ?><span class="px-2">|</span>
	<?php echo $BricLoop->get_developer_credits(); ?>
</div>
<?php 
