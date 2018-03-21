<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div id="carousel-slider-tab-background" class="shapla-tab tab-background">
	<?php
	$this->form->buttonset( array(
		'group'       => 'carousel_slider_content',
		'position'    => $slide_num,
		'meta_key'    => '_content_slider',
		'id'          => 'background_type',
		'input_class' => 'background_type',
		'name'        => esc_html__( 'Background Type:', 'carousel-slider' ),
		'desc'        => esc_html__( 'Choose slide background type.', 'carousel-slider' ),
		'std'         => 'classic',
		'options'     => array(
			'classic'  => esc_html__( 'Classic', 'carousel-slider' ),
			'gradient' => esc_html__( 'Gradient', 'carousel-slider' ),
		),
	) );
	?>
    <div class="gradient_background_type"
         style="display: <?php echo ( 'gradient' == $_background_type ) ? 'block' : 'none'; ?>">
        <div class="slide_bg_wrapper">
            <div class="slide-media-left">
                <div class="slide_thumb">
                    <div class="content_slide_canvas gradient_canvas"></div>
                </div>
            </div>
            <div class="slide-media-right">
				<?php
				$this->form->gradient_color( array(
					'id'          => 'bg_gradient_color',
					'group'       => 'carousel_slider_content',
					'position'    => $slide_num,
					'meta_key'    => '_content_slider',
					'input_class' => 'bg_gradient_color',
					'name'        => esc_html__( 'Gradient Background:', 'carousel-slider' ),
					'desc'        => esc_html__( 'The angles 0deg, 180deg, 270deg, and 90deg are equivalent to the values to top, to bottom, to left, and to right respectively.', 'carousel-slider' ),
					'std'         => '["#0fb8ad 0%", "#1fc8db 51%", "#2cb5e8 75%"]',
				) );
				?>
            </div>
        </div>
    </div>
    <div class="classic_background_type"
         style="display: <?php echo ( 'classic' == $_background_type ) ? 'block' : 'none'; ?>">
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
</div>
<!-- .tab-background -->