<?php
/**
 *		Template for Google Maps Block
 *
 *
 */

//Use our Google Maps render class

$gmaps = new GoogleMaps();

$gmaps->enqueue_scripts();

add_action( 'admin_enqueue_scripts', [ $gmaps, 'enqueue_scripts'], 9999 );


$map = get_field( 'map_block' );

if ( !empty( $map) ) {

	?>
<div class="acf-map">	
<div class="marker" data-lat="<?php echo $map['lat']; ?>" data-lng="<?php echo $map['lng']; ?>"></div>
</div>	

	<?php

}



if ( is_admin() ) {

	
?>
<style>.acf-map {
width: 100%;
height: 400px;
border: #ccc solid 1px;
margin: 20px 0;
}
</style>
<?php
	
	//For some reason it only renders the map if this script is up higher.
	printf( '<script type="text/javascript" src="%s"></script>', get_template_directory_uri().'/assets/js/google-maps-render.min.js' );

	
}