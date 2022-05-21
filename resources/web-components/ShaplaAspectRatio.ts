import ShaplaBaseComponent from "./ShaplaBaseComponent";

class ShaplaAspectRatio extends ShaplaBaseComponent {
	constructor() {
		super();

		this.attachShadow({mode: 'open'});

		// Create CSS to apply to the shadow DOM
		const style = document.createElement('style');
		style.textContent = ShaplaAspectRatio.getStyle();

		let el = this.getElement() as HTMLElement;
		if (this.hasAttribute('rounded')) {
			el.classList.add('is-rounded');
		}
		if (!this.hasAttribute('width')) {
			el.classList.add('is-fullwidth');
		}
		el.innerHTML = this.innerHTML;

		// attach the created elements to the shadow DOM
		(this.shadowRoot as ShadowRoot).append(style, el);

		this.getDynamicStyle();
	}

	/**
	 * Get component shadow element
	 *
	 * @returns {HTMLButtonElement}
	 */
	getElement() {
		return this.el('div', {class: 'shapla-aspect-ratio'});
	}

	getDynamicStyle() {
		let width = this.getAttribute('width') as string,
			height = this.getAttribute('height') as string,
			widthRatio = this.getAttribute('width-ratio') as string ?? '1',
			heightRatio = this.getAttribute('height-ratio') as string ?? '1';

		const el = (this.shadowRoot as ShadowRoot).querySelector('.shapla-aspect-ratio') as HTMLElement;
		if (el) {
			if (this.hasAttribute('width')) {
				el.style.width = width;
				el.style.height = height ? height : width;
			} else {
				el.style.paddingTop = (100 / parseInt(widthRatio)) * parseInt(heightRatio) + "%";
				el.style.width = '100%';
			}
		}
	}

	static getStyle() {
		return `.shapla-aspect-ratio {  display: block;  position: relative;}
.shapla-aspect-ratio.is-rounded > * {  border-radius: 290486px;}
.shapla-aspect-ratio > * {  display: block;  bottom: 0;  left: 0;  position: absolute;  right: 0;  top: 0; height: 100%;  width: 100%;}`;
	}
}

customElements.define('shapla-aspect-ratio', ShaplaAspectRatio)
