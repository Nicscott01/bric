<?php
/**
 *  Shortcode Template: Format Address
 *
 *
 */
?>
<div class="address">	
	<?php 
		if ( !empty ( $this->address->line_1 ) ) : ?>
	
	<span><?php echo $this->address->line_1; 
		if ( !empty( $this->address->line_2 ) ) {
			echo '<br>'.$this->address->line_2.'<br>';
		}
		else {
			echo '<br>';
		}
		?></span>
		
	<?php endif; 
	
	if ( !empty( $this->address->city ) ) :
	?>	
	<span><?php echo $this->address->city; ?></span>,
	<?php endif; 
		
	if ( !empty( $this->address->state ) ) : 
	?>
	<span><?php echo $this->address->state; ?></span>
	<?php endif;
	
	if ( !empty( $this->address->zip ) ) : 
	?>
	<span><?php echo $this->address->zip; ?></span>
	<?php endif; ?>
</div>
