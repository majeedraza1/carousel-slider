<?php

namespace CarouselSlider\Modules\HeroCarousel;

use CarouselSlider\Abstracts\Data;
use CarouselSlider\Abstracts\SliderSetting;
use CarouselSlider\Helper;
use CarouselSlider\Supports\Sanitize;
use CarouselSlider\Supports\Validate;

defined( 'ABSPATH' ) || exit;

/**
 * Item class
 *
 * @package Modules/HeroCarousel
 */
class Item extends Data {
	/**
	 * Default content
	 *
	 * @var string[]
	 */
	protected static $default = [
		// Slide Content.
		'slide_heading'            => '',
		'slide_description'        => '',
		// Slide Background.
		'img_id'                   => '',
		'img_bg_position'          => 'center center',
		'img_bg_size'              => 'contain',
		'bg_color'                 => 'rgba(0,0,0,0.6)',
		'ken_burns_effect'         => '',
		'bg_overlay'               => '',
		// Slide Style.
		'content_alignment'        => 'center',
		'heading_font_size'        => '40',
		'heading_gutter'           => '30px',
		'heading_color'            => '#ffffff',
		'description_font_size'    => '20',
		'description_gutter'       => '30px',
		'description_color'        => '#ffffff',
		// Slide Link.
		'link_type'                => 'none',
		'slide_link'               => '',
		'link_target'              => '_self',
		// Slide Button #1.
		'button_one_text'          => '',
		'button_one_url'           => '',
		'button_one_target'        => '_self',
		'button_one_type'          => 'stroke',
		'button_one_size'          => 'medium',
		'button_one_border_width'  => '3px',
		'button_one_border_radius' => '0px',
		'button_one_bg_color'      => '#ffffff',
		'button_one_color'         => '#323232',
		// Slide Button #2.
		'button_two_text'          => '',
		'button_two_url'           => '',
		'button_two_target'        => '_self',
		'button_two_type'          => 'stroke',
		'button_two_size'          => 'medium',
		'button_two_border_width'  => '3px',
		'button_two_border_radius' => '0px',
		'button_two_bg_color'      => '#ffffff',
		'button_two_color'         => '#323232',
	];

	/**
	 * Slider settings
	 *
	 * @var array
	 */
	protected $slider_settings = [];

	/**
	 * SliderSetting class
	 *
	 * @var SliderSetting
	 */
	protected $setting;

	/**
	 * Class constructor.
	 *
	 * @param array $args Optional arguments.
	 * @param array $slider_settings Slider settings.
	 */
	public function __construct( array $args = [], array $slider_settings = [] ) {
		$this->data            = wp_parse_args( $args, self::get_default() );
		$this->slider_settings = $slider_settings;
	}

	/**
	 * Get slider setting
	 *
	 * @return SliderSetting
	 */
	public function get_setting(): SliderSetting {
		return $this->setting;
	}

	/**
	 * Set setting
	 *
	 * @param Setting|SliderSetting $setting The SliderSetting object.
	 */
	public function set_setting( Setting $setting ) {
		$this->setting         = $setting;
		$this->slider_settings = $this->setting->get_content_settings();
	}

	/**
	 * Get default data
	 *
	 * @return string[]
	 */
	public static function get_default(): array {
		return self::$default;
	}

	/**
	 * Sanitize item data
	 *
	 * @param array $data The data to be sanitized.
	 *
	 * @return array
	 */
	public static function sanitize( array $data ): array {
		$color_fields  = [
			'bg_color',
			'bg_overlay',
			'heading_color',
			'description_color',
			'button_one_bg_color',
			'button_one_color',
			'button_two_bg_color',
			'button_two_color',
		];
		$data          = wp_parse_args( $data, self::get_default() );
		$sanitize_data = [];
		foreach ( $data as $key => $value ) {
			if ( in_array( $key, [ 'slide_heading', 'slide_description' ], true ) ) {
				$sanitize_data[ $key ] = Sanitize::html( $value );
			} elseif ( in_array( $key, [ 'img_id', 'heading_font_size', 'description_font_size' ], true ) ) {
				$sanitize_data[ $key ] = Sanitize::int( $value );
			} elseif ( in_array( $key, [ 'slide_link', 'button_one_url', 'button_two_url' ], true ) ) {
				$sanitize_data[ $key ] = Sanitize::url( $value );
			} elseif ( in_array( $key, $color_fields, true ) ) {
				$sanitize_data[ $key ] = Sanitize::color( $value );
			} else {
				$sanitize_data[ $key ] = Sanitize::text( $value );
			}
		}

		return $sanitize_data;
	}

