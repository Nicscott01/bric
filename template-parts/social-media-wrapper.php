<?php
/**
 *		UL Element for Social Media
 *
 *
 */

$block_class = isset($block['className']) ? $block['className'] : '';

printf( '<ul class="list-unstyled list-inline d-flex flex-row justify-content-center align-items-center flex-nowrap social-media-accounts %s" style="--bric-social-icon-size: %s;">%s</ul>', $block_class, $icon_size, $o );
