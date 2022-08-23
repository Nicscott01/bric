<?php
/**
 *  Template for company info
 * 
 * 
 */


$orientation = get_field( 'orientation' );

 ?>
 <div class="block company-info-block <?php echo !empty( $block['className'] ) ? $block['className'] : ''; ?> d-flex justify-content-center">
    <address class="row <?php echo $orientation == 'stacked' ? 'justify-content-center align-items-center text-center' : ''; ?>">
            <?php 

                $address_parts = [];
            
                $address_parts[] = !empty( get_option( 'bric_si_address_1' ) ) ? get_option( 'bric_si_address_1' ) . '<br>' : '';
                $address_parts[] = !empty( get_option( 'bric_si_address_2' ) ) ? get_option( 'bric_si_address_2' ) . '<br>'  : '';
                $address_parts[] = !empty( get_option( 'bric_si_city' ) ) ? get_option( 'bric_si_city' ) : '';
                $address_parts[] = !empty( get_option( 'bric_si_state' ) ) ? ', ' . get_option( 'bric_si_state' ) . ' ' . get_option( 'bric_si_zip' ) : '';

            
                if ( !empty( $address_parts ) ) {

                    $border_width = bric_get_theme_mod( 'borders', 'border_width' );

                    printf( '<section class="col-%s physical-address %s">%s</section>', 
                                    $orientation == 'wide' ? '6' : '12',
                                    $orientation == 'wide' ? "border-end border-primary border-{$border_width} d-flex justify-content-end align-items-center text-end" : 'sep-bottom',
                                    implode( '', $address_parts )
                    );
                    
                }


                $phones_emails_parts = [];


                $phones_emails_parts[] = !empty( get_option( 'bric_si_phone_prefix' ) ) ? get_option( 'bric_si_phone_prefix' ) : '';
                $phones_emails_parts[] = !empty( get_option( 'bric_si_phone' ) ) ? sprintf( '<a href="tel:"%1$s">%1$s</a>', get_option( 'bric_si_phone' ) ). '<br>' : '';
                $phones_emails_parts[] = !empty( get_option( 'bric_si_fax_prefix' ) ) ? get_option( 'bric_si_fax_prefix' ) : '';
                $phones_emails_parts[] = !empty( get_option( 'bric_si_fax' ) ) ? get_option( 'bric_si_fax' ) . '<br>' : '';
                $phones_emails_parts[] = !empty( get_option( 'bric_si_email' ) ) ? sprintf( '<a href="mailto:"%1$s">%1$s</a>', get_option( 'bric_si_email' )). '<br>' : '';
            
                if ( !empty( $phones_emails_parts ) ) {
                    printf( '<section class="col-%s phones-emails %s">%s</section>', 
                                    $orientation == 'wide' ? '6' : '12',
                                    $orientation == 'wide' ? '' : '',
                                    implode( '', $phones_emails_parts )
                    );
                }

            ?>
    </address>

 </div>