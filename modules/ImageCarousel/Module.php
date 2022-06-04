<?php

namespace CarouselSlider\Modules\ImageCarousel;

use CarouselSlider\Supports\Validate;
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Module class
 *
 * @package Modules/ImageCarousel
 */
class Module {
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

			add_filter( 'carousel_slider/register_view', [ self::$instance, 'view' ] );

			// Add custom link to media gallery.
			add_filter( 'attachment_fields_to_edit', [ self::$instance, 'attachment_fields_to_edit' ], 10, 2 );
			add_filter( 'attachment_fields_to_save', [ self::$instance, 'attachment_fields_to_save' ], 10, 2 );

			Admin::init();
		}

		return self::$instance;
	}

	/**
	 * Register view
	 *
	 * @param array $views Registered views.
	 *
	 * @return array
	 */
	public function view( array $views ): array {
		$views['image-carousel']     = new View();
		$views['image-carousel-url'] = new UrlView();

		return $views;
	}


	/**
	 * Adding our custom fields to the $form_fields array
	 *
	 * @param array   $form_fields The form fields.
	 * @param WP_Post $post The WP_Post object.
	 *
	 * @return array
	 */
	public function attachment_fields_to_edit( array $form_fields, WP_Post $post ): array {
		$value = get_post_meta( $post->ID, '_carousel_slider_link_url', true );
		$field = [
			'label'      => __( 'Link to URL', 'carousel-slider' ),
			'input'      => 'textarea',
			'value'      => $value,
			'extra_rows' => [
				'carouselSliderInfo' => __( '"Link to URL" only works on Carousel Slider for linking image to a custom url.', 'carousel-slider' ),
			],
		];

		$form_fields['carousel_slider_link_url'] = $field;

		return $form_fields;
	}

	/**
	 * Save custom field value
	 *
	 * @param array $post The post object as array.
	 * @param array $attachment Attachment data.
	 *
	 * @return array
	 */
	public function attachment_fields_to_save( array $post, array $attachment ): array {
		$slider_link_url = $attachment['carousel_slider_link_url'] ?? null;

		if ( Validate::url( $slider_link_url ) ) {
			update_post_meta( $post['ID'], '_carousel_slider_link_url', esc_url_raw( $slider_link_url ) );
		} else {
			delete_post_meta( $post['ID'], '_carousel_slider_link_url' );
		}

		return $post;
	}
}
