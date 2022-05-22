let list = document.querySelector('#the-list'),
	deactivateLink = list?.querySelector('[data-slug="carousel-slider"] span.deactivate a') as HTMLAnchorElement,
	dialog = document.querySelector('#carousel-slider-deactivate-feedback-dialog-wrapper') as HTMLDialogElement,
	cross = dialog?.querySelector('.feedback-dialog__cross') as HTMLElement,
	footer = dialog?.querySelector('.feedback-dialog__footer') as HTMLElement,
	form = dialog?.querySelector('form') as HTMLFormElement,
	inputs = dialog?.querySelectorAll('input[type=radio]') as NodeListOf<HTMLInputElement>;

const deActivateLink = document.createElement('a');
deActivateLink.classList.add('button--skip-feedback');
deActivateLink.textContent = 'Skip & Deactivate'
deActivateLink.href = deactivateLink?.getAttribute('href') as string;

const submitBtn = document.createElement('button');
submitBtn.classList.add('shapla-button', 'is-primary', 'is-small','button--submit-feedback');
submitBtn.textContent = 'Submit & Deactivate'

footer?.append(deActivateLink, submitBtn);

deActivateLink.addEventListener('click', () => {
	// @ts-ignore - TS dom lib need to be updated
	dialog.close();
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
		// @ts-ignore - TS dom lib need to be updated
		dialog.showModal();
	}
})

cross.addEventListener('click', () => {
	// @ts-ignore - TS dom lib need to be updated
	dialog.close();
});