	/**
	 * Lazy load image
	 *
	 * @return bool
	 */
	public function lazy_load_image(): bool {
		return $this->setting->lazy_load_image();
	}

	/**
	 * Get slider id
	 *
	 * @return int
	 */
	public function get_slider_id(): int {
		return $this->setting->get_slider_id();
	}

	/**
	 * Get item id
	 *
	 * @return int
	 */
	public function get_item_id(): int {
		return (int) $this->get_prop( 'id' );
	}

	/**
	 * Get link type
	 *
	 * @return string
	 */
	public function get_link_type(): string {
		$link_type = $this->get_prop( 'link_type', 'full' );

		return in_array( $link_type, [ 'full', 'button' ], true ) ? $link_type : 'full';
	}

	/**
	 * Get slide padding
	 *
	 * @return array
	 */
	public function get_slide_padding(): array {
		$default       = [
			'top'    => '1rem',
			'right'  => '3rem',
			'bottom' => '1rem',
			'left'   => '3rem',
		];
		$slide_padding = isset( $this->slider_settings['slide_padding'] ) && is_array( $this->slider_settings['slide_padding'] ) ?
			$this->slider_settings['slide_padding'] : [];

		return wp_parse_args( $slide_padding, $default );
	}

	/**
	 * Get content width
	 *
	 * @return mixed|string
	 */
	public function get_content_width() {
		return $this->slider_settings['content_width'] ?? '800px';
	}

	/**
	 * Get Slider height
	 *
	 * @return mixed|string
	 */
	public function get_slide_height() {
		return $this->slider_settings['slide_height'] ?? '300px';
	}

	/**
	 * Get content animation
	 *
	 * @return mixed|string
	 */
	public function get_content_animation() {
		if ( $this->has_prop( 'content_animation' ) ) {
			return $this->get_prop( 'content_animation' );
		}

		return $this->slider_settings['content_animation'] ?? '';
	}

	/**
	 * Get item view
	 *
	 * @return string
	 */
	public function get_view(): string {
		$html = $this->get_cell_start();

		$html .= $this->get_cell_background();

		$html .= $this->get_cell_inner_start();

		// Background Overlay.
		$bg_overlay = $this->get_prop( 'bg_overlay' );
		if ( ! empty( $bg_overlay ) ) {
			$overlay_style = 'background-color: ' . $bg_overlay . ';';

			$html .= '<div class="carousel-slider-hero__cell__background_overlay" style="' . $overlay_style . '"></div>';
		}

		$cell_content_attr = [
			'class'          => 'carousel-slider-hero__cell__content hidden',
			'style'          => 'max-width:' . $this->get_content_width(),
			'data-animation' => $this->get_content_animation(),
		];

		$html .= '<div ' . join( ' ', Helper::array_to_attribute( $cell_content_attr ) ) . '>';

		// Slide Heading.
		$html .= $this->get_heading();

		// Slide Description.
		$html .= $this->get_description();

		if ( 'button' === $this->get_link_type() ) {
			$html .= '<div class="carousel-slider-hero__cell__buttons">';
			$html .= $this->get_button_one();
			$html .= $this->get_button_two();
			$html .= '</div>'; // .carousel-slider-hero__cell__buttons
		}

		$html .= '</div>';// .carousel-slider-hero__cell__content
		$html .= '</div>';// .carousel-slider-hero__cell__inner

		$html .= $this->get_cell_end();

		return apply_filters( 'carousel_slider_content', $html, $this->to_array(), $this->get_item_id() );
	}

