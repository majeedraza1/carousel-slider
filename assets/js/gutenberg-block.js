(function (blocks, element, components) {
    'use strict';

    var el = element.createElement, // function to create elements
        SelectControl = components.SelectControl, // select control
        InspectorControls = blocks.InspectorControls, // sidebar controls
        settings = window.carousel_slider_gutenberg_block;

    blocks.registerBlockType('carousel-slider/slider', {
        title: settings.block_title,
        icon: 'slides',
        category: 'common',
        attributes: {sliderID: {type: 'integer', default: 0}},

        edit: function (props) {
            var focus = props.focus;
            var sliderID = props.attributes.sliderID;
            var children = [];

            if (!sliderID)
                sliderID = ''; // Default.

            function onFormChange(newSliderID) {
                // updates the form id on the props
                props.setAttributes({sliderID: newSliderID});
            }

            // Set up the form dropdown in the side bar 'block' settings
            var inspectorControls = el(InspectorControls, {},
                el(SelectControl, {
                    label: settings.selected_form,
                    value: sliderID.toString(),
                    options: settings.forms,
                    onChange: onFormChange
                })
            );

            /**
             * Create the div container, add an overlay so the user can interact
             * with the form in Gutenberg, then render the iframe with form
             */
            if ('' === sliderID) {
                children.push(
                    el('div', {style: {width: '100%'}},
                        el('h3', {className: 'carousel-slider-sliders-title'}, settings.block_title),
                        el(SelectControl, {value: sliderID, options: settings.forms, onChange: onFormChange})
                    )
                );
            } else {
                children.push(
                    el('div', {className: 'carousel-slider-sliders-container'},
                        el('div', {className: 'carousel-slider-sliders-overlay'}),
                        el('iframe', {
                            src: settings.siteUrl + '?carousel_slider_preview=1&carousel_slider_iframe=1&slider_id=' + sliderID,
                            height: '0',
                            width: '500',
                            scrolling: 'no'
                        })
                    )
                )
            }

            return [children, !!focus && inspectorControls];
        },

        save: function (props) {
            var sliderID = props.attributes.sliderID;

            if (!sliderID)
                return '';
            /**
             * we're essentially just adding a short code, here is where
             * it's save in the editor
             *
             * return content wrapped in DIV as raw HTML is unsupported
             */
            var returnHTML = '[carousel_slide id=' + parseInt(sliderID) + ']';
            return el('div', null, returnHTML);
        },
    });
})(window.wp.blocks, window.wp.element, window.wp.components);