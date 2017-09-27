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
