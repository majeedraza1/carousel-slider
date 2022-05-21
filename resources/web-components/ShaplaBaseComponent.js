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
   * Trigger 'close' event
   */
  triggerCustomEvent(name) {
    this.dispatchEvent(new CustomEvent(name));
  }
}

export default ShaplaBaseComponent;
