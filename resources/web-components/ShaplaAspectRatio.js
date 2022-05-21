import ShaplaBaseComponent from "./ShaplaBaseComponent.js";

class ShaplaAspectRatio extends ShaplaBaseComponent {
  constructor() {
    super();

    this.attachShadow({mode: 'open'});

    // Create CSS to apply to the shadow DOM
    const style = document.createElement('style');
    style.textContent = ShaplaAspectRatio.getStyle();

    let el = this.getElement();
    if (this.hasAttribute('rounded')) {
      el.classList.add('is-rounded');
    }
    if (!this.hasAttribute('width')) {
      el.classList.add('is-fullwidth');
    }
    el.innerHTML = this.innerHTML;

    // attach the created elements to the shadow DOM
    this.shadowRoot.append(style, el);

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
    let width = this.getAttribute('width'),
      height = this.getAttribute('height'),
      widthRatio = this.getAttribute('width-ratio') ?? 1,
      heightRatio = this.getAttribute('height-ratio') ?? 1;

    const el = this.shadowRoot.querySelector('.shapla-aspect-ratio');
    if (this.hasAttribute('width')) {
      el.style.width = width;
      el.style.height = height ? height : width;
    } else {
      el.style.paddingTop = (100 / parseInt(widthRatio)) * parseInt(heightRatio) + "%";
      el.style.width = '100%';
    }
  }

  static getStyle() {
    return `.shapla-aspect-ratio {  display: block;  position: relative;}
.shapla-aspect-ratio.is-rounded > * {  border-radius: 290486px;}
.shapla-aspect-ratio > * {  display: block;  bottom: 0;  left: 0;  position: absolute;  right: 0;  top: 0; height: 100%;  width: 100%;}`;
  }
}

customElements.define('shapla-aspect-ratio', ShaplaAspectRatio)
