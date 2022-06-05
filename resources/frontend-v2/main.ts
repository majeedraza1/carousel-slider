import Swiper, {Navigation, Pagination, Scrollbar, Autoplay, Lazy} from 'swiper';

const dispatchEvent = (type: string, detail: any) => {
	document.dispatchEvent(new CustomEvent(type, {detail: detail}))
}

const sliders = document.querySelectorAll('.swiper') as NodeListOf<HTMLElement>;
sliders.forEach((slider: HTMLElement) => {
	const swiperWrapperEl = (slider.querySelector('[data-swiper]') as HTMLElement);
	const swiperSettingsString = swiperWrapperEl.getAttribute('data-swiper');
	const sliderType = swiperWrapperEl.getAttribute('data-slide-type');

	const swiperSettings = JSON.parse(swiperSettingsString as string);
	const swiper = new Swiper(slider, {
		...swiperSettings,
		modules: [Navigation, Pagination, Scrollbar, Autoplay, Lazy],
	});

	setTimeout(() => {
		dispatchEvent('CarouselSlider.init', {slider_type: sliderType, swiper: swiper})
	}, 5)
})

document.addEventListener('CarouselSlider.init', (event: CustomEventInit) => {
	const swiper = event.detail.swiper;
	if ('hero-banner-slider' === event.detail.slider_type) {
		const addAnimation = (el: HTMLElement) => {
			let content = el.querySelector('.carousel-slider-hero__cell__content') as HTMLElement,
				animation = content.getAttribute('data-animation') as string;
			content.classList.remove('hidden');
			content.classList.add('animated', animation);
		}

		const removeAllAnimation = () => {
			return new Promise(resolve => {
				swiper.el.querySelectorAll('.animated')?.forEach((cellContentEl: HTMLElement) => {
					let animation = cellContentEl.getAttribute('data-animation') as string;
					cellContentEl.classList.remove('animated', animation)
					cellContentEl.classList.add('hidden')
				})
				resolve(true);
			})
		}

		addAnimation(swiper.slides[swiper.activeIndex]);
		swiper.on('slideChangeTransitionEnd', () => {
			removeAllAnimation().then(() => {
				addAnimation(swiper.slides[swiper.activeIndex]);
			})
		});
	}
})


