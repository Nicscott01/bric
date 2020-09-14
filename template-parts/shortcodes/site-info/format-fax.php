<?php
/**
 *      Format Fax
 *
 */
?>  
<span class="fax-wrapper"><?php echo ( !empty( $label) ? $label.'&nbsp;' : '' ) ?><a href="fax:<?php echo $this->phone->fax; ?>"><?php echo $this->phone->fax; ?></a></span>