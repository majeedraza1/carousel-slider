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