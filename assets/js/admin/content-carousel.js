(function ($) {
    "use strict";
    var template = $('#carouselSliderContentTemplate');

    $('.carousel-slider__add-slide').on('click', function (e) {
        e.preventDefault();

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'add_content_slide',
                editor_id: 1
            },
            success: function (response) {
                $('#carouselSliderContentInside').append(response);
            }
        });

    })
})(jQuery);