(function ($) {
    'use strict';

    var value, thisInput;

    // Update the text value
    $(document).on('mousedown', 'input[type=range]', function () {
        value = $(this).val();
        $(this).mousemove(function () {
            value = $(this).val();
            $(this).closest('.carousel-slider-range-wrapper').find('.range-value .value').val(value);
        });
    });

    $(document).on('input', '.range-value .value', function () {
        value = $(this).val();
        $(this).closest('.carousel-slider-range-wrapper').find('input[type=range]').val(value);
    });

    // Handle the reset button
    $(document).on('click', '.carousel-slider-range-reset', function () {
        thisInput = $(this).closest('.carousel-slider-range-wrapper').find('input');
        value = thisInput.data('reset_value');
        value = !!value ? value : 0;
        thisInput.val(value);
        thisInput.change();
        $(this).closest('.carousel-slider-range-wrapper').find('.range-value .value').val(value);
    });

})(jQuery);