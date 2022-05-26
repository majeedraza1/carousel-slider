import 'owl.carousel/dist/owl.carousel.js';
import 'magnific-popup/dist/jquery.magnific-popup.js';
import $ from 'jquery';

$('body').find('.carousel-slider').each(function () {

	let _this = $(this),
		owlSettings = _this.data('owl-settings');

	if (typeof owlSettings === "object") {
		Object.assign(owlSettings, {
			navText: [
				'<svg class="carousel-slider-nav-icon" viewBox="0 0 20 20"><path d="M14 5l-5 5 5 5-1 2-7-7 7-7z"/></svg>',
				'<svg class="carousel-slider-nav-icon" viewBox="0 0 20 20"><path d="M6 15l5-5-5-5 1-2 7 7-7 7z"/></svg>'
			]
		})
	}

	_this.owlCarousel(owlSettings);

	if ('hero-banner-slider' === _this.data('slide-type')) {
		_this.on('change.owl.carousel', function () {
			let sliderContent = _this.find('.carousel-slider-hero__cell__content'),
				_animation = sliderContent.data('animation');
			if (_animation) {
				sliderContent.removeClass('animated' + ' ' + _animation).hide();
			}
		});
		_this.on('changed.owl.carousel', function (e) {
			let current = jQuery(e.target).find('.carousel-slider-hero__cell__content').eq(e.item.index),
				_animation = current.data('animation');
			if (_animation) {
				setTimeout(function () {
					current.show().addClass('animated' + ' ' + _animation);
				}, owlSettings.autoplaySpeed);
			}
		});
	}

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
});
