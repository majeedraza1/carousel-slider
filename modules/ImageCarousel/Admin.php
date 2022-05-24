<?php

namespace CarouselSlider\Modules\ImageCarousel;

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
		}

		return self::$instance;
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
		$form        = new MetaBoxForm();
		$images_urls = get_post_meta( $slider_id, '_images_urls', true );

		if ( 'image-carousel-url' === $slider_type ) {
			$form->images_url(
				[
					'id'          => '_images_urls',
					'label'       => esc_html__( 'Images URLs', 'carousel-slider' ),
					'description' => esc_html__( 'Enter external images URLs.', 'carousel-slider' ),
				]
			);
		}

		if ( 'image-carousel' === $slider_type ) {
			$form->images_gallery(
				[
					'id'   => '_wpdh_image_ids',
					'name' => esc_html__( 'Carousel Images', 'carousel-slider' ),
					'desc' => esc_html__( 'Choose carousel images from media library.', 'carousel-slider' ),
				]
			);
			$form->switch(
				[
					'id'    => '_shuffle_images',
					'name'  => esc_html__( 'Shuffle', 'carousel-slider' ),
					'label' => esc_html__( 'Shuffle Images Order', 'carousel-slider' ),
					'desc'  => esc_html__( 'Check to shuffle images order at each page refresh.', 'carousel-slider' ),
					'std'   => 'off',
				]
			);
			$form->switch(
				[
					'id'    => '_image_lightbox',
					'name'  => esc_html__( 'Show Lightbox Gallery', 'carousel-slider' ),
					'label' => esc_html__( 'Show Lightbox Gallery', 'carousel-slider' ),
					'desc'  => esc_html__( 'Check to show lightbox gallery.', 'carousel-slider' ),
					'std'   => 'off',
				]
			);
		}

		$form->switch(
			[
				'id'          => '_show_attachment_title',
				'label'       => esc_html__( 'Show Image Title', 'carousel-slider' ),
				'description' => esc_html__( 'Check to show title below image. Only works with image carousel.', 'carousel-slider' ),
				'default'     => 'off',
			]
		);
		$form->switch(
			[
				'id'          => '_show_attachment_caption',
				'label'       => esc_html__( 'Show Image Caption', 'carousel-slider' ),
				'description' => esc_html__( 'Check to show caption below image. Only works with image carousel.', 'carousel-slider' ),
				'default'     => 'off',
			]
		);
		$form->select(
			[
				'id'          => '_image_target',
				'label'       => esc_html__( 'Image Target', 'carousel-slider' ),
				'description' => esc_html__( 'Choose where to open the linked image.', 'carousel-slider' ),
				'default'     => '_self',
				'choices'     => [
					'_self'  => esc_html__( 'Open in the same frame as it was clicked', 'carousel-slider' ),
					'_blank' => esc_html__( 'Open in a new window or tab', 'carousel-slider' ),
				],
			]
		);

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
}
