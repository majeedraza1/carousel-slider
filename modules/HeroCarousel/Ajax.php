<?php

namespace CarouselSlider\Modules\HeroCarousel;

defined( 'ABSPATH' ) || exit;

/**
 * Ajax class
 *
 * @package Modules/HeroCarousel
 */
class Ajax {

	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			add_action( 'wp_ajax_add_content_slide', [ self::$instance, 'add_slide_template' ] );
		}

		return self::$instance;
	}

	/**
	 * Add slider template
	 *
	 * @return void
	 */
	public function add_slide_template() {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( ! isset( $_POST['post_id'] ) ) {
			wp_send_json( __( 'Required attribute is not set properly.', 'carousel-slider' ), 422 );
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$task = isset( $_POST['task'] ) ? sanitize_text_field( $_POST['task'] ) : 'add-slide';

		$slider_content = get_post_meta( $post_id, '_content_slider', true );
		$slider_content = is_array( $slider_content ) ? array_values( $slider_content ) : [];

		if ( 'add-slide' === $task ) {
			$new_content = $this->add_new_item( $post_id, $slider_content );
			wp_send_json( $new_content, 201 );
		}

		$last_index    = count( $slider_content ) - 1;
		$current_index = $this->get_current_index( $last_index );
		if ( - 1 === $current_index ) {
			wp_send_json_error();
		}

		$new_index = [
			'move-slide-top'    => 0,
			'move-slide-up'     => $current_index - 1,
			'move-slide-down'   => $current_index + 1,
			'move-slide-bottom' => $last_index,
		];

		if ( 'delete-slide' === $task ) {
			array_splice( $slider_content, $current_index, 1 );
		}

		if ( array_key_exists( $task, $new_index ) ) {
			$slider_content = $this->move_array_element( $slider_content, $current_index, $new_index[ $task ] );
		}

		$slider_content = array_values( $slider_content );

		update_post_meta( $post_id, '_content_slider', $slider_content );
		wp_send_json( $slider_content );
	}

	/**
	 * Move array element position
	 *
	 * @param  array $array  Array content.
	 * @param  int   $current_index  The current index.
	 * @param  int   $new_index  The new index.
	 *
	 * @return array
	 */
	private function move_array_element( array $array, int $current_index, int $new_index ): array {
		$output = array_splice( $array, $current_index, 1 );
		array_splice( $array, $new_index, 0, $output );

		return $array;
	}

	/**
	 * Add new item
	 *
	 * @param  int   $post_id  The post id.
	 * @param  array $slider_content  The slider content.
	 *
	 * @return array
	 */
	protected function add_new_item( int $post_id, array $slider_content ): array {
		$data             = [
			'slide_heading'     => 'Slide Heading',
			'slide_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas, magnam!',
		];
		$slider_content[] = wp_parse_args( $data, Item::get_default() );
		update_post_meta( $post_id, '_content_slider', $slider_content );

		return $slider_content;
	}

	/**
	 * Get current index
	 *
	 * @param  int $last_index  Last slider index.
	 *
	 * @return int
	 */
	protected function get_current_index( int $last_index ): int {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$index = isset( $_POST['slide_pos'] ) && is_numeric( $_POST['slide_pos'] ) ? absint( $_POST['slide_pos'] ) : - 1;
		if ( in_array( $index, range( 0, $last_index ), true ) ) {
			return $index;
		}

		return - 1;
	}
}
