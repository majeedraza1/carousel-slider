<?php

if ( ! class_exists( 'Carousel_Slider_Content_Carousel' ) ):

	class Carousel_Slider_Content_Carousel {

		protected static $instance = null;

		/**
		 * Ensures only one instance of this class is loaded or can be loaded.
		 *
		 * @return Carousel_Slider_Content_Carousel
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {
			add_action( 'wp_ajax_add_content_slide', array( $this, 'add_slide_template' ) );
		}

		public function add_slide_template() {
			$post_id   = absint( $_POST['post_id'] );
			$task      = isset( $_POST['task'] ) ? esc_attr( $_POST['task'] ) : 'add-slide';
			$slide_pos = isset( $_POST['slide_pos'] ) ? absint( $_POST['slide_pos'] ) : null;

			$slider = get_post_meta( $post_id, '_content_slider', true );

			if ( $task == 'add-slide' ) {
				$slider = $this->add_slide( $post_id, $slider );
			}
			if ( $task == 'delete-slide' && ! is_null( $slide_pos ) ) {
				$slider = $this->delete_slide( $slide_pos, $post_id, $slider );
			}
			if ( $task == 'move-slide-top' && ! is_null( $slide_pos ) ) {
				$slider = $this->move_slide_top( $slide_pos, $slider, $post_id );
			}
			if ( $task == 'move-slide-up' && ! is_null( $slide_pos ) ) {
				$slider = $this->move_slide_up( $slide_pos, $slider, $post_id );
			}
			if ( $task == 'move-slide-down' && ! is_null( $slide_pos ) ) {
				$slider = $this->move_slide_down( $slider, $slide_pos, $post_id );
			}
			if ( $task == 'move-slide-bottom' && ! is_null( $slide_pos ) ) {
				$slider = $this->move_slide_bottom( $slider, $slide_pos, $post_id );
			}

			echo isset( $slider ) ? json_encode( $slider ) : '';
			wp_die();
		}

		private function content_slide_default() {
			$data = array(
				'content'          => '',
				'bg_color'         => 'rgba(0,0,0,0.6)',
				'img_id'           => '',
				'img_bg_position'  => 'center center',
				'img_bg_size'      => 'contain',
				'link_url'         => '',
				'link_target'      => '',
				'popup_type'       => 'image', // Image, Video, HTML
				'popup_img_id'     => '',
				'popup_img_title'  => '',
				'popup_video_id'   => '',
				'popup_video_type' => '',
				'popup_html'       => '',
				'popup_bg_color'   => 'rgba(0,0,0,0.6)',
				'popup_width'      => '',
			);

			return $data;
		}

		/**
		 * Add new slide
		 *
		 * @param $post_id
		 * @param $content_slider
		 *
		 * @return array
		 */
		private function add_slide( $post_id, $content_slider ) {
			$default = $this->content_slide_default();
			if ( is_array( $content_slider ) && count( $content_slider ) > 0 ) {
				$content_slider[] = $default;
			} else {
				$content_slider = array( $default );
			}
			update_post_meta( $post_id, '_content_slider', $content_slider );

			return $content_slider;
		}

		/**
		 * Delete a slide
		 *
		 * @param $slide_position
		 * @param $post_id
		 * @param $slider
		 *
		 * @return mixed
		 */
		private function delete_slide( $slide_position, $post_id, $slider ) {
			array_splice( $slider, $slide_position, 1 );
			update_post_meta( $post_id, '_content_slider', $slider );

			return $slider;
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
		 * @param $slide_pos
		 * @param $slider
		 * @param $post_id
		 *
		 * @return mixed
		 */
		private function move_slide_top( $slide_pos, $slider, $post_id ) {
			if ( $slide_pos !== 0 ) {
				$slider = $this->move_array_element( $slider, $slide_pos, 0 );
				update_post_meta( $post_id, '_content_slider', $slider );
			}

			return $slider;
		}

		/**
		 * @param $slide_pos
		 * @param $slider
		 * @param $post_id
		 *
		 * @return mixed
		 */
		private function move_slide_up( $slide_pos, $slider, $post_id ) {
			if ( $slide_pos !== 0 ) {
				$slider = $this->move_array_element( $slider, $slide_pos, ( $slide_pos - 1 ) );
				update_post_meta( $post_id, '_content_slider', $slider );
			}

			return $slider;
		}

		/**
		 * @param $slider
		 * @param $slide_pos
		 * @param $post_id
		 *
		 * @return array
		 */
		private function move_slide_down( $slider, $slide_pos, $post_id ) {
			$last_index = count( $slider ) - 1;
			if ( $slide_pos !== $last_index ) {
				$slider = $this->move_array_element( $slider, $slide_pos, ( $slide_pos + 1 ) );
				update_post_meta( $post_id, '_content_slider', $slider );
			}

			return $slider;
		}

		/**
		 * @param $slider
		 * @param $slide_pos
		 * @param $post_id
		 *
		 * @return array
		 */
		private function move_slide_bottom( $slider, $slide_pos, $post_id ) {
			$last_index = count( $slider ) - 1;
			if ( $slide_pos !== $last_index ) {
				$slider = $this->move_array_element( $slider, $slide_pos, $last_index );
				update_post_meta( $post_id, '_content_slider', $slider );
			}

			return $slider;
		}
	}

endif;

Carousel_Slider_Content_Carousel::init();
