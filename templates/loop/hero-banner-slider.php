<?php

defined( 'ABSPATH' ) || die;

/**
 * The global variable that are available to use here
 *
 * This template can be overridden by copying it to yourtheme/carousel-slider/loop/hero-banner-slider.php.
 *
 * @global \CarouselSlider\Modules\HeroCarousel\Setting $setting Slider setting object.
 * @global \CarouselSlider\Modules\HeroCarousel\Item $object Hero carousel item object.
 */

$html = $object->get_cell_start();

$html .= $object->get_cell_background();

$html .= $object->get_cell_inner_start();

// Background Overlay.
$bg_overlay = $object->get_prop( 'bg_overlay' );
if ( ! empty( $bg_overlay ) ) {
	$overlay_style = 'background-color: ' . $bg_overlay . ';';

	$html .= '<div class="carousel-slider-hero__cell__background_overlay" style="' . $overlay_style . '"></div>';
}

$cell_content_attr = [
	'class'          => 'carousel-slider-hero__cell__content hidden',
	'style'          => 'max-width:' . $object->get_content_width(),
	'data-animation' => $object->get_content_animation(),
];

$html .= '<div ' . join( ' ', \CarouselSlider\Helper::array_to_attribute( $cell_content_attr ) ) . '>';

// Slide Heading.
$html .= $object->get_heading();

// Slide Description.
$html .= $object->get_description();

if ( 'button' === $object->get_link_type() ) {
	$html .= '<div class="carousel-slider-hero__cell__buttons">';
	$html .= $object->get_button_one();
	$html .= $object->get_button_two();
	$html .= '</div>'; // .carousel-slider-hero__cell__buttons
}

$html .= '</div>';// .carousel-slider-hero__cell__content
$html .= '</div>';// .carousel-slider-hero__cell__inner

$html .= $object->get_cell_end();

\CarouselSlider\Helper::print_unescaped_internal_string( $html );