	/**
	 * Get background
	 *
	 * @return string
	 */
	public function get_cell_background(): string {
		// Slide Background.
		$img_bg_position  = $this->get_prop( 'img_bg_position' );
		$img_bg_size      = $this->get_prop( 'img_bg_size' );
		$bg_color         = $this->get_prop( 'bg_color' );
		$ken_burns_effect = $this->get_prop( 'ken_burns_effect' );
		$img_id           = $this->get_prop( 'img_id' );
		$img_src          = wp_get_attachment_image_src( $img_id, 'full' );
		$have_img         = is_array( $img_src ) && Validate::url( $img_src[0] );

		$styles = [
			'background-position' => $img_bg_position,
			'background-size'     => $img_bg_size,
		];
		if ( $have_img && ! $this->lazy_load_image() ) {
			$styles['background-image'] = "url($img_src[0])";
		}
		if ( ! empty( $bg_color ) ) {
			$styles['background-color'] = $bg_color;
		}

		$_slide_bg_class = 'carousel-slider-hero__cell__background';

		if ( $this->lazy_load_image() ) {
			$_slide_bg_class .= Helper::is_using_swiper() ? ' swiper-lazy' : ' owl-lazy';
		}

		if ( 'zoom-in' === $ken_burns_effect ) {
			$_slide_bg_class .= ' carousel-slider-hero-ken-in';
		} elseif ( 'zoom-out' === $ken_burns_effect ) {
			$_slide_bg_class .= ' carousel-slider-hero-ken-out';
		}

		$attrs = [
			'id'    => sprintf( 'slide-item-%s-%s', $this->get_slider_id(), $this->get_item_id() ),
			'class' => $_slide_bg_class,
			'style' => Helper::array_to_style( $styles ),
		];

		if ( $have_img && $this->lazy_load_image() ) {
			if ( Helper::is_using_swiper() ) {
				$attrs['data-background'] = $img_src[0];
			} else {
				$attrs['data-src'] = $img_src[0];
			}
		}

		return '<div ' . implode( ' ', Helper::array_to_attribute( $attrs ) ) . '></div>';
	}

	/**
	 * Get cell inner start content
	 *
	 * @return string
	 */
	public function get_cell_inner_start(): string {
		$slide_padding = $this->get_slide_padding();
		$alignment     = $this->get_prop( 'content_alignment', 'left' );
		$alignment     = in_array( $alignment, [ 'left', 'center', 'right' ], true ) ? $alignment : 'left';

		$classes = [
			'carousel-slider-hero__cell__inner',
			'carousel-slider--h-position-center',
			'carousel-slider--v-position-middle',
			'carousel-slider--text-' . $alignment,
		];

		$styles = [
			'padding-top'    => $slide_padding['top'],
			'padding-right'  => $slide_padding['right'],
			'padding-bottom' => $slide_padding['bottom'],
			'padding-left'   => $slide_padding['left'],
		];

		return '<div class="' . implode( ' ', $classes ) . '" style="' . Helper::array_to_style( $styles ) . '">';
	}

	/**
	 * Get heading
	 *
	 * @return string
	 */
	public function get_heading(): string {
		$html          = '';
		$slide_heading = $this->get_prop( 'slide_heading' );
		if ( empty( $slide_heading ) ) {
			return $html;
		}
		$styles = [
			'--cs-heading-font-size' => (int) $this->get_prop( 'heading_font_size', 40 ) . 'px',
			'--cs-heading-gutter'    => $this->get_prop( 'heading_gutter', '30px' ),
			'--cs-heading-color'     => $this->get_prop( 'heading_color', '#ffffff' ),
		];

		$html .= '<div class="carousel-slider-hero__cell__heading" style="' . Helper::array_to_style( $styles ) . '">';
		$html .= wp_kses_post( $slide_heading );
		$html .= '</div>';

		return $html;
	}

	/**
	 * Get slide description
	 *
	 * @return string
	 */
	public function get_description(): string {
		$html              = '';
		$slide_description = $this->get_prop( 'slide_description' );
		if ( empty( $slide_description ) ) {
			return $html;
		}

		$styles = [
			'--cs-description-font-size' => (int) $this->get_prop( 'description_font_size', 20 ) . 'px',
			'--cs-description-gutter'    => $this->get_prop( 'description_gutter', '30px' ),
			'--cs-description-color'     => $this->get_prop( 'description_color', '#ffffff' ),
		];

		$html .= '<div class="carousel-slider-hero__cell__description" style="' . Helper::array_to_style( $styles ) . '">';
		$html .= wp_kses_post( $slide_description );
		$html .= '</div>';

		return $html;
	}

