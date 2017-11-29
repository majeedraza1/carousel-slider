(function ($) {
    'use strict';

    var mobile = 767,
        tablet = 768,
        desktop = 1025,
        minHeadingFont = 20,
        minDescFont = 16,
        heading,
        headingFontSize = 60,
        headingFontSizeNum = 60,
        description,
        descriptionFontSize = 24,
        descriptionFontSizeNum = 24;


    function scaleSliderText() {
        var windowW = $(window).width();

        $('.carousel-slider .slide-content').each(function () {

            heading = $(this).find('.heading-title');
            headingFontSize = heading.data('font-size');
            headingFontSizeNum = parseInt(headingFontSize);

            if (headingFontSizeNum) {
                if (windowW <= mobile) {
                    headingFontSize = headingFontSizeNum / 3;
                    headingFontSize = headingFontSize < minHeadingFont ? minHeadingFont : headingFontSize;
                    heading.css('font-size', headingFontSize + 'px');
                }
                if (windowW >= tablet && windowW < desktop) {
                    headingFontSize = headingFontSizeNum / 2;
                    headingFontSize = headingFontSize < minHeadingFont ? minHeadingFont : headingFontSize;
                    heading.css('font-size', headingFontSize + 'px');
                }
                if (windowW >= desktop) {
                    heading.css('font-size', headingFontSize + 'px');
                }
            }

            description = $(this).find('.description-title');
            descriptionFontSize = description.data('font-size');
            descriptionFontSizeNum = parseInt(descriptionFontSize);

            if (descriptionFontSizeNum) {
                if (windowW <= mobile) {
                    descriptionFontSize = descriptionFontSizeNum / 3;
                    descriptionFontSize = descriptionFontSize < minDescFont ? minDescFont : descriptionFontSize;
                    description.css('font-size', descriptionFontSize + 'px');
                }
                if (windowW >= tablet && windowW < desktop) {
                    descriptionFontSize = descriptionFontSizeNum / 2;
                    descriptionFontSize = descriptionFontSize < minDescFont ? minDescFont : descriptionFontSize;
                    description.css('font-size', descriptionFontSize + 'px');
                }
                if (windowW >= desktop) {
                    description.css('font-size', descriptionFontSize + 'px');
                }
            }
        });
    }


    window.addEventListener("load", scaleSliderText);
    window.addEventListener("resize", scaleSliderText);
    window.addEventListener("orientationchange", scaleSliderText);

})(jQuery);