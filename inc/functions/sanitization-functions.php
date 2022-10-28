<?php

use function PHPSTORM_META\map;

function bric_sanitize_year( $val ) {



  return $val;

}



function bric_floatval( $val ) {

    return strval( floatval( $val ) );

}


function bric_intval( $val ) {

    return intval( $val );

}


function bric_value_with_units( $val ) {

  $units = [
    'px',
    '%',
    'vw',
    'vh',
    'em',
    'rem'
  ];

  //Set a default
  $val_unit = '';
  $value = '';

  foreach ( $units as $unit ) {

    //This should never be zero or false
    if( strpos( $val, $unit ) ) {

      $val_unit = $unit;

      $value = intval( str_replace( $unit, '', $val ) );

      continue;
    }
    
  }

  if ( empty( $val_unit ) ) {

    $val_unit = 'px';

  }

  if ( empty( $value ) ) {

    return '';

  }

  return $value . $val_unit;


}