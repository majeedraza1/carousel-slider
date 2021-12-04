<?php

namespace CarouselSlider\Modules\HeroCarousel;

defined( 'ABSPATH' ) || exit;

class Ajax {

	/**
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

	public function add_slide_template() {
		if ( ! isset( $_POST['post_id'] ) ) {
			wp_send_json( __( 'Required attribute is not set properly.', 'carousel-slider' ), 422 );
		}

		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
		$task    = isset( $_POST['task'] ) ? sanitize_text_field( $_POST['task'] ) : 'add-slide';

		$slider_content = get_post_meta( $post_id, '_content_slider', true );
		$slider_content = is_array( $slider_content ) ? $slider_content : [];

		if ( $task == 'add-slide' ) {
			$new_content = $this->add_new_item( $post_id, $slider_content );
			wp_send_json( $new_content, 201 );
		}

		$last_index    = count( $slider_content ) - 1;
		$current_index = $this->get_current_index( $last_index );
		if ( $current_index === - 1 ) {
			wp_send_json_error();
		}

		$new_index = [
			'move-slide-top'    => 0,
			'move-slide-up'     => $current_index - 1,
			'move-slide-down'   => $current_index + 1,
			'move-slide-bottom' => $last_index,
		];

		if ( $task == 'delete-slide' ) {
			array_splice( $slider_content, $current_index, 1 );
		}

		if ( array_key_exists( $task, $new_index ) ) {
			$slider_content = $this->move_array_element( $slider_content, $current_index, $new_index[ $task ] );
		}

		update_post_meta( $post_id, '_content_slider', $slider_content );
		wp_send_json( $slider_content );
	}

	/**
	 * Move array element position
	 *
	 * @param $array
	 * @param $current_index
	 * @param $new_index
	 *
	 * @return mixed
	 */
	private function move_array_element( $array, $current_index, $new_index ) {
		$output = array_splice( $array, $current_index, 1 );
		array_splice( $array, $new_index, 0, $output );

		return $array;
	}

	/**
	 * @param int $post_id
	 * @param array $slider_content
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
	 * @param int $last_index
	 *
	 * @return int
	 */
	protected function get_current_index( int $last_index ): int {
		$index = isset( $_POST['slide_pos'] ) && is_numeric( $_POST['slide_pos'] ) ? absint( $_POST['slide_pos'] ) : - 1;
		if ( in_array( $index, range( 0, $last_index ), true ) ) {
			return $index;
		}

		return - 1;
	}
}
