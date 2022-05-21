class ShaplaCross extends HTMLElement {
  constructor() {
    super();
    this.attachShadow({mode: 'open'});

    // Create CSS to apply to the shadow DOM
    const style = document.createElement('style');
    style.textContent = ShaplaCross.getStyle();

    // attach the created elements to the shadow DOM
    this.shadowRoot.append(style, this.getElement());
  }

  /**
   * Get component shadow element
   *
   * @returns {HTMLButtonElement}
   */
  getElement() {
    const button = document.createElement('button');
    button.classList.add('shapla-cross')

    if (this.hasAttribute('size')) {
      button.classList.add(`is-${this.getAttribute('size')}`)
    }
    return button;
  }

  /**
   * Update dom when attribute changed
   *
   * @param {string} name
   * @param {any} oldValue
   * @param {any} newValue
   */
  attributeChangedCallback(name, oldValue, newValue) {
    const button = this.shadowRoot.querySelector('button');
    if ('size' === name && this.hasAttribute('size')) {
      button.classList.add(`is-${this.getAttribute('size')}`)
    }
  }

  /**
   * List of attribute to observe
   *
   * @returns {string[]}
   */
  static get observedAttributes() {
    return ['size'];
  }

  /**
   * Get component style
   *
   * @returns {string}
   */
  static getStyle() {
    return `.shapla-cross {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  background-color: var(--delete-icon-background, hsla(0, 0%, 4%, .2));
  border: none;
  border-radius: 290486px;
  cursor: pointer;
  display: inline-block;
  flex-grow: 0;
  flex-shrink: 0;
  font-size: 0;
  height: var(--delete-icon-size, 20px);
  outline: none;
  pointer-events: auto;
  position: relative;
  -webkit-user-select: none;
  user-select: none;
  vertical-align: top;
  width: var(--delete-icon-size, 20px)
}

.shapla-cross:after, .shapla-cross:before {
  background-color: var(--delete-icon-color, #fff);
  content: "";
  display: block;
  left: 50%;
  position: absolute;
  top: 50%;
  transform: translateX(-50%) translateY(-50%) rotate(45deg);
  transform-origin: center center
}

.shapla-cross:before {
  height: 2px;
  width: 50%
}

.shapla-cross:after {
  height: 50%;
  width: 2px
}

.shapla-cross:focus, .shapla-cross:hover {
  background-color: var(--delete-icon-background-dark, hsla(0, 0%, 4%, .3))
}

.shapla-cross:active {
  box-shadow: 0 3px 4px 0 rgba(0, 0, 0, .14), 0 3px 3px -2px rgba(0, 0, 0, .2), 0 1px 8px 0 rgba(0, 0, 0, .12)
}

.shapla-cross.is-small {
  --delete-icon-size: 16px
}

.shapla-cross.is-medium {
  --delete-icon-size: 24px
}

.shapla-cross.is-large {
  --delete-icon-size: 32px
}

.shapla-cross.is-error {
  --delete-icon-background: var(--shapla-error, #dc3545);
  --delete-icon-background-dark: var(--shapla-error-variant, #d32535);
  --delete-icon-color: var(--shapla-on-error, #fff)
}`
  }
}

customElements.define('shapla-cross', ShaplaCross);
