<?php
/**
 *  Site Credits Block template
 * 
 * 
 * 
 */

 

 if ( !empty( DEVELOPER_NAME ) && !empty( DEVELOPER_URL ) ) {
			
    ?>
<div class="developer-credits">
    <a href="<?php echo DEVELOPER_URL; ?>" class="text-decoration-none" target="_blank">Website by <?php echo DEVELOPER_NAME; ?></a>
</div>
    <?php               
}