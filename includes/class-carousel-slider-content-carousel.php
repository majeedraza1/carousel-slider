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
			$post_id = absint( $_POST['post_id'] );
			$default = $this->content_slide_default();

			$content_slider = get_post_meta( $post_id, '_content_slider', true );
			if ( is_array( $content_slider ) && count( $content_slider ) > 0 ) {
				$content_slider[] = $default;
				update_post_meta( $post_id, '_content_slider', $content_slider );
			} else {
				update_post_meta( $post_id, '_content_slider', array( $default ) );
			}
		}

		private function content_slide_default() {
			$data = array(
				'content'          => '',
				'bg_color'         => '',
				'img_id'           => '',
				'img_bg_position'  => '',
				'img_bg_Size'      => '',
				'link_url'         => '',
				'link_target'      => '',
				'popup_type'       => '', // Image, Video, HTML
				'popup_img_id'     => '',
				'popup_img_title'  => '',
				'popup_video_id'   => '',
				'popup_video_type' => '',
				'popup_html'       => '',
				'popup_bg_color'   => '',
				'popup_width'      => '',
			);

			return $data;
		}
	}

endif;

Carousel_Slider_Content_Carousel::init();
