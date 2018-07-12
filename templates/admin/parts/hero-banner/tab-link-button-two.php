<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div data-id="closed" id="content_carousel_button_one" class="shapla-toggle shapla-toggle--stroke">
	<span class="shapla-toggle-title">
		<?php esc_html_e( 'Button #2', 'carousel-slider' ); ?>
	</span>
    <div class="shapla-toggle-inner">
        <div class="shapla-toggle-content">

            <div class="sp-input-group" id="field-button_two_text">
                <div class="sp-input-label">
                    <label for="button_two_text"><?php esc_html_e( 'Button Text', 'carousel-slider' ); ?></label>
                    <p class="sp-input-desc"><?php esc_html_e( 'Add the button text', 'carousel-slider' ); ?></p>
                </div>
                <div class="sp-input-field">
                    <input name="carousel_slider_content[<?php echo $slide_num; ?>][button_two_text]"
                           id="button_two_text" class="sp-input-text" value="<?php echo $_btn_2_text; ?>">
                </div>
            </div><!-- Button Text -->

            <div class="sp-input-group" id="field-button_two_url">
                <div class="sp-input-label">
                    <label for="button_two_url"><?php esc_html_e( 'Button URL', 'carousel-slider' ); ?></label>
                    <p class="sp-input-desc"><?php esc_html_e( 'Add the button url e.g. http://example.com', 'carousel-slider' ); ?></p>
                </div>
                <div class="sp-input-field">
                    <input name="carousel_slider_content[<?php echo $slide_num; ?>][button_two_url]"
                           id="button_two_url" class="sp-input-text" value="<?php echo $_btn_2_url; ?>">
                </div>
            </div><!-- Button URL -->

            <div class="sp-input-group" id="field-button_two_target">
                <div class="sp-input-label">
                    <label for="button_two_target"><?php esc_html_e( 'Open Button Link In', 'carousel-slider' ); ?></label>
                </div>
                <div class="sp-input-field">
                    <select name="carousel_slider_content[<?php echo $slide_num; ?>][button_two_target]"
                            id="button_two_target" class="sp-input-text">
                        <option value="_blank" <?php selected( $_btn_2_target, '_blank' ); ?>><?php esc_html_e( 'New Window', 'carousel-slider' ); ?></option>
                        <option value="_self" <?php selected( $_btn_2_target, '_self' ); ?>><?php esc_html_e( 'Same window', 'carousel-slider' ); ?></option>
                    </select>
                </div>
            </div><!-- Open Button Link In -->

            <div class="sp-input-group" id="field-button_two_target">
                <div class="sp-input-label">
                    <label for="button_two_type"><?php esc_html_e( 'Button Type', 'carousel-slider' ); ?></label>
                </div>
                <div class="sp-input-field">
                    <select name="carousel_slider_content[<?php echo $slide_num; ?>][button_two_type]"
                            id="button_two_target" class="sp-input-text">
                        <option value="normal" <?php selected( $_btn_2_type, 'normal' ); ?>><?php esc_html_e( 'Normal', 'carousel-slider' ); ?></option>
                        <option value="stroke" <?php selected( $_btn_2_type, 'stroke' ); ?>><?php esc_html_e( 'Stroke', 'carousel-slider' ); ?></option>
                    </select>
                </div>
            </div><!-- Button Type -->

            <div class="sp-input-group" id="field-button_two_size">
                <div class="sp-input-label">
                    <label for="button_two_type"><?php esc_html_e( 'Button Size', 'carousel-slider' ); ?></label>
                </div>
                <div class="sp-input-field">
                    <select name="carousel_slider_content[<?php echo $slide_num; ?>][button_two_size]"
                            id="button_two_size" class="sp-input-text">
                        <option value="large" <?php selected( $_btn_2_size, 'large' ); ?>><?php esc_html_e( 'Large', 'carousel-slider' ); ?></option>
                        <option value="medium" <?php selected( $_btn_2_size, 'medium' ); ?>><?php esc_html_e( 'Medium', 'carousel-slider' ); ?></option>
                        <option value="small" <?php selected( $_btn_2_size, 'small' ); ?>><?php esc_html_e( 'Small', 'carousel-slider' ); ?></option>
                    </select>
                </div>
            </div><!-- Button Size -->


            <div class="sp-input-group" id="field-button_two_border_width">
                <div class="sp-input-label">
                    <label for="button_two_border_width"><?php esc_html_e( 'Border Width', 'carousel-slider' ); ?></label>
                    <p class="sp-input-desc"><?php esc_html_e( 'Enter border width in pixel. e.g. 2px', 'carousel-slider' ); ?></p>
                </div>
                <div class="sp-input-field">
                    <input name="carousel_slider_content[<?php echo $slide_num; ?>][button_two_border_width]"
                           id="button_two_border_width" class="sp-input-text"
                           value="<?php echo $_btn_2_border_width; ?>">
                </div>
            </div><!-- Border Width -->

            <div class="sp-input-group" id="field-button_two_border_radius">
                <div class="sp-input-label">
                    <label for="button_two_border_radius"><?php esc_html_e( 'Border Radius', 'carousel-slider' ); ?></label>
                    <p class="sp-input-desc"><?php esc_html_e( 'Enter border radius in pixel. e.g. 3px', 'carousel-slider' ); ?></p>
                </div>
                <div class="sp-input-field">
                    <input name="carousel_slider_content[<?php echo $slide_num; ?>][button_two_border_radius]"
                           id="button_two_border_radius" class="sp-input-text"
                           value="<?php echo $_btn_2_border_radius; ?>">
                </div>
            </div><!-- Border Radius -->

            <div class="sp-input-group" id="field-button_two_bg_color">
                <div class="sp-input-label">
                    <label for="button_two_bg_color"><?php esc_html_e( 'Button Color', 'carousel-slider' ); ?></label>
                </div>
                <div class="sp-input-field">
                    <input id="button_two_bg_color" class="color-picker" value="<?php echo $_btn_2_bg_color; ?>"
                           name="carousel_slider_content[<?php echo $slide_num; ?>][button_two_bg_color]"
                           data-alpha="true" data-default-color="#00d1b2">
                </div>
            </div><!-- Button Color -->

            <div class="sp-input-group" id="field-button_two_color">
                <div class="sp-input-label">
                    <label for="button_two_color"><?php esc_html_e( 'Button Text Color', 'carousel-slider' ); ?></label>
                </div>
                <div class="sp-input-field">
                    <input id="button_two_color" class="color-picker" value="<?php echo $_btn_2_color; ?>"
                           name="carousel_slider_content[<?php echo $slide_num; ?>][button_two_color]"
                           data-alpha="true" data-default-color="#ffffff">
                </div>
            </div><!-- Button Text Color -->

        </div>
    </div>
</div>