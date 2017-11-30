(function ($) {
    $('body').find('.carousel-slider').each(function () {
        var _this = $(this);
        var isVideo = _this.data('slide-type') === 'video-carousel';
        var videoWidth = isVideo ? _this.data('video-width') : false;
        var videoHeight = isVideo ? _this.data('video-height') : false;
        var autoWidth = _this.data('auto-width');
        var stagePadding = parseInt(_this.data('stage-padding'));
        autoWidth = isVideo ? isVideo : autoWidth;
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
                video: isVideo,
                videoWidth: videoWidth,
                videoHeight: videoHeight,
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
        }

        if (jQuery().magnificPopup) {
            var popupType = _this.data('slide-type') === 'product-carousel' ? 'ajax' : 'image';
            var popupGallery = _this.data('slide-type') !== 'product-carousel';
            $(this).find('.magnific-popup').magnificPopup({
                type: popupType,
                gallery: {
                    enabled: popupGallery
                },
                zoom: {
                    enabled: popupGallery,
                    duration: 300,
                    easing: 'ease-in-out'
                }
            });
        }
    });
})(jQuery);
