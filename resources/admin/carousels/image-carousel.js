/** global: wp */
import $ from 'jquery';

let frame,
	_this = $('#carousel_slider_gallery_btn'),
	images = _this.data('ids'),
	selection = loadImages(images);

const updateDom = (ids_string, gallery_html) => {
	$('#_carousel_slider_images_ids').val(ids_string);
	$('.carousel_slider_gallery_list').html(gallery_html);
	document.body.dispatchEvent(new CustomEvent('CarouselSlider.refresh.preview'))
}

_this.on('click', function (e) {
	e.preventDefault();
	let options = {
		title: _this.data('create'),
		state: 'gallery-edit',
		frame: 'post',
		selection: selection
	};

	if (frame || selection) {
		options['title'] = _this.data('edit');
	}

	frame = wp.media(options).open();

	// Tweak Views
	frame.menu.get('view').unset('cancel');
	frame.menu.get('view').unset('separateCancel');
	frame.menu.get('view').get('gallery-edit').el.innerHTML = _this.data('edit');
	frame.content.get('view').sidebar.unset('gallery'); // Hide Gallery Settings in sidebar

	// when editing a gallery
	overrideGalleryInsert()
	frame.on('toolbar:render:gallery-edit', function () {
		overrideGalleryInsert()
	});

	frame.on('content:render:browse', function (browser) {
		if (!browser) return;
		// Hide Gallery Settings in sidebar
		browser.sidebar.on('ready', function () {
			browser.sidebar.unset('gallery');
		});
		// Hide filter/search as they don't work
		browser.toolbar.on('ready', function () {
			if (browser.toolbar.controller._state === 'gallery-library') {
				browser.toolbar.$el.hide();
			}
		});
	});

	// All images removed
	frame.state().get('library').on('remove', function () {
		let models = frame.state().get('library');
		if (models.length === 0) {
			selection = false;
			updateDom('', '');
		}
	});
});

const onClickModalSaveButton = () => {
	let models = frame.state().get('library'),
		ids = [],
		html = '';

	models.each(function (attachment) {
		ids.push(attachment.id);
		let src = attachment.attributes.sizes.thumbnail || attachment.attributes.sizes.full;
		html += `<li><img src="${src.url}" width="50" height="50" class="attachment-50x50 size-50x50" loading="lazy"></li>`;
	});

	selection = loadImages(ids.toString());
	frame.close();
	updateDom(ids.toString(), html);
}

function overrideGalleryInsert() {
	frame.toolbar.get('view').set({
		insert: {
			style: 'primary',
			text: _this.data('save'),
			click: () => onClickModalSaveButton()
		}
	});
}

function loadImages(images) {
	if (!images) {
		return false;
	}

	if (typeof images !== 'string') {
		images = images.toString();
	}

	let shortcode = new wp.shortcode({
		tag: 'gallery',
		attrs: {ids: images},
		type: 'single'
	});

	let attachments = wp.media.gallery.attachments(shortcode);

	let selection = new wp.media.model.Selection(attachments.models, {
		props: attachments.props.toJSON(),
		multiple: true
	});

	selection.gallery = attachments.gallery;

	selection.more().done(function () {
		// Break ties with the query.
		selection.props.set({query: false});
		selection.unmirror();
		selection.props.unset('orderby');
	});

	return selection;
}
