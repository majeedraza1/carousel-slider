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
						?>
                        <div class="accordion">
                            <div class="accordion-header">
								<?php printf( '%s %s', esc_html__( 'Slide', 'carousel-slider' ), $slide_num + 1 ); ?>
                            </div>
                            <div class="accordion-content">
                                <div class="accordion-content-inside">
									<?php
									wp_editor(
										$content_slider['content'],
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
                                                <input type="hidden" name="img_id" value="0">
                                            </div>

                                            <div class="slide_image_settings_line">
                                                <span>Background Position:</span>
                                                <select name="img_bg_position">
                                                    <option value="left top">Top Left</option>
                                                    <option value="center top">Top Center</option>
                                                    <option value="right top">Top Right</option>
                                                    <option value="left center">Center Left</option>
                                                    <option value="center center">Center</option>
                                                    <option value="right center">Center Right</option>
                                                    <option value="left bottom">Bottom Left</option>
                                                    <option value="center bottom">Bottom Center</option>
                                                    <option value="right bottom">Bottom Right</option>
                                                </select>
                                            </div>

                                            <div class="slide_image_settings_line">
                                                <span>Background Size:</span>
                                                <select name="img_bg_Size">
                                                    <option value="auto">no resize</option>
                                                    <option value="contain" selected="">contain</option>
                                                    <option value="cover">cover</option>
                                                    <option value="100% 100%">100%</option>
                                                    <option value="100% auto">100% width</option>
                                                    <option value="auto 100%">100% height</option>
                                                </select>
                                            </div>

                                            <div class="slide_image_settings_line">
                                                <span>Background Color:</span>
                                                <input type="text" name="bg_color" class="colorpicker" value="#f4cccc">
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