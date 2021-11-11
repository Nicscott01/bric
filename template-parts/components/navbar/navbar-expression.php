<?php
/** 
 *  The main nav expression, 
 *  templatized for ease of edit
 *  hopefully :)
 */

 $left_side = '';
 $left_side_class = '';

//Do we have a left side?
if ( isset( $this->main_nav_menu_obj_left ) && !empty( $this->main_nav_menu_obj_left ) ) {

    $left_side = '<div class="left-side">
    %11$s
    </div>';

    $left_side_class = 'has-left-side';

}


return 
'%9$s
<nav class="navbar navbar-expand-%4$s navbar-%7$s bg-%8$s has-left-side" role="navigation">%5$s
'. $left_side .'
%1$s
<div class="right-side">
%10$s
%3$s
%2$s
</div>
%6$s
</nav>';