import {createPopper} from '@popperjs/core'
import {createEl, createUUID} from "@/utils/misc";

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
		this.uuid = createUUID();
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
		let arrowElement = createEl('div', {
			'data-popper-arrow': '',
			class: this.options.mainClass + '__arrow'
		});

		// Create arrow element, <div class="tooltip__inner"></div>
		let innerElement = createEl("div", {class: this.options.mainClass + '__inner'});
		if (this.options.html) {
			innerElement.innerHTML = content;
		} else {
			innerElement.innerText = content;
		}

		// Create main element, <div class="tooltip"></div>
		let mainElement = createEl("div",
			{
				'data-tooltip-for': this.uuid,
				'data-remove-on-close': '',
				role: 'tooltip',
				class: `${this.options.mainClass} is-theme-${this.options.theme}`
			},
			[arrowElement, innerElement]
		);

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
}

export {Tooltip}
export default Tooltip;
