(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    (factory());
}(this, (function () { 'use strict';

    var CarouselSlider = function CarouselSlider($) {
        $('body').find('.carousel-slider').each(function () {
            var _this = $(this);
            var autoWidth = _this.data('auto-width');
            var stagePadding = parseInt(_this.data('stage-padding'));
            stagePadding = stagePadding > 0 ? stagePadding : 0;

            if (jQuery().owlCarousel) {
                _this.owlCarousel({
                    stagePadding: stagePadding,
                    nav: _this.data('nav'),
                    dots: _this.data('dots'),
                    margin: _this.data('margin'),
                    loop: _this.data('loop'),
                    autoplay: _this.data('autoplay'),
                    autoplayTimeout: _this.data('autoplay-timeout'),
                    autoplaySpeed: _this.data('autoplay-speed'),
                    autoplayHoverPause: _this.data('autoplay-hover-pause'),
                    slideBy: _this.data('slide-by'),
                    lazyLoad: _this.data('lazy-load'),
                    autoWidth: autoWidth,
                    navText: [
                        '<svg class="carousel-slider-nav-icon" viewBox="0 0 20 20"><path d="M14 5l-5 5 5 5-1 2-7-7 7-7z"></path></use></svg>',
                        '<svg class="carousel-slider-nav-icon" viewBox="0 0 20 20"><path d="M6 15l5-5-5-5 1-2 7 7-7 7z"></path></svg>'
                    ],
                    responsive: {
                        320: {items: _this.data('colums-mobile')},
                        600: {items: _this.data('colums-small-tablet')},
                        768: {items: _this.data('colums-tablet')},
                        993: {items: _this.data('colums-small-desktop')},
                        1200: {items: _this.data('colums-desktop')},
                        1921: {items: _this.data('colums')}
                    }
                });

                if ('hero-banner-slider' === _this.data('slide-type')) {
                    var animation = _this.data('animation');
                    if (animation.length) {
                        _this.on('change.owl.carousel', function () {
                            var sliderContent = _this.find('.carousel-slider-hero__cell__content');
                            sliderContent.removeClass('animated' + ' ' + animation).hide();
                        });
                        _this.on('changed.owl.carousel', function (e) {
                            setTimeout(function () {
                                var current = $(e.target).find('.carousel-slider-hero__cell__content').eq(e.item.index);
                                current.show().addClass('animated' + ' ' + animation);
                            }, _this.data('autoplay-speed'));
                        });
                    }
                }
            }

            if (jQuery().magnificPopup) {
                if (_this.data('slide-type') === 'product-carousel') {
                    $(this).find('.magnific-popup').magnificPopup({
                        type: 'ajax'
                    });
                } else if ('video-carousel' === _this.data('slide-type')) {
                    $(this).find('.magnific-popup').magnificPopup({
                        type: 'iframe'
                    });
                } else {
                    $(this).find('.magnific-popup').magnificPopup({
                        type: 'image',
                        gallery: {
                            enabled: true
                        },
                        zoom: {
                            enabled: true,
                            duration: 300,
                            easing: 'ease-in-out'
                        }
                    });
                }
            }
        });
    };

    new CarouselSlider(jQuery);

})));
