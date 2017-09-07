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
				// Get WordPress media upload URL
				$upload_link = esc_url( get_upload_iframe_src( 'image', $post->ID ) );
				$num_to_word = new Carousel_Slider_Number_To_Word;

				if ( is_array( $content_sliders ) && count( $content_sliders ) > 0 ) {
					$total_sliders = count( $content_sliders );
					foreach ( $content_sliders as $slide_num => $content_slider ) {
						$_to_word = str_replace( array( ' ', '-' ), '_', $num_to_word->convert( $slide_num ) );

						$_all_bg_position = carousel_slider_background_position();
						$_all_bg_size     = carousel_slider_background_size();
						$_content         = isset( $content_slider['content'] ) ? $content_slider['content'] : '';
						$_img_bg_position = ! empty( $content_slider['img_bg_position'] ) ? esc_attr( $content_slider['img_bg_position'] ) : 'center center';
						$_img_bg_size     = ! empty( $content_slider['img_bg_size'] ) ? esc_attr( $content_slider['img_bg_size'] ) : 'contain';
						$_bg_color        = ! empty( $content_slider['bg_color'] ) ? esc_attr( $content_slider['bg_color'] ) : '';
						$_img_id          = ! empty( $content_slider['img_id'] ) ? absint( $content_slider['img_id'] ) : 0;
						$_img_src         = wp_get_attachment_image_src( $_img_id, 'full' );
						$_have_img        = is_array( $_img_src );

						// Canvas style
						$canvas_style = 'background-repeat: no-repeat;';
						$canvas_style .= 'background-position: ' . $_img_bg_position . ';';
						$canvas_style .= 'background-size: ' . $_img_bg_size . ';';
						$canvas_style .= 'background-color: ' . $_bg_color . ';';
						if ( $_have_img ) {
							$canvas_style .= 'background-image: url(' . $_img_src[0] . ')';
						}
						?>
                        <div class="shapla-toggle shapla-toggle--normal" data-id="closed">
                            <div class="shapla-toggle-title">
								<?php printf( '%s %s', esc_html__( 'Slide', 'carousel-slider' ), $slide_num + 1 ); ?>
                            </div>
                            <div class="shapla-toggle-inner">
                                <div class="shapla-toggle-content">

                                    <div class="carousel_slider__slide_actions">

                                        <button class="button carousel_slider__delete_slide"
                                                data-post-id="<?php echo $post->ID; ?>"
                                                data-slide-pos="<?php echo $slide_num; ?>"
                                                title="<?php esc_html_e( 'Delete current slide', 'carousel-slider' ); ?>"
                                        >
                                            <svg class="icon icon-trash" width="20" height="26">
                                                <use xlink:href="#icon-trash"></use>
                                            </svg>
                                        </button>

										<?php if ( $slide_num !== 0 ): ?>
											<?php if ( $total_sliders > 2 && $slide_num > 1 ): ?>
                                                <button class="button carousel_slider__move_top"
                                                        data-post-id="<?php echo $post->ID; ?>"
                                                        data-slide-pos="<?php echo $slide_num; ?>"
                                                        title="<?php esc_html_e( 'Move Slide to Top', 'carousel-slider' ); ?>"
                                                >
                                                    <svg class="icon icon-trash" width="20" height="26">
                                                        <use xlink:href="#icon-angle-up-alt"></use>
                                                    </svg>
                                                </button>
											<?php endif; ?>
                                            <button class="button carousel_slider__move_up"
                                                    data-post-id="<?php echo $post->ID; ?>"
                                                    data-slide-pos="<?php echo $slide_num; ?>"
                                                    title="<?php esc_html_e( 'Move Slide Up', 'carousel-slider' ); ?>"
                                            >
                                                <svg class="icon icon-trash" width="20" height="26">
                                                    <use xlink:href="#icon-angle-up"></use>
                                                </svg>
                                            </button>
										<?php endif; ?>

										<?php if ( $slide_num !== ( $total_sliders - 1 ) ): ?>
                                            <button class="button carousel_slider__move_down"
                                                    data-post-id="<?php echo $post->ID; ?>"
                                                    data-slide-pos="<?php echo $slide_num; ?>"
                                                    title="<?php esc_html_e( 'Move Slide Down', 'carousel-slider' ); ?>"
                                            >
                                                <svg class="icon icon-trash" width="20" height="26">
                                                    <use xlink:href="#icon-angle-down"></use>
                                                </svg>
                                            </button>
											<?php if ( $total_sliders > 2 && $slide_num < ( $total_sliders - 2 ) ): ?>
                                                <button class="button carousel_slider__move_bottom"
                                                        data-post-id="<?php echo $post->ID; ?>"
                                                        data-slide-pos="<?php echo $slide_num; ?>"
                                                        title="<?php esc_html_e( 'Move Slide to Bottom', 'carousel-slider' ); ?>"
                                                >
                                                    <svg class="icon icon-trash" width="20" height="26">
                                                        <use xlink:href="#icon-angle-down-alt"></use>
                                                    </svg>
                                                </button>
											<?php endif; ?>
										<?php endif; ?>

                                    </div>
                                    <div class="clear" style="width: 100%; margin-bottom: 1rem; height: 1px;"></div>

                                    <div class="shapla-section shapla-tabs shapla-tabs--stroke">
                                        <div class="shapla-tab-inner">
                                            <ul class="shapla-nav shapla-clearfix">
                                                <li>
                                                    <a href="#carousel-slider-tab-content"><?php esc_html_e( 'Slide Content', 'carousel-slider' ); ?></a>
                                                </li>
                                                <li>
                                                    <a href="#carousel-slider-tab-background"><?php esc_html_e( 'Slide Background', 'carousel-slider' ); ?></a>
                                                </li>
                                            </ul>
                                            <div id="carousel-slider-tab-content" class="shapla-tab">
												<?php
												wp_editor(
													$_content,
													'carousel_slider_content_' . $_to_word,
													array(
														'textarea_name' => 'carousel_slider_content[' . $slide_num . '][content]',
														'textarea_rows' => 10,
													)
												);
												?>
                                            </div>
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
                                                            <input type="hidden"
                                                                   class="background_image_id"
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
                                                            <span><?php esc_html_e( 'Background Color:', 'carousel-slider' ); ?></span>
                                                            <input type="text"
                                                                   name="carousel_slider_content[<?php echo $slide_num; ?>][bg_color]"
                                                                   class="slide-color-picker"
                                                                   value="<?php echo $_bg_color; ?>"
                                                                   data-alpha="true"
                                                                   data-default-color="rgba(255,255,255,0.5)">
                                                        </div>
                                                    </div>

                                                </div>
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

<svg width="1" height="1">
    <symbol id="icon-trash" viewBox="0 0 20 20">
        <path d="M12 4h3c0.55 0 1 0.45 1 1v1h-13v-1c0-0.55 0.45-1 1-1h3c0.23-1.14 1.29-2 2.5-2s2.27 0.86 2.5 2zM8 4h3c-0.21-0.58-0.85-1-1.5-1s-1.29 0.42-1.5 1zM4 7h11v10c0 0.55-0.45 1-1 1h-9c-0.55 0-1-0.45-1-1v-10zM7 16v-7h-1v7h1zM10 16v-7h-1v7h1zM13 16v-7h-1v7h1z"></path>
    </symbol>
    <symbol id="icon-angle-down" viewBox="0 0 20 20">
        <path d="M5 6l5 5 5-5 2 1-7 7-7-7z"></path>
    </symbol>
    <symbol id="icon-angle-up" viewBox="0 0 20 20">
        <path d="M15 14l-5-5-5 5-2-1 7-7 7 7z"></path>
    </symbol>
    <symbol id="icon-angle-down-alt" viewBox="0 0 20 20">
        <path d="M9 2h2v12l4-4 2 1-7 7-7-7 2-1 4 4v-12z"></path>
    </symbol>
    <symbol id="icon-angle-up-alt" viewBox="0 0 20 20">
        <path d="M11 18h-2v-12l-4 4-2-1 7-7 7 7-2 1-4-4v12z"></path>
    </symbol>
</svg>
