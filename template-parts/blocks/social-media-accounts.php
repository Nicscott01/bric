<?php
/**
 *  Template to display list of social media accounts
 * 
 * 
 */


 $accounts = get_field( 'social_media_accounts', 'option' );

 
 $icon_size = get_field( 'icon_size' );

 

 //var_dump( $accounts );

 if ( empty( $accounts ) ) {

    if ( is_admin(  ) ) {
        
        echo '<p class="notice">There are no social media accounts registered on this site.</p>';
        
        return;

    } else {

        return;
    }

 }

?>
<div id="<?php echo $block['id']; ?>" class="block social-media-accounts-block">
    <ul class="list-unstyled list-inline d-flex social-media-accounts text-<?php echo get_field( 'display_color' ); ?>">
    <?php

    foreach( $accounts as $account ) {

        if ( is_object( $account['icon'] ) ) {

            $icon = $account['icon']->element;

        }

        printf( '<li class="px-1"><a href="%s">%s</a></li>', $account['url'], $icon );

    }

    ?>
    </ul>
</div>
<?php

if ( !empty( $icon_size ) ) {
//We have to do this because icon replce with Font Awesome doesn't show the SVG until after page load
    ?>
<style>
#<?php echo $block['id']; ?> .social-media-accounts li svg {
    min-height: <?php echo $icon_size; ?>rem;
}
</style>
    <?php

}
