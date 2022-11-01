<?php
/**
 *  Search Result Template
 * 
 * 
 * 
 * 
 */


 ?>
 <article id="post-<?php the_ID();?>" class="col-12 mb-4">
    <h1 class="entry-title h3"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
    <?php the_excerpt(); ?>
</article>