import Tooltip from "@/libs/tooltip/index.js";
import '@/libs/tooltip/index.scss';
import '@/libs/color-picker/index.js';
import 'select2/dist/js/select2.js'
import $ from 'jquery';

let elements = document.querySelectorAll(".cs-tooltip");
if (elements.length) {
	elements.forEach(element => new Tooltip(element));
}

// Initializing WP Color Picker
$('.color-picker').wpColorPicker();

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
