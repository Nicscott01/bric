<?php


if ( isset( $this->args['gallery_display'] ) && $this->args['gallery_display'] == 'masonry' ) {
					
    if ( $c == 1 ) {
       $item_class = " grid-sizer";
		//The below solution breaks photoswipe from working
		//$output .= '<div class="image-item grid-sizer p-0 d-block"></div>';
    }

    elseif ( $c % 3 == 0) {
        $item_class = " image-width-2 image-height-2";
        $image_large = 'medium_large';
    }

    else {
        $item_class= ' ';
        $image_large = 'medium';
    }

}
else {
    $image_large = 'medium';
}

$image_cats = wp_get_object_terms( $image['id'], 'project_size' );

foreach( $image_cats as $img_cat ) {

    $item_class .= ' project-size-' . $img_cat->slug;

}



//Image ratio
$ratio = $image['height'] / $image['width'];

$image_wrap_style = sprintf( 'padding-top:%s%%; position:relative; overflow:hidden;', $ratio*100 );

$output .= "<figure class='image-item".$item_class."' itemprop='associatedMedia' itemscope itemtype='http://schema.org/ImageObject'>";


$aria_text = sprintf( "Click to enlarge thumbnail of %s", 
                     ( $image['alt'] ) ? $image['alt'] : $image['title'] );

$img = wp_get_attachment_image( $image['id'], $image_large, false, array(
    'itemprop' => 'thumbnail',
	'style' => 'position:absolute; top:0; left:0; width:101%; height:101%;'
));


$output .= sprintf( "<a class='d-block border border-light' style='%s' href='%s' itemprop='contentUrl' data-size='%s' aria-label='%s'>%s</a>",
                $image_wrap_style,
 			   apply_filters( 'bric_photo_gallery_full_size', $image['url'], $image ),
                $image['width'].'x'.$image['height'],
                $aria_text,
                $img );

$img_cap = '';

//Check for caption text, if not, check for alt text, if not, then no caption.
if ( !empty ( $image['caption'] ) ) {

    $img_cap = '<span class="image-caption">'.$image['caption'].' - </span>';
}


$output .= sprintf("<figcaption itemprop='caption description' class='d-none'>%s</figcaption>",
                    $img_cap
                );

    


//$output .= '<div class="photo-id text-center d-flex justify-content-center align-items-center"><p class="h4 text-dark m-0">ID '.$image['id'].'</p></div>';

$output .= "</figure>";