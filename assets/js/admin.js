(function ($) {
    'use strict';
    $(document).on("click", ".accordion-header", function () {
        $(this).toggleClass('active');
        var panel = $(this).next();

        if (parseInt(panel.css('max-height')) > 0) {
            panel.css('max-height', '0');
            panel.css('overflow', 'hidden');
        } else {
            panel.css('max-height', panel.prop('scrollHeight') + "px");
            panel.css('overflow', 'visible');
        }
    });
})(jQuery);
(function ($) {
    "use strict";

    var body = $('body'),
        contentButtonModal = $('#contentButtonModal'),
        frame,
        section,
        imgIdInput,
        slideCanvas,
        delImgLink,
        buttonConfig;

    // Add new content slide
    body.on('click', '.carousel-slider__add-slide', function (e) {
        e.preventDefault();

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'add_content_slide',
                task: 'add-slide',
                post_id: $(this).data('post-id')
            },
            success: function () {
                window.location.reload(true);
            }
        });
    });

    // Delete a slide
    body.on('click', '.carousel_slider__delete_slide', function (e) {
        e.preventDefault();

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'add_content_slide',
                task: 'delete-slide',
                post_id: $(this).data('post-id'),
                slide_pos: $(this).data('slide-pos')
            },
            success: function () {
                window.location.reload(true);
            }
        });
    });

    // Move slide to top
    body.on('click', '.carousel_slider__move_top', function (e) {
        e.preventDefault();

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'add_content_slide',
                task: 'move-slide-top',
                post_id: $(this).data('post-id'),
                slide_pos: $(this).data('slide-pos')
            },
            success: function () {
                window.location.reload(true);
            }
        });
    });

    // Move slide up
    body.on('click', '.carousel_slider__move_up', function (e) {
        e.preventDefault();

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'add_content_slide',
                task: 'move-slide-up',
                post_id: $(this).data('post-id'),
                slide_pos: $(this).data('slide-pos')
            },
            success: function () {
                window.location.reload(true);
            }
        });
    });

    // Move slide down
    body.on('click', '.carousel_slider__move_down', function (e) {
        e.preventDefault();

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'add_content_slide',
                task: 'move-slide-down',
                post_id: $(this).data('post-id'),
                slide_pos: $(this).data('slide-pos')
            },
            success: function () {
                window.location.reload(true);
            }
        });
    });

    // Move slide to bottom
    body.on('click', '.carousel_slider__move_bottom', function (e) {
        e.preventDefault();

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'add_content_slide',
                task: 'move-slide-bottom',
                post_id: $(this).data('post-id'),
                slide_pos: $(this).data('slide-pos')
            },
            success: function () {
                window.location.reload(true);
            }
        });
    });

    // Add slide image
    body.on('click', '.slide_image_add', function (e) {
        e.preventDefault();

        var _this = $(this);
        section = _this.closest('.slide_bg_wrapper');
        slideCanvas = section.find('.content_slide_canvas');
        imgIdInput = section.find('.background_image_id');
        delImgLink = section.find('.delete-bg-img');

        // If the media frame already exists, reopen it.
        if (frame) {
            frame.open();
            return;
        }

        // Create a new media frame
        frame = wp.media({
            title: _this.data('title'),
            button: {
                text: _this.data('button-text')
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected in the media frame...
        frame.on('select', function () {

            // Get media attachment details from the frame state
            var attachment = frame.state().get('selection').first().toJSON();

            // Send the attachment URL to our custom image input field.
            slideCanvas.css('background-image', 'url(' + attachment.url + ')');

            // Send the attachment id to our hidden input
            imgIdInput.val(attachment.id);

            // Show the remove image link
            delImgLink.removeClass('hidden');
        });

        // Finally, open the modal on click
        frame.open();
    });

    // Remove slide image
    body.on('click', '.delete-bg-img', function (e) {
        e.preventDefault();

        section = $(this).closest('.slide_bg_wrapper');
        slideCanvas = section.find('.content_slide_canvas');
        imgIdInput = section.find('.background_image_id');
        delImgLink = section.find('.delete-bg-img');

        // Clear out the preview image
        slideCanvas.css('background-image', '');

        // Delete the image id from the hidden input
        imgIdInput.val('0');

        // Hide the delete image link
        delImgLink.addClass('hidden');
    });

    // Background Position
    body.on('change', '.background_image_position', function () {
        var _val = $(this).val();
        section = $(this).closest('.slide_bg_wrapper');
        slideCanvas = section.find('.content_slide_canvas');
        slideCanvas.css('background-position', _val);
    });

    // Background Size
    body.on('change', '.background_image_size', function () {
        var _val = $(this).val();
        section = $(this).closest('.slide_bg_wrapper');
        slideCanvas = section.find('.content_slide_canvas');
        slideCanvas.css('background-size', _val);
    });

    // Add Button Style to Modal for Edit
    $('.addContentButton').on('click', function (e) {
        e.preventDefault();

        buttonConfig = $(this).closest('.button_config');
        var button_text = buttonConfig.find('.button_text').val();
        var button_url = buttonConfig.find('.button_url').val();
        var button_target = buttonConfig.find('.button_target').val();
        var button_type = buttonConfig.find('.button_type').val();
        var button_size = buttonConfig.find('.button_size').val();
        var button_color = buttonConfig.find('.button_color').val();

        contentButtonModal.find('#_button_text').val(button_text);
        contentButtonModal.find('#_button_url').val(button_url);
        contentButtonModal.find('#_button_target').val(button_target);
        contentButtonModal.find('#_button_type').val(button_type);
        contentButtonModal.find('#_button_size').val(button_size);
        contentButtonModal.find('#_button_color').val(button_color);

        contentButtonModal.addClass('is-active');
    });

    // Save Button style from modal form
    $('#saveContentButton').on('click', function (e) {
        e.preventDefault();

        if (!buttonConfig) {
            contentButtonModal.removeClass('is-active');
            return false;
        }

        var button_text = contentButtonModal.find('#_button_text').val();
        var button_url = contentButtonModal.find('#_button_url').val();
        var button_target = contentButtonModal.find('#_button_target').val();
        var button_type = contentButtonModal.find('#_button_type').val();
        var button_size = contentButtonModal.find('#_button_size').val();
        var button_color = contentButtonModal.find('#_button_color').val();

        buttonConfig.find('.button_text').val(button_text);
        buttonConfig.find('.button_url').val(button_url);
        buttonConfig.find('.button_target').val(button_target);
        buttonConfig.find('.button_type').val(button_type);
        buttonConfig.find('.button_size').val(button_size);
        buttonConfig.find('.button_color').val(button_color);

        contentButtonModal.removeClass('is-active');

    });

    // Background Color
    $('.slide-color-picker').each(function () {
        section = $(this).closest('.slide_bg_wrapper');
        slideCanvas = section.find('.content_slide_canvas');
        $(this).wpColorPicker({
            palettes: [
                '#2196F3', // Blue
                '#009688', // Teal
                '#4CAF50', // Green
                '#F44336', // Red
                '#FFEB3B', // Yellow
                '#00D1B2', // Firoza
                '#000000', // Blank
                '#ffffff' // White
            ],
            change: function (event, ui) {
                slideCanvas.css('background-color', ui.color.toString());
            }
        });
    });

    // Slide Link
    $(document).on('change', '.link_type', function (e) {
        var _this = $(this);
        var _val = _this.val();
        var _tab = _this.closest('.tab-content-link');
        var _linkFull = _tab.find('.ContentCarouselLinkFull');
        var _linkBtn = _tab.find('.ContentCarouselLinkButtons');
        if (_val === 'full') {
            _linkBtn.hide();
            _linkFull.show();
        } else if (_val === 'button') {
            _linkFull.hide();
            _linkBtn.show();
        } else {
            _linkFull.hide();
            _linkBtn.hide();
        }
    })

})(jQuery);
(function ($) {
    "use strict";

    var frame,
        _this = $('#carousel_slider_gallery_btn'),
        images = _this.data('ids'),
        selection = loadImages(images);

    _this.on('click', function (e) {
        e.preventDefault();
        var options = {
            title: _this.data('create'),
            state: 'gallery-edit',
            frame: 'post',
            selection: selection
        };

        if (frame || selection) {
            options['title'] = _this.data('edit');
        }

        frame = wp.media(options).open();

        // Tweak Views
        frame.menu.get('view').unset('cancel');
        frame.menu.get('view').unset('separateCancel');
        frame.menu.get('view').get('gallery-edit').el.innerHTML = _this.data('edit');
        frame.content.get('view').sidebar.unset('gallery'); // Hide Gallery Settings in sidebar

        // when editing a gallery
        overrideGalleryInsert();
        frame.on('toolbar:render:gallery-edit', function () {
            overrideGalleryInsert();
        });

        frame.on('content:render:browse', function (browser) {
            if (!browser) return;
            // Hide Gallery Settings in sidebar
            browser.sidebar.on('ready', function () {
                browser.sidebar.unset('gallery');
            });
            // Hide filter/search as they don't work
            browser.toolbar.on('ready', function () {
                if (browser.toolbar.controller._state === 'gallery-library') {
                    browser.toolbar.$el.hide();
                }
            });
        });

        // All images removed
        frame.state().get('library').on('remove', function () {
            var models = frame.state().get('library');
            if (models.length === 0) {
                selection = false;
                $.post(ajaxurl, {
                    ids: '',
                    action: 'carousel_slider_save_images',
                    post_id: _this.data('id')
                });
            }
        });

        function overrideGalleryInsert() {
            frame.toolbar.get('view').set({
                insert: {
                    style: 'primary',
                    text: _this.data('save'),
                    click: function () {
                        var models = frame.state().get('library'),
                            ids = '';

                        models.each(function (attachment) {
                            ids += attachment.id + ','
                        });

                        this.el.innerHTML = _this.data('progress');

                        $.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            data: {
                                ids: ids,
                                action: 'carousel_slider_save_images',
                                post_id: _this.data('id')
                            },
                            success: function () {
                                selection = loadImages(ids);
                                $('#_carousel_slider_images_ids').val(ids);
                                frame.close();
                            },
                            dataType: 'html'
                        }).done(function (data) {
                            $('.carousel_slider_gallery_list').html(data);
                        });
                    }
                }
            });
        }

    });

    function loadImages(images) {
        if (images) {
            var shortcode = new wp.shortcode({
                tag: 'gallery',
                attrs: {ids: images},
                type: 'single'
            });

            var attachments = wp.media.gallery.attachments(shortcode);

            var selection = new wp.media.model.Selection(attachments.models, {
                props: attachments.props.toJSON(),
                multiple: true
            });

            selection.gallery = attachments.gallery;

            selection.more().done(function () {
                // Break ties with the query.
                selection.props.set({query: false});
                selection.unmirror();
                selection.props.unset('orderby');
            });

            return selection;
        }
        return false;
    }

})(jQuery);
/**
 * Carousel Slider Gallery from URL
 */
