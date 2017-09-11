<div id="carousel-slider-tab-link" class="shapla-tab tab-content">

    <div class="sp-input-group" id="field-_link_type">
        <div class="sp-input-label">
            <label for="_link_type"><?php esc_html_e( 'Slide Link Type', 'carousel-slider' ); ?></label>
            <p class="sp-input-desc"><?php esc_html_e( 'Select how the slide will link.', 'carousel-slider' ); ?></p>
        </div>
        <div class="sp-input-field">
            <select name="carousel_slider_content[<?php echo $slide_num; ?>][link_type]"
                    id="_link_type" class="sp-input-text">
                <option value="full" <?php selected( $_link_type, 'full' ); ?>><?php esc_html_e( 'Full Slide', 'carousel-slider' ); ?></option>
                <option value="button" <?php selected( $_link_type, 'button' ); ?>><?php esc_html_e( 'Button', 'carousel-slider' ); ?></option>
            </select>
        </div>
    </div>

    <div class="sp-input-group" id="field-_slide_link">
        <div class="sp-input-label">
            <label for="_slide_link"><?php esc_html_e( 'Slide Link', 'carousel-slider' ); ?></label>
            <p class="sp-input-desc"><?php esc_html_e( 'Please enter your URL that will be used to link the full slide.', 'carousel-slider' ); ?></p>
        </div>
        <div class="sp-input-field">
            <input type="url" id="_slide_link"
                   class="regular-text" value="<?php echo $_slide_link; ?>"
                   name="carousel_slider_content[<?php echo $slide_num; ?>][slide_link]">
        </div>
    </div>

    <div class="sp-input-group" id="field-_link_target">
        <div class="sp-input-label">
            <label for="_link_target"><?php esc_html_e( 'Open Slide Link In New Window', 'carousel-slider' ); ?></label>
        </div>
        <div class="sp-input-field">
            <select name="carousel_slider_content[<?php echo $slide_num; ?>][link_target]"
                    id="_link_target" class="sp-input-text">
                <option value="_blank" <?php selected( $_link_target, '_blank' ); ?>><?php esc_html_e( 'Yes', 'carousel-slider' ); ?></option>
                <option value="_self" <?php selected( $_link_target, '_self' ); ?>><?php esc_html_e( 'No', 'carousel-slider' ); ?></option>
            </select>
        </div>
    </div>

    <div class="sp-input-group" id="field-_button_one">
        <div class="sp-input-label">
            <label for="_button_one"><?php esc_html_e( 'Button', 'carousel-slider' ); ?></label>
        </div>
        <div class="sp-input-field">
            <button class="button" data-toggle="modal" data-target="#addContentButton"><?php esc_html_e( 'Add button', 'carousel-slider' ); ?></button>
        </div>
    </div>

</div>