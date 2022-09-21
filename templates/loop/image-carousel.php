<?php

use CarouselSlider\Helper;
use CarouselSlider\Modules\ImageCarousel\Item;
use CarouselSlider\Modules\ImageCarousel\Setting;

defined( 'ABSPATH' ) || die;

/**
 * The global variable that are available to use here
 *
 * This template can be overridden by copying it to yourtheme/carousel-slider/loop/image-carousel.php.
 *
 * @global Setting $setting Slider setting object.
 * @global Item $object The Image Carousel Item object.
 */
$full_img     = $object->get_image_src( 'full' );
$link_context = $setting->should_show_lightbox() ? 'lightbox' : 'link';
?>
<div class="carousel-slider__item">
	<?php
	// Print start anchor tag for lightbox and external link.
	Helper::print_unescaped_internal_string( $object->get_link_html_start( $link_context, $setting->get_image_target() ) );

	// Print image.
	if ( $setting->lazy_load_image() ) {
		$image_src  = $object->get_image_src( $setting->get_image_size() );
		$lazy_class = $setting->is_using_swiper() ? 'swiper-lazy' : 'owl-lazy';
		echo '<img class="' . esc_attr( $lazy_class ) . '" data-src="' . esc_attr( $image_src[0] ) . '" alt="' . esc_attr( $object->get_alt_text() ) . '">';
	} else {
		Helper::print_unescaped_internal_string( $object->get_image( $setting->get_image_size() ) );
	}

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
	Helper::print_unescaped_internal_string( $object->get_link_html_end( $link_context ) );

	// For swiper, add placeholder element for lazy loading.
	if ( $setting->is_using_swiper() && $setting->lazy_load_image() ) {
		echo '<div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>';
	}
	?>
</div>
