<?php

namespace CarouselSlider\Modules\VideoCarousel;

use CarouselSlider\Frontend\Shortcode;

defined( 'ABSPATH' ) || exit;

class VideoCarouselModule {
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

			add_action( 'carousel_slider/meta_box_content', [ self::$instance, 'meta_box_content' ], 10, 2 );
			add_action( 'carousel_slider/save_slider', [ self::$instance, 'save_slider' ] );
			add_filter( 'carousel_slider/view', [ self::$instance, 'view' ], 10, 3 );
		}

		return self::$instance;
	}

	/**
	 * Meta box content
	 *
	 * @param int $slider_id
	 * @param string $slider_type
	 */
	public function meta_box_content( int $slider_id, string $slider_type ) {
		$is_video_carousel = $slider_type == 'video-carousel';
		$urls              = get_post_meta( $slider_id, '_video_url', true );
		$description       = sprintf( '%s<br><br>%s %s',
			esc_html__( 'Only support youtube and vimeo. Enter video URL from youtube or vimeo separating each by comma', 'carousel-slider' ),
			esc_html__( 'Example:', 'carousel-slider' ),
			'https://www.youtube.com/watch?v=O4-EM32h7b4,https://www.youtube.com/watch?v=72IO4gzB8mU,https://vimeo.com/193773669,https://vimeo.com/193517656'
		);
		?>
		<div data-id="open" id="section_video_settings" class="shapla-toggle shapla-toggle--stroke"
			 style="display: <?php echo $is_video_carousel ? 'block' : 'none'; ?>">
			<span class="shapla-toggle-title">
				<?php esc_html_e( 'Video Settings', 'carousel-slider' ); ?>
			</span>
			<div class="shapla-toggle-inner">
				<div class="shapla-toggle-content">
					<div class="sp-input-group" id="field-_video_url">
						<div class="sp-input-label">
							<label for="_video_url"><?php esc_html_e( 'Video URLs', 'carousel-slider' ) ?></label>
							<p class="sp-input-desc"><?php echo $description ?></p>
						</div>
						<div class="sp-input-field">
							<textarea class="sp-input-textarea" id="_video_url" cols="35" rows="6"
									  name="_video_url"><?php echo esc_textarea( $urls ) ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Save slider video url
	 *
	 * @param int $slider_id
	 */
	public function save_slider( int $slider_id ) {
		if ( isset( $_POST['_video_url'] ) ) {
			$urls          = is_string( $_POST['_video_url'] ) ? explode( ',', $_POST['_video_url'] ) : $_POST['_video_url'];
			$sanitize_urls = [];
			if ( is_array( $urls ) ) {
				foreach ( $urls as $url ) {
					if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {
						$sanitize_urls[] = $url;
					}
				}
			}
			update_post_meta( $slider_id, '_video_url', implode( ',', $sanitize_urls ) );
		}
	}

	/**
	 * Get slider CSS style variables
	 *
	 * @param int $slider_id
	 *
	 * @return array
	 */
	public static function get_css_variable( int $slider_id ): array {
		$nav_color        = get_post_meta( $slider_id, '_nav_color', true );
		$active_nav_color = get_post_meta( $slider_id, '_nav_active_color', true );
		$arrow_size       = get_post_meta( $slider_id, '_arrow_size', true );
		$arrow_size       = is_numeric( $arrow_size ) ? absint( $arrow_size ) : 48;
		$bullet_size      = get_post_meta( $slider_id, '_bullet_size', true );
		$bullet_size      = is_numeric( $bullet_size ) ? absint( $bullet_size ) : 10;
		$css_var          = [
			"--carousel-slider-nav-color"        => $nav_color,
			"--carousel-slider-active-nav-color" => $active_nav_color,
			"--carousel-slider-arrow-size"       => $arrow_size . 'px',
			"--carousel-slider-bullet-size"      => $bullet_size . 'px',
		];

		return apply_filters( 'carousel_slider/css_var', $css_var, $slider_id );
	}

	/**
	 * @param string $html
	 * @param int $slider_id
	 * @param string $slider_type
	 *
	 * @return string
	 */
	public function view( string $html, int $slider_id, string $slider_type ): string {
		if ( 'video-carousel' == $slider_type ) {
			return static::get_view( $slider_id );
		}

		return $html;
	}

	/**
	 * Get view
	 *
	 * @param int $slider_id
	 *
	 * @return string
	 */
	public static function get_view( int $slider_id ): string {
		$urls = get_post_meta( $slider_id, '_video_url', true );
		if ( is_string( $urls ) ) {
			$urls = array_filter( explode( ',', $urls ) );
		}
		$urls = VideoUtils::get_video_url( $urls );

		$css_classes = [
			"carousel-slider-outer",
			"carousel-slider-outer-videos",
			"carousel-slider-outer-{$slider_id}"
		];
		$css_vars    = self::get_css_variable( $slider_id );
		$styles      = [];
		foreach ( $css_vars as $key => $var ) {
			$styles[] = sprintf( "%s:%s", $key, $var );
		}

		$options = ( new Shortcode )->carousel_options( $slider_id );
		$html    = '<div class="' . join( ' ', $css_classes ) . '" style="' . implode( ';', $styles ) . '">';
		$html    .= '<div ' . join( " ", $options ) . '>';
		foreach ( $urls as $url ) {
			$html .= '<div class="carousel-slider-item-video">';
			$html .= '<div class="carousel-slider-video-wrapper">';
			$html .= '<a class="magnific-popup" href="' . esc_url( $url['url'] ) . '">';
			$html .= '<div class="carousel-slider-video-play-icon"></div>';
			$html .= '<div class="carousel-slider-video-overlay"></div>';
			$html .= '<img class="owl-lazy" data-src="' . esc_url( $url['thumbnail']['large'] ) . '"/>';
			$html .= '</a>';
			$html .= '</div>';
			$html .= '</div>';
		}
		$html .= '</div>';
		$html .= '</div>';

		return apply_filters( 'carousel_slider_videos_carousel', $html, $slider_id );
	}
}
