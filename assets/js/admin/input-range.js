(function ($) {
    'use strict';

    var value, thisInput, inputDefault;

    // Update the text value
    $('input[type=range]').on('mousedown', function () {
        value = $(this).val();
        $(this).mousemove(function () {
            value = $(this).val();
            $(this).closest('.carousel-slider-range-wrapper').find('.range-value .value').val(value);
        });
    });

    $(document).on('input', '.range-value .value', function (e) {
        value = $(this).val();
        $(this).closest('.carousel-slider-range-wrapper').find('input[type=range]').val(value);
    });

    // Handle the reset button
    $('.carousel-slider-range-reset').click(function () {
        thisInput = $(this).closest('.carousel-slider-range-wrapper').find('input');
        inputDefault = thisInput.data('reset_value');
        thisInput.val(inputDefault);
        thisInput.change();
        $(this).closest('.carousel-slider-range-wrapper').find('.range-value .value').text(inputDefault);
    });

})(jQuery);