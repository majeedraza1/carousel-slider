/**
 * Create dynamic element
 *
 * @param {string} tagName
 * @param {object} attributes
 * @param {array} children
 * @returns {HTMLElement}
 */
const createEl = (tagName: string, attributes: Record<string, string> = {}, children: (Node | string)[] = []): Node => {
	let el = document.createElement(tagName);
	if (Object.keys(attributes).length) {
		Object.entries(attributes).forEach(([key, value]) => {
			el.setAttribute(key, value);
		})
	}
	if (children.length) {
		el.append(...children);
	}
	return el;
}

/**
 * Create UUID
 *
 * @returns {string}
 */
const createUUID = (): string => {
	const pattern = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx';
	return pattern.replace(/[xy]/g, (c) => {
		const r = (Math.random() * 16) | 0;
		const v = c === 'x' ? r : ((r & 0x3) | 0x8);
		return v.toString(16);
	});
}

export {createEl, createUUID}
