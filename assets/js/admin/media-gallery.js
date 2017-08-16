/* global CarouselSlider */
(function ($) {
    "use strict";

    var frame,
        images = CarouselSlider.image_ids,
        selection = loadImages(images);

    $('#carousel_slider_gallery_btn').on('click', function (e) {
        e.preventDefault();
        var options = {
            title: CarouselSlider.create_btn_text,
            state: 'gallery-edit',
            frame: 'post',
            selection: selection
        };

        if (frame || selection) {
            options['title'] = CarouselSlider.edit_btn_text;
        }

        frame = wp.media(options).open();

        // Tweak Views
        frame.menu.get('view').unset('cancel');
        frame.menu.get('view').unset('separateCancel');
        frame.menu.get('view').get('gallery-edit').el.innerHTML = CarouselSlider.edit_btn_text;
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
                    post_id: CarouselSlider.post_id,
                    nonce: CarouselSlider.nonce
                });
            }
        });

        function overrideGalleryInsert() {
            frame.toolbar.get('view').set({
                insert: {
                    style: 'primary',
                    text: CarouselSlider.save_btn_text,
                    click: function () {
                        var models = frame.state().get('library'),
                            ids = '';

                        models.each(function (attachment) {
                            ids += attachment.id + ','
                        });

                        this.el.innerHTML = CarouselSlider.progress_btn_text;

                        $.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            data: {
                                ids: ids,
                                action: 'carousel_slider_save_images',
                                post_id: CarouselSlider.post_id,
                                nonce: CarouselSlider.nonce
                            },
                            success: function () {
                                selection = loadImages(ids);
                                $('#_carousel_slider_images_ids').val(ids);
                                frame.close();
                            },
                            dataType: 'html'
                        }).done(function (data) {
                            $('.carousel_slider_gallery_list').html(data);
                            console.log(data);
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