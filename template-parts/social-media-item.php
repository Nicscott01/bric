<?php
/**
 *		LI for social media shortcode
 *
 */

//$o .= sprintf( '<li class="social-account list-inline-item"><a href="%s" target="_blank" aria-label="Follow us on %s">%s</a></li>', $social['url'], $social['platform'], $social['icon']->element );
?>
<li class="social-account list-inline-item"><a href="<?php echo $social['url']; ?>" target="_blank" aria-label="Follow us on <?php echo $social['platform']; ?>"><?php echo $social['icon']->element; ?></a></li>