<?php

use CarouselSlider\Modules\HeroCarousel\HeroCarouselHelper;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
$upload_link       = esc_url( get_upload_iframe_src( 'image', $post->ID ) );
$_all_bg_position  = HeroCarouselHelper::background_position();
$_all_bg_size      = HeroCarouselHelper::background_size();
$slide_num         = $slide_num ?? 0;
$_img_bg_position  = ! empty( $content_slider['img_bg_position'] ) ? esc_attr( $content_slider['img_bg_position'] ) : 'center center';
$_img_bg_size      = ! empty( $content_slider['img_bg_size'] ) ? esc_attr( $content_slider['img_bg_size'] ) : 'cover';
$_bg_color         = ! empty( $content_slider['bg_color'] ) ? esc_attr( $content_slider['bg_color'] ) : '';
$_img_id           = ! empty( $content_slider['img_id'] ) ? absint( $content_slider['img_id'] ) : 0;
$_ken_burns_effect = ! empty( $content_slider['ken_burns_effect'] ) ? esc_attr( $content_slider['ken_burns_effect'] ) : '';
$_bg_overlay       = ! empty( $content_slider['bg_overlay'] ) ? esc_attr( $content_slider['bg_overlay'] ) : '';
$_img_src          = wp_get_attachment_image_src( $_img_id, 'full' );
$_have_img         = is_array( $_img_src );

// Canvas style
$canvas_style = 'background-repeat: no-repeat;';
$canvas_style .= 'background-position: ' . $_img_bg_position . ';';
$canvas_style .= 'background-size: ' . $_img_bg_size . ';';
$canvas_style .= 'background-color: ' . $_bg_color . ';';
if ( $_have_img ) {
	$canvas_style .= 'background-image: url(' . $_img_src[0] . ')';
}
?>
<div id="carousel-slider-tab-background" class="shapla-tab tab-background">
	<div class="slide_bg_wrapper">
		<div class="slide-media-left">
			<div class="slide_thumb">
				<div class="content_slide_canvas"
					 style="<?php echo $canvas_style; ?>"></div>
				<span class="delete-bg-img<?php echo ! $_have_img ? ' hidden' : ''; ?>"
					  title="<?php esc_html_e( 'Delete the background image for this slide', 'carousel-slider' ); ?>">&times;</span>
			</div>
		</div>
		<div class="slide-media-right">

			<div class="slide_image_settings_line">
				<a href="<?php echo esc_url( $upload_link ); ?>"
				   data-title="<?php esc_html_e( 'Select or Upload Slide Background Image', 'carousel-slider' ); ?>"
				   data-button-text="<?php esc_html_e( 'Set Background Image', 'carousel-slider' ); ?>"
				   class="button slide_image_add"><?php esc_html_e( 'Set Background Image', 'carousel-slider' ); ?></a>
				<input type="hidden" class="background_image_id"
					   name="carousel_slider_content[<?php echo $slide_num; ?>][img_id]"
					   value="<?php echo $_img_id; ?>">
			</div>

			<div class="slide_image_settings_line">
				<span><?php esc_html_e( 'Background Position:', 'carousel-slider' ); ?></span>
				<select class="background_image_position"
						name="carousel_slider_content[<?php echo $slide_num; ?>][img_bg_position]">
					<?php
					foreach ( $_all_bg_position as $key => $label ) {
						$selected = $key == $_img_bg_position ? 'selected' : '';
						printf(
							'<option value="%s" %s>%s</option>',
							$key,
							$selected,
							$label
						);
					}
					?>
				</select>
			</div>

			<div class="slide_image_settings_line">
				<span><?php esc_html_e( 'Background Size:', 'carousel-slider' ); ?></span>
				<select class="background_image_size"
						name="carousel_slider_content[<?php echo $slide_num; ?>][img_bg_size]">
					<?php
					foreach ( $_all_bg_size as $key => $label ) {
						$selected = $key == $_img_bg_size ? 'selected' : '';
						printf( '<option value="%s" %s>%s</option>', $key, $selected, $label );
					}
					?>
				</select>
			</div>

			<div class="slide_image_settings_line">
				<span><?php esc_html_e( 'Ken Burns Effect:', 'carousel-slider' ); ?></span>
				<select class="background_image_size"
						name="carousel_slider_content[<?php echo $slide_num; ?>][ken_burns_effect]">
					<option value="">None</option>
					<option value="zoom-in" <?php selected( 'zoom-in', $_ken_burns_effect ); ?>>Zoom In</option>
					<option value="zoom-out" <?php selected( 'zoom-out', $_ken_burns_effect ); ?>>Zoom Out</option>
				</select>
			</div>

			<div class="slide_image_settings_line">
				<span><?php esc_html_e( 'Background Color:', 'carousel-slider' ); ?></span>
				<input type="text" name="carousel_slider_content[<?php echo $slide_num; ?>][bg_color]"
					   class="slide-color-picker" value="<?php echo $_bg_color; ?>"
					   data-alpha="true" data-default-color="rgba(255,255,255,0.5)">
			</div>

			<div class="slide_image_settings_line">
				<span><?php esc_html_e( 'Background Overlay:', 'carousel-slider' ); ?></span>
				<input type="text" name="carousel_slider_content[<?php echo $slide_num; ?>][bg_overlay]"
					   class="slide-color-picker" value="<?php echo $_bg_overlay; ?>"
					   data-alpha="true" data-default-color="rgba(0,0,0,0.5)">
			</div>
		</div>

	</div>
</div>
<!-- .tab-background -->
