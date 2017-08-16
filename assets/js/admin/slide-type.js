(function ($) {
    "use strict";

    if ($('#_carousel_slider_slide_type').val() == 'post-carousel') {
        var _postQueryType = $('#_post_query_type').val();
        if (_postQueryType == 'latest_posts') {
        }
        if (_postQueryType == 'date_range') {
            $('#field-_post_date_after').show();
            $('#field-_post_date_before').show();
        }
        if (_postQueryType == 'post_categories') {
            $('#field-_post_categories').show();
        }
        if (_postQueryType == 'post_tags') {
            $('#field-_post_tags').show();
        }
        if (_postQueryType == 'specific_posts') {
            $('#field-_post_in').show();
            $('#field-_posts_per_page').hide();
        }
    }

    $('#_post_query_type').on('change', function () {

        $('#field-_post_date_after').hide('fast');
        $('#field-_post_date_before').hide('fast');
        $('#field-_post_categories').hide('fast');
        $('#field-_post_tags').hide('fast');
        $('#field-_post_in').hide('fast');
        $('#field-_posts_per_page').show('fast');

        if (this.value == 'date_range') {
            $('#field-_post_date_after').slideDown();
            $('#field-_post_date_before').slideDown();
        }
        if (this.value == 'post_categories') {
            $('#field-_post_categories').slideDown();
        }
        if (this.value == 'post_tags') {
            $('#field-_post_tags').slideDown();
        }
        if (this.value == 'specific_posts') {
            $('#field-_post_in').slideDown();
            $('#field-_posts_per_page').hide('fast');
        }
    });

    if ($('#_carousel_slider_slide_type').val() == 'product-carousel') {
        var _productQueryType = $('#_product_query_type').val();
        if (_productQueryType == 'query_porduct') {
            $('#field-_product_query').show();
        }
        if (_productQueryType == 'product_categories') {
            $('#field-_product_categories').show();
        }
        if (_productQueryType == 'product_tags') {
            $('#field-_product_tags').show();
        }
        if (_productQueryType == 'specific_products') {
            $('#field-_product_in').show();
        }
    }

    $('#_product_query_type').on('change', function () {

        $('#field-_product_query').hide('fast');
        $('#field-_product_categories').hide('fast');
        $('#field-_product_tags').hide('fast');
        $('#field-_product_in').hide('fast');
        $('#field-_products_per_page').show('fast');

        if (this.value == 'query_porduct') {
            $('#field-_product_query').slideDown();
        }
        if (this.value == 'product_categories') {
            $('#field-_product_categories').slideDown();
        }
        if (this.value == 'product_tags') {
            $('#field-_product_tags').slideDown();
        }
        if (this.value == 'specific_products') {
            $('#field-_product_in').slideDown();
            $('#field-_products_per_page').hide('fast');
        }
    });

    $('#_carousel_slider_slide_type').on('change', function () {

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
})(jQuery);
