import $ from 'jquery';
import Tooltip from "@/libs/tooltip/index.ts";
import '@/libs/tooltip/index.scss';
import '@/libs/color-picker/index.js';
import 'select2/dist/js/select2.js'

import '@/admin/carousels/hero-banner-slider.js';
import '@/admin/carousels/image-carousel.js';
import '@/admin/carousels/image-carousel-url.js';
import '@/admin/carousels/post-carousel.js';
import '@/admin/carousels/product-carousel.js';
import '@/admin/add-new.ts';

let elements = document.querySelectorAll(".cs-tooltip");
if (elements.length) {
	elements.forEach(element => new Tooltip(element, {theme: 'light'}));
}

// Initializing WP Color Picker
$('.color-picker').each(function () {
	$(this).wpColorPicker();
});

// Initializing Select2
$("select.select2").each(function () {
	$(this).select2();
});

// Initializing jQuery UI Accordion
$(".shapla-toggle").each(function () {
	if ($(this).attr('data-id') === 'closed') {
		$(this).accordion({collapsible: true, heightStyle: "content", active: false});
	} else {
		$(this).accordion({collapsible: true, heightStyle: "content"});
	}
});

// Initializing jQuery UI Tab
$(".shapla-tabs").tabs({
	hide: {effect: "fadeOut", duration: 200},
	show: {effect: "fadeIn", duration: 200}
});

// input-copy-to-clipboard
let inputs = document.querySelectorAll('.input-copy-to-clipboard');
inputs.forEach(inputEl => {
	inputEl.addEventListener('click', () => {
		navigator.permissions.query({name: "clipboard-write"}).then((result) => {
			if (result.state === "granted" || result.state === "prompt") {
				navigator.clipboard.writeText(inputEl.innerHTML).then(() => {
					window.console.log('Copied successfully');
				}).catch(error => {
					window.console.log('Fail to copy', error);
				})
			} else {
				window.console.log('ClipBoard API status: ' + result.state);
			}
		});
	})
})

const toggleSlidesPerViewContainer = (value) => {
	let slidesPerViewContainer = document.querySelector('#field-_slides_per_view');
	if (value === 'slider') {
		slidesPerViewContainer.style.display = 'none';
	} else {
		slidesPerViewContainer.style.display = 'block';
	}
}

const toggleAutoWidthContainer = (value) => {
	let autoWidthContainer = document.querySelector('#field-_auto_width');
	if (value === 'slider') {
		autoWidthContainer.style.display = 'none';
	} else {
		autoWidthContainer.style.display = 'block';
	}
}

// Hide "Slides Per View" option if "Slider Type" is "Slider"
document.querySelectorAll('[name="carousel_slider[_type_of_slider]"]').forEach(sliderType => {
	sliderType.addEventListener('change', (event) => {
		toggleSlidesPerViewContainer(event.target.value);
		toggleAutoWidthContainer(event.target.value);
	});
});
document.addEventListener('DOMContentLoaded', () => {
	let sliderType = document.querySelector('[name="carousel_slider[_type_of_slider]"]:checked');
	toggleSlidesPerViewContainer(sliderType.value);
	toggleAutoWidthContainer(sliderType.value);
	let autoWidth = document.querySelector('[type="checkbox"][name="carousel_slider[_auto_width]"]:checked');
	if (autoWidth) {
		toggleSlidesPerViewContainer(autoWidth ? 'slider' : 'carousel');
	}
});
// Hide "Slides Per View" option if "Auto Width" is "on"
document.querySelectorAll('[type="checkbox"][name="carousel_slider[_auto_width]"]').forEach(autoWidth => {
	autoWidth.addEventListener('change', (event) => {
		toggleSlidesPerViewContainer(event.target.checked ? 'slider' : 'carousel');
	});
});
