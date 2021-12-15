<?php

namespace CarouselSlider\Frontend;

defined( 'ABSPATH' ) || exit;

/**
 * Preview class
 */
class Preview {
	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	protected static $instance;

	/**
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_action( 'template_redirect', [ self::$instance, 'show_preview' ] );
		}

		return self::$instance;
	}

	/**
	 * Include custom template
	 *
	 * @return void
	 */
	public function show_preview() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $_GET['carousel_slider_preview'], $_GET['carousel_slider_iframe'], $_GET['slider_id'] ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_pages' ) ) {
			return;
		}
		add_filter( 'carousel_slider_load_scripts', '__return_true' );
		echo $this->preview_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit();
	}

	/**
	 * Preview html
	 *
	 * @return string
	 */
	public function preview_html(): string {
		ob_start();
		wp_head();
		$wp_head = ob_get_clean();

		ob_start();
		wp_footer();
		$wp_footer = ob_get_clean();

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$slider_id = isset( $_GET['slider_id'] ) ? intval( $_GET['slider_id'] ) : 0;

		$html  = '<!DOCTYPE html>' . PHP_EOL;
		$html .= '<html ' . get_language_attributes() . '>' . PHP_EOL;
		$html .= '<head>' . PHP_EOL;
		$html .= '<meta charset="' . get_bloginfo( 'charset' ) . '">' . PHP_EOL;
		$html .= '<meta name="viewport" content="width=device-width, initial-scale=1">' . PHP_EOL;
		$html .= $wp_head . PHP_EOL;
		$html .= '<style type="text/css" media="screen">
				html {margin-top: 0 !important;}
				* html body {margin-top: 0 !important;}
				#wpadminbar {display: none !important;}
				.carousel-slider-preview-container {max-width: 1024px;margin-left: auto;margin-right: auto;}
				@media screen and ( max-width: 782px ) {
					html {margin-top: 0 !important;}
					* html body {margin-top: 0 !important;}
				}
			</style>' . PHP_EOL;
		$html .= '</head>' . PHP_EOL;
		$html .= '</body>' . PHP_EOL;
		$html .= '<div class="carousel-slider-preview-container">' . PHP_EOL;
		$html .= do_shortcode( '[carousel_slide id="' . $slider_id . '"]' ) . PHP_EOL;
		$html .= '</div>' . PHP_EOL;
		$html .= $wp_footer . PHP_EOL;
		$html .= '<script type="text/javascript">
			(function () {
				if (window.frameElement) {
					window.frameElement.height = document.querySelector(".carousel-slider-preview-container").offsetHeight;
				}
			})();
		</script>' . PHP_EOL;
		$html .= '</body>' . PHP_EOL;
		$html .= '</html>';

		return $html;
	}
}
