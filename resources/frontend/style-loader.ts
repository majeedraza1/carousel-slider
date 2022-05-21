import {createEl} from "../utils/misc";

declare global {
	interface Window {
		ajaxurl: string;
		carouselSliderCssUrl: string;
	}
}

// Load stylesheet
if (window.carouselSliderCssUrl) {
	let element = document.querySelector('#carousel-slider-frontend-css');
	if (!element) {
		let elementLink = createEl('link', {
			id: 'carousel-slider-frontend-css',
			rel: 'stylesheet',
			media: 'all',
			href: window.carouselSliderCssUrl,
		});
		document.head.append(elementLink);
	}
}
