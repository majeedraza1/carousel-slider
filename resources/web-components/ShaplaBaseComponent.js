import {createEl} from "@/utils/misc";

class ShaplaBaseComponent extends HTMLElement {
	/**
	 * Create dynamic element
	 *
	 * @param {string} tagName
	 * @param {object} attributes
	 * @param {array} children
	 * @returns {HTMLElement}
	 */
	el(tagName, attributes = {}, children = []) {
		return createEl(tagName, attributes, children);
	}

	/**
	 * Trigger 'close' event
	 */
	triggerCustomEvent(name) {
		this.dispatchEvent(new CustomEvent(name));
	}
}

export default ShaplaBaseComponent;
