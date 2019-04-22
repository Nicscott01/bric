<div class="fwp search-results-summary col-12">
	<h2>Search Results</h2>
	<?php 
	
	//if ( isset( FWP()->facet->query_args )) {
				
		//printf( '<p><span class="facet-label">Total Results: </span><span class="facet-value facetwp-counts">%s</span></p>', count ( FWP()->facet->query_args['post__in']  ));
		
//	} 
	echo '<div class="d-flex"><div class="facet-label">Total Results:&nbsp;</div>'.do_shortcode('[facetwp counts="true"]').'</div>';
	echo '<div class="facetwp-selections"></div>';
	
	//echo do_shortcode( '[facetwp counts="true"]' );
	
	/*
	
	//Get the selected values
	if ( !empty( FWP()->facet->facets )) :



		foreach ( FWP()->facet->facets as $facet ) {
			
			
			if ( !empty( $facet['selected_values'] ) ) {
		
			printf( '<p><span class="facet-label">%s: </span>', $facet['label'] );

			$values = [];



				foreach ( $facet['selected_values'] as $value ) {

					//Create the label from the slug
					//todo: look up the real label

					$label = explode( '-', $value );

					$label = array_map('ucfirst', $label );


					$values[] = sprintf( '<span class="facet-value facetwp-selections">%s</span>', implode( ' ', $label) );

				}
				
				
			echo implode( ', ', $values );

			echo '</p>';


			}


		}	


	
	endif;
	*/
	?>
</div>