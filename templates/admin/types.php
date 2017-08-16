<div class="sp-input-group" style="margin: 10px 0 30px;">
    <div class="sp-input-label">
        <label for="_carousel_slider_slide_type">
			<?php esc_html_e( 'Slide Type', 'carousel-slider' ); ?>
        </label>
    </div>
    <div class="sp-input-field">
        <select name="carousel_slider[_slide_type]" id="_carousel_slider_slide_type" class="sp-input-text">
            <option value="image-carousel" <?php echo $slide_type == 'image-carousel' ? 'selected' : ''; ?>>
				<?php esc_html_e( 'Image Carousel - from Media Library', 'carousel-slider' ); ?>
            </option>
            <option value="image-carousel-url" <?php echo $slide_type == 'image-carousel-url' ? 'selected' : ''; ?>>
				<?php esc_html_e( 'Image Carousel - from URL', 'carousel-slider' ); ?>
            </option>
            <option value="post-carousel" <?php echo $slide_type == 'post-carousel' ? 'selected' : ''; ?>>
				<?php esc_html_e( 'Post Carousel', 'carousel-slider' ); ?>
            </option>
            <option value="video-carousel"<?php echo $slide_type == 'video-carousel' ? 'selected' : ''; ?>>
				<?php esc_html_e( 'Video Carousel', 'carousel-slider' ); ?>
            </option>
			<?php $disabled = carousel_slider_is_woocommerce_active() ? '' : 'disabled'; ?>
            <option value="product-carousel" <?php echo $slide_type == 'product-carousel' ? 'selected' : ''; ?> <?php echo $disabled; ?>>
				<?php esc_html_e( 'WooCommerce Product Carousel', 'carousel-slider' ); ?>
            </option>
        </select>
    </div>
</div>