<?php
/**
 *  Testimonials Display Quote/Masonry
 * 
 * 
 */


 //Setup the arguments
 $args = [
    'post_type' => 'testimonial',
    'posts_per_page' => -1,
 ];


 //Maybe we have a display group?
 $display_group = get_field( 'display_group' );

 
 if ( !empty( $display_group ) ) {

    $args['tax_query'] = [
        'relation' => 'OR',
        [
            'taxonomy' => 'testimonial_display_group',
            'field' => 'term_id',
            'terms' => $display_group,
            'operator' => 'IN'
        ]
    ];

 }

 //get the quotes
 $testimonials = get_posts( $args );


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


//@update 9/7/22 - removed "full-width" class from testimonials so they stay within the container
?>
<div class="block testimonials-display-block">
    <div class="row my-0 testimonials justify-content-center align-content-center mx-auto">
<?php

$count = count( $testimonials );

foreach( $testimonials as $testimonial ) {

    $name = get_field( 'name', $testimonial->ID );
    $title = get_field( 'title', $testimonial->ID );

    $attribution = '';

    $attribution .= !empty( $name ) ? $name . ',<br>' : '';
    $attribution .= $title;

    printf( '
    <div class="col-12 col-md-6 %s testimonial p-2">
        <div class="quote mb-5 p-4 py-5 text-center text-white rounded bg-%s">
            <p>"%s"</p>
        </div>
        <div class="attribution text-end">
            <p>%s</p>
        </div>
    </div>', $count > 2 ? 'col-lg-4' : '', $bg_colors[$c], $testimonial->post_content, $attribution );

    if ( $c == 3 ) {
        $c = 0;
    } else {
        $c++;
    }

}
?>
    </div>
</div>
<?php

//Enqueue Masonry
wp_enqueue_script( 'masonry' );


//Init the masonry
ob_start();
?>
( function($) {

    $('.testimonials').masonry({
        itemSelector: '.testimonial',
        columnWidth: '.testimonial',
        percentPosition: true,
        gutter:0,
        horizontalOrder: true
    });

})(jQuery);

<?php
$script = ob_get_clean();

wp_add_inline_script( 'masonry', $script, 'after' );