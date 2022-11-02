<?php

/**
 *  Template for search header (top of page)
 * 
 * 
 */
?>
<div class="row search-header bg-dark py-5">
    <div class="col-12 py-5">
        <div class="container-xxl">
            <h1 class="text-white">You searched for: <?php echo get_search_query() ?></h1>
            <?php echo get_search_form(0); ?>
        </div>
    </div>
</div>