(function ($) {
    'use strict';

    var body = $('body'),
        modal = $('#CarouselSliderModal'),
        modalOpenBtn = $('#_images_urls_btn'),
        template = $('#carouselSliderGalleryUrlTemplate').html();

    // URL Images Model
    modalOpenBtn.on('click', function (e) {
        e.preventDefault();
        modal.css("display", "block");
        $("body").addClass("overflowHidden");
    });
    modal.on('click', '.carousel_slider-close', function (e) {
        e.preventDefault();
        modal.css("display", "none");
        $("body").removeClass("overflowHidden");
    });

    var carouselSliderBodyHeight = $(window).height() - (38 + 48 + 32 + 30);
    $('.carousel_slider-modal-body').css('height', carouselSliderBodyHeight + 'px');

    // Append new row
    body.on('click', '.add_row', function () {
        $(this).closest('.carousel_slider-fields').after(template);
    });

    // Delete current row
    body.on('click', '.delete_row', function () {
        $(this).closest('.carousel_slider-fields').remove();
    });

    // Make fields sortable
    $('#carousel_slider_form').sortable();

})(jQuery);
(function ($) {
    'use strict';

    // Open modal
    $(document).on('click', '[data-toggle="modal"]', function (e) {
        e.preventDefault();
        $($(this).data('target')).addClass('is-active');
    });

    // Close modal
    $(document).on('click', '[data-dismiss="modal"]', function (e) {
        e.preventDefault();
        $(this).closest('.modal').removeClass('is-active');
    });

})(jQuery);
(function ($) {
    "use strict";

    var slide_type = $('#_carousel_slider_slide_type'),
        section_images_settings = $('#section_images_settings'),
        section_url_images_settings = $('#section_url_images_settings'),
        section_images_general_settings = $('#section_images_general_settings'),
        section_post_query = $('#section_post_query'),
        section_video_settings = $('#section_video_settings'),
        section_product_query = $('#section_product_query'),
        section_content_carousel = $('#section_content_carousel'),
        // Slide Type -- Post
        _post_query_type = $('#_post_query_type'),
        _post_date_after = $('#field-_post_date_after'),
        _post_date_before = $('#field-_post_date_before'),
        _post_categories = $('#field-_post_categories'),
        _post_tags = $('#field-_post_tags'),
        _post_in = $('#field-_post_in'),
        _posts_per_page = $('#field-_posts_per_page'),
        // Slide Type -- Product
        _product_query_type = $('#_product_query_type'),
        _product_query = $('#field-_product_query'),
        _product_categories = $('#field-_product_categories'),
        _product_tags = $('#field-_product_tags'),
        _product_in = $('#field-_product_in'),
        _products_per_page = $('#field-_products_per_page');

    // Slide Type
    slide_type.on('change', function () {
        section_images_settings.hide('fast');
        section_url_images_settings.hide('fast');
        section_images_general_settings.hide('fast');
        section_post_query.hide('fast');
        section_video_settings.hide('fast');
        section_product_query.hide('fast');
        section_content_carousel.hide('fast');

        if (this.value === 'image-carousel') {
            section_images_settings.slideDown();
            section_images_general_settings.slideDown();
        }
        if (this.value === 'image-carousel-url') {
            section_url_images_settings.slideDown();
            section_images_general_settings.slideDown();
        }
        if (this.value === 'post-carousel') {
            section_post_query.slideDown();
        }
        if (this.value === 'video-carousel') {
            section_video_settings.slideDown();
        }
        if (this.value === 'product-carousel') {
            section_product_query.slideDown();
            _product_query.show();
        }
        if (this.value === 'hero-banner-slider') {
            section_content_carousel.slideDown();
        }
    });

    // Slide Type -- Post Carousel
    if (slide_type.val() === 'post-carousel') {
        var _postQueryType = _post_query_type.val();
        if (_postQueryType === 'date_range') {
            _post_date_after.show();
            _post_date_before.show();
        }
        if (_postQueryType === 'post_categories') {
            _post_categories.show();
        }
        if (_postQueryType === 'post_tags') {
            _post_tags.show();
        }
        if (_postQueryType === 'specific_posts') {
            _post_in.show();
            _posts_per_page.hide();
        }
    }

    _post_query_type.on('change', function () {

        _post_date_after.hide('fast');
        _post_date_before.hide('fast');
        _post_categories.hide('fast');
        _post_tags.hide('fast');
        _post_in.hide('fast');
        _posts_per_page.show('fast');

        if (this.value === 'date_range') {
            _post_date_after.slideDown();
            _post_date_before.slideDown();
        }
        if (this.value === 'post_categories') {
            _post_categories.slideDown();
        }
        if (this.value === 'post_tags') {
            _post_tags.slideDown();
        }
        if (this.value === 'specific_posts') {
            _post_in.slideDown();
            _posts_per_page.hide('fast');
        }
    });

    // Slide Type -- Product Carousel
    if (slide_type.val() === 'product-carousel') {
        var _productQueryType = _product_query_type.val();
        if (_productQueryType === 'query_porduct') {
            _product_query.show();
        }
        if (_productQueryType === 'product_categories') {
            _product_categories.show();
        }
        if (_productQueryType === 'product_tags') {
            _product_tags.show();
        }
        if (_productQueryType === 'specific_products') {
            _product_in.show();
        }
    }

    _product_query_type.on('change', function () {

        _product_query.hide('fast');
        _product_categories.hide('fast');
        _product_tags.hide('fast');
        _product_in.hide('fast');
        _products_per_page.show('fast');

        if (this.value === 'query_porduct') {
            _product_query.slideDown();
        }
        if (this.value === 'product_categories') {
            _product_categories.slideDown();
        }
        if (this.value === 'product_tags') {
            _product_tags.slideDown();
        }
        if (this.value === 'specific_products') {
            _product_in.slideDown();
            _products_per_page.hide('fast');
        }
    });
})(jQuery);

