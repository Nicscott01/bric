<?php
/**
 *  Format Email
 *
 *
 */
$output = '';


if ( $this->is_email( $item ) ) {

    $class = 'email';
    $output = sprintf( '%2$s<a href="mailto:%1$s">%1$s</a>', $item, 
                   ( !empty( $label) ? $label.'&nbsp;' : '' )
                  );

}

elseif ( $this->is_url( $item ) ) {

    $class = 'email';
    $output = sprintf( '%2$s<a href="mailto:%1$s" target="_blank">%1$s</a>', $item, 
                   ( !empty( $label) ? $label.'&nbsp;' : '' )
                  );

}

else {

    $class = 'url';
    $output = sprintf( '%2$s<a href="%1$s">%1$s</a>', $item, 
                   ( !empty( $label) ? $label.'&nbsp;' : '' )
                  );

}


printf( '<span class="%s-wrapper">%s</span>', $class, $output );