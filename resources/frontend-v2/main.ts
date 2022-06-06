import 'magnific-popup/dist/jquery.magnific-popup.js';
import '../web-components/ShaplaCross.ts'
import '../web-components/ShaplaDialog.ts'
import '../web-components/ShaplaAspectRatio.ts'

import Swiper, {Autoplay, Keyboard, Lazy, Mousewheel, Navigation, Pagination, Scrollbar} from 'swiper';
import {createEl} from "../utils/misc";

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
		modules: [Autoplay, Lazy, Mousewheel, Navigation, Pagination, Scrollbar, Keyboard],
	});

	setTimeout(() => {
		dispatchEvent('CarouselSlider.init', {slider_type: sliderType, swiper: swiper})
	}, 5);

	const findOrCreateDialog = () => {
		let dialog = document.querySelector('#carousel-slider-dialog');
		if (dialog) {
			return dialog;
		}

		let dialog2 = document.createElement('shapla-dialog');
		dialog2.id = 'carousel-slider-dialog';
		dialog2.setAttribute('type', 'lightbox');

		document.body.append(dialog2);

		dialog2.addEventListener('close', () => {
			dialog2.remove();
		})
		return dialog2;
	}

	// support for lightbox
	let links = slider.querySelectorAll('.magnific-popup');
	links.forEach(link => {
		link.addEventListener('click', event => {
			event.preventDefault();
			let modal = findOrCreateDialog();
			if ('video-carousel' === sliderType) {
				let url = link.getAttribute('data-embed_url');
				let aspectRatio = createEl('shapla-aspect-ratio', {
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
				modal.setAttribute('content-size', 'large');
				modal.innerHTML = aspectRatio.outerHTML
			} else if ('product-carousel' === sliderType) {
				const getDialogContent = () => {
					let url = link.getAttribute('href') as string;
					return new Promise(resolve => {
						let xhr = new XMLHttpRequest();
						xhr.addEventListener("load", () => {
							resolve(xhr.responseText as string)
						});
						xhr.open("GET", url);
						xhr.send();
					})
				}
				getDialogContent().then((data: string) => {
					modal.setAttribute('type', 'box');
					// modal.setAttribute('content-size', 'large');
					modal.innerHTML = data;
				})
			} else {
				let url = link.getAttribute('href');
				modal.innerHTML = `<img src="${url}" />`
			}
			modal.setAttribute('open', '');
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
