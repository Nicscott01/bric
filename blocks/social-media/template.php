<?php
/**
 *  Social Media Block template
 * 
 * 
 * 
 */

use function Bric\BricSocialMedia;


$social_icons = get_field( 'social_accounts' );
$icon_size = get_field( 'icon_size' );
$margin = get_field( 'margin' );

//Left/Center/Right
//$align = $block['align'];

//Align content top/center/bottom
//$v_align = $block['alignContent'];

//Text Align
//$text_align = $block['alignText'];

//var_dump( get_block_classes( $block, 'row' ) );


if ( !empty( $icon_size ) && intval( $icon_size  ) > 0 ) {

    $icon_size = intval( $icon_size )  / 16 . 'rem';

}




    $social_accounts = BricSocialMedia()->get_social_accounts();

    $icons_data = BricSocialMedia()->social_icons;

   // var_dump( $icons_data );
  //  var_dump(  BricSocialMedia()->social_icons );
  //  var_dump( $social_accounts );



$text_color = isset( $block['textColor'] ) ? $block['textColor'] : '';

//var_dump( $social_icons );



?>
<ul class="list-unstyled list-inline d-flex <?php echo get_block_classes( $block, get_field( 'flex_direction' ) ); ?> flex-nowrap social-media-accounts" style="--bric-social-icon-size:<?php echo $icon_size?>; --bric-link-color:var(--bric-<?php echo $text_color; ?>);">
<?php


//EACH ITEM



foreach( $social_icons as $social ) {

    $platform = $social['account'];
    $icon_id = $social['icon']->id;

   // var_dump( $social['icon'] );
    //var_dump( strtolower( $social_icon['account'] ) );

    if (isset( $social_accounts[ strtolower( $social['account']) ]['url'] )) {
        $url = $social_accounts[ strtolower( $social['account']) ]['url'];
    }

    //var_dump( $url );
  
    if ( !empty( $url ) ) {

        if ( is_admin() ) {

            $svg = $social['icon']->element;
        
        } else {
        
            //$social['url'] = $social_accounts[strtolower($social['platform'])]['url'];

            if ( isset( $icons_data[$icon_id]['viewBox'] ) && !empty( $icons_data[$icon_id]['viewBox'] ) ) {
                $svg = sprintf( '<svg viewBox="%s"><use xlink:href="#%s"></use></svg>', $icons_data[$icon_id]['viewBox'], $icon_id );
            }
        }

        if ( !empty( $svg ) ) {
?>
<li class="social-account list-inline-item m-0">
    <a href="<?php echo $url; ?>" class="social-icon" target="_blank" aria-label="Follow us on <?php echo $platform; ?>">
    <?php echo $svg ?>
    </a>
</li>
<?php
        }
    }
}
?>
</ul>