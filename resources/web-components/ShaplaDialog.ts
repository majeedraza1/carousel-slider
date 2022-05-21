import ShaplaBaseComponent from "./ShaplaBaseComponent";

class ShaplaDialog extends ShaplaBaseComponent {

	/**
	 * Only generate core template on construct
	 * Add rest of the template on connectedCallback method
	 */
	constructor() {
		super();
		this.attachShadow({mode: 'open'});

		// Create CSS to apply to the shadow DOM
		const style = document.createElement('style');
		style.textContent = ShaplaDialog.getStyle();
		(this.shadowRoot as ShadowRoot).append(style, ...this.getWrapperTemplate());
	}

	connectedCallback() {
		const type = this.getAttribute('type');

		if ('card' === type) {
			this.renderCardTemplate();
			const cardFooter = (this.shadowRoot as ShadowRoot).querySelector('.shapla-modal-card__footer') as HTMLElement;
			// @ts-ignore
			if (cardFooter.querySelector('slot').assignedNodes().length < 1) {
				cardFooter.classList.add('no-content')
			}
		}
		if ('confirm' === type) {
			this.updateConfirmDom();
		}

		const cardFixedCross = (this.shadowRoot as ShadowRoot).querySelector('.shapla-modal-close.is-fixed') as HTMLElement;

		// Add card type class.
		const elContent = (this.shadowRoot as ShadowRoot).querySelector('.shapla-modal-content') as HTMLElement;

		if ('confirm' === type) {
			cardFixedCross.remove();
			elContent.classList.add('shapla-modal-confirm');
			elContent.innerHTML = '';
			elContent.append(...this.getConfirmTemplate());
		} else if ('box' === type) {
			elContent.classList.add('shapla-modal-box');
		}

		const cardBackdrop = (this.shadowRoot as ShadowRoot).querySelector('.shapla-modal-background') as HTMLElement;
		const backgroundTheme = this.getAttribute('backdrop-theme') as string;
		if (["dark", "light"].indexOf(backgroundTheme) !== -1) {
			cardBackdrop.classList.add(`is-${backgroundTheme}`)
		}

		this.updateContentSize();

		// Trigger 'close' event on Esc keydown
		this.closeOnEscape();
		// Trigger 'close' event on backdrop click
		this.closeOnBackdropClick();
		// Trigger 'close' event on cross click
		this.closeOnCrossClick();
	}

	renderCardTemplate() {
		const cardFixedCross = (this.shadowRoot as ShadowRoot).querySelector('.shapla-modal-close.is-fixed') as HTMLElement;
		const elContent = (this.shadowRoot as ShadowRoot).querySelector('.shapla-modal-content') as HTMLElement;

		// Add card type class.
		cardFixedCross.remove();

		elContent.classList.add('shapla-modal-card');
		elContent.innerHTML = '';
		elContent.append(...this.getCartTemplate());

		const heading = this.getAttribute('heading') as string;
		if (heading) {
			// @ts-ignore
			(this.shadowRoot as ShadowRoot).querySelector('.shapla-modal-card__title').innerHTML = heading;
		}
	}

	updateContentSize() {
		const contentSize = this.getAttribute('content-size') as string;
		if (["small", "medium", "large", "full", 'custom'].indexOf(contentSize) !== -1) {
			// @ts-ignore
			(this.shadowRoot as ShadowRoot).querySelector('.shapla-modal-content').classList.add(`is-${contentSize}`);
		}
	}

	updateConfirmDom() {
		const icon = this.getAttribute('icon') ?? 'primary';
		const title = this.getAttribute('heading') as string;
		const message = this.getAttribute('message') as string ?? 'Are you sure?';
		const confirmButtonText = this.getAttribute('confirm-button') ?? 'Ok';
		const cancelButtonText = this.getAttribute('cancel-button') ?? 'Cancel';

		const confirmBtn = (this.shadowRoot as ShadowRoot).querySelector('.button--confirm') as HTMLButtonElement;
		const cancelBtn = (this.shadowRoot as ShadowRoot).querySelector('.button--cancel') as HTMLButtonElement;

		if (confirmButtonText) {
			confirmBtn.innerHTML = confirmButtonText;
		}

		if (cancelButtonText) {
			cancelBtn.innerHTML = cancelButtonText;
		}

		const contentDom = (this.shadowRoot as ShadowRoot).querySelector('.shapla-modal-confirm') as HTMLElement;

		if (["primary", "success", "error"].indexOf(icon) !== -1) {
			contentDom.querySelector('.shapla-modal-confirm__icon')?.classList.add(`is-${icon}`)
		}

		if (!this.hasAttribute('content-size')) {
			this.setAttribute('content-size', 'small');
			this.updateContentSize();
		}

		if (!this.hasAttribute('disabled-backdrop-click')) {
			this.setAttribute('disabled-backdrop-click', '');
		}

		if (title.length) {
			// @ts-ignore
			contentDom.querySelector('.shapla-modal-confirm__title').innerHTML = title;
		}

		if (message.length) {
			// @ts-ignore
			contentDom.querySelector('.shapla-modal-confirm__message').innerHTML = message;
		}

		this.closeOnCrossClick();
	}

