import '../web-components/ShaplaCross.js'
import '../web-components/ShaplaDialog.js'

/**
 * Create dynamic element
 *
 * @param {string} tagName
 * @param {object} attributes
 * @param {array} children
 * @returns {HTMLElement}
 */
const createEl = (tagName, attributes = {}, children = []) => {
	let el = document.createElement(tagName);
	if (Object.keys(attributes).length) {
		Object.entries(attributes).forEach(([key, value]) => {
			el.setAttribute(key, value);
		})
	}
	if (children.length) {
		el.append(...children);
	}
	return el;
}

/**
 * Add custom radio input for slider type
 *
 * @param {object} args
 * @returns {HTMLElement}
 */
const createSliderTypeChooser = (args) => {
	let icon = createEl('span', {class: 'option-slider-type__icon'},);
	icon.innerHTML = args.icon;
	let inputAttr = {
		type: 'radio',
		name: 'slider_type',
		id: `_slide_type__${args.slug}`,
		class: 'screen-reader-text',
		value: args.slug,
	};
	if (args.enabled === false) {
		inputAttr.disabled = true;
	}
	return createEl('div', {class: 'shapla-column is-6-tablet is-4-desktop is-3-fullhd'}, [
		createEl('input', inputAttr),
		createEl('label', {class: 'option-slider-type', for: `_slide_type__${args.slug}`}, [
			createEl('span', {class: 'option-slider-type__content'}, [
				icon,
				createEl('span', {class: 'option-slider-type__label'}, [args.label]),
				args.pro ? createEl('span', {class: 'option-slider-type__pro'}, ['Pro']) : '',
			])
		])
	])
}

/**
 *
 * @param {HTMLButtonElement} btn
 * @param {string} title
 * @param {string} type
 */
const updateCreateBtnStatus = (btn, title, type) => {
	if (title.length > 2 && type.length) {
		btn.removeAttribute('disabled');
	}
}

// Add carousel
const addNewLinks = document.querySelectorAll("[href*='post-new.php?post_type=carousels']");
if (addNewLinks) {
	const requestData = {title: '', type: ''}

	const btnCreate = createEl('button', {class: 'shapla-button is-primary', disabled: ''}, ['Next']);
	const btnCancel = createEl('button', {class: 'shapla-button'}, ['Cancel']);
	const dialog = createEl('shapla-dialog', {type: 'card', 'content-size': 'large', heading: 'Add New Carousel'}, [
		createEl('div', {slot: 'footer', class: 'cs-flex cs-space-x-1'}, [
			btnCancel,
			btnCreate
		])
	]);
	dialog.addEventListener('close', () => {
		dialog.removeAttribute('open');
	})
	btnCancel.addEventListener('click', () => {
		dialog.removeAttribute('open');
	})
	document.querySelector('body').append(dialog);

	const titleDiv = createEl('div', {class: 'shapla-columns'}, [
		createEl('div', {class: 'shapla-column is-12-tablet'}, [
			createEl('input', {
				type: 'text',
				name: 'slider_title',
				size: 30,
				value: '',
				id: 'title',
				spellcheck: "true",
				autocomplete: 'Off',
				placeholder: 'Add Title',
				class: 'widefat'
			})
		])
	]);

	dialog.append(titleDiv);

	const columnsDiv = createEl('div', {class: 'shapla-columns is-multiline'})
	dialog.append(columnsDiv);
	let typesRadio = [];
	window.CarouselSliderL10n.sliderTypes.forEach(_type => {
		typesRadio.push(createSliderTypeChooser(_type));
	})
	columnsDiv.append(...typesRadio);

	addNewLinks.forEach((element) => {
		element.addEventListener('click', (event) => {
			event.preventDefault();
			dialog.setAttribute('open', '')
		})
	});

	dialog.querySelectorAll('input[name="slider_title"]').forEach(inputEl => {
		inputEl.addEventListener('input', event => {
			requestData.title = event.target.value;
			updateCreateBtnStatus(btnCreate, requestData.title, requestData.type);
		})
	})

	dialog.querySelectorAll('input[name="slider_type"]').forEach(inputEl => {
		inputEl.addEventListener('change', event => {
			requestData.type = event.target.value;
			updateCreateBtnStatus(btnCreate, requestData.title, requestData.type);
		})
	})

	btnCreate.addEventListener('click', event => {
		if (btnCreate.hasAttribute('disabled')) {
			return;
		}
		btnCreate.classList.add('is-loading');

		fetch(window.CarouselSliderL10n.restRoot + '/carousels', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-WP-Nonce': window.CarouselSliderL10n.restNonce
			},
			body: JSON.stringify(requestData),
		})
			.then(response => response.json())
			.then(data => {
				if (data.data.edit_link) {
					let link = document.createElement('a');
					link.href = data.data.edit_link;
					link.click();
				}
			})
			.catch((error) => {
				console.error('Error:', error);
			}).finally(() => {
			btnCreate.classList.remove('is-loading');
		});
	})
}
