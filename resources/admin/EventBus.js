class EventBus {
	/**
	 * Listen event
	 *
	 * @param event
	 * @param callback
	 */
	static on(event, callback) {
		document.addEventListener(event, (e) => callback(e.detail));
	}

	/**
	 * Dispatch event
	 *
	 * @param event
	 * @param data
	 */
	static dispatch(event, data) {
		document.dispatchEvent(new CustomEvent(event, {detail: data}));
	}

	/**
	 * Show dialog
	 *
	 * @param {Object} params
	 */
	static changeSlideType(params = {}) {
		EventBus.dispatch('change.CarouselSlider.SlideType', params);
	}

	/**
	 * Show dialog
	 *
	 * @param {function} callback
	 */
	static onChangeSlideType(callback) {
		EventBus.on('change.CarouselSlider.SlideType', data => callback(data));
	}
}

export default EventBus;
