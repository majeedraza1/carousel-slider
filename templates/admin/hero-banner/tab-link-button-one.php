<?php

use CarouselSlider\Supports\MetaBoxForm;

defined( 'ABSPATH' ) || exit;

$slide_num            = $slide_num ?? 0;
$_btn_1_text          = ! empty( $content_slider['button_one_text'] ) ? esc_attr( $content_slider['button_one_text'] ) : '';
$_btn_1_url           = ! empty( $content_slider['button_one_url'] ) ? esc_attr( $content_slider['button_one_url'] ) : '';
$_btn_1_target        = ! empty( $content_slider['button_one_target'] ) ? esc_attr( $content_slider['button_one_target'] ) : '_self';
$_btn_1_type          = ! empty( $content_slider['button_one_type'] ) ? esc_attr( $content_slider['button_one_type'] ) : 'normal';
$_btn_1_size          = ! empty( $content_slider['button_one_size'] ) ? esc_attr( $content_slider['button_one_size'] ) : 'medium';
$_btn_1_bg_color      = ! empty( $content_slider['button_one_bg_color'] ) ? esc_attr( $content_slider['button_one_bg_color'] ) : '#00d1b2';
$_btn_1_color         = ! empty( $content_slider['button_one_color'] ) ? esc_attr( $content_slider['button_one_color'] ) : '#ffffff';
$_btn_1_border_width  = ! empty( $content_slider['button_one_border_width'] ) ? esc_attr( $content_slider['button_one_border_width'] ) : '0px';
$_btn_1_border_radius = ! empty( $content_slider['button_one_border_radius'] ) ? esc_attr( $content_slider['button_one_border_radius'] ) : '3px';
$metaBox              = new MetaBoxForm;
?>
<div data-id="closed" id="content_carousel_button_one" class="shapla-toggle shapla-toggle--stroke">
	<span class="shapla-toggle-title">
		<?php esc_html_e( 'Button #1', 'carousel-slider' ); ?>
	</span>
	<div class="shapla-toggle-inner">
		<div class="shapla-toggle-content">
			<?php
			$metaBox->text( [
				'id'               => 'button_one_text',
				'name'             => esc_html__( 'Button Text', 'carousel-slider' ),
				'desc'             => esc_html__( 'Add button text', 'carousel-slider' ),
				'input_attributes' => [
					'name'  => sprintf( "carousel_slider_content[%s][button_one_text]", $slide_num ),
					'value' => $_btn_1_text,
				],
			] );
			$metaBox->text( [
				'id'               => 'button_one_url',
				'name'             => esc_html__( 'Button URL', 'carousel-slider' ),
				'desc'             => esc_html__( 'Add the button url e.g. https://example.com', 'carousel-slider' ),
				'input_attributes' => [
					'name'  => sprintf( "carousel_slider_content[%s][button_one_url]", $slide_num ),
					'value' => $_btn_1_url,
				],
			] );
			$metaBox->select( [
				'id'               => 'button_one_target',
				'name'             => esc_html__( 'Open Button Link In', 'carousel-slider' ),
				'desc'             => esc_html__( 'Add the button url e.g. https://example.com', 'carousel-slider' ),
				'options'          => [
					'_blank' => esc_html__( 'New Window', 'carousel-slider' ),
					'_self'  => esc_html__( 'Same Window', 'carousel-slider' ),
				],
				'input_attributes' => [
					'name'  => sprintf( "carousel_slider_content[%s][button_one_target]", $slide_num ),
					'value' => $_btn_1_target,
				],
			] );
			$metaBox->select( [
				'id'               => 'button_one_type',
				'name'             => esc_html__( 'Button Type', 'carousel-slider' ),
				'options'          => [
					'normal' => esc_html__( 'Normal', 'carousel-slider' ),
					'stroke' => esc_html__( 'Stroke', 'carousel-slider' ),
				],
				'input_attributes' => [
					'name'  => sprintf( "carousel_slider_content[%s][button_one_type]", $slide_num ),
					'value' => $_btn_1_type,
				],
			] );
			$metaBox->select( [
				'id'               => 'button_one_size',
				'name'             => esc_html__( 'Button Size', 'carousel-slider' ),
				'options'          => [
					'large'  => esc_html__( 'Large', 'carousel-slider' ),
					'medium' => esc_html__( 'Medium', 'carousel-slider' ),
					'small'  => esc_html__( 'Small', 'carousel-slider' ),
				],
				'input_attributes' => [
					'name'  => sprintf( "carousel_slider_content[%s][button_one_size]", $slide_num ),
					'value' => $_btn_1_size,
				],
			] );
			$metaBox->text( [
				'id'               => 'button_one_border_width',
				'name'             => esc_html__( 'Border Width', 'carousel-slider' ),
				'desc'             => esc_html__( 'Enter border width in pixel. e.g. 2px', 'carousel-slider' ),
				'input_attributes' => [
					'name'  => sprintf( "carousel_slider_content[%s][button_one_border_width]", $slide_num ),
					'value' => $_btn_1_border_width,
				],
			] );
			$metaBox->text( [
				'id'               => 'button_one_border_radius',
				'name'             => esc_html__( 'Border Radius', 'carousel-slider' ),
				'desc'             => esc_html__( 'Enter border radius in pixel. e.g. 2px', 'carousel-slider' ),
				'input_attributes' => [
					'name'  => sprintf( "carousel_slider_content[%s][button_one_border_radius]", $slide_num ),
					'value' => $_btn_1_border_radius,
				],
			] );
			$metaBox->color( [
				'id'               => 'button_one_bg_color',
				'name'             => esc_html__( 'Button Color', 'carousel-slider' ),
				'std'              => '#00d1b2',
				'input_attributes' => [
					'name'  => sprintf( "carousel_slider_content[%s][button_one_bg_color]", $slide_num ),
					'value' => $_btn_1_bg_color,
				],
			] );
			$metaBox->color( [
				'id'               => 'button_one_color',
				'name'             => esc_html__( 'Button Text Color', 'carousel-slider' ),
				'std'              => '#ffffff',
				'input_attributes' => [
					'name'  => sprintf( "carousel_slider_content[%s][button_one_color]", $slide_num ),
					'value' => $_btn_1_color,
				],
			] );
			?>
		</div>
	</div>
</div>
