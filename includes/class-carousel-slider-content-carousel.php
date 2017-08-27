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
			add_action( 'wp_ajax_add_content_slide', [ $this, 'add_slide_template' ] );
		}

		public function add_slide_template() {
			$content   = '';
			$editor_id = 'carousel_slider_content_two';
			$options   = array(
				'textarea_name' => 'slide_content[]',
				'textarea_rows' => 5,
			);
			echo '<div class="cs-editor">';
			echo $this->get_wp_editor( $content, $editor_id, $options );
			echo '</div>';
			die();
		}

		public function get_wp_editor( $content = '', $editor_id, $options = array() ) {
			ob_start();

			wp_editor( $content, $editor_id, $options );

			$editor = ob_get_clean();
			$editor .= \_WP_Editors::enqueue_scripts();
			$editor .= print_footer_scripts();
			$editor .= \_WP_Editors::editor_js();

			return $editor;
		}
	}

endif;

Carousel_Slider_Content_Carousel::init();
