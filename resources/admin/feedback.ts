let list = document.querySelector('#the-list'),
	deactivateLink = list?.querySelector('[data-slug="carousel-slider"] span.deactivate a') as HTMLAnchorElement,
	dialog = document.querySelector('#carousel-slider-deactivate-feedback-dialog-wrapper') as HTMLElement,
	deActivateLink = dialog?.querySelector('.button--skip-feedback') as HTMLAnchorElement,
	submitBtn = dialog?.querySelector('.button--submit-feedback') as HTMLButtonElement,
	form = dialog?.querySelector('form') as HTMLFormElement;

deActivateLink.href = deactivateLink?.getAttribute('href') as string;

deActivateLink.addEventListener('click', () => {
	dialog.removeAttribute('open');
});

submitBtn.addEventListener('click', event => {
	event.preventDefault();
	submitBtn.classList.add('is-loading');

	const sendRequest = () => {
		return new Promise(resolve => {
			let httpRequest = new XMLHttpRequest();
			httpRequest.open('POST', window.ajaxurl);
			httpRequest.onreadystatechange = () => { // Call a function when the state changes.
				if (httpRequest.readyState === XMLHttpRequest.DONE && httpRequest.status === 200) {
					resolve(true);
				}
			}
			httpRequest.send(new FormData(form));
		})
	}

	sendRequest().then(() => {
		deActivateLink.click();
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
