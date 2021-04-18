jQuery('body').find('.carousel-slider').each(function () {

	let _this = jQuery(this),
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
		let animation = _this.data('animation');
		if (animation.length) {
			_this.on('change.owl.carousel', function () {
				let sliderContent = _this.find('.carousel-slider-hero__cell__content');
				sliderContent.removeClass('animated' + ' ' + animation).hide();
			});
			_this.on('changed.owl.carousel', function (e) {
				setTimeout(function () {
					let current = jQuery(e.target).find('.carousel-slider-hero__cell__content').eq(e.item.index);
					current.show().addClass('animated' + ' ' + animation);
				}, _this.data('autoplay-speed'));
			});
		}
	}

	if (jQuery().magnificPopup) {
		if (_this.data('slide-type') === 'product-carousel') {
			jQuery(this).find('.magnific-popup').magnificPopup({
				type: 'ajax'
			});
		} else if ('video-carousel' === _this.data('slide-type')) {
			jQuery(this).find('.magnific-popup').magnificPopup({
				type: 'iframe'
			});
		} else {
			jQuery(this).find('.magnific-popup').magnificPopup({
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
