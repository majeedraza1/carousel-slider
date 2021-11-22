// Load stylesheet
if (window.carouselSliderCssUrl) {
	let element = document.querySelector('#carousel-slider-frontend-css');
	if (!element) {
		let elementLink = document.createElement('link');
		elementLink.id = 'carousel-slider-frontend-css';
		elementLink.rel = 'stylesheet'
		elementLink.media = 'all'
		elementLink.href = window.carouselSliderCssUrl
		document.head.append(elementLink);
	}
}