	closeOnCrossClick() {
		((this.shadowRoot as ShadowRoot).querySelectorAll('.shapla-modal-close, .button--cancel') || []).forEach(cardCross => {
			cardCross.addEventListener('click', () => this.triggerCloseEvent());
		})
	}

	closeOnBackdropClick() {
		const cardBackdrop = (this.shadowRoot as ShadowRoot).querySelector('.shapla-modal-background') as HTMLElement
		if (!this.hasAttribute('disabled-backdrop-click')) {
			if ('confirm' !== this.getAttribute('type') as string) {
				cardBackdrop.addEventListener('click', () => this.triggerCloseEvent());
			}
		}
	}

	/**
	 * Close on escape
	 */
	closeOnEscape() {
		// Add a keyboard event to close all modals
		document.addEventListener('keydown', (event) => {
			const e = event || window.event;

			if (e.keyCode === 27 && this.hasAttribute('open')) { // Escape key
				this.triggerCloseEvent();
			}
		});
	}

	/**
	 * Trigger 'close' event
	 */
	triggerCloseEvent() {
		this.triggerCustomEvent('close');
	}

	/**
	 * Update dom when attribute changed
	 *
	 * @param {string} name
	 * @param {any} oldValue
	 * @param {any} newValue
	 */
	attributeChangedCallback(name: string, oldValue: string, newValue: string) {
		const modal = (this.shadowRoot as ShadowRoot).querySelector('.shapla-modal') as HTMLElement;
		if ('open' === name && this.hasAttribute('open')) {
			modal.classList.add('is-active')
		} else {
			modal.classList.remove('is-active')
		}
	}

	/**
	 * List of attribute to observe
	 *
	 * @returns {string[]}
	 */
	static get observedAttributes() {
		return ['open', 'content-size'];
	}

	/**
	 * Get element main Wrapper Template
	 *
	 * @returns {HTMLElement[]}
	 */
	getWrapperTemplate() {
		const el = this.el('div', {class: 'shapla-modal'}, [
			this.el('div', {class: 'shapla-modal-background'}),
			this.el('shapla-cross', {class: 'shapla-modal-close is-fixed', size: 'large'}),
			this.el('div', {class: 'shapla-modal-content'}, [
				this.el('slot')
			])
		])
		return [el];
	}

	/**
	 * Get card template
	 *
	 * @returns {HTMLElement[]}
	 */
	getCartTemplate() {
		let header = this.el('header', {class: 'shapla-modal-card__header'}, [
			this.el('div', {class: 'shapla-modal-card__title'}, [
				this.el('slot', {name: 'heading'})
			]),
			this.el('shapla-cross', {class: 'shapla-modal-close', size: 'medium'})
		]);

		let body = this.el('section', {class: 'shapla-modal-card__body'}, [
			this.el('slot')
		])

		let footer = this.el('footer', {class: 'shapla-modal-card__footer is-pulled-right'}, [
			this.el('slot', {name: 'footer'})
		])

		return [header, body, footer];
	}

	/**
	 * Get confirm template
	 *
	 * @returns {HTMLElement[]}
	 */
	getConfirmTemplate() {
		let elContent = this.el('div', {class: 'shapla-modal-confirm__content'}, [
			this.el('div', {class: 'shapla-modal-confirm__icon'}, [
				this.el('div', {class: 'shapla-modal-confirm__icon-content'}, ['!'])
			]),
			this.el('h3', {class: 'shapla-modal-confirm__title'}),
			this.el('div', {class: 'shapla-modal-confirm__message'}),
		])

		let elAction = this.el('div', {class: 'shapla-modal-confirm__actions'}, [
			this.el('slot', {name: 'actions'}, [
				this.el('button', {class: 'shapla-button button--cancel'}),
				this.el('button', {class: 'shapla-button is-primary button--confirm'}),
			])
		])

		return [elContent, elAction];
	}

