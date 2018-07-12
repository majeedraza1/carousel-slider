<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div data-id="open" id="section_content_carousel" class="shapla-toggle shapla-toggle--stroke"
     style="display: <?php echo $slide_type != 'hero-banner-slider' ? 'none' : 'block'; ?>">
    <span class="shapla-toggle-title">
        <?php esc_html_e( 'Hero Banner Slider', 'carousel-slider' ); ?>
    </span>
    <div class="shapla-toggle-inner">
        <div class="shapla-toggle-content">
            <button class="button carousel-slider__add-slide" data-post-id="<?php echo $post->ID; ?>">Add Slide</button>
            <div id="carouselSliderContentInside">
				<?php
				$content_sliders    = get_post_meta( $post->ID, '_content_slider', true );
				$content_settings   = get_post_meta( $post->ID, '_content_slider_settings', true );
				$_slide_height      = isset( $content_settings['slide_height'] ) ? $content_settings['slide_height'] : '400px';
				$_content_width     = isset( $content_settings['content_width'] ) ? $content_settings['content_width'] : '850px';
				$_slide_animation   = isset( $content_settings['slide_animation'] ) ? $content_settings['slide_animation'] : 'fadeOut';
				$_slide_padding     = isset( $content_settings['slide_padding'] ) ? $content_settings['slide_padding'] : array();
				$_content_animation = isset( $content_settings['content_animation'] ) ? $content_settings['content_animation'] : '';


				// Get WordPress media upload URL
				$upload_link = esc_url( get_upload_iframe_src( 'image', $post->ID ) );

				if ( is_array( $content_sliders ) && count( $content_sliders ) > 0 ) {
					$total_sliders = count( $content_sliders );
					foreach ( $content_sliders as $slide_num => $content_slider ) {

						$_all_bg_position = array(
							'left top'      => 'left top',
							'left center'   => 'left center',
							'left bottom'   => 'left bottom',
							'center top'    => 'center top',
							'center center' => 'center', // Default
							'center bottom' => 'center bottom',
							'right top'     => 'right top',
							'right center'  => 'right center',
							'right bottom'  => 'right bottom',
						);
						$_all_bg_size     = array(
							'auto'    => 'auto',
							'contain' => 'contain',
							'cover'   => 'cover', // Default
						);
						// Slide Content
						$_slide_heading     = isset( $content_slider['slide_heading'] ) ? $content_slider['slide_heading'] : '';
						$_slide_description = isset( $content_slider['slide_description'] ) ? $content_slider['slide_description'] : '';
						// Slide Background
						$_img_bg_position  = ! empty( $content_slider['img_bg_position'] ) ? esc_attr( $content_slider['img_bg_position'] ) : 'center center';
						$_img_bg_size      = ! empty( $content_slider['img_bg_size'] ) ? esc_attr( $content_slider['img_bg_size'] ) : 'cover';
						$_bg_color         = ! empty( $content_slider['bg_color'] ) ? esc_attr( $content_slider['bg_color'] ) : '';
						$_img_id           = ! empty( $content_slider['img_id'] ) ? absint( $content_slider['img_id'] ) : 0;
						$_ken_burns_effect = ! empty( $content_slider['ken_burns_effect'] ) ? esc_attr( $content_slider['ken_burns_effect'] ) : '';
						$_bg_overlay       = ! empty( $content_slider['bg_overlay'] ) ? esc_attr( $content_slider['bg_overlay'] ) : '';
						$_img_src          = wp_get_attachment_image_src( $_img_id, 'full' );
						$_have_img         = is_array( $_img_src );
						// Slide Link
						$_link_type   = ! empty( $content_slider['link_type'] ) ? esc_attr( $content_slider['link_type'] ) : 'full';
						$_slide_link  = ! empty( $content_slider['slide_link'] ) ? esc_url( $content_slider['slide_link'] ) : '';
						$_link_target = ! empty( $content_slider['link_target'] ) ? esc_attr( $content_slider['link_target'] ) : '_blank';
						// Slide Style
						$_content_alignment  = ! empty( $content_slider['content_alignment'] ) ? esc_attr( $content_slider['content_alignment'] ) : 'left';
						$_heading_font_size  = ! empty( $content_slider['heading_font_size'] ) ? absint( $content_slider['heading_font_size'] ) : '40';
						$_heading_gutter     = ! empty( $content_slider['heading_gutter'] ) ? esc_attr( $content_slider['heading_gutter'] ) : '30px';
						$_heading_color      = ! empty( $content_slider['heading_color'] ) ? esc_attr( $content_slider['heading_color'] ) : '#ffffff';
						$_desc_font_size     = ! empty( $content_slider['description_font_size'] ) ? absint( $content_slider['description_font_size'] ) : '20';
						$_description_gutter = ! empty( $content_slider['description_gutter'] ) ? esc_attr( $content_slider['description_gutter'] ) : '30px';
						$_desc_color         = ! empty( $content_slider['description_color'] ) ? esc_attr( $content_slider['description_color'] ) : '#ffffff';

						$_btn_1_text          = ! empty( $content_slider['button_one_text'] ) ? esc_attr( $content_slider['button_one_text'] ) : '';
						$_btn_1_url           = ! empty( $content_slider['button_one_url'] ) ? esc_attr( $content_slider['button_one_url'] ) : '';
						$_btn_1_target        = ! empty( $content_slider['button_one_target'] ) ? esc_attr( $content_slider['button_one_target'] ) : '_self';
						$_btn_1_type          = ! empty( $content_slider['button_one_type'] ) ? esc_attr( $content_slider['button_one_type'] ) : 'normal';
						$_btn_1_size          = ! empty( $content_slider['button_one_size'] ) ? esc_attr( $content_slider['button_one_size'] ) : 'medium';
						$_btn_1_bg_color      = ! empty( $content_slider['button_one_bg_color'] ) ? esc_attr( $content_slider['button_one_bg_color'] ) : '#00d1b2';
						$_btn_1_color         = ! empty( $content_slider['button_one_color'] ) ? esc_attr( $content_slider['button_one_color'] ) : '#ffffff';
						$_btn_1_border_width  = ! empty( $content_slider['button_one_border_width'] ) ? esc_attr( $content_slider['button_one_border_width'] ) : '0px';
						$_btn_1_border_radius = ! empty( $content_slider['button_one_border_radius'] ) ? esc_attr( $content_slider['button_one_border_radius'] ) : '3px';

						$_btn_2_text          = ! empty( $content_slider['button_two_text'] ) ? esc_attr( $content_slider['button_two_text'] ) : '';
						$_btn_2_url           = ! empty( $content_slider['button_two_url'] ) ? esc_attr( $content_slider['button_two_url'] ) : '';
						$_btn_2_target        = ! empty( $content_slider['button_two_target'] ) ? esc_attr( $content_slider['button_two_target'] ) : '_self';
						$_btn_2_type          = ! empty( $content_slider['button_two_type'] ) ? esc_attr( $content_slider['button_two_type'] ) : 'normal';
						$_btn_2_size          = ! empty( $content_slider['button_two_size'] ) ? esc_attr( $content_slider['button_two_size'] ) : 'medium';
						$_btn_2_bg_color      = ! empty( $content_slider['button_two_bg_color'] ) ? esc_attr( $content_slider['button_two_bg_color'] ) : '#00d1b2';
						$_btn_2_color         = ! empty( $content_slider['button_two_color'] ) ? esc_attr( $content_slider['button_two_color'] ) : '#ffffff';
						$_btn_2_border_width  = ! empty( $content_slider['button_two_border_width'] ) ? esc_attr( $content_slider['button_two_border_width'] ) : '0px';
						$_btn_2_border_radius = ! empty( $content_slider['button_two_border_radius'] ) ? esc_attr( $content_slider['button_two_border_radius'] ) : '3px';


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
                                                title="<?php esc_html_e( 'Delete current slide',
											        'carousel-slider' ); ?>"
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
                                                        title="<?php esc_html_e( 'Move Slide to Top',
													        'carousel-slider' ); ?>"
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
                                                        title="<?php esc_html_e( 'Move Slide to Bottom',
													        'carousel-slider' ); ?>"
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
                                                    <a href="#carousel-slider-tab-background"><?php esc_html_e( 'Slide Background',
															'carousel-slider' ); ?></a>
                                                </li>
                                                <li>
                                                    <a href="#carousel-slider-tab-content"><?php esc_html_e( 'Slide Content',
															'carousel-slider' ); ?></a>
                                                </li>
                                                <li>
                                                    <a href="#carousel-slider-tab-link"><?php esc_html_e( 'Slide Link',
															'carousel-slider' ); ?></a>
                                                </li>
                                                <li>
                                                    <a href="#carousel-slider-tab-style"><?php esc_html_e( 'Slide Style',
															'carousel-slider' ); ?></a>
                                                </li>
                                            </ul>

											<?php
											include CAROUSEL_SLIDER_TEMPLATES . '/admin/parts/hero-banner/tab-content.php';
											include CAROUSEL_SLIDER_TEMPLATES . '/admin/parts/hero-banner/tab-link.php';
											include CAROUSEL_SLIDER_TEMPLATES . '/admin/parts/hero-banner/tab-background.php';
											include CAROUSEL_SLIDER_TEMPLATES . '/admin/parts/hero-banner/tab-style.php';
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

            <div class="content_settings">
                <div class="sp-input-group" id="field-_content_slide_height">
                    <div class="sp-input-label">
                        <label for="_content_slide_height"><?php esc_html_e( 'Slide Height',
								'carousel-slider' ); ?></label>
                        <p class="sp-input-desc"><?php esc_html_e( 'Enter a px, em, rem or vh value for slide height. ex: 100vh',
								'carousel-slider' ); ?></p>
                    </div>
                    <div class="sp-input-field">
                        <input type="text" name="content_settings[slide_height]" id="_content_slide_height"
                               class="sp-input-text" value="<?php echo $_slide_height; ?>">
                    </div>
                </div>
                <div class="sp-input-group" id="field-_content_slide_height">
                    <div class="sp-input-label">
                        <label for="_content_slide_height"><?php esc_html_e( 'Slider Content Max Width',
								'carousel-slider' ); ?></label>
                        <p class="sp-input-desc"><?php esc_html_e( 'Enter a px, em, rem or % value for slide height. ex: 960px',
								'carousel-slider' ); ?></p>
                    </div>
                    <div class="sp-input-field">
                        <input type="text" name="content_settings[content_width]" id="_content_content_width"
                               class="sp-input-text" value="<?php echo $_content_width; ?>">
                    </div>
                </div>
				<?php
				$animations = [
					''            => esc_html__( 'None', 'carousel-slider' ),
					'fadeInDown'  => esc_html__( 'Fade In Down', 'carousel-slider' ),
					'fadeInUp'    => esc_html__( 'Fade In Up', 'carousel-slider' ),
					'fadeInRight' => esc_html__( 'Fade In Right', 'carousel-slider' ),
					'fadeInLeft'  => esc_html__( 'Fade In Left', 'carousel-slider' ),
					'zoomIn'      => esc_html__( 'Zoom In', 'carousel-slider' ),
				];
				?>
                <div class="sp-input-group" id="field-_content_animation">
                    <div class="sp-input-label">
                        <label for="_content_animation"><?php esc_html_e( 'Content Animation',
								'carousel-slider' ); ?></label>
                        <p class="sp-input-desc"><?php esc_html_e( 'Select slide content animation.',
								'carousel-slider' ); ?></p>
                    </div>
                    <div class="sp-input-field">
                        <select name="content_settings[content_animation]" id="_content_animation"
                                class="sp-input-text">
							<?php
							foreach ( $animations as $animation_slug => $animation ) {
								$__selected = selected( $_content_animation, $animation_slug, false );
								echo '<option value="' . $animation_slug . '" ' . $__selected . '>' . $animation . '</option>';
							}
							?>
                        </select>
                    </div>
                </div>
                <div class="sp-input-group" id="field-_content_slide_padding">
                    <div class="sp-input-label">
                        <label for="_content_slide_padding"><?php esc_html_e( 'Slider Padding',
								'carousel-slider' ); ?></label>
                        <p class="sp-input-desc"><?php esc_html_e( 'Enter padding around slide in px, em or rem.',
								'carousel-slider' ); ?></p>
                    </div>
                    <div class="sp-input-field">
                        <span class="dashicons dashicons-arrow-up-alt"></span>
                        <input name="content_settings[slide_padding][top]" class="spacing-text" placeholder="Top"
                               value="<?php echo isset( $_slide_padding['top'] ) ? $_slide_padding['top'] : '1rem'; ?>">

                        <span class="dashicons dashicons-arrow-right-alt"></span>
                        <input name="content_settings[slide_padding][right]" class="spacing-text" placeholder="Right"
                               value="<?php echo isset( $_slide_padding['right'] ) ? $_slide_padding['right'] : '3rem'; ?>">

                        <span class="dashicons dashicons-arrow-down-alt"></span>
                        <input name="content_settings[slide_padding][bottom]" class="spacing-text" placeholder="Bottom"
                               value="<?php echo isset( $_slide_padding['bottom'] ) ? $_slide_padding['bottom'] : '1rem'; ?>">

                        <span class="dashicons dashicons-arrow-left-alt"></span>
                        <input name="content_settings[slide_padding][left]" class="spacing-text" placeholder="Left"
                               value="<?php echo isset( $_slide_padding['left'] ) ? $_slide_padding['left'] : '3rem'; ?>">
                    </div>
                </div>
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
