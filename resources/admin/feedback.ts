let list = document.querySelector('#the-list'),
	deactivateLink = list?.querySelector('[data-slug="carousel-slider"] span.deactivate a') as HTMLAnchorElement,
	dialog = document.querySelector('#carousel-slider-deactivate-feedback-dialog-wrapper') as HTMLElement,
	skipBtnLink = dialog?.querySelector('.button--skip-feedback') as HTMLAnchorElement,
	submitBtn = dialog?.querySelector('.button--submit-feedback') as HTMLButtonElement,
	form = dialog?.querySelector('form') as HTMLFormElement,
	inputs = form.querySelectorAll('input[type=radio]');

skipBtnLink.href = deactivateLink?.getAttribute('href') as string;

const sendRequest = (body: any = null) => {
	return new Promise(resolve => {
		let xhr = new XMLHttpRequest();
		xhr.open('POST', window.ajaxurl);
		// Call a function when the state changes.
		xhr.addEventListener('readystatechange', () => {
			if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
				resolve(true);
			}
		})
		xhr.send(body);
	})
}

skipBtnLink.addEventListener('click', () => {
	let formData = new FormData(form);
	formData.append('reason_key', 'skip_and_deactivate');
	sendRequest(formData).then(() => {
		// Nothing to do here.
	});
	dialog.removeAttribute('open');
});

submitBtn.addEventListener('click', event => {
	event.preventDefault();
	submitBtn.classList.add('is-loading');

	sendRequest(new FormData(form)).then(() => {
		skipBtnLink.click();
	}).finally(() => {
		submitBtn.classList.add('is-loading');
	})
})

deactivateLink.addEventListener('click', event => {
	event.preventDefault();
	if (dialog) {
		dialog.setAttribute('open', '');
	}
})

dialog.addEventListener('close', () => {
	dialog.removeAttribute('open');
});

inputs.forEach(input => {
	input.addEventListener('change', (event) => {
		if ((event.target as HTMLInputElement).value && submitBtn.hasAttribute('disabled')) {
			submitBtn.removeAttribute('disabled');
		}
	})
})
