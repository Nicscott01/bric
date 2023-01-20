<?php
/**
 *		LI for social media shortcode
 *
 */


$text_color = isset( $block['textColor'] ) ? 'text-' . $block['textColor'] : '';



if ( is_admin() ) {

    $svg = $social['icon']->element;

} else {

    $svg = sprintf( '<svg viewBox="%s"><use xlink:href="#%s"></use></svg>', $social['viewBox'], strtolower( $social['platform'] ) );
}




?>
<li class="social-account list-inline-item"><a href="<?php echo $social['url']; ?>" class="<?php echo $text_color; ?>" target="_blank" aria-label="Follow us on <?php echo $social['platform']; ?>"><?php echo $svg ?>
</a></li>
