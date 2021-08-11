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