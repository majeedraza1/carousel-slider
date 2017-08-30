(function ($) {
    "use strict";

    var body = $('body'),
        frame,
        section,
        imgIdInput,
        slideCanvas,
        delImgLink;

    // Add new content slide
    body.on('click', '.carousel-slider__add-slide', function (e) {
        e.preventDefault();

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'add_content_slide',
                post_id: $(this).data('post-id')
            },
            success: function () {
                $('form#post').submit();
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

})(jQuery);