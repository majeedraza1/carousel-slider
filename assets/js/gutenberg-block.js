/**
 * Carousel Slider Block
 *
 * A block for embedding a carousel slider into a post/page.
 */
(function (blocks, i18n, editor, element, components) {

    var el = element.createElement, // function to create elements
        settings = window.carousel_slider_gutenberg_block,
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

        edit: function (props) {
            var sliderID = props.attributes.sliderID,
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
                props.setAttributes({
                    sliderID: parseInt(event.target.getAttribute('data-slider_id')),
                    sliderName: event.target.innerText
                });
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
                _.each(settings.sliders, function (slider, index) {
                    var liEl = filterInputContainer.querySelector("[data-slider_id='" + slider.value + "']");
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
            var sliderItems = [];
            _.each(settings.sliders, function (slider, index) {
                sliderItems.push(el('li', {
                        className: 'carousel-slider-filter-option',
                        'data-slider_id': slider.value, onMouseDown: selectSlider
                    },
                    slider.label + " ( ID: " + slider.value + " )"))
            });

            // Set up slider filter for the block
            var inputFilterMain = el('div', {
                    id: 'carousel-slider-filter-input-main',
                    className: 'carousel-slider-filter-input'
                },
                el(TextControl, {
                    id: 'carousel-slider-sliderFilter-main',
                    placeHolder: settings.select_slider,
                    className: 'carousel-slider-filter-input-el blocks-select-control__input',
                    onChange: carouselSliderOnValueChange,
                    onClick: carouselSliderFocusClick,
                    onKeyUp: carouselSliderInputKeyUp,
                    onBlur: carouselSliderHideOptions
                }),
                el('span', {
                    id: 'carousel-slider-filter-input-icon-main',
                    className: 'carousel-slider-filter-input-icon',
                    onClick: carouselSliderFocusClick,
                    dangerouslySetInnerHTML: {__html: '&#9662;'}
                }),
                el('div', {
                        id: 'carousel-slider-filter-container-main',
                        className: 'carousel-slider-filter-option-container'
                    },
                    el('ul', null, sliderItems)
                )
            );
            // Create filter input for the sidebar blocks settings
            var inputFilterSidebar = el('div', {
                    id: 'carousel-slider-filter-input-sidebar',
                    className: 'carousel-slider-filter-input'
                },
                el(TextControl, {
                    id: 'carousel-slider-sliderFilter-sidebar',
                    placeHolder: settings.select_slider,
                    className: 'carousel-slider-filter-input-el blocks-select-control__input',
                    onChange: carouselSliderOnValueChange,
                    onClick: carouselSliderFocusClick,
                    onKeyUp: carouselSliderInputKeyUp,
                    onBlur: carouselSliderHideOptions
                }),
                el('span', {
                    id: 'carousel-slider-filter-input-icon-sidebar',
                    className: 'carousel-slider-filter-input-icon',
                    onClick: carouselSliderFocusClick,
                    dangerouslySetInnerHTML: {__html: '&#9662;'}
                }),
                el('div', {
                        id: 'carousel-slider-filter-container-sidebar',
                        className: 'carousel-slider-filter-option-container'
                    },
                    el('ul', null, sliderItems)
                )
            );

            // Set up the slider filter dropdown in the side bar 'block' settings
            var inspectorControls = el(InspectorControls, {},
                el('span', null, settings.selected_slider),
                el('br', null),
                el('span', null, sliderName),
                el('br', null),
                el('hr', null),
                el('label', {for: 'carousel-slider-sliderFilter-sidebar'}, settings.filter_slider),
                inputFilterSidebar
            );

            /**
             * Create the div container, add an overlay so the user can interact
             * with the slider in Gutenberg, then render the iframe with slider
             */
            if ('' === sliderID) {
                children.push(el('div', {style: {width: '100%'}},
                    el('img', {className: 'carousel-slider-block-logo', src: settings.block_logo}),
                    el('div', null, settings.block_title),
                    inputFilterMain
                ));
            } else {
                children.push(
                    el('div', {className: 'carousel-slider-iframe-container'},
                        el('div', {className: 'carousel-slider-iframe-overlay'}),
                        el('iframe', {
                            className: 'carousel-slider-iframe',
                            src: settings.site_url + '?carousel_slider_preview=1&carousel_slider_iframe=1&slider_id=' + sliderID,
                            height: '0',
                            width: '500',
                            scrolling: 'no'
                        })
                    )
                )
            }
            children.push(inspectorControls);
            return [children];
        },

        save: function (props) {
            var sliderID = props.attributes.sliderID;

            if (!sliderID) return '';
            /**
             * we're essentially just adding a short code, here is where
             * it's save in the editor
             *
             * return content wrapped in DIV b/c raw HTML is unsupported
             * going forward
             */
            var returnHTML = '[carousel_slide id=' + parseInt(sliderID) + ']';
            return el('div', null, returnHTML);
        }
    });
})(window.wp.blocks, window.wp.i18n, window.wp.editor, window.wp.element, window.wp.components);