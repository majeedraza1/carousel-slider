class CarouselSlider {
    constructor($) {
        $('body').find('.carousel-slider').each(function () {
            let slider = $(this);

            if (jQuery().owlCarousel) {
                let owl_options = slider.data('owl_carousel');
                if (typeof owl_options !== "undefined") {
                    slider.owlCarousel(owl_options);
                }

                if ('hero-banner-slider' === slider.data('slide_type')) {
                    let animation = slider.data('animation');
                    if (animation.length) {
                        slider.on('change.owl.carousel', function () {
                            slider.find('.carousel-slider-hero__cell__content')
                                .removeClass('animated' + ' ' + animation)
                                .hide();
                        });
                        slider.on('changed.owl.carousel', function (e) {
                            setTimeout(function () {
                                $(e.target).find('.carousel-slider-hero__cell__content')
                                    .eq(e.item.index)
                                    .show()
                                    .addClass('animated' + ' ' + animation);
                            }, slider.data('autoplay-speed'));
                        });
                    }
                }
            }

            if (jQuery().magnificPopup) {
                let popup = slider.data('magnific_popup');
                if (typeof popup !== "undefined") {
                    $(this).magnificPopup(popup);
                }
            }
        });
    }
}

export {CarouselSlider}