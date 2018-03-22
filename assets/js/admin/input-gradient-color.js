(function ($) {
    'use strict';

    $('.gradient-color-picker').each(function () {
        var _this = $(this),
            _tab_bg = _this.closest('.tab-background'),
            _wrapper = _this.closest('.gradient-color-wrapper'),
            _angle = _wrapper.find('.gradient-color-angle'),
            _colors = _wrapper.find('.gradient-color-colors'),
            _type = _wrapper.find('.gradient-color-type:checked'),
            _points = _this.data('points'),
            _preview = _tab_bg.find('.gradient_canvas');

        $(this).gradientPicker({
            fillDirection: _angle.val() + 'deg',
            type: _type.val(),
            controlPoints: _points,
            change: function (points, styles) {
                _colors.val(JSON.stringify(points));
                for (var i = 0; i < styles.length; ++i) {
                    _preview.css("background-image", styles[i]);
                }
            }
        });
    });

    $('.gradient-color-type').on('change', function () {
        var _this = $(this),
            _wrapper = _this.closest('.gradient-color-wrapper'),
            _rangeWrapper = _wrapper.find('.carousel-slider-range-wrapper');

        if ('linear' === _this.val()) {
            _rangeWrapper.css('display', 'block');
        } else {
            _rangeWrapper.css('display', 'none');
        }
    })
})(jQuery);
