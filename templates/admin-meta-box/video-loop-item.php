<?php

defined( 'ABSPATH' ) || die;

/**
 * @var CarouselSlider\Modules\VideoCarousel\Item $item Item object.
 * @var int $index Item index number.
 */
?>
<div class="carousel_slider-fields media-url-form-field media-video-form-field">
	<div class="media-url-form-field__content">
		<label class="setting media-url-form-field__item">
			<span class="name"><?php esc_html_e( 'Youtube or Vimeo URL', 'carousel-slider' ); ?></span>
			<input type="url" name="_video_urls[url][]"
					value="<?php echo esc_url( $item->get_url() ); ?>" autocomplete="off"
					placeholder="https://www.youtube.com/watch?v=UOYK79yVrJ4">
		</label>
	</div>
	<div class="media-url-form-field__actions">
		<span><span class="dashicons dashicons-move"></span></span>
		<span class="add_row"><span class="dashicons dashicons-plus-alt"></span></span>
		<span class="delete_row"><span class="dashicons dashicons-trash"></span></span>
	</div>
</div>
