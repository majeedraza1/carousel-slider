import $ from 'jquery';

let deactivateLink = $('#the-list').find('[data-slug="carousel-slider"] span.deactivate a');
let dialog = document.querySelector('#carousel-slider-deactivate-feedback-dialog-wrapper'),
	cross = dialog.querySelector('.feedback-dialog__cross'),
	footer = dialog.querySelector('.feedback-dialog__footer'),
	form = dialog.querySelector('form'),
	inputs = dialog.querySelectorAll('input[type=radio]');

const deActivateLink = document.createElement('a');
deActivateLink.classList.add('button--skip-feedback');
deActivateLink.textContent = 'Skip & Deactivate'
deActivateLink.href = deactivateLink.attr('href');

const submitBtn = document.createElement('button');
submitBtn.classList.add('button', 'button-primary', 'button--submit-feedback');
submitBtn.textContent = 'Submit & Deactivate'

footer.append(deActivateLink, submitBtn);

submitBtn.addEventListener('click', event => {
	event.preventDefault();

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
	})
})

deactivateLink.on('click', event => {
	event.preventDefault();
	if (dialog) {
		dialog.showModal();
	}
})

cross.addEventListener('click', () => {
	dialog.close();
});

inputs.forEach(input => {
	input.addEventListener('change', event => {
		console.log(event.target.value);
	})
})
