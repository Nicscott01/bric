/**
 *		Create the slideout div based on the 
 *		menu navbar item output on the page
 *
 */
(function($) {
    $(document).ready(function(e) {


        if (typeof(slideout) == 'undefined') {
            return;
        }

        $('#' + slideout.target_id).parent().clone().appendTo('body').attr('id', slideout.id).find('ul').attr('id', '');

        var $slideout = $('#' + slideout.id);


        if (slideout.target_id_left != '') {

            $slideout.find('ul').prepend($('#' + slideout.target_id_left).children().clone());


        }

        //Change the ID on each li element
        $slideout.find('li').each(function(i) {
            var id = $(this).attr('id');
            $(this).attr('id', 'slideout-' + id);

            $(this).find('a').attr('data-toggle', '');

        });

        Bric.mainmenu = new Slideout({
            'panel': document.getElementById('total-page-wrapper'),
            'menu': document.getElementById(slideout.id),
            'side': slideout.side,
        });

        if (parseInt(slideout.close_button)) {
            $slideout.prepend('<div class="close-button w-100 text-right"><button class="btn btn-primary" onclick="Bric.mainmenu.close();"><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="times" class="svg-inline--fa fa-times fa-w-10" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z"></path></svg></button></>')
        }


        //document.querySelector('.navbar-toggler').addEventListener('click')
        $('#main-navbar-toggler').click(function() {
            Bric.mainmenu.toggle();
        });

        $(document).trigger('bricSlideOutComplete');



    });

})(jQuery);