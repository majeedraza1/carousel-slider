(function ($) {
    'use strict';

    // Open modal
    $(document).on('click', '[data-toggle="modal"]', function (e) {
        e.preventDefault();
        $($(this).data('target')).addClass('is-active');
    });

    // Close modal
    $(document).on('click', '[data-dismiss="modal"]', function (e) {
        e.preventDefault();
        $(this).closest('.modal').removeClass('is-active');
    });

})(jQuery);