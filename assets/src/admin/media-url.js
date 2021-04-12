/**
 * Carousel Slider Gallery from URL
 */

const _l10n = window.CarouselSliderAdminL10n;
let $ = jQuery, body = $('body'), modal = $('#CarouselSliderModal'), modalOpenBtn = $('#_images_urls_btn');

let template = `<div class="media-url--column shapla-column is-4">
<div class="carousel_slider-fields">
	<label class="setting">
			<span class="name">${_l10n.url}</span>
			<input type="url" name="_images_urls[url][]" value="" autocomplete="off">
	</label>
	<label class="setting">
			<span class="name">${_l10n.title}</span>
			<input type="text" name="_images_urls[title][]" value="" autocomplete="off">
	</label>
	<label class="setting">
			<span class="name">${_l10n.caption}</span>
			<textarea name="_images_urls[caption][]"></textarea>
	</label>
	<label class="setting">
			<span class="name">${_l10n.altText}</span>
			<input type="text" name="_images_urls[alt][]" value="" autocomplete="off">
	</label>
	<label class="setting">
			<span class="name">${_l10n.linkToUrl}</span>
			<input type="text" name="_images_urls[link_url][]" value="" autocomplete="off">
	</label>
	<div class="actions">
			<span><span class="dashicons dashicons-move"></span></span>
			<span class="add_row"><span class="dashicons dashicons-plus-alt"></span></span>
			<span class="delete_row"><span class="dashicons dashicons-trash"></span></span>
	</div>
</div>
</div>`;

// URL Images Model
modalOpenBtn.on('click', event => {
	event.preventDefault();
	modal.css("display", "block");
	$("body").addClass("overflowHidden");
});
modal.on('click', '.carousel_slider-close', event => {
	event.preventDefault();
	modal.css("display", "none");
	$("body").removeClass("overflowHidden");
});

let carouselSliderBodyHeight = $(window).height() - (38 + 48 + 32 + 30);
$('.carousel_slider-modal-body').css('height', carouselSliderBodyHeight + 'px');

// Append new row
body.on('click', '.add_row', function () {
	$(this).closest('.media-url--column').after(template);
});

// Delete current row
body.on('click', '.delete_row', function () {
	if (confirm("Are you sure to delete")) {
		$(this).closest('.media-url--column').remove();
	}
});

// Make fields sortable
$('#carousel_slider_form').sortable();
