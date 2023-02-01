<?php
/**
 *  Site Credits Block template
 * 
 * 
 * 
 */

 $dev_name = get_field( 'developer_name' );
 $dev_url = get_field( 'developer_url' );

 
 if ( !empty( $dev_name ) ) {

    $developer_name = $dev_name;

 } elseif( !empty( DEVELOPER_NAME ) ) {

    $developer_name = DEVELOPER_NAME;

 } else {

    return;
 }


 if ( !empty( $dev_url ) ) {

    $developer_url = $dev_url;

 } elseif( !empty( DEVELOPER_URL ) ) {

    $developer_url  = DEVELOPER_URL;

 } else {

    return;
 }

	
?>
<div class="developer-credits">
    <a href="<?php echo $developer_url; ?>" class="text-decoration-none <?php echo get_block_classes( $block ); ?>" target="_blank">Website by <?php echo $developer_name; ?></a>
</div>
