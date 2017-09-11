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
						// Slide Content
						$_slide_heading     = isset( $content_slider['slide_heading'] ) ? $content_slider['slide_heading'] : '';
						$_slide_description = isset( $content_slider['slide_description'] ) ? $content_slider['slide_description'] : '';
						// Slide Background
						$_img_bg_position = ! empty( $content_slider['img_bg_position'] ) ? esc_attr( $content_slider['img_bg_position'] ) : 'center center';
						$_img_bg_size     = ! empty( $content_slider['img_bg_size'] ) ? esc_attr( $content_slider['img_bg_size'] ) : 'contain';
						$_bg_color        = ! empty( $content_slider['bg_color'] ) ? esc_attr( $content_slider['bg_color'] ) : '';
						$_img_id          = ! empty( $content_slider['img_id'] ) ? absint( $content_slider['img_id'] ) : 0;
						$_img_src         = wp_get_attachment_image_src( $_img_id, 'full' );
						$_have_img        = is_array( $_img_src );
						// Slide Link
						$_link_type   = ! empty( $content_slider['link_type'] ) ? esc_attr( $content_slider['link_type'] ) : 'full';
						$_slide_link  = ! empty( $content_slider['slide_link'] ) ? esc_url( $content_slider['slide_link'] ) : '';
						$_link_target = ! empty( $content_slider['link_target'] ) ? esc_attr( $content_slider['link_target'] ) : '_blank';
						// Slide Style
						$_content_alignment = ! empty( $content_slider['content_alignment'] ) ? esc_attr( $content_slider['content_alignment'] ) : 'left';
						$_heading_font_size = ! empty( $content_slider['heading_font_size'] ) ? esc_attr( $content_slider['heading_font_size'] ) : '60px';
						$_heading_color     = ! empty( $content_slider['heading_color'] ) ? esc_attr( $content_slider['heading_color'] ) : '#ffffff';
						$_heading_bg_color  = ! empty( $content_slider['heading_background_color'] ) ? esc_attr( $content_slider['heading_background_color'] ) : '';
						$_desc_font_size    = ! empty( $content_slider['description_font_size'] ) ? esc_attr( $content_slider['description_font_size'] ) : '24px';
						$_desc_color        = ! empty( $content_slider['description_color'] ) ? esc_attr( $content_slider['description_color'] ) : '#ffffff';
						$_desc_bg_color     = ! empty( $content_slider['description_background_color'] ) ? esc_attr( $content_slider['description_background_color'] ) : '';


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
                                                    <a href="#carousel-slider-tab-link"><?php esc_html_e( 'Slide Link', 'carousel-slider' ); ?></a>
                                                </li>
                                                <li>
                                                    <a href="#carousel-slider-tab-background"><?php esc_html_e( 'Slide Background', 'carousel-slider' ); ?></a>
                                                </li>
                                                <li>
                                                    <a href="#carousel-slider-tab-style"><?php esc_html_e( 'Slide Style', 'carousel-slider' ); ?></a>
                                                </li>
                                            </ul>

											<?php
											include CAROUSEL_SLIDER_TEMPLATES . '/admin/parts/tab-content.php';
											include CAROUSEL_SLIDER_TEMPLATES . '/admin/parts/tab-link.php';
											include CAROUSEL_SLIDER_TEMPLATES . '/admin/parts/tab-background.php';
											include CAROUSEL_SLIDER_TEMPLATES . '/admin/parts/tab-style.php';
											?>

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

<svg width="1" height="1" style="display: none;">
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


<div id="addContentButton" class="modal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Button</p>
            <button class="delete-icon" data-dismiss="modal"></button>
        </header>

        <section class="modal-card-body">
            <table id="shapla-sc-form-table" class="form-table">

                <tr>
                    <th scope="row"><label for="_button_text">Button Text</label></th>
                    <td>
                        <input class="widefat" name="_button_text" id="_button_text" value="" type="text">
                        <span class="description">Add the button text</span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="_button_url">Button URL</label></th>
                    <td>
                        <input class="widefat" name="_button_url" id="_button_url" value="" type="url">
                        <span class="description">Add the button‘s url e.g. http://example.com</span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="_button_target">Open Button Link In</label></th>
                    <td>
                        <select class="widefat" name="_button_target" id="_button_target">
                            <option value="_blank">New Window</option>
                            <option value="_self">Same window</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="_button_type">Button Type</label></th>
                    <td>
                        <select class="widefat" name="_button_type" id="_button_type">
                            <option value="normal">Normal</option>
                            <option value="stroke">Stroke</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="_button_size">Button Size</label></th>
                    <td>
                        <select class="widefat" name="_button_size" id="_button_size">
                            <option value="large">Large</option>
                            <option value="medium">Medium</option>
                            <option value="small">Small</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="_button_color">Button Color</label></th>
                    <td>
                        <input type="text" name="_button_color" id="_button_color" class="color-picker"
                               data-alpha="true" data-default-color="#f44336">
                    </td>
                </tr>

            </table>
        </section>

        <footer class="modal-card-foot">
            <a class="button button-primary">Add Button</a>
            <a class="button" data-dismiss="modal">Cancel</a>
        </footer>
    </div>
</div>
