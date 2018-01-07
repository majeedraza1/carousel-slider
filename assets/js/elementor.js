(function ($) {
    'use strict';

    var CarouselSliderHeroCarousel = function ($scope, $) {
        var slider_elem = $scope.find('.elementor-slides').eq(0);
        var settings = slider_elem.data('slider_options');
        var animation = slider_elem.data('animation');

        if (!slider_elem.length) {
            return;
        }

        slider_elem.slick(settings);

        slider_elem.on({
            beforeChange: function () {
                var sliderContent = slider_elem.find('.elementor-slide-content');
                sliderContent.removeClass('animated' + ' ' + animation).hide();
            },
            afterChange: function (event, slick, currentSlide) {
                var $currentSlide = jQuery(slick.$slides.get(currentSlide)).find('.elementor-slide-content');
                $currentSlide
                    .show()
                    .addClass('animated' + ' ' + animation);
            }
        });
    };

    var CarouselSliderTestimonialCarousel = function ($scope, $) {

        var slider_elem = $scope.find('.elementor-main-swiper').eq(0);
        var settings = slider_elem.data('settings');

        console.log(slider_elem);
        console.log(settings);

        var selectors = {
            mainSwiper: '.elementor-main-swiper',
            swiperSlide: '.swiper-slide'
        };

        var slidesPerView = {
            desktop: 3,
            tablet: 2,
            mobile: 1
        };


        new Swiper('.elementor-main-swiper', {
            pagination: {
                el: '.swiper-pagination'
            },
            navigation: {
                nextEl: '.elementor-swiper-button-next',
                prevEl: '.elementor-swiper-button-prev'
            }
        });
    };

    // Make sure you run this code under Elementor..
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/carousel-slider-hero-carousel.default', CarouselSliderHeroCarousel);
        elementorFrontend.hooks.addAction('frontend/element_ready/carousel-slider-testimonial-carousel.default', CarouselSliderTestimonialCarousel);
    });

})(jQuery);
