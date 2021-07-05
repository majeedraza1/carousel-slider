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

		$post_id   = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
		$task      = isset( $_POST['task'] ) ? sanitize_text_field( $_POST['task'] ) : 'add-slide';
		$slide_pos = isset( $_POST['slide_pos'] ) && is_numeric( $_POST['slide_pos'] ) ? absint( $_POST['slide_pos'] ) : null;

		$slider_content = get_post_meta( $post_id, '_content_slider', true );
		$slider_content = is_array( $slider_content ) ? $slider_content : [];
		$last_index     = count( $slider_content ) - 1;

		if ( $task == 'add-slide' ) {
			$data             = [
				'slide_heading'     => 'Slide Heading',
				'slide_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas, magnam!',
			];
			$slider_content[] = wp_parse_args( $data, Item::get_default() );
		}
		if ( $task == 'delete-slide' && ! is_null( $slide_pos ) ) {
			array_splice( $slider_content, $slide_pos, 1 );
		}
		if ( $task == 'move-slide-top' && ! is_null( $slide_pos ) && $slide_pos !== 0 ) {
			$slider_content = $this->move_array_element( $slider_content, $slide_pos, 0 );
		}
		if ( $task == 'move-slide-up' && ! is_null( $slide_pos ) ) {
			$slider_content = $this->move_array_element( $slider_content, $slide_pos, $slide_pos - 1 );
		}
		if ( $task == 'move-slide-down' && ! is_null( $slide_pos ) && $slide_pos !== $last_index ) {
			$slider_content = $this->move_array_element( $slider_content, $slide_pos, ( $slide_pos + 1 ) );
		}
		if ( $task == 'move-slide-bottom' && ! is_null( $slide_pos ) && $slide_pos !== $last_index ) {
			$slider_content = $this->move_array_element( $slider_content, $slide_pos, $last_index );
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
}
