import React from "react";
import {registerBlockType} from '@wordpress/blocks';
import {SelectControl} from '@wordpress/components';
import {InspectorControls} from '@wordpress/block-editor';

const settings = window.i18nCarouselSliderBlock ||
	{sliders: [], site_url: '', block_logo: '', block_title: '', select_slider: ''}

/**
 * Carousel Slider Block
 *
 * A block for embedding a carousel slider into a post/page.
 */
// @ts-ignore
registerBlockType('carousel-slider/slider', {
	title: settings.block_title,
	icon: 'slides',
	category: 'common',

	attributes: {
		sliderID: {type: 'integer', default: 0},
	},

	edit: (props) => {
		let sliderID = props.attributes.sliderID, children = [];

		if (!sliderID) sliderID = '';

		let previewUrl = new URL(settings.site_url);
		previewUrl.searchParams.append('carousel_slider_preview', '1');
		previewUrl.searchParams.append('carousel_slider_iframe', '1');
		previewUrl.searchParams.append('slider_id', sliderID as string);
		let iFrameSrc = previewUrl.toString();

		const changeSlider = (slider_id: string) => {
			props.setAttributes({sliderID: parseInt(slider_id)});
		}

		const selectControl = (
			// @ts-ignore
			<SelectControl label={settings.select_slider} value={sliderID as string} options={settings.sliders}
						   onChange={changeSlider}/>
		)

		let iFrame = (
			<div className="carousel-slider-iframe-container">
				<div className="carousel-slider-iframe-overlay"/>
				<iframe className="carousel-slider-iframe" scrolling="no" src={iFrameSrc} height="0" width="500"/>
			</div>
		)

		let element = (
			<div className="carousel-slider-editor-controls">
				<img className="carousel-slider-editor-controls__logo" src={settings.block_logo} alt=""/>
				<div className="carousel-slider-editor-controls__title">{settings.block_title}</div>
				<div className="carousel-slider-editor-controls__input">
					{selectControl}
				</div>
			</div>
		)

		// Set up the slider filter dropdown in the side bar 'block' settings
		let inspectorControls = (
			<InspectorControls>
				<div className="carousel-slider-inspector-controls">
					{selectControl}
				</div>
			</InspectorControls>
		)

		/**
		 * Create the div container, add an overlay so the user can interact
		 * with the slider in Gutenberg, then render the iframe with slider
		 */
		if ('' === sliderID) {
			children.push(element);
		} else {
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
