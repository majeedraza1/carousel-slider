const $ = window.jQuery;

// Initializing Select2
$("select.select2").each(function () {
	$(this).select2();
});

// Initializing jQuery UI Accordion
$(".shapla-toggle").each(function () {
	if ($(this).attr('data-id') === 'closed') {
		$(this).accordion({
			collapsible: true,
			heightStyle: "content",
			active: false
		});
	} else {
		$(this).accordion({
			collapsible: true,
			heightStyle: "content"
		});
	}
});

// Initializing jQuery UI Tab
$(".shapla-tabs").tabs({
	hide: {
		effect: "fadeOut",
		duration: 200
	},
	show: {
		effect: "fadeIn",
		duration: 200
	}
});

// Initializing WP Color Picker
$('.color-picker').each(function () {
	$(this).wpColorPicker();
});
