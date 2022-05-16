import $ from 'jquery';

let deactivateLink = $('#the-list').find('[data-slug="carousel-slider"] span.deactivate a');
let dialog = document.querySelector('#carousel-slider-deactivate-feedback-dialog-wrapper'),
	cross = dialog.querySelector('.feedback-dialog__cross'),
	footer = dialog.querySelector('.feedback-dialog__footer'),
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
	console.log('send data to remote server.')
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
