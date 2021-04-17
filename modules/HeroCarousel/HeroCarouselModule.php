<?php

namespace CarouselSlider\Modules\HeroCarousel;

use CarouselSlider\Frontend\Shortcode;
use CarouselSlider\Helper;

defined( 'ABSPATH' ) || exit;

class HeroCarouselModule {
	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	protected static $instance;

	/**
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_filter( 'carousel_slider/view', [ self::$instance, 'view' ], 10, 3 );
			add_action( 'carousel_slider/save_slider', [ self::$instance, 'save_slider' ] );

			Ajax::init();
		}

		return self::$instance;
	}

	/**
	 * @param string $html
	 * @param int $slider_id
	 * @param string $slider_type
	 *
	 * @return string
	 */
	public function view( string $html, int $slider_id, string $slider_type ): string {
		if ( 'hero-banner-slider' == $slider_type ) {
			return static::get_view( $slider_id );
		}

		return $html;
	}

	public static function get_view( int $slider_id ): string {
		$items             = get_post_meta( $slider_id, '_content_slider', true );
		$lazy_load_image   = get_post_meta( $slider_id, '_lazy_load_image', true );
		$be_lazy           = in_array( $lazy_load_image, array( 'on', 'off' ) ) ? $lazy_load_image : 'on';
		$settings          = get_post_meta( $slider_id, '_content_slider_settings', true );
		$content_animation = ! empty( $settings['content_animation'] ) ? esc_attr( $settings['content_animation'] ) : '';

		$css_classes = [
			"carousel-slider-outer",
			"carousel-slider-outer-contents",
			"carousel-slider-outer-$slider_id"
		];
		$css_vars    = Helper::get_css_variable( $slider_id );
		$styles      = Helper::array_to_style( $css_vars );
		$options     = join( " ", ( new Shortcode )->carousel_options( $slider_id ) );

		$html = '<div class="' . join( ' ', $css_classes ) . '" style="' . $styles . '">';
		$html .= '<div data-animation="' . $content_animation . '" ' . $options . '>';
		foreach ( $items as $slide_id => $slide ) {
			$args = array_merge( $settings, [
				'item_id'         => $slide_id,
				'slider_id'       => $slider_id,
				'lazy_load_image' => $be_lazy
			] );
			$item = new Item( $slide, $args );
			$html .= $item->get_view();
		}
		$html .= '</div>';
		$html .= '</div>';

		return apply_filters( 'carousel_slider_hero_banner_carousel', $html, $slider_id );
	}

	/**
	 * Save slider content and settings
	 *
	 * @param int $slider_id
	 */
	public function save_slider( int $slider_id ) {
		if ( isset( $_POST['carousel_slider_content'] ) ) {
			$_content_slides = is_array( $_POST['carousel_slider_content'] ) ? $_POST['carousel_slider_content'] : [];
			$_slides         = array_map( function ( $slide ) {
				return Item::sanitize( $slide );
			}, $_content_slides );

			update_post_meta( $slider_id, '_content_slider', $_slides );
		}

		if ( isset( $_POST['content_settings'] ) ) {
			$this->update_content_settings( $slider_id );
		}
	}

	/**
	 * Update hero carousel settings
	 *
	 * @param int $post_id post id
	 */
	private function update_content_settings( int $post_id ) {
		$setting   = $_POST['content_settings'];
		$_settings = [
			'slide_height'      => sanitize_text_field( $setting['slide_height'] ),
			'content_width'     => sanitize_text_field( $setting['content_width'] ),
			'content_animation' => sanitize_text_field( $setting['content_animation'] ),
			'slide_padding'     => [
				'top'    => sanitize_text_field( $setting['slide_padding']['top'] ),
				'right'  => sanitize_text_field( $setting['slide_padding']['right'] ),
				'bottom' => sanitize_text_field( $setting['slide_padding']['bottom'] ),
				'left'   => sanitize_text_field( $setting['slide_padding']['left'] ),
			],
		];
		update_post_meta( $post_id, '_content_slider_settings', $_settings );
	}
}
