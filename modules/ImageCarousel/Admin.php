<?php

namespace CarouselSlider\Modules\ImageCarousel;

use CarouselSlider\Helper;
use CarouselSlider\Supports\MetaBoxForm;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class
 *
 * @package Modules/ImageCarousel
 */
class Admin {
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
			add_action( 'carousel_slider/save_slider', [ self::$instance, 'save_slider' ], 10, 2 );
		}

		return self::$instance;
	}

	/**
	 * Save slider data
	 *
	 * @param int   $slider_id The slider id.
	 * @param array $data User submitted data.
	 *
	 * @return void
	 */
	public function save_slider( int $slider_id, array $data ) {
		$settings = $data['image_carousel'] ?? [];
		foreach ( $settings as $key => $val ) {
			if ( is_array( $val ) ) {
				$val = implode( ',', $val );
			}

			update_post_meta( $slider_id, $key, sanitize_text_field( $val ) );
		}
		// Save URL image carousel.
		$images_urls = isset( $data['_images_urls'] ) && is_array( $data['_images_urls'] ) ? $data['_images_urls'] : [];
		if ( count( $images_urls ) ) {
			$url         = $images_urls['url'] ?? [];
			$title       = $images_urls['title'] ?? [];
			$caption     = $images_urls['caption'] ?? [];
			$alt         = $images_urls['alt'] ?? [];
			$link_url    = $images_urls['link_url'] ?? [];
			$total_items = count( $url );

			$urls = array();

			for ( $i = 0; $i < $total_items; $i++ ) {
				$urls[] = array(
					'url'      => esc_url_raw( $url[ $i ] ),
					'title'    => sanitize_text_field( $title[ $i ] ),
					'caption'  => sanitize_text_field( $caption[ $i ] ),
					'alt'      => sanitize_text_field( $alt[ $i ] ),
					'link_url' => esc_url_raw( $link_url[ $i ] ),
				);
			}
			update_post_meta( $slider_id, '_images_urls', $urls );
		}
	}

	/**
	 * Show meta box content for product carousel
	 *
	 * @param int    $slider_id The slider id.
	 * @param string $slider_type The slider type.
	 */
	public function meta_box_content( int $slider_id, string $slider_type ) {
		if ( ! in_array( $slider_type, [ 'image-carousel', 'image-carousel-url' ], true ) ) {
			return;
		}

		$settings_fields = self::get_settings( $slider_type );
		$html            = '';
		foreach ( $settings_fields as $field ) {
			$html .= MetaBoxForm::field( $field );
		}

		Helper::print_unescaped_internal_string( $html );

		if ( 'image-carousel-url' === $slider_type ) {
			$this->image_url_dialog( $slider_id );
		}
	}

	/**
	 * Load image url dialog
	 *
	 * @param int $slider_id The slider id.
	 *
	 * @return void
	 */
	public function image_url_dialog( $slider_id ) {
		$images_urls = get_post_meta( $slider_id, '_images_urls', true );
		?>
		<shapla-dialog type="card" id="CarouselSliderModal"
					   heading="<?php esc_html_e( 'Image Carousel - from URL', 'carousel-slider' ); ?>"
		>
			<div class="carousel_slider-modal-body">
				<div>
					<div id="carousel_slider_form" class="carousel_slider-form shapla-columns is-multiline">
						<?php
						if ( is_array( $images_urls ) ) :
							foreach ( $images_urls as $image ) :
								?>
								<div class="media-url--column shapla-column is-12">
									<div class="carousel_slider-fields media-url-form-field">
										<div class="media-url-form-field__content">
											<label class="setting media-url-form-field__item">
													<span
														class="name"><?php esc_html_e( 'URL', 'carousel-slider' ); ?></span>
												<input type="url" name="_images_urls[url][]"
													   value="<?php echo esc_url( $image['url'] ); ?>"
													   autocomplete="off">
											</label>
											<label class="setting media-url-form-field__item">
													<span
														class="name"><?php esc_html_e( 'Title', 'carousel-slider' ); ?></span>
												<input type="text" name="_images_urls[title][]"
													   value="<?php echo esc_attr( $image['title'] ); ?>"
													   autocomplete="off">
											</label>
											<label class="setting media-url-form-field__item">
													<span
														class="name"><?php esc_html_e( 'Caption', 'carousel-slider' ); ?></span>
												<textarea
													name="_images_urls[caption][]"><?php echo esc_textarea( $image['caption'] ); ?></textarea>
											</label>
											<label class="setting media-url-form-field__item">
													<span
														class="name"><?php esc_html_e( 'Alt Text', 'carousel-slider' ); ?></span>
												<input type="text" name="_images_urls[alt][]"
													   value="<?php echo esc_attr( $image['alt'] ); ?>"
													   autocomplete="off">
											</label>
											<label class="setting media-url-form-field__item">
													<span
														class="name"><?php esc_html_e( 'Link To URL', 'carousel-slider' ); ?></span>
												<input type="text" name="_images_urls[link_url][]"
													   value="<?php echo esc_url( $image['link_url'] ); ?>"
													   autocomplete="off">
											</label>
										</div>
										<div class="media-url-form-field__actions">
											<span><span class="dashicons dashicons-move"></span></span>
											<span class="add_row"><span
													class="dashicons dashicons-plus-alt"></span></span>
											<span class="delete_row"><span
													class="dashicons dashicons-trash"></span></span>
										</div>
									</div>
								</div>
								<?php
							endforeach;
						endif;
						?>
						<div class="shapla-column is-12">
							<button class="button add_row">Add Item</button>
						</div>
					</div>
				</div>
			</div>
			<div slot="footer">
				<button class="button button-primary">
					<?php esc_html_e( 'Save', 'carousel-slider' ); ?>
				</button>
			</div>
		</shapla-dialog>
		<?php
	}

	/**
	 * Get settings
	 *
	 * @param string $slider_type The slider type.
	 *
	 * @return array
	 */
	public static function get_settings( string $slider_type ): array {
		$settings = [];

		if ( 'image-carousel-url' === $slider_type ) {
			$settings[] = [
				'group'       => 'image_carousel',
				'type'        => 'images_url',
				'id'          => '_images_urls',
				'label'       => esc_html__( 'Images URLs', 'carousel-slider' ),
				'description' => esc_html__( 'Enter external images URLs.', 'carousel-slider' ),
			];
		}

		if ( 'image-carousel' === $slider_type ) {
			$settings[] = [
				'group'       => 'image_carousel',
				'type'        => 'images_gallery',
				'id'          => '_wpdh_image_ids',
				'name'        => esc_html__( 'Carousel Images', 'carousel-slider' ),
				'description' => esc_html__( 'Choose carousel images from media library.', 'carousel-slider' ),
			];
			$settings[] = [
				'group'       => 'image_carousel',
				'type'        => 'switch',
				'id'          => '_shuffle_images',
				'label'       => esc_html__( 'Shuffle Images Order', 'carousel-slider' ),
				'description' => esc_html__( 'Check to shuffle images order at each page refresh.', 'carousel-slider' ),
				'default'     => 'off',
			];
			$settings[] = [
				'group'       => 'image_carousel',
				'type'        => 'switch',
				'id'          => '_image_lightbox',
				'label'       => esc_html__( 'Show Lightbox Gallery', 'carousel-slider' ),
				'description' => esc_html__( 'Check to show lightbox gallery.', 'carousel-slider' ),
				'default'     => 'off',
			];
		}

		$settings[] = [
			'group'       => 'image_carousel',
			'type'        => 'switch',
			'id'          => '_show_attachment_title',
			'label'       => esc_html__( 'Show Image Title', 'carousel-slider' ),
			'description' => esc_html__( 'Check to show title below image. Only works with image carousel.', 'carousel-slider' ),
			'default'     => 'off',
		];
		$settings[] = [
			'group'       => 'image_carousel',
			'type'        => 'switch',
			'id'          => '_show_attachment_caption',
			'label'       => esc_html__( 'Show Image Caption', 'carousel-slider' ),
			'description' => esc_html__( 'Check to show caption below image. Only works with image carousel.', 'carousel-slider' ),
			'default'     => 'off',
		];
		$settings[] = [
			'group'       => 'image_carousel',
			'type'        => 'button_group',
			'id'          => '_image_target',
			'label'       => esc_html__( 'Image Target', 'carousel-slider' ),
			'description' => esc_html__( 'Choose where to open the linked image.', 'carousel-slider' ),
			'default'     => '_self',
			'choices'     => [
				'_self'  => esc_html__( 'Same browser tab', 'carousel-slider' ),
				'_blank' => esc_html__( 'New browser tab', 'carousel-slider' ),
			],
		];

		return $settings;
	}
}
