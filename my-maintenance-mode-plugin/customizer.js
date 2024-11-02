(function ($) {
    wp.customize('smm_mobile_bg_image', function (value) {
        value.bind(function (newval) {
            // Update the preview with the new background image
            $('body').css('background-image', 'url(' + newval + ')');
        });
    });
})(jQuery);
