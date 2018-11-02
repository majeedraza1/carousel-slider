<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

if ( ! class_exists( 'Carousel_Slider_Hero_Carousel' ) ):

	class Carousel_Slider_Hero_Carousel {

		protected static $instance = null;

		/**
		 * Ensures only one instance of this class is loaded or can be loaded.
		 *
		 * @return Carousel_Slider_Hero_Carousel
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

			if ( ! isset( $_POST['post_id'] ) ) {
				$this->send_json( __( 'Required attribute is not set properly.', 'carousel-slider' ), 422 );
			}

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

			if ( isset( $slider ) ) {
				$this->send_json( $slider );
			}

			$this->send_json( __( 'Required action is unauthorized.', 'carousel-slider' ), 401 );
		}

		private function content_slide_default() {
			$data = array(
				// Slide Content
				'slide_heading'            => 'Slide Heading',
				'slide_description'        => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas, magnam!',
				// Slide Background
				'img_id'                   => '',
				'img_bg_position'          => 'center center',
				'img_bg_size'              => 'cover',
				'bg_color'                 => 'rgba(0,0,0,0.6)',
				'ken_burns_effect'         => '',
				'bg_overlay'               => '',
				// Slide Style
				'content_alignment'        => 'center',
				'heading_font_size'        => '40',
				'heading_gutter'           => '30px',
				'heading_color'            => '#ffffff',
				'description_font_size'    => '20',
				'description_gutter'       => '30px',
				'description_color'        => '#ffffff',
				// Slide Link
				'link_type'                => 'none',
				'slide_link'               => '',
				'link_target'              => '_self',
				// Slide Button #1
				'button_one_text'          => '',
				'button_one_url'           => '',
				'button_one_target'        => '_self',
				'button_one_type'          => 'stroke',
				'button_one_size'          => 'medium',
				'button_one_border_width'  => '3px',
				'button_one_border_radius' => '0px',
				'button_one_bg_color'      => '#ffffff',
				'button_one_color'         => '#323232',
				// Slide Button #2
				'button_two_text'          => '',
				'button_two_url'           => '',
				'button_two_target'        => '_self',
				'button_two_type'          => 'stroke',
				'button_two_size'          => 'medium',
				'button_two_border_width'  => '3px',
				'button_two_border_radius' => '0px',
				'button_two_bg_color'      => '#ffffff',
				'button_two_color'         => '#323232',
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

		/**
		 * Send json response with status code
		 *
		 * @param string $response
		 * @param int $status_code
		 */
		private function send_json( $response, $status_code = 200 ) {
			@header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
			status_header( $status_code );

			echo wp_json_encode( $response );
			die;
		}
	}

endif;

Carousel_Slider_Hero_Carousel::init();
