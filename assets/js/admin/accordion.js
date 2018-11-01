(function ($) {
    'use strict';
    $(document).on("click", ".accordion-header", function () {
        $(this).toggleClass('active');
        var panel = $(this).next();

        if (parseInt(panel.css('max-height')) > 0) {
            panel.css('max-height', '0');
            panel.css('overflow', 'hidden');
        } else {
            panel.css('max-height', panel.prop('scrollHeight') + "px");
            panel.css('overflow', 'visible');
        }
    });
})(jQuery);