	static getStyle() {
		return `.shapla-modal,.shapla-modal-background{bottom:0;left:0;position:absolute;right:0;top:0}
    .shapla-modal{align-items:center;display:none;flex-direction:column;justify-content:center;overflow:hidden;
    position:fixed;z-index:var(--modal-z-index,100000)}
    .shapla-modal.is-active{display:flex}
    .shapla-modal-background{background-color:var(--modal-backdrop-color,rgba(0,0,0,.5))}
    .shapla-modal-background.is-light{--modal-backdrop-color:var(--modal-backdrop-color-light,hsla(0,0%,100%,.5))}
    .shapla-modal .shapla-delete-icon.is-fixed,.shapla-modal .shapla-modal-close.is-fixed{
    position:fixed;right:var(--modal-close-right,1.25rem);top:var(--modal-close-top,1.25rem)}
    .shapla-modal-content{margin:0 var(--modal-content-margin,20px);
    max-height:calc(100vh - var(--modal-content-spacing, 160px));overflow:auto;position:relative;
    width:var(--modal-content-width,calc(100% - var(--modal-content-margin, 20px)*2))}
    .shapla-modal-content.is-small{--modal-content-width:var(--modal-content-width-small,320px)}
    .shapla-modal-content.is-full{height:calc(100vh - var(--modal-content-margin, 20px)*2);
    width:calc(100vw - var(--modal-content-margin, 20px)*2)}
    @media print,screen and (min-width:768px){
    .shapla-modal-content{--modal-content-spacing:40px;margin:0 auto}
    .shapla-modal-content:not(.is-small):not(.is-full):not(.is-large){
    --modal-content-width:var(--modal-content-width-medium,640px)}}
    @media screen and (min-width:1024px){
    .shapla-modal-content.is-large{--modal-content-width:var(--modal-content-width-large,960px)}}
    .shapla-modal-card{display:flex;flex-direction:column;max-height:calc(100vh - 40px);overflow:hidden}
    .shapla-modal-card__footer,.shapla-modal-card__header{align-items:center;background-color:#fff;display:flex;
    flex-shrink:0;justify-content:flex-start;padding:1rem;position:relative}
    .shapla-modal-card__footer>*+*,.shapla-modal-card__header>*+*{margin-left:.5rem}
    .shapla-modal-card__header{border-bottom:1px solid rgba(0,0,0,.12);border-top-left-radius:4px;
    border-top-right-radius:4px}
    .shapla-modal-card__title{flex-grow:1;flex-shrink:0;font-size:1.5rem;font-weight:400;line-height:1;margin:0}
    .shapla-modal-card__footer{border-bottom-left-radius:4px;border-bottom-right-radius:4px;
    border-top:1px solid rgba(0,0,0,.12)}
    .shapla-modal-card__footer.is-pulled-right{justify-content:flex-end}
    .shapla-modal-card__footer.no-content{border-top:none;padding:2px}
    .shapla-modal-card__body{background-color:#fff;flex-grow:1;flex-shrink:1;overflow:auto;padding:1rem}
    .shapla-modal-box,.shapla-modal-confirm{background-color:#fff;border-radius:4px;
    box-shadow:0 9px 46px 8px rgba(0,0,0,.14),0 11px 15px -7px rgba(0,0,0,.12),0 24px 38px 3px rgba(0,0,0,.2);padding:1rem}
    .shapla-modal-confirm__content{padding:1rem;text-align:center}
    .shapla-modal-confirm__icon{border:.25em solid var(--shapla-primary,#0d6efd);border-radius:50%;
    color:var(--shapla-primary,#0d6efd);cursor:default;display:flex;height:5em;justify-content:center;
    margin:1.25em auto 1.875em;-webkit-user-select:none;user-select:none;width:5em}
    .shapla-modal-confirm__icon.is-success{border-color:var(--shapla-success,#198754);color:var(--shapla-success,#198754)}
    .shapla-modal-confirm__icon.is-error{border-color:var(--shapla-error,#dc3545);color:var(--shapla-error,#dc3545)}
    .shapla-modal-confirm__icon-content{align-items:center;display:flex;font-size:3.75em}
    .shapla-modal-confirm__title{font-size:1.875em;margin:0 0 .4em;text-align:center}
    .shapla-modal-confirm__actions{display:flex;justify-content:center;padding:1rem}
    .shapla-modal-confirm__actions>*+*{margin-left:.5rem}`;
	}
}

customElements.define('shapla-dialog', ShaplaDialog);
