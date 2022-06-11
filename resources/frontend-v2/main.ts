import '../web-components/ShaplaCross.ts'
import '../web-components/ShaplaDialog.ts'
import '../web-components/ShaplaAspectRatio.ts'

import Swiper, {
	Autoplay, Keyboard, Lazy, Mousewheel, Navigation, Pagination, Scrollbar,
	EffectFade, EffectCube, EffectCoverflow, EffectFlip, EffectCards, EffectCreative
} from 'swiper';
import {createEl} from "../utils/misc";

const dispatchEvent = (type: string, detail: any) => {
	document.dispatchEvent(new CustomEvent(type, {detail: detail}))
}

const getAjaxContent = (url: string): Promise<string> => {
	return new Promise(resolve => {
		let xhr = new XMLHttpRequest();
		xhr.addEventListener("load", () => {
			resolve(xhr.responseText as string)
		});
		xhr.open("GET", url);
		xhr.send();
	})
}

const findOrCreateDialog = (size: string = 'medium', type: string = 'lightbox') => {
	let dialog = document.querySelector('#carousel-slider-dialog');
	if (dialog) {
		return dialog;
	}

	let dialog2 = createEl('shapla-dialog', {
		'id': 'carousel-slider-dialog',
		'type': type,
		'content-size': size,
	});

	document.body.append(dialog2);

	dialog2.addEventListener('close', () => {
		dialog2.remove();
	})
	return dialog2;
}

const sliders = document.querySelectorAll('.swiper') as NodeListOf<HTMLElement>;
sliders.forEach((slider: HTMLElement) => {
	const swiperWrapperEl = (slider.querySelector('[data-swiper]') as HTMLElement);
	const swiperSettingsString = swiperWrapperEl.getAttribute('data-swiper');
	const sliderType = swiperWrapperEl.getAttribute('data-slide-type');

	const swiperSettings = JSON.parse(swiperSettingsString as string);
	const swiper = new Swiper(slider, {
		...swiperSettings,
		modules: [
			Autoplay, Lazy, Mousewheel, Navigation, Pagination, Scrollbar, Keyboard,
			EffectFade, EffectCube, EffectCoverflow, EffectFlip, EffectCards, EffectCreative
		],
	});

	setTimeout(() => {
		dispatchEvent('CarouselSlider.init', {slider_type: sliderType, swiper: swiper})
	}, 5);

	// support for lightbox
	let links = slider.querySelectorAll('.magnific-popup');
	links.forEach(link => {
		link.addEventListener('click', event => {
			event.preventDefault();
			if ('video-carousel' === sliderType) {
				let modal = findOrCreateDialog('large');
				modal.setAttribute('open', '');
				let url = link.getAttribute('data-embed_url');
				let aspectRatio = createEl('div', {class: 'dialog--video-carousel'}, [
					createEl('shapla-aspect-ratio', {
						'width-ratio': '16',
						'height-ratio': '9'
					}, [
						createEl('iframe', {
							class: 'cs-iframe',
							src: `${url}`,
							frameborder: '0',
							allowfullscreen: '',
						})
					])
				])
				modal.innerHTML = aspectRatio.outerHTML
			} else if ('product-carousel' === sliderType) {
				let modal = findOrCreateDialog('large', 'box');
				let url = link.getAttribute('href') as string;
				modal.innerHTML = '<div class="dialog--loading">Loading...</div>';
				getAjaxContent(url).then((data: string) => {
					let el = createEl('div', {class: 'dialog--product-carousel mfp-content'});
					el.innerHTML = data;
					modal.setAttribute('open', '');
					modal.innerHTML = el.outerHTML;
				})
				modal.setAttribute('open', '');
			} else {
				let modal = findOrCreateDialog();
				modal.setAttribute('open', '');
				let url = link.getAttribute('href') as string,
					width = link.getAttribute('data-width') as string,
					height = link.getAttribute('data-height') as string;
				let el = createEl('div', {class: 'dialog--image-carousel'}, [
					createEl('shapla-aspect-ratio', {
						'width-ratio': width,
						'height-ratio': height
					}, [
						createEl('img', {src: url})
					])
				]);
				modal.innerHTML = el.outerHTML;
			}
		})
	})
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
