<?php

/**
 *  Template for Upper Header (Navbar Component)
 * 
 * 
 */



if (is_active_sidebar('upper-header')) { ?>
    <div class="upper-header">
        <?php dynamic_sidebar('upper-header'); ?>
    </div>
<?php
}
