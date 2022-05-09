<?php

/**
 *  Template for search header (top of page)
 * 
 * 
 */
?>
<div class="col-12 mb-2 search-header">
    <h1>Search Results for: <?php echo get_search_query() ?></h1>
    <?php echo get_search_form(0); ?>
</div>