<?php
/**
 *  Testimonials Display Quote/Masonry
 * 
 * 
 */

 //get the quotes
 $testimonials = get_posts( [
     'post_type' => 'testimonial',
     'posts_per_page' => -1,
    
 ]);


if ( empty( $testimonials ) ) {

    return false;

}

$bg_colors = [
    'primary',
    'secondary',
    'red',
    'yellow'
];

$c = 0;

?>
<div class="block testimonials-display-block full-width">
    <div class="row m-0 p-2">
<?php
foreach( $testimonials as $testimonial ) {

    $name = get_field( 'name', $testimonial->ID );
    $title = get_field( 'title', $testimonial->ID );

    $attribution = '';

    $attribution .= !empty( $name ) ? $name . ',<br>' : '';
    $attribution .= $title;

    printf( '
    <div class="col-12 col-md-6 col-lg-4 testimonial p-2">
        <div class="quote mb-5 p-4 py-5 text-center text-white rounded bg-%s">
            <p>"%s"</p>
        </div>
        <div class="attribution text-end">
            <p>%s</p>
        </div>
    </div>', $bg_colors[$c], $testimonial->post_content, $attribution );

    if ( $c == 3 ) {
        $c = 0;
    } else {
        $c++;
    }

}
?>
    </div>
</div>