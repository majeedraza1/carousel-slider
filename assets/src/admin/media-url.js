/**
 * Carousel Slider Gallery from URL
 */

const _l10n = window.CarouselSliderAdminL10n,
	$ = jQuery,
	body = $('body'),
	modal = $('#CarouselSliderModal');

let template = `<div class="media-url--column shapla-column is-12">
<div class="carousel_slider-fields media-url-form-field">
	<div class="media-url-form-field__content">
		<label class="setting media-url-form-field__item">
			<span class="name">${_l10n.url}</span>
			<input type="url" name="_images_urls[url][]" value="" autocomplete="off">
		</label>
		<label class="setting media-url-form-field__item">
			<span class="name">${_l10n.title}</span>
			<input type="text" name="_images_urls[title][]" value="" autocomplete="off">
		</label>
		<label class="setting media-url-form-field__item">
			<span class="name">${_l10n.caption}</span>
			<textarea name="_images_urls[caption][]"></textarea>
		</label>
		<label class="setting media-url-form-field__item">
			<span class="name">${_l10n.altText}</span>
			<input type="text" name="_images_urls[alt][]" value="" autocomplete="off">
		</label>
		<label class="setting media-url-form-field__item">
			<span class="name">${_l10n.linkToUrl}</span>
			<input type="text" name="_images_urls[link_url][]" value="" autocomplete="off">
		</label>
	</div>
	<div class="media-url-form-field__actions">
		<span class="move_row"><span class="dashicons dashicons-move"></span></span>
		<span class="add_row"><span class="dashicons dashicons-plus-alt"></span></span>
		<span class="delete_row"><span class="dashicons dashicons-trash"></span></span>
	</div>
</div>
</div>`;

// URL Images Model
$(document).on('click', '#_images_urls_btn', event => {
	event.preventDefault();
	$(document).find('.shapla-modal').addClass('is-active');
	$("body").addClass("overflowHidden");
});

$(document).on('click', '[data-dismiss="shapla-modal"]', event => {
	event.preventDefault();
	$(event.target).closest('.shapla-modal').removeClass('is-active');
	$("body").removeClass("overflowHidden");
});

// Append new row
$(document).on('click', '.add_row', function (event) {
	event.preventDefault();
	let currentColumn = $(this).closest('.media-url--column');
	if (currentColumn.length) {
		currentColumn.after(template);
	} else {
		let columns = modal.find('.media-url--column');
		if (columns.length) {
			columns.last().after(template);
		} else {
			modal.find('#carousel_slider_form').prepend(template);
		}
	}
});

// Delete current row
$(document).on('click', '.delete_row', function () {
	if (confirm("Are you sure to delete")) {
		$(this).closest('.media-url--column').remove();
	}
});

// Make fields sortable
$('#carousel_slider_form').sortable();
