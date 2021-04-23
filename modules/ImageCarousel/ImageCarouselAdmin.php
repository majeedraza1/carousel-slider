<?php

namespace CarouselSlider\Modules\ImageCarousel;

use CarouselSlider\Supports\MetaBoxForm;

defined( 'ABSPATH' ) || exit;

class ImageCarouselAdmin {
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
	 * @param int $slider_id
	 * @param string $slider_type
	 */
	public function meta_box_content( int $slider_id, string $slider_type ) {
		$metabox       = new MetaBoxForm;
		$show_settings = in_array( $slider_type, [ 'image-carousel', 'image-carousel-url' ] );
		?>
		<div data-id="open" id="section_url_images_settings" class="shapla-toggle shapla-toggle--stroke"
			 style="display: <?php echo $slider_type != 'image-carousel-url' ? 'none' : 'block'; ?>">
			<span class="shapla-toggle-title">
				<?php esc_html_e( 'URL Images', 'carousel-slider' ); ?>
			</span>
			<div class="shapla-toggle-inner">
				<div class="shapla-toggle-content">
					<?php
					$metabox->images_url( array(
						'id'   => '_images_urls',
						'name' => esc_html__( 'Images URLs', 'carousel-slider' ),
						'desc' => esc_html__( 'Enter external images URLs.', 'carousel-slider' ),
					) );
					?>
				</div>
			</div>
		</div>
		<div data-id="open" id="section_images_settings" class="shapla-toggle shapla-toggle--stroke"
			 style="display: <?php echo $slider_type != 'image-carousel' ? 'none' : 'block'; ?>">
			<span class="shapla-toggle-title">
				<?php esc_html_e( 'Media Images', 'carousel-slider' ); ?>
			</span>
			<div class="shapla-toggle-inner">
				<div class="shapla-toggle-content">
					<?php
					$metabox->images_gallery( array(
						'id'   => '_wpdh_image_ids',
						'name' => esc_html__( 'Carousel Images', 'carousel-slider' ),
						'desc' => esc_html__( 'Choose carousel images from media library.', 'carousel-slider' ),
					) );
					?>
				</div>
			</div>
		</div>
		<div data-id="open" id="section_images_general_settings" class="shapla-toggle shapla-toggle--stroke"
			 style="display: <?php echo $show_settings ? 'block' : 'none'; ?>">
			<span class="shapla-toggle-title">
				<?php esc_html_e( 'Image Carousel Settings', 'carousel-slider' ); ?>
			</span>
			<div class="shapla-toggle-inner">
				<div class="shapla-toggle-content">
					<?php
					$metabox->checkbox( array(
						'id'    => '_show_attachment_title',
						'name'  => esc_html__( 'Show Image Title', 'carousel-slider' ),
						'label' => esc_html__( 'Show Image Title', 'carousel-slider' ),
						'desc'  => esc_html__( 'Check to show title below image. Only works with image carousel.', 'carousel-slider' ),
						'std'   => 'off'
					) );
					$metabox->checkbox( array(
						'id'    => '_show_attachment_caption',
						'name'  => esc_html__( 'Show Image Caption', 'carousel-slider' ),
						'label' => esc_html__( 'Show Image Caption', 'carousel-slider' ),
						'desc'  => esc_html__( 'Check to show caption below image. Only works with image carousel.', 'carousel-slider' ),
						'std'   => 'off'
					) );
					$metabox->select( array(
						'id'      => '_image_target',
						'name'    => esc_html__( 'Image Target', 'carousel-slider' ),
						'desc'    => esc_html__( 'Choose where to open the linked image.', 'carousel-slider' ),
						'std'     => '_self',
						'options' => array(
							'_self'  => esc_html__( 'Open in the same frame as it was clicked', 'carousel-slider' ),
							'_blank' => esc_html__( 'Open in a new window or tab', 'carousel-slider' ),
						),
					) );
					$metabox->checkbox( array(
						'id'    => '_image_lightbox',
						'name'  => esc_html__( 'Show Lightbox Gallery', 'carousel-slider' ),
						'label' => esc_html__( 'Show Lightbox Gallery', 'carousel-slider' ),
						'desc'  => esc_html__( 'Check to show lightbox gallery.', 'carousel-slider' ),
						'std'   => 'off'
					) );
					?>
				</div>
			</div>
		</div>
		<?php
	}
}
