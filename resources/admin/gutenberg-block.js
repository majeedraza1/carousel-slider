import React from "react";
import {registerBlockType} from '@wordpress/blocks';
import {SelectControl} from '@wordpress/components';
import {InspectorControls} from '@wordpress/block-editor';

const settings = window.i18nCarouselSliderBlock || {
	sliders: [], site_url: '', block_logo: '', block_title: '', select_slider: '', selected_slider: '',
	filter_slider: '',
}

/**
 * Carousel Slider Block
 *
 * A block for embedding a carousel slider into a post/page.
 */

// register our block
registerBlockType('carousel-slider/slider', {
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

		if (!sliderID) sliderID = '';
		if (!sliderName) sliderName = '';

		let previewUrl = new URL(settings.site_url);
		previewUrl.searchParams.append('carousel_slider_preview', '1');
		previewUrl.searchParams.append('carousel_slider_iframe', '1');
		previewUrl.searchParams.append('slider_id', sliderID);
		let iFrameSrc = previewUrl.toString();

		const changeSlider = (slider_id) => {
			let getActiveSlider = settings.sliders.find(_slider => _slider.value == slider_id);
			props.setAttributes({sliderID: parseInt(slider_id), sliderName: getActiveSlider.label});
		}

		const selectControl = (
			<SelectControl label={settings.select_slider} value={sliderID} options={settings.sliders}
						   onChange={changeSlider}/>
		)

		// Set up the slider filter dropdown in the side bar 'block' settings
		let inspectorControls = (
			<InspectorControls>
				<div>
					<div>{settings.selected_slider}</div>
					<div>{sliderName}</div>
				</div>
				{selectControl}
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
					{selectControl}
				</div>
			)
			children.push(element);
		} else {
			let iFrame = (
				<div className="carousel-slider-iframe-container">
					<div className="carousel-slider-iframe-overlay"/>
					<iframe className="carousel-slider-iframe" scrolling="no" src={iFrameSrc} height="0" width="500"/>
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
			return <div>{`[carousel_slide id='${attributes.sliderID}']`}</div>;
		}
		return '';
	}
});
