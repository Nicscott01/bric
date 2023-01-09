<?php
/**
 *  Columns Block template
 * 
 * 
 * 
 */

$attributes = $block;

$link = get_field( 'link' );
$download = get_field( 'download' );
$theme_color = get_field( 'theme_color' );
$size = get_field( 'size' );
$border = get_field( 'border' );

if ( ! $is_preview ) {
    //var_dump( $block );
   // var_dump( $attributes );
}


//Init the default
$style_type = 'solid';


//var_dump( $block );
//Get the Style Attribute from the className field
if ( isset( $block['className'] ) ) {
    
    $re = '/(?>^|\s)is-style-([^\s]+)/m';
    $str = $block['className'];

    preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);

    // Print the entire match result

    if ( isset( $matches[0][1] ) ) {

        $style_type = $matches[0][1];
    }

}

//var_dump( $style_type );



//Define button class.
switch( $style_type ) {

    case "link" :
    
        $base_button_class = 'btn btn-link';

        break;

    case "outline" :

        $base_button_class = 'btn btn-outline-' . $theme_color;

        break;

    default : 

        $base_button_class = 'btn btn-' . $theme_color;
}


if ( isset( $attributes['backgroundColor' ] ) ) {

    $base_button_class .= ' bg-' . $attributes['backgroundColor' ];


} 


if ( isset( $attributes['textColor' ] ) ) {

    $base_button_class .= ' text-' . $attributes['textColor' ];

}


if ( !empty( $border ) && $border !== 'none' && $style_type == 'solid' ) {

    $base_button_class .= ' border border-' . $border;
}



if ( !empty( $size ) && $size !== 'standard' ) {

    $base_button_class .= ' btn-' . $size;
}

$alignment = '';

if ( isset( $attributes['align'] ) ) {

    switch( $attributes['align'] ) {

        case "right" :

            $alignment = 'end';

            break;

        case "center" : 

            $alignment = 'center';

            break;

        case "left" :

            $alignment = 'start'; //don't need it

    }




}

//Overall Alignment
if ( !empty( $alignment ) ) {

    ?>
    <div class="d-flex justify-content-<?php echo $alignment; ?>">
    <?php

}


if ( !empty( $download ) ) {

    global $bric_button;

    //Output our button style class to the args of the template.
    //$args = apply_filters( 'dlm_get_template_part_args', $args, $template, $slug, $name );
    $bric_button['class'] = isset( $attributes['className'] ) ? $base_button_class . ' ' . $attributes['className'] : $base_button_class;
    $bric_button[ 'text' ] = isset( $link['title'] ) ? $link['title'] : 'My Button';
    $bric_button[ 'alignment' ] = $alignment; 

    //var_dump( $download );
    echo do_shortcode( sprintf( '[download id=%s template="button"]', $download->ID ) );

 
} else {


    //Use class d-inline-block for backend editor rendering

?>
<a href="<?php echo isset( $link['url'] ) ? $link['url'] : '#'; ?>" class="<?php echo $is_preview ? 'd-inline-block ' : ''; ?><?php echo $base_button_class; ?> <?php echo isset( $attributes['className'] ) ? $attributes['className'] : '';?>" target="<?php echo isset( $link['target'] ) ? $link['target'] : ""; ?>"><?php echo isset( $link['title'] ) ? $link['title'] : "My Button"; ?></a>
<?php

}

if ( !empty( $alignment ) ) {

    ?>
    </div>
    <?php
}
