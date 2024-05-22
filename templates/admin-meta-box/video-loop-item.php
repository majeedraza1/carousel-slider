<?php

defined( 'ABSPATH' ) || die;

/**
 * This template is for admin video carousel meta box loop item
 *
 * @var CarouselSlider\Modules\VideoCarousel\Item $item Item object.
 * @var int $index Item index number.
 */
?>
<div class="carousel_slider-fields--video-urls shapla-column is-12 is-6-fullhd">
	<div class="carousel_slider-fields media-url-form-field">
		<div class="media-url-form-field__content">
			<label class="setting media-url-form-field__item">
				<span class="name"><?php esc_html_e( 'Youtube or Vimeo URL', 'carousel-slider' ); ?></span>
				<input type="url" name="_video_urls[]"
					   value="<?php echo esc_url( $item->get_url() ); ?>" autocomplete="off"
					   placeholder="https://www.youtube.com/watch?v=UOYK79yVrJ4">
			</label>
		</div>
		<div class="media-url-form-field__actions flex-direction-row">
			<span class="sort_video_url_row"><span class="dashicons dashicons-move"></span></span>
			<span class="add_video_url_row"><span class="dashicons dashicons-plus-alt"></span></span>
			<span class="delete_video_url_row"><span class="dashicons dashicons-trash"></span></span>
		</div>
	</div>
</div>
