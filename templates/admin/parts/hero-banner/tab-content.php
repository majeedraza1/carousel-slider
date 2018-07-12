<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div id="carousel-slider-tab-content" class="shapla-tab tab-content">

    <div class="sp-input-group" id="field-_slide_heading">
        <div class="sp-input-label">
            <label for="_slide_heading"><?php esc_html_e( 'Slide Heading', 'carousel-slider' ); ?></label>
            <p class="sp-input-desc"><?php esc_html_e( 'Enter the heading for your slide. This field can take HTML markup.', 'carousel-slider' ); ?></p>
        </div>
        <div class="sp-input-field">
            <textarea name="carousel_slider_content[<?php echo $slide_num; ?>][slide_heading]"
                      id="_slide_heading" cols="30" rows="3"
                      class="regular-text"><?php echo esc_textarea( $_slide_heading ); ?></textarea>
        </div>
    </div>

    <div class="sp-input-group" id="field-_slide_description">
        <div class="sp-input-label">
            <label for="_slide_description"><?php esc_html_e( 'Slide Description', 'carousel-slider' ); ?></label>
            <p class="sp-input-desc"><?php esc_html_e( 'Enter the description for your slide. This field can take HTML markup.', 'carousel-slider' ); ?></p>
        </div>
        <div class="sp-input-field">
        <textarea name="carousel_slider_content[<?php echo $slide_num; ?>][slide_description]"
                  id="_slide_description" cols="30" rows="5"
                  class="regular-text"><?php echo esc_textarea( $_slide_description ); ?></textarea>
        </div>
    </div><!-- Slide Description -->

</div>
<!-- .tab-content -->