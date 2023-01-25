<?php
/**
 *  Handles Cookie Consent Loader
 * 
 * 
 * 
 * 
 */

$uc_id = get_field( 'cc_code', 'option' );

if ( empty( $uc_id ) ) {
    return;
}

 ?><link rel="preconnect" href="//privacy-proxy.usercentrics.eu">
    <link rel="preload" href="//privacy-proxy.usercentrics.eu/latest/uc-block.bundle.js" as="script">
    <script type="application/javascript" src="https://privacy-proxy.usercentrics.eu/latest/uc-block.bundle.js"></script>
    <script id="usercentrics-cmp" src="https://app.usercentrics.eu/browser-ui/latest/loader.js" data-settings-id="<?php echo $uc_id; ?>"  async></script>
