<div data-id="open" id="section_content_carousel" class="shapla-toggle shapla-toggle--stroke"
     style="display: <?php echo $slide_type != 'content-carousel' ? 'none' : 'block'; ?>">
	<span class="shapla-toggle-title">
		<?php esc_html_e( 'Content Carousel/Slider', 'carousel-slider' ); ?>
	</span>
    <div class="shapla-toggle-inner">
        <div class="shapla-toggle-content">
            <button class="button carousel-slider__add-slide" data-post-id="<?php echo $post->ID; ?>">Add Slide</button>
            <div id="carouselSliderContentInside">
				<?php
				$content_sliders = get_post_meta( $post->ID, '_content_slider', true );
				$num_to_word     = new Carousel_Slider_Number_To_Word;

				if ( is_array( $content_sliders ) && count( $content_sliders ) > 0 ) {
					foreach ( $content_sliders as $slide_num => $content_slider ) {
						$_to_word = str_replace( array( ' ', '-' ), '_', $num_to_word->convert( $slide_num ) );

						$_all_bg_position = carousel_slider_background_position();
						$_all_bg_size     = carousel_slider_background_size();
						$_content         = isset( $content_slider['content'] ) ? $content_slider['content'] : '';
						$_img_id          = ! empty( $content_slider['img_id'] ) ? absint( $content_slider['img_id'] ) : 0;
						$_img_bg_position = ! empty( $content_slider['img_bg_position'] ) ? esc_attr( $content_slider['img_bg_position'] ) : 'center center';
						$_img_bg_size     = ! empty( $content_slider['img_bg_size'] ) ? esc_attr( $content_slider['img_bg_size'] ) : 'contain';
						$_bg_color        = ! empty( $content_slider['bg_color'] ) ? esc_attr( $content_slider['bg_color'] ) : '#f1f1f1';
						?>
                        <div class="accordion">
                            <div class="accordion-header">
								<?php printf( '%s %s', esc_html__( 'Slide', 'carousel-slider' ), $slide_num + 1 ); ?>
                            </div>
                            <div class="accordion-content">
                                <div class="accordion-content-inside">
									<?php
									wp_editor(
										$_content,
										'carousel_slider_content_' . $_to_word,
										array(
											'textarea_name' => 'carousel_slider_content[' . $slide_num . '][content]',
											'textarea_rows' => 5,
										)
									);
									?>
                                    <div class="slide_bg_wrapper">
                                        <div class="slide-media-left">
                                            <div class="slide_thumb">
                                                <div style=""></div>
                                                <span class="delete-bg-img"
                                                      title="Delete the background image for this slide">&times;</span>
                                            </div>
                                        </div>
                                        <div class="slide-media-right">

                                            <div class="slide_image_settings_line">
                                                <button class="button slide_image_add">Set Background Image</button>
                                                <input type="hidden"
                                                       name="carousel_slider_content[<?php echo $slide_num; ?>][img_id]"
                                                       value="<?php echo $_img_id; ?>">
                                            </div>

                                            <div class="slide_image_settings_line">
                                                <span>Background Position:</span>
                                                <select name="carousel_slider_content[<?php echo $slide_num; ?>][img_bg_position]">
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
                                                <span>Background Size:</span>
                                                <select name="carousel_slider_content[<?php echo $slide_num; ?>][img_bg_size]">
													<?php
													foreach ( $_all_bg_size as $key => $label ) {
														$selected = $key == $_img_bg_size ? 'selected' : '';
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
                                                <span>Background Color:</span>
                                                <input type="text"
                                                       name="carousel_slider_content[<?php echo $slide_num; ?>][bg_color]"
                                                       class="color-picker"
                                                       value="<?php echo $_bg_color; ?>"
                                                       data-alpha="true" data-default-color="#f1f1f1">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
						<?php
					}
				}
				?>
            </div>
        </div>
    </div>
</div>