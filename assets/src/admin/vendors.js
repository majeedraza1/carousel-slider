(function ($) {
    "use strict";

    // Initializing Select2
    $("select.select2").each(function () {
        $(this).select2();
    });

    // Initializing jQuery UI Accordion
    $(".shapla-toggle").each(function () {
        if ($(this).attr('data-id') === 'closed') {
            $(this).accordion({
                collapsible: true,
                heightStyle: "content",
                active: false
            });
        } else {
            $(this).accordion({
                collapsible: true,
                heightStyle: "content"
            });
        }
    });

    // Initializing jQuery UI Tab
    $(".shapla-tabs").tabs({
        hide: {
            effect: "fadeOut",
            duration: 200
        },
        show: {
            effect: "fadeIn",
            duration: 200
        }
    });

    //Initializing jQuery UI Date picker
    $('.datepicker').each(function () {
        $(this).datepicker({
            dateFormat: 'MM dd, yy',
            changeMonth: true,
            changeYear: true,
            onClose: function (selectedDate) {
                $(this).datepicker('option', 'minDate', selectedDate);
            }
        });
    });

    // Initializing WP Color Picker
    $('.color-picker').each(function () {
        $(this).wpColorPicker();
    });
})(jQuery);
