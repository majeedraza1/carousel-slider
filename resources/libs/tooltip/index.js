import {createPopper} from '@popperjs/core'

class Tooltip {

	/**
	 * Register automatically
	 */
	static register() {
		let elements = document.querySelectorAll(".cs-tooltip, [data-tooltip-target], [data-tooltip]");
		if (elements.length) {
			elements.forEach(element => new Tooltip(element));
		}
	}

	/**
	 * @param {HTMLElement} element
	 * @param {object} options
	 */
	constructor(element, options = {}) {
		this.uuid = this.createUUID();
		this.forElement = this.updateTooltipTargetElement(element);
		this.element = null;
		this.options = Object.assign({
			theme: 'dark',
			html: true,
			container: 'body',
			mainClass: 'shapla-tooltip',
			activeClass: 'is-active',
			removeOnClose: true
		}, options);

		this.popperInstance = null;

		// Initialize instance.
		this.init();
	}

	/**
	 * Initialize element.
	 */
	init() {
		const showEvents = ['mouseenter', 'focus'];
		const hideEvents = ['mouseleave', 'blur'];

		showEvents.forEach(event => {
			this.forElement.addEventListener(event, () => this.show());
		});

		hideEvents.forEach(event => {
			this.forElement.addEventListener(event, () => this.hide());
		});
	}

	/**
	 * Show tooltip
	 */
	show() {
		this.createTooltipElementIfNotExists();

		this.element.classList.add(this.options.activeClass);

		this.popperInstance = createPopper(this.forElement, this.element, {
			modifiers: [
				{
					name: 'offset',
					options: {
						offset: [0, 8],
					},
				},
			],
		});

		// Enable the event listeners
		this.popperInstance.setOptions((options) => ({
			...options,
			modifiers: [
				...options.modifiers,
				{name: 'eventListeners', enabled: true},
			],
		}));

		this.popperInstance.update();
	}

	/**
	 * Hide tooltip
	 */
	hide() {
		this.element.classList.remove(this.options.activeClass);

		// Disable the event listeners
		if (this.popperInstance) {
			this.popperInstance.setOptions((options) => ({
				...options,
				modifiers: [
					...options.modifiers,
					{name: 'eventListeners', enabled: false},
				],
			}));
		}

		if (this.options.removeOnClose) {
			setTimeout(() => this.element.remove(), 10);
		}
	}

	/**
	 * Validate tooltip and tooltip for elements
	 */
	createTooltipElementIfNotExists() {
		this.element = document.querySelector(`[data-tooltip-for="${this.uuid}"]`)

		if (!this.element) {
			let content = this.forElement.getAttribute('data-tooltip') ||
				this.forElement.getAttribute('title');

			this.element = this.createTooltipElement(content);
		}
	}

	/**
	 * Create tooltip element
	 *
	 * @param {string} content
	 * @returns {HTMLDivElement}
	 */
	createTooltipElement(content) {
		// Create arrow element, <div class="tooltip__arrow"></div>
		let arrowElement = document.createElement("div");
		arrowElement.setAttribute('data-popper-arrow', '');
		arrowElement.classList.add(this.options.mainClass + '__arrow');

		// Create arrow element, <div class="tooltip__inner"></div>
		let innerElement = document.createElement("div");
		innerElement.classList.add(this.options.mainClass + '__inner');
		if (this.options.html) {
			innerElement.innerHTML = content;
		} else {
			innerElement.innerText = content;
		}

		// Create main element, <div class="tooltip"></div>
		let mainElement = document.createElement("div");
		mainElement.classList.add(this.options.mainClass);
		mainElement.classList.add(`is-theme-${this.options.theme}`);
		mainElement.setAttribute('data-tooltip-for', this.uuid);
		mainElement.setAttribute('data-remove-on-close', '');
		mainElement.setAttribute('role', 'tooltip');
		mainElement.appendChild(arrowElement);
		mainElement.appendChild(innerElement);

		let containerElement = document.querySelector(this.options.container);
		containerElement.appendChild(mainElement);

		return mainElement;
	}

	/**
	 * Update tooltip for element
	 *
	 * @param {HTMLDivElement} targetElement
	 * @returns {HTMLDivElement}
	 */
	updateTooltipTargetElement(targetElement) {
		let _content = targetElement.getAttribute('data-tooltip') || targetElement.getAttribute('title');
		targetElement.setAttribute('aria-describedby', 'tooltip');
		targetElement.setAttribute('data-tooltip-target', this.uuid);
		targetElement.setAttribute('data-tooltip', _content);
		if (targetElement.hasAttribute('title')) {
			targetElement.removeAttribute('title');
		}
		return targetElement
	}

	/**
	 * Create UUID
	 *
	 * @returns {string}
	 */
	createUUID() {
		const pattern = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx';
		return pattern.replace(/[xy]/g, (c) => {
			const r = (Math.random() * 16) | 0;
			const v = c === 'x' ? r : ((r & 0x3) | 0x8);
			return v.toString(16);
		});
	}
}

export {Tooltip}
export default Tooltip;
