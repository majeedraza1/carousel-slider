<?php
defined( 'ABSPATH' ) || exit;

$_images_urls = get_post_meta( $post->ID, '_images_urls', true );
?>
<div class="shapla-modal" id="CarouselSliderModal">
	<div class="shapla-modal-background" data-dismiss="shapla-modal"></div>
	<div class="shapla-modal-content shapla-modal-card is-large">
		<header class="shapla-modal-card__header">
			<p class="shapla-modal-card__title"><?php esc_html_e( 'Image Carousel - from URL', 'carousel-slider' ); ?></p>
			<div class="shapla-delete-icon is-medium" data-dismiss="shapla-modal"></div>
		</header>
		<section class="shapla-modal-card__body">
			<div class="carousel_slider-modal-body">
				<div>
					<div id="carousel_slider_form" class="carousel_slider-form shapla-columns is-multiline">
						<?php if ( is_array( $_images_urls ) ): foreach ( $_images_urls as $image ): ?>
							<div class="media-url--column shapla-column is-4">
								<div class="carousel_slider-fields">
									<label class="setting">
										<span class="name"><?php esc_html_e( 'URL', 'carousel-slider' ); ?></span>
										<input type="url" name="_images_urls[url][]" value="<?php echo $image['url']; ?>"
											   autocomplete="off">
									</label>
									<label class="setting">
										<span class="name"><?php esc_html_e( 'Title', 'carousel-slider' ); ?></span>
										<input type="text" name="_images_urls[title][]"
											   value="<?php echo $image['title']; ?>"
											   autocomplete="off">
									</label>
									<label class="setting">
										<span class="name"><?php esc_html_e( 'Caption', 'carousel-slider' ); ?></span>
										<textarea name="_images_urls[caption][]"><?php echo $image['caption']; ?></textarea>
									</label>
									<label class="setting">
										<span class="name"><?php esc_html_e( 'Alt Text', 'carousel-slider' ); ?></span>
										<input type="text" name="_images_urls[alt][]" value="<?php echo $image['alt']; ?>"
											   autocomplete="off">
									</label>
									<label class="setting">
										<span class="name"><?php esc_html_e( 'Link To URL', 'carousel-slider' ); ?></span>
										<input type="text" name="_images_urls[link_url][]"
											   value="<?php echo $image['link_url']; ?>"
											   autocomplete="off">
									</label>
									<div class="actions">
										<span><span class="dashicons dashicons-move"></span></span>
										<span class="add_row"><span class="dashicons dashicons-plus-alt"></span></span>
										<span class="delete_row"><span class="dashicons dashicons-trash"></span></span>
									</div>
								</div>
							</div>
						<?php endforeach;endif; ?>
						<div class="shapla-column is-12">
							<button class="button add_row">Add Item</button>
						</div>
					</div>
				</div>
			</div>
		</section>
		<footer class="shapla-modal-card__footer is-pulled-right">
			<button class="button is-success"><?php esc_html_e( 'Save', 'carousel-slider' ); ?></button>
		</footer>
	</div>
</div>
