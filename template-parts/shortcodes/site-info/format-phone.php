<?php
/**
 *      Format Phone
 *
 */

//return sprintf( '<span class="tel-wrapper">%2$s<a href="tel:%1$s">%1$s</a></span>', $this->phone->main, 
	//				   ( !empty( $label) ? $label.'&nbsp;' : '' )
	//				  );	
?>
<span class="tel-wrapper"><?php echo ( !empty( $label) ? $label.'&nbsp;' : '' ) ?><a href="tel:<?php echo $this->phone->main; ?>"><?php echo $this->phone->main; ?></a></span>