<?php

use CarouselSlider\Helper;
use CarouselSlider\Modules\ImageCarousel\ExternalImageItem;
use CarouselSlider\Modules\ImageCarousel\Setting;

defined( 'ABSPATH' ) || die;

/**
 * The global variable that are available to use here
 *
 * This template can be overridden by copying it to yourtheme/carousel-slider/loop/image-carousel-url.php.
 *
 * @global Setting $setting Slider setting object.
 * @global ExternalImageItem $object The Image Carousel Item object.
 */
?>
<div class="carousel-slider__item">
	<?php
	// Print start anchor tag for lightbox and external link.
	Helper::print_unescaped_internal_string( $object->get_link_html_start( $setting->get_image_target() ) );

	// Print image.
	Helper::print_unescaped_internal_string( $object->get_image_html( $setting->lazy_load_image() ) );

	if ( $setting->should_show_title() || $setting->should_show_caption() ) {
		echo '<div class="carousel-slider__caption">';

		if ( $setting->should_show_title() ) {
			echo '<h4 class="title">' . esc_html( $object->get_title() ) . '</h4>';
		}
		if ( $setting->should_show_caption() ) {
			echo '<p class="caption">' . esc_html( $object->get_caption() ) . '</p>';
		}

		echo '</div>';
	}

	// Print end anchor tag for lightbox and external link.
	Helper::print_unescaped_internal_string( $object->get_link_html_end() );

	// For swiper, add placeholder element for lazy loading.
	if ( $setting->is_using_swiper() && $setting->lazy_load_image() ) {
		echo '<div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>';
	}
	?>
</div>
