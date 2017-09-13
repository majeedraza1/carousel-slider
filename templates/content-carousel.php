<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

carousel_slider_inline_style( $id );
$content_sliders = get_post_meta( $id, '_content_slider', true );
$settings        = get_post_meta( $id, '_content_slider_settings', true );
?>
<div <?php echo join( " ", $this->carousel_options( $id ) ); ?>>
	<?php
	foreach ( $content_sliders as $slide_id => $slide ):

		// Slide Content
		$_slide_heading     = isset( $slide['slide_heading'] ) ? $slide['slide_heading'] : '';
		$_slide_description = isset( $slide['slide_description'] ) ? $slide['slide_description'] : '';
		// Slide Style
		$_content_alignment = ! empty( $slide['content_alignment'] ) ? esc_attr( $slide['content_alignment'] ) : 'left';
		$_heading_font_size = ! empty( $slide['heading_font_size'] ) ? esc_attr( $slide['heading_font_size'] ) : '60px';
		$_heading_color     = ! empty( $slide['heading_color'] ) ? esc_attr( $slide['heading_color'] ) : '#ffffff';
		$_heading_bg_color  = ! empty( $slide['heading_background_color'] ) ? esc_attr( $slide['heading_background_color'] ) : '';
		$_desc_font_size    = ! empty( $slide['description_font_size'] ) ? esc_attr( $slide['description_font_size'] ) : '24px';
		$_desc_color        = ! empty( $slide['description_color'] ) ? esc_attr( $slide['description_color'] ) : '#ffffff';
		$_desc_bg_color     = ! empty( $slide['description_background_color'] ) ? esc_attr( $slide['description_background_color'] ) : '';

		// Slide Background
		$_img_bg_position = ! empty( $slide['img_bg_position'] ) ? esc_attr( $slide['img_bg_position'] ) : 'center center';
		$_img_bg_size     = ! empty( $slide['img_bg_size'] ) ? esc_attr( $slide['img_bg_size'] ) : 'contain';
		$_bg_color        = ! empty( $slide['bg_color'] ) ? esc_attr( $slide['bg_color'] ) : '';
		$_img_id          = ! empty( $slide['img_id'] ) ? absint( $slide['img_id'] ) : 0;
		$_img_src         = wp_get_attachment_image_src( $_img_id, 'full' );
		$_have_img        = is_array( $_img_src );

		$_link_type   = isset( $slide['link_type'] ) && in_array( $slide['link_type'],
			array( 'full', 'button' ) ) ? $slide['link_type'] : 'full';
		$_slide_link  = ! empty( $slide['slide_link'] ) ? esc_url( $slide['slide_link'] ) : '';
		$_link_target = ! empty( $slide['link_target'] ) && in_array( $slide['link_target'],
			array( '_self', '_blank' ) ) ? esc_url( $slide['link_target'] ) : '_self';

		// Slide Button One
		$_btn_1_text   = ! empty( $slide['button_one_text'] ) ? esc_attr( $slide['button_one_text'] ) : '';
		$_btn_1_url    = ! empty( $slide['button_one_url'] ) ? esc_url( $slide['button_one_url'] ) : '';
		$_btn_1_target = ! empty( $slide['button_one_target'] ) ? esc_attr( $slide['button_one_target'] ) : '';
		$_btn_1_type   = ! empty( $slide['button_one_type'] ) ? esc_attr( $slide['button_one_type'] ) : '';
		$_btn_1_size   = ! empty( $slide['button_one_size'] ) ? esc_attr( $slide['button_one_size'] ) : '';
		$_btn_1_color  = ! empty( $slide['button_one_color'] ) ? carousel_slider_sanitize_color( $slide['button_one_color'] ) : '';

		// Slide Button Two
		$_btn_2_text   = ! empty( $slide['button_two_text'] ) ? esc_attr( $slide['button_two_text'] ) : '';
		$_btn_2_url    = ! empty( $slide['button_two_url'] ) ? esc_url( $slide['button_two_url'] ) : '';
		$_btn_2_target = ! empty( $slide['button_two_target'] ) ? esc_attr( $slide['button_two_target'] ) : '';
		$_btn_2_type   = ! empty( $slide['button_two_type'] ) ? esc_attr( $slide['button_two_type'] ) : '';
		$_btn_2_size   = ! empty( $slide['button_two_size'] ) ? esc_attr( $slide['button_two_size'] ) : '';
		$_btn_2_color  = ! empty( $slide['button_two_color'] ) ? carousel_slider_sanitize_color( $slide['button_two_color'] ) : '';

		// Slide background style
		$canvas_style = '';
		$canvas_style .= 'background-repeat: no-repeat;';
		$canvas_style .= 'background-position: ' . $_img_bg_position . ';';
		$canvas_style .= 'background-size: ' . $_img_bg_size . ';';
		if ( $_have_img ) {
			$canvas_style .= 'background-image: url(' . $_img_src[0] . ')';
		}

		$content_inner_style = $_bg_color ? 'background-color: ' . $_bg_color . ';' : '';

		$content_style = '';
		if ( $_content_alignment == 'left' ) {
			$content_style .= 'align-items: flex-start;';
		} elseif ( $_content_alignment == 'right' ) {
			$content_style .= 'align-items: flex-end;';
		} else {
			$content_style .= 'align-items: center;';
		}

		$content_style .= isset( $settings['slide_height'] ) ? 'min-height: ' . $settings['slide_height'] . ';' : '';
		$content_style .= isset( $settings['content_width'] ) ? 'max-width: ' . $settings['content_width'] . ';' : '850px';

		$html = '';

		if ( $_link_type == 'full' && carousel_slider_is_url( $_slide_link ) ) {
			$html .= '<a href="' . $_slide_link . '" target="' . $_link_target . '">';
		}

		$html .= '<div class="carousel-slider__content" id="slide-item-' . $id . '-' . $slide_id . '" style="' . $canvas_style . '">';
		$html .= '<div class="slide-content-inner" style="' . $content_inner_style . '">';
		$html .= '<div class="slide-content" style="' . $content_style . '">';

		// Slide heading
		if ( $_slide_heading ) {
			$heading_style         = '';
			$heading_wrapper_style = '';
			$heading_style         .= 'font-size: ' . $_heading_font_size . ';';
			$heading_style         .= 'color: ' . $_heading_color . ';';
			if ( ! empty( $_heading_bg_color ) ) {
				$heading_wrapper_style .= 'background-color: ' . $_heading_bg_color . ';';
				$heading_wrapper_style .= 'padding: 0 1rem;';
			}

			$html .= '<div class="heading">';
			$html .= '<div class="heading-title-wrapper" style="' . $heading_wrapper_style . '">';
			$html .= '<h2 class="heading-title" style="' . $heading_style . '">' . wp_kses_post( $_slide_heading ) . "</h2>";
			$html .= '</div>';
			$html .= '</div>';
		}

		// Slide description
		if ( $_slide_description ) {
			$desc_style         = '';
			$desc_wrapper_style = '';
			$desc_style         .= 'font-size: ' . $_desc_font_size . ';';
			$desc_style         .= 'color: ' . $_desc_color . ';';
			if ( ! empty( $_desc_bg_color ) ) {
				$desc_wrapper_style .= 'background-color: ' . $_desc_bg_color . ';';
				$desc_wrapper_style .= 'padding: 0 1rem;';
			}

			$html .= '<div class="description">';
			$html .= '<div class="description-title-wrapper" style="' . $desc_wrapper_style . '">';
			$html .= '<h3 class="description-title" style="' . $desc_style . '">' . wp_kses_post( $_slide_description ) . "</h3>";
			$html .= '</div>';
			$html .= '</div>';
		}

		// Buttons
		if ( $_link_type == 'button' ) {
			$html .= '<div class="buttons">';
			$html .= '<div class="buttons-wrapper">';
			// Slide Button #1
			if ( carousel_slider_is_url( $_btn_1_url ) ) {
				$_btn_1_class = 'button cs-button';
				$_btn_1_class .= ' cs-button--' . $_btn_1_type;
				$_btn_1_class .= ' cs-button--' . $_btn_1_size;

				$html .= '<span class="buttons-wrapper-one">';
				$html .= '<a class="' . $_btn_1_class . '" href="' . $_btn_1_url . '" target="' . $_btn_1_target . '">' . esc_attr( $_btn_1_text ) . "</a>";
				$html .= '</span>';
			}
			// Slide Button #2
			if ( carousel_slider_is_url( $_btn_2_url ) ) {
				$_btn_2_class = 'button cs-button';
				$_btn_2_class .= ' cs-button--' . $_btn_2_type;
				$_btn_2_class .= ' cs-button--' . $_btn_2_size;

				$html .= '<span class="buttons-wrapper-two">';
				$html .= '<a class="' . $_btn_2_class . '" href="' . $_btn_2_url . '" target="' . $_btn_2_target . '">' . esc_attr( $_btn_2_text ) . "</a>";
				$html .= '</span>';
			}
			$html .= '</div>';
			$html .= '</div>';
		}

		$html .= '</div>'; // .slide-content
		$html .= '</div>'; // .slide-content-inner
		$html .= '</div>'; // .carousel-slider__content

		if ( $_link_type == 'full' && carousel_slider_is_url( $_slide_link ) ) {
			$html .= '</a>';
		}

		echo apply_filters( 'carousel_slider_content', $html, $slide_id, $slide );
	endforeach;
	?>

</div>