	/**
	 * Button one content
	 *
	 * @return string
	 */
	public function get_button_one(): string {
		$html = '';
		$url  = $this->get_prop( 'button_one_url' );
		if ( ! Validate::url( $url ) ) {
			return $html;
		}
		$btn_text = $this->get_prop( 'button_one_text' );
		$target   = $this->get_prop( 'button_one_target', '_self' );

		$classes  = 'button cs-hero-button';
		$classes .= ' cs-hero-button-' . $this->get_item_id() . '-1';
		$classes .= ' cs-hero-button-' . $this->get_prop( 'button_one_type', 'normal' );
		$classes .= ' cs-hero-button-' . $this->get_prop( 'button_one_size', 'medium' );

		$style = [
			'--cs-button-bg-color'      => $this->get_prop( 'button_one_bg_color', '#00d1b2' ),
			'--cs-button-color'         => $this->get_prop( 'button_one_color', '#ffffff' ),
			'--cs-button-border-width'  => $this->get_prop( 'button_one_border_width', '0px' ),
			'--cs-button-border-radius' => $this->get_prop( 'button_one_border_radius', '3px' ),
		];

		$html .= '<span class="carousel-slider-hero__cell__button__one" style="' . Helper::array_to_style( $style ) . '">';
		$html .= '<a class="' . $classes . '" href="' . $url . '" target="' . $target . '">' . esc_html( $btn_text ) . '</a>';
		$html .= '</span>';

		return $html;
	}

	/**
	 * Button two content
	 *
	 * @return string
	 */
	public function get_button_two(): string {
		$html = '';
		$url  = $this->get_prop( 'button_two_url' );
		if ( ! Validate::url( $url ) ) {
			return $html;
		}
		$text   = $this->get_prop( 'button_two_text' );
		$target = $this->get_prop( 'button_two_target', '_self' );

		$classes  = 'button cs-hero-button';
		$classes .= ' cs-hero-button-' . $this->get_item_id() . '-2';
		$classes .= ' cs-hero-button-' . $this->get_prop( 'button_two_type', 'normal' );
		$classes .= ' cs-hero-button-' . $this->get_prop( 'button_two_size', 'medium' );

		$style = [
			'--cs-button-bg-color'      => $this->get_prop( 'button_two_bg_color', '#00d1b2' ),
			'--cs-button-color'         => $this->get_prop( 'button_two_color', '#ffffff' ),
			'--cs-button-border-width'  => $this->get_prop( 'button_two_border_width', '0px' ),
			'--cs-button-border-radius' => $this->get_prop( 'button_two_border_radius', '3px' ),
		];

		$html .= '<span class="carousel-slider-hero__cell__button__two" style="' . Helper::array_to_style( $style ) . '">';
		$html .= '<a class="' . $classes . '" href="' . esc_url( $url ) . '" target="' . esc_attr( $target ) . '">' . esc_html( $text ) . '</a>';
		$html .= '</span>';

		return $html;
	}

	/**
	 * Get cell start html
	 *
	 * @return string
	 */
	public function get_cell_start(): string {
		$link_type    = $this->get_link_type();
		$slide_link   = $this->get_prop( 'slide_link' );
		$link_target  = $this->get_prop( 'link_target', '_self' );
		$is_full_link = 'full' === $link_type && Validate::url( $slide_link );

		$cell_attr = [
			'class' => 'carousel-slider-hero__cell hero__cell-' . $this->get_item_id(),
			'style' => '--cell-height: ' . $this->get_slide_height(),
		];
		if ( $is_full_link ) {
			$cell_attr = array_merge(
				$cell_attr,
				[
					'href'   => $slide_link,
					'target' => $link_target,
				]
			);
		}

		return '<' . ( $is_full_link ? 'a' : 'div' ) . ' ' . join( ' ', Helper::array_to_attribute( $cell_attr ) ) . '>';
	}

	/**
	 * Get cell end html
	 *
	 * @return string
	 */
	public function get_cell_end(): string {
		$is_full_link = 'full' === $this->get_link_type() && Validate::url( $this->get_prop( 'slide_link' ) );

		return $is_full_link ? '</a>' : '</div>'; // .carousel-slider-hero__cell
	}
}
