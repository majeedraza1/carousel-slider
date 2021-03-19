/**
 * Carousel Slider Block
 *
 * A block for embedding a carousel slider into a post/page.
 */

const blocks = window.wp.blocks,
	editor = window.wp.blockEditor,
	components = window.wp.components,
	settings = window.i18nCarouselSliderBlock,
	TextControl = components.TextControl,// text input control
	InspectorControls = editor.InspectorControls; // sidebar controls

// register our block
blocks.registerBlockType('carousel-slider/slider', {
	title: settings.block_title,
	icon: 'slides',
	category: 'common',

	attributes: {
		sliderID: {type: 'integer', default: 0},
		sliderName: {type: 'string', default: ''}
	},

	edit: (props) => {
		let sliderID = props.attributes.sliderID,
			sliderName = props.attributes.sliderName,
			children = [];

		if (!sliderID) sliderID = ''; // Default.
		if (!sliderName) sliderName = ''; // Default

		// this function is required, but we don't need it to do anything
		function carouselSliderOnValueChange(sliderName) {
		}

		// show the dropdown when we click on the input
		function carouselSliderFocusClick(event) {
			var elementID = event.target.getAttribute('id');
			var idArray = elementID.split('-');
			var carouselSliderOptions = document.getElementById('carousel-slider-filter-container-' + idArray[idArray.length - 1]);
			// get the related input element
			var carouselSliderInput = document.getElementById('carousel-slider-sliderFilter-' + idArray[idArray.length - 1]);
			// set focus to the element so the onBlur function runs properly
			carouselSliderInput.focus();
			carouselSliderOptions.style.display = 'block';
		}

		// function for select the slider on filter drop down item click
		function selectSlider(event) {
			//set the attributes from the selected for item
			let value = event.target.value || event.target.getAttribute('data-slider_id');
			props.setAttributes({sliderID: parseInt(value), sliderName: event.target.innerText});
			/**
			 * Get the main div of the filter to tell if this is being
			 * selected from the sidebar or block so we can hide the dropdown
			 */
			var elementID = event.target.parentNode.parentNode;
			var idArray = elementID.getAttribute('id').split('-');
			var carouselSliderOptions = document.getElementById('carousel-slider-filter-container-' + idArray[idArray.length - 1]);
			var inputEl = document.getElementById('carousel-slider-sliderFilter-sidebar');

			if (inputEl) {
				inputEl.value = '';
			}
			carouselSliderOptions.style.display = 'none';
		}

		function carouselSliderHideOptions(event) {
			/**
			 * Get the main div of the filter to tell if this is being
			 * selected from the sidebar or block so we can hide the dropdown
			 */
			var elementID = event.target.getAttribute('id');
			var idArray = elementID.split('-');
			var carouselSliderOptions = document.getElementById('carousel-slider-filter-container-' + idArray[idArray.length - 1]);
			carouselSliderOptions.style.display = 'none';
		}

		function carouselSliderInputKeyUp(event) {
			var val = event.target.value;
			/**
			 * Get the main div of the filter to tell if this is being
			 * selected from the sidebar or block so we can SHOW the dropdown
			 */
			var filterInputContainer = event.target.parentNode.parentNode.parentNode;
			filterInputContainer.querySelector('.carousel-slider-filter-option-container').style.display = 'block';
			filterInputContainer.style.display = 'block';

			// Let's filter the sliders here
			settings.sliders.forEach(slider => {
				let liEl = filterInputContainer.querySelector("[data-slider_id='" + slider.value + "']");
				if (0 <= slider.label.toLowerCase().indexOf(val.toLowerCase())) {
					// shows options that DO contain the text entered
					liEl.style.display = 'block';
				} else {
					// hides options the do not contain the text entered
					liEl.style.display = 'none';
				}
			});
		}

		// Set up the slider items from the localized php variables
		let sliderItems = settings.sliders.map(slider => {
			let label = slider.label + " ( ID: " + slider.value + " )";
			return (<li className="carousel-slider-filter-option" key={slider.value} data-slider_id={slider.value}
						onMouseDown={selectSlider}>{label}</li>)
		});

		// Set up slider filter for the block
		let inputFilterMain = (
			<div id="carousel-slider-filter-input-main" className="carousel-slider-filter-input">
				<TextControl
					id='carousel-slider-sliderFilter-main'
					className='carousel-slider-filter-input-el blocks-select-control__input'
					placeholder={settings.select_slider}
					onChange={carouselSliderOnValueChange}
					onClick={carouselSliderFocusClick}
					onKeyUp={carouselSliderInputKeyUp}
				/>
				<span id='carousel-slider-filter-input-icon-main' className='carousel-slider-filter-input-icon'
					  onClick={carouselSliderFocusClick}>&#9662;</span>
				<div id='carousel-slider-filter-container-main' className='carousel-slider-filter-option-container'>
					<ul>{sliderItems}</ul>
				</div>
			</div>
		)

		let sliderItems2 = settings.sliders.map(slider => {
			let label = slider.label + " ( ID: " + slider.value + " )";
			return (<li className="carousel-slider-filter-option" key={`side-${slider.value}`}
						data-slider_id={slider.value} onMouseDown={selectSlider}>{label}</li>)
		});
		let inputFilterSidebar = (
			<div id="carousel-slider-filter-input-sidebar" className="carousel-slider-filter-input">
				<TextControl
					id='carousel-slider-sliderFilter-sidebar'
					className='carousel-slider-filter-input-el blocks-select-control__input'
					placeholder={settings.select_slider}
					onChange={carouselSliderOnValueChange}
					onClick={carouselSliderFocusClick}
					onKeyUp={carouselSliderInputKeyUp}
					onBlur={carouselSliderHideOptions}
				/>
				<span id='carousel-slider-filter-input-icon-sidebar' className='carousel-slider-filter-input-icon'
					  onClick={carouselSliderFocusClick}>&#9662;</span>
				<div id='carousel-slider-filter-container-sidebar' className='carousel-slider-filter-option-container'>
					<ul>{sliderItems2}</ul>
				</div>
			</div>
		)

		// Create filter input for the sidebar blocks settings

		// Set up the slider filter dropdown in the side bar 'block' settings
		let inspectorControls = (
			<InspectorControls>
				<span>{settings.selected_slider}</span>
				<br/>
				<span>{sliderName}</span>
				<br/>
				<label htmlFor="carousel-slider-sliderFilter-sidebar">{settings.filter_slider}</label>
				{inputFilterSidebar}
			</InspectorControls>
		)

		/**
		 * Create the div container, add an overlay so the user can interact
		 * with the slider in Gutenberg, then render the iframe with slider
		 */
		if ('' === sliderID) {
			let element = (
				<div style={{width: '100%'}}>
					<img className="carousel-slider-block-logo" src={settings.block_logo} alt=""/>
					<div>{settings.block_title}</div>
					{inputFilterMain}
				</div>
			)
			children.push(element);
		} else {
			let iFrameSrc = settings.site_url + '?carousel_slider_preview=1&carousel_slider_iframe=1&slider_id=' + sliderID;
			let iFrame = (
				<div className="carousel-slider-iframe-container">
					<div className="carousel-slider-iframe-overlay"/>
					<iframe className="carousel-slider-iframe" scrolling="no" src={iFrameSrc} height="0"
							width="500"/>
				</div>
			)
			children.push(iFrame)
		}
		children.push(inspectorControls);
		return [children];
	},

	/**
	 * we're essentially just adding a short code, here is where it's save in the editor
	 * return content wrapped in DIV b/c raw HTML is unsupported going forward
	 */
	save: ({attributes}) => {
		if (attributes.sliderID) {
			let shortcode = `[carousel_slide id='${attributes.sliderID}']`;
			return <div>{shortcode}</div>;
		}
		return '';
	}
});
