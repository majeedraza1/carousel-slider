<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$content_sliders  = get_post_meta( $id, '_content_slider', true );
$settings         = get_post_meta( $id, '_content_slider_settings', true );
$_lazy_load_image = get_post_meta( $id, '_lazy_load_image', true );
$_be_lazy         = in_array( $_lazy_load_image, array( 'on', 'off' ) ) ? $_lazy_load_image : 'on';
$slide_options    = join( " ", $this->carousel_options( $id ) );

if ( empty( $settings['content_animation'] ) ) {
	$content_animation = '';
} else {
	$content_animation = esc_attr( $settings['content_animation'] );
}

?>
<div class="carousel-slider-outer carousel-slider-outer-contents carousel-slider-outer-<?php echo $id; ?>">
	<?php carousel_slider_inline_style( $id ); ?>
    <div <?php echo $slide_options; ?> data-animation="<?php echo $content_animation; ?>">
		<?php
		foreach ( $content_sliders as $slide_id => $slide ):

			$html = '';

			$_link_type   = isset( $slide['link_type'] ) && in_array( $slide['link_type'],
				array( 'full', 'button' ) ) ? $slide['link_type'] : 'full';
			$_slide_link  = ! empty( $slide['slide_link'] ) ? esc_url( $slide['slide_link'] ) : '';
			$_link_target = ! empty( $slide['link_target'] ) && in_array( $slide['link_target'],
				array( '_self', '_blank' ) ) ? esc_attr( $slide['link_target'] ) : '_self';

			$_cell_style = '';
			$_cell_style .= isset( $settings['slide_height'] ) ? 'height: ' . $settings['slide_height'] . ';' : '';

			if ( $_link_type == 'full' && carousel_slider_is_url( $_slide_link ) ) {
				$html .= '<a class="carousel-slider-hero__cell hero__cell-' . $slide_id . '" style="' . $_cell_style . '" href="' . $_slide_link . '" target="' . $_link_target . '">';
			} else {
				$html .= '<div class="carousel-slider-hero__cell hero__cell-' . $slide_id . '" style="' . $_cell_style . '">';
			}

			// Slide Background
			$_img_bg_position  = ! empty( $slide['img_bg_position'] ) ? esc_attr( $slide['img_bg_position'] ) : 'center center';
			$_img_bg_size      = ! empty( $slide['img_bg_size'] ) ? esc_attr( $slide['img_bg_size'] ) : 'contain';
			$_bg_color         = ! empty( $slide['bg_color'] ) ? esc_attr( $slide['bg_color'] ) : '';
			$_bg_overlay       = ! empty( $slide['bg_overlay'] ) ? esc_attr( $slide['bg_overlay'] ) : '';
			$_ken_burns_effect = ! empty( $slide['ken_burns_effect'] ) ? esc_attr( $slide['ken_burns_effect'] ) : '';
			$_img_id           = ! empty( $slide['img_id'] ) ? absint( $slide['img_id'] ) : 0;
			$_img_src          = wp_get_attachment_image_src( $_img_id, 'full' );
			$_have_img         = is_array( $_img_src );

			// Slide background
			$_slide_bg_style = '';
			$_slide_bg_style .= 'background-position: ' . $_img_bg_position . ';';
			$_slide_bg_style .= 'background-size: ' . $_img_bg_size . ';';
			if ( $_have_img && $_be_lazy == 'off' ) {
				$_slide_bg_style .= 'background-image: url(' . $_img_src[0] . ');';
			}
			if ( ! empty( $_bg_color ) ) {
				$_slide_bg_style .= 'background-color: ' . $_bg_color . ';';
			}

			// Background class
			$_slide_bg_class = 'carousel-slider-hero__cell__background';

			if ( 'zoom-in' == $_ken_burns_effect ) {
				$_slide_bg_class .= ' carousel-slider-hero-ken-in';
			} elseif ( 'zoom-out' == $_ken_burns_effect ) {
				$_slide_bg_class .= ' carousel-slider-hero-ken-out';
			}

			if ( $_be_lazy == 'on' ) {
				$html .= '<div class="' . $_slide_bg_class . ' owl-lazy" data-src="' . $_img_src[0] . '" id="slide-item-' . $id . '-' . $slide_id . '" style="' . $_slide_bg_style . '"></div>';
			} else {
				$html .= '<div class="' . $_slide_bg_class . '" id="slide-item-' . $id . '-' . $slide_id . '" style="' . $_slide_bg_style . '"></div>';
			}

			// Cell Inner
			$_content_alignment = ! empty( $slide['content_alignment'] ) ? esc_attr( $slide['content_alignment'] ) : 'left';
			$_cell_inner_class  = 'carousel-slider-hero__cell__inner carousel-slider--h-position-center';
			if ( $_content_alignment == 'left' ) {
				$_cell_inner_class .= ' carousel-slider--v-position-middle carousel-slider--text-left';
			} elseif ( $_content_alignment == 'right' ) {
				$_cell_inner_class .= ' carousel-slider--v-position-middle carousel-slider--text-right';
			} else {
				$_cell_inner_class .= ' carousel-slider--v-position-middle carousel-slider--text-center';
			}

			$slide_padding   = isset( $settings['slide_padding'] ) && is_array( $settings['slide_padding'] ) ? $settings['slide_padding'] : array();
			$_padding_top    = isset( $slide_padding['top'] ) ? esc_attr( $slide_padding['top'] ) : '1rem';
			$_padding_right  = isset( $slide_padding['right'] ) ? esc_attr( $slide_padding['right'] ) : '3rem';
			$_padding_bottom = isset( $slide_padding['bottom'] ) ? esc_attr( $slide_padding['bottom'] ) : '1rem';
			$_padding_left   = isset( $slide_padding['left'] ) ? esc_attr( $slide_padding['left'] ) : '3rem';

			$_cell_inner_style = '';
			$_cell_inner_style .= 'padding: ' . $_padding_top . ' ' . $_padding_right . ' ' . $_padding_bottom . ' ' . $_padding_left . '';

			$html .= '<div class="' . $_cell_inner_class . '" style="' . $_cell_inner_style . '">';

			// Background Overlay
			if ( ! empty( $_bg_overlay ) ) {
				$_bg_overlay_style = 'background-color: ' . $_bg_overlay . ';';

				$html .= '<div class="carousel-slider-hero__cell__background_overlay" style="' . $_bg_overlay_style . '"></div>';
			}

			$_content_style = '';
			$_content_style .= isset( $settings['content_width'] ) ? 'max-width: ' . $settings['content_width'] . ';' : '850px;';

			$html .= '<div class="carousel-slider-hero__cell__content" style="' . $_content_style . '">';

			// Slide Heading
			$_slide_heading = isset( $slide['slide_heading'] ) ? $slide['slide_heading'] : '';

			$html .= '<div class="carousel-slider-hero__cell__heading">';
			$html .= wp_kses_post( $_slide_heading );
			$html .= '</div>'; // .carousel-slider-hero__cell__heading

			$_slide_description = isset( $slide['slide_description'] ) ? $slide['slide_description'] : '';

			$html .= '<div class="carousel-slider-hero__cell__description">';
			$html .= wp_kses_post( $_slide_description );
			$html .= '</div>'; // .carousel-slider-hero__cell__content

			// Buttons
			if ( $_link_type == 'button' ) {
				$html .= '<div class="carousel-slider-hero__cell__buttons">';

				// Slide Button #1
				$_btn_1_text   = ! empty( $slide['button_one_text'] ) ? esc_attr( $slide['button_one_text'] ) : '';
				$_btn_1_url    = ! empty( $slide['button_one_url'] ) ? esc_url( $slide['button_one_url'] ) : '';
				$_btn_1_target = ! empty( $slide['button_one_target'] ) ? esc_attr( $slide['button_one_target'] ) : '_self';
				$_btn_1_type   = ! empty( $slide['button_one_type'] ) ? esc_attr( $slide['button_one_type'] ) : 'normal';
				$_btn_1_size   = ! empty( $slide['button_one_size'] ) ? esc_attr( $slide['button_one_size'] ) : 'medium';
				if ( carousel_slider_is_url( $_btn_1_url ) ) {
					$_btn_1_class = 'button cs-hero-button';
					$_btn_1_class .= ' cs-hero-button-' . $slide_id . '-1';
					$_btn_1_class .= ' cs-hero-button-' . $_btn_1_type;
					$_btn_1_class .= ' cs-hero-button-' . $_btn_1_size;

					$html .= '<span class="carousel-slider-hero__cell__button__one">';
					$html .= '<a class="' . $_btn_1_class . '" href="' .
					         $_btn_1_url . '" target="' . $_btn_1_target . '">' . esc_attr( $_btn_1_text ) . "</a>";
					$html .= '</span>';
				}

				// Slide Button #2
				$_btn_2_text   = ! empty( $slide['button_two_text'] ) ? esc_attr( $slide['button_two_text'] ) : '';
				$_btn_2_url    = ! empty( $slide['button_two_url'] ) ? esc_url( $slide['button_two_url'] ) : '';
				$_btn_2_target = ! empty( $slide['button_two_target'] ) ? esc_attr( $slide['button_two_target'] ) : '_self';
				$_btn_2_size   = ! empty( $slide['button_two_size'] ) ? esc_attr( $slide['button_two_size'] ) : 'medium';
				$_btn_2_type   = ! empty( $slide['button_two_type'] ) ? esc_attr( $slide['button_two_type'] ) : 'normal';
				if ( carousel_slider_is_url( $_btn_2_url ) ) {
					$_btn_2_class = 'button cs-hero-button';
					$_btn_2_class .= ' cs-hero-button-' . $slide_id . '-2';
					$_btn_2_class .= ' cs-hero-button-' . $_btn_2_type;
					$_btn_2_class .= ' cs-hero-button-' . $_btn_2_size;

					$html .= '<span class="carousel-slider-hero__cell__button__two">';
					$html .= '<a class="' . $_btn_2_class . '" href="' . $_btn_2_url . '" target="' . $_btn_2_target . '">' . esc_attr( $_btn_2_text ) . "</a>";
					$html .= '</span>';
				}

				$html .= '</div>'; // .carousel-slider-hero__cell__button
			}

			$html .= '</div>'; // .carousel-slider-hero__cell__content
			$html .= '</div>'; // .carousel-slider-hero__cell__inner

			if ( $_link_type == 'full' && carousel_slider_is_url( $_slide_link ) ) {
				$html .= '</a>'; // .carousel-slider-hero__cell
			} else {
				$html .= '</div>'; // .carousel-slider-hero__cell
			}

			echo apply_filters( 'carousel_slider_content', $html, $slide_id, $slide );
		endforeach;
		?>
    </div>
</div>