(function ($) {
    "use strict";

    // Initializing TipTip
    $(".cs-tooltip").each(function () {
        $(this).tipTip();
    });

    // Initializing Select2
    $("select.select2").each(function () {
        $(this).select2();
    });

    // Initializing jQuery UI Accordion
    $(".shapla-toggle").each(function () {
        if ($(this).attr('data-id') === 'closed') {
            $(this).accordion({
                collapsible: true,
                heightStyle: "content",
                active: false
            });
        } else {
            $(this).accordion({
                collapsible: true,
                heightStyle: "content"
            });
        }
    });

    // Initializing jQuery UI Tab
    $(".shapla-tabs").tabs({
        hide: {
            effect: "fadeOut",
            duration: 200
        },
        show: {
            effect: "fadeIn",
            duration: 200
        }
    });

    //Initializing jQuery UI Date picker
    $('.datepicker').each(function () {
        $(this).datepicker({
            dateFormat: 'MM dd, yy',
            changeMonth: true,
            changeYear: true,
            onClose: function (selectedDate) {
                $(this).datepicker('option', 'minDate', selectedDate);
            }
        });
    });

    // Initializing WP Color Picker
    $('.color-picker').each(function () {
        $(this).wpColorPicker({
            palettes: [
                '#2196F3', // Blue
                '#009688', // Teal
                '#4CAF50', // Green
                '#F44336', // Red
                '#FFEB3B', // Yellow
                '#00D1B2', // Firoza
                '#000000', // Blank
                '#ffffff' // White
            ]
        });
    });
})(jQuery);