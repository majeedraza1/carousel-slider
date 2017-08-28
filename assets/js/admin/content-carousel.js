(function ($) {
    "use strict";

    $('.carousel-slider__add-slide').on('click', function (e) {
        e.preventDefault();

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'add_content_slide',
                post_id: $(this).data('post-id')
            },
            success: function () {
                $('form#post').submit();
            }
        });

    })
})(jQuery);