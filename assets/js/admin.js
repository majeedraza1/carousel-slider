(function( $ ) {
    "use strict";

    if ( $('#_carousel_slider_slide_type').val() == 'post-carousel' ) {
        var _postQueryType = $('#_post_query_type').val();
        if ( _postQueryType == 'latest_posts' ) {}
        if ( _postQueryType == 'date_range' ) {
            $('#field-_post_date_after').show();
            $('#field-_post_date_before').show();
        }
        if ( _postQueryType == 'post_categories' ) {
            $('#field-_post_categories').show();
        }
        if ( _postQueryType == 'post_tags' ) {
            $('#field-_post_tags').show();
        }
        if ( _postQueryType == 'specific_posts' ) {
            $('#field-_post_in').show();
            $('#field-_posts_per_page').hide();
        }
    }

    $('#_post_query_type').on('change', function(){

        $('#field-_post_date_after').hide('fast');
        $('#field-_post_date_before').hide('fast');
        $('#field-_post_categories').hide('fast');
        $('#field-_post_tags').hide('fast');
        $('#field-_post_in').hide('fast');
        $('#field-_posts_per_page').show('fast');

        if ( this.value == 'date_range' ) {
            $('#field-_post_date_after').slideDown();
            $('#field-_post_date_before').slideDown();
        }
        if ( this.value == 'post_categories' ) {
            $('#field-_post_categories').slideDown();
        }
        if ( this.value == 'post_tags' ) {
            $('#field-_post_tags').slideDown();
        }
        if ( this.value == 'specific_posts' ) {
            $('#field-_post_in').slideDown();
            $('#field-_posts_per_page').hide('fast');
        }
    });

    if ( $('#_carousel_slider_slide_type').val() == 'product-carousel' ) {
        var _productQueryType = $('#_product_query_type').val();
        if ( _productQueryType == 'query_porduct' ) {
            $('#field-_product_query').show();
        }
        if ( _productQueryType == 'product_categories' ) {
            $('#field-_product_categories').show();
        }
        if ( _productQueryType == 'product_tags' ) {
            $('#field-_product_tags').show();
        }
        if ( _productQueryType == 'specific_products' ) {
            $('#field-_product_in').show();
        }
    }

    $('#_product_query_type').on('change', function(){

        $('#field-_product_query').hide('fast');
        $('#field-_product_categories').hide('fast');
        $('#field-_product_tags').hide('fast');
        $('#field-_product_in').hide('fast');
        $('#field-_products_per_page').show('fast');

        if ( this.value == 'query_porduct' ) {
            $('#field-_product_query').slideDown();
        }
        if ( this.value == 'product_categories' ) {
            $('#field-_product_categories').slideDown();
        }
        if ( this.value == 'product_tags' ) {
            $('#field-_product_tags').slideDown();
        }
        if ( this.value == 'specific_products' ) {
            $('#field-_product_in').slideDown();
            $('#field-_products_per_page').hide('fast');
        }
    });

    $('#_carousel_slider_slide_type').on('change', function() {

        $('#section_images_settings').hide('fast');
        $('#section_url_images_settings').hide('fast');
        $('#section_images_general_settings').hide('fast');
        $('#section_post_query').hide('fast');
        $('#section_video_settings').hide('fast');
        $('#section_product_query').hide('fast');
        
        if (this.value == 'image-carousel') {
            $('#section_images_settings').slideDown();
            $('#section_images_general_settings').slideDown();
        }
        if (this.value == 'image-carousel-url') {
            $('#section_url_images_settings').slideDown();
            $('#section_images_general_settings').slideDown();
        }
        if (this.value == 'post-carousel') {
            $('#section_post_query').slideDown();
        }
        if (this.value == 'video-carousel') {
            $('#section_video_settings').slideDown();
        }
        if (this.value == 'product-carousel') {
            $('#section_product_query').slideDown();
        }
    });

    // Select2
    $("select.select2").each( function () {
        $(this).select2();
    });

    // Accordion
    $(".shapla-toggle").each( function () {
        if($(this).attr('data-id') == 'closed') {
            $(this).accordion({
                header: '.shapla-toggle-title',
                collapsible: true,
                heightStyle: "content",
                active: false
            });
        } else {
            $(this).accordion({
                header: '.shapla-toggle-title',
                collapsible: true,
                heightStyle: "content"
            });
        }
    });
    
    //Initializing jQuery UI Datepicker
    $('.datepicker').each(function(){
        $( this ).datepicker({
            dateFormat: 'MM dd, yy',
            changeMonth: true,
            changeYear: true,
            onClose: function( selectedDate ){
                $( this ).datepicker( 'option', 'minDate', selectedDate );
            }
        });
    });

    // Initializing WP Color Picker
    $('.colorpicker').each(function(){
        $(this).wpColorPicker();
    });

    // URL Images Model
    $( "#_images_urls_btn" ).on('click', function(){
        $( "#CarouselSliderModal" ).css("display", "block");
        $("body").addClass("overflowHidden");
    });
    $( "#CarouselSliderModal" ).on('click', '.carousel_slider-close', function(){
        $( "#CarouselSliderModal" ).css("display", "none");
        $("body").removeClass("overflowHidden");
    });

    $('.carousel_slider-fields').livequery(function(){
        var carouselSliderFields = '<div class="carousel_slider-fields">'
            + '<label class="setting">'
                + '<span class="name">URL</span>'
                + '<input type="url" name="_images_urls[url][]" value="" autocomplete="off">'
            + '</label>'
            + '<label class="setting">'
                + '<span class="name">Title</span>'
                + '<input type="text" name="_images_urls[title][]" value="" autocomplete="off">'
            + '</label>'
            + '<label class="setting">'
                + '<span class="name">Caption</span>'
                + '<textarea name="_images_urls[caption][]"></textarea>'
            + '</label>'
            + '<label class="setting">'
                + '<span class="name">Alt Text</span>'
                + '<input type="text" name="_images_urls[alt][]" value="" autocomplete="off">'
            + '</label>'
            + '<label class="setting">'
                + '<span class="name">Link To URL</span>'
                + '<input type="text" name="_images_urls[link_url][]" value="" autocomplete="off">'
            + '</label>'
            + '<div class="actions">'
                + '<span><span class="dashicons dashicons-move"></span></span>'
                + '<span class="add_row"><span class="dashicons dashicons-plus-alt"></span></span>'
                + '<span class="delete_row"><span class="dashicons dashicons-trash"></span></span>'
            + '</div>'
        + '</div>';
        // Append new row
        $( this ).on('click', '.add_row', function(){
            $( this ).closest('.carousel_slider-fields').after(carouselSliderFields);
        });
        // Delete current row row
        $( this ).on('click', '.delete_row', function(){
            $( this ).closest('.carousel_slider-fields').remove();
        });
    });
    $('#carousel_slider_form').sortable();
    var carouselSliderBodyHeight = $( window ).height() - (38 + 48 + 32 + 30);
    $('.carousel_slider-modal-body').css('height', carouselSliderBodyHeight + 'px');

    // Gallery Images Editor
    var frame,
        isMultiple = true,
        images = CarouselSlider.image_ids,
        selection = loadImages(images);

    $('#carousel_slider_gallery_btn').on('click', function(e) {
        e.preventDefault();
        var options = {
            title: CarouselSlider.create_btn_text,
            state: 'gallery-edit',
            // frame: 'post',
            frame: 'post',
            selection: selection
        };

        if( frame || selection ) {
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
        frame.on( 'toolbar:render:gallery-edit', function() {
            overrideGalleryInsert();
        });

        frame.on( 'content:render:browse', function( browser ) {
            if ( !browser ) return;
            // Hide Gallery Settings in sidebar
            browser.sidebar.on('ready', function(){
                browser.sidebar.unset('gallery');
            });
            // Hide filter/search as they don't work
            browser.toolbar.on('ready', function(){
                if(browser.toolbar.controller._state == 'gallery-library'){
                    browser.toolbar.$el.hide();
                }
            });
        });

        // All images removed
        frame.state().get('library').on( 'remove', function() {
            var models = frame.state().get('library');
            if(models.length == 0){
                selection = false;
                $.post(ajaxurl, {
                    ids: '',
                    action: 'carousel_slider_save_images',
                    post_id: CarouselSlider.post_id,
                    nonce: CarouselSlider.nonce
                });
            }
        });

        function overrideGalleryInsert(){
            frame.toolbar.get('view').set({
                insert: {
                    style: 'primary',
                    text: CarouselSlider.save_btn_text,
                    click: function(){
                        var models = frame.state().get('library'),
                            ids = '';

                        models.each( function( attachment ) {
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
                            success: function(){
                                selection = loadImages(ids);
                                $('#_carousel_slider_images_ids').val( ids );
                                frame.close();
                            },
                            dataType: 'html'
                        }).done( function( data ) {
                            $('.carousel_slider_gallery_list').html( data );
                            console.log(data);
                        });
                    }
                }
            });
        }

    });

    function loadImages(images){
        if (images){
            var shortcode = new wp.shortcode({
                tag:      'gallery',
                attrs:    { ids: images },
                type:     'single'
            });

            var attachments = wp.media.gallery.attachments( shortcode );

            var selection = new wp.media.model.Selection( attachments.models, {
                props:    attachments.props.toJSON(),
                multiple: true
            });

            selection.gallery = attachments.gallery;

            selection.more().done( function() {
                // Break ties with the query.
                selection.props.set({ query: false });
                selection.unmirror();
                selection.props.unset('orderby');
            });

            return selection;
        }
        return false;
    }
})(jQuery);