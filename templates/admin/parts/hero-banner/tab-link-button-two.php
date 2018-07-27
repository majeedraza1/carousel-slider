<?php

use CarouselSlider\Supports\Metabox;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div data-id="closed" id="content_carousel_button_one" class="shapla-toggle shapla-toggle--stroke">
	<span class="shapla-toggle-title">
		<?php esc_html_e( 'Button #2', 'carousel-slider' ); ?>
	</span>
    <div class="shapla-toggle-inner">
        <div class="shapla-toggle-content">
			<?php
			echo Metabox::text( array(
				'type'             => 'text',
				'id'               => 'button_two_text',
				'group'            => 'carousel_slider_content',
				'index'            => $slide_num,
				'meta_key'         => '_content_slider',
				'label'            => esc_html__( 'Button Text:', 'carousel-slider' ),
				'description'      => esc_html__( 'Please enter button text.', 'carousel-slider' ),
				'input_attributes' => array( 'class' => 'sp-input-text' ),
			) );

			echo Metabox::text( array(
				'type'             => 'text',
				'id'               => 'button_two_url',
				'group'            => 'carousel_slider_content',
				'index'            => $slide_num,
				'meta_key'         => '_content_slider',
				'label'            => esc_html__( 'Button URL:', 'carousel-slider' ),
				'description'      => esc_html__( 'Add the button url e.g. http://example.com', 'carousel-slider' ),
				'input_attributes' => array( 'class' => 'sp-input-text' ),
			) );

			echo Metabox::buttonset( array(
				'id'       => 'button_two_target',
				'group'    => 'carousel_slider_content',
				'index'    => $slide_num,
				'meta_key' => '_content_slider',
				'label'    => esc_html__( 'Open Button Link In New Window:', 'carousel-slider' ),
				'default'  => '_self',
				'choices'  => array(
					'_blank' => esc_html__( 'Yes', 'carousel-slider' ),
					'_self'  => esc_html__( 'No', 'carousel-slider' ),
				),
			) );

			echo Metabox::buttonset( array(
				'id'       => 'button_two_type',
				'group'    => 'carousel_slider_content',
				'index'    => $slide_num,
				'meta_key' => '_content_slider',
				'label'    => esc_html__( 'Button Type:', 'carousel-slider' ),
				'default'  => 'stroke',
				'choices'  => array(
					'normal' => esc_html__( 'Normal', 'carousel-slider' ),
					'stroke' => esc_html__( 'Stroke', 'carousel-slider' ),
				),
			) );

			echo Metabox::buttonset( array(
				'id'       => 'button_two_size',
				'group'    => 'carousel_slider_content',
				'index'    => $slide_num,
				'meta_key' => '_content_slider',
				'label'    => esc_html__( 'Button Size:', 'carousel-slider' ),
				'default'  => 'medium',
				'choices'  => array(
					'large'  => esc_html__( 'Large', 'carousel-slider' ),
					'medium' => esc_html__( 'Medium', 'carousel-slider' ),
					'small'  => esc_html__( 'Small', 'carousel-slider' ),
				),
			) );

			echo Metabox::text( array(
				'type'             => 'text',
				'id'               => 'button_two_border_width',
				'group'            => 'carousel_slider_content',
				'index'            => $slide_num,
				'meta_key'         => '_content_slider',
				'default'          => '2px',
				'label'            => esc_html__( 'Border Width:', 'carousel-slider' ),
				'description'      => esc_html__( 'Enter border width in pixel. e.g. 2px', 'carousel-slider' ),
				'input_attributes' => array( 'class' => 'sp-input-text' ),
			) );

			echo Metabox::text( array(
				'type'             => 'text',
				'id'               => 'button_two_border_radius',
				'group'            => 'carousel_slider_content',
				'index'            => $slide_num,
				'meta_key'         => '_content_slider',
				'default'          => '3px',
				'label'            => esc_html__( 'Border Radius:', 'carousel-slider' ),
				'description'      => esc_html__( 'Enter border radius in pixel. e.g. 3px', 'carousel-slider' ),
				'input_attributes' => array( 'class' => 'sp-input-text' ),
			) );

			echo Metabox::color( array(
				'id'          => 'button_two_bg_color',
				'group'       => 'carousel_slider_content',
				'index'       => $slide_num,
				'meta_key'    => '_content_slider',
				'default'     => '#00d1b2',
				'label'       => esc_html__( 'Button Background Color:', 'carousel-slider' ),
				'description' => esc_html__( 'Choose button background color.', 'carousel-slider' ),
			) );

			echo Metabox::color( array(
				'id'          => 'button_two_color',
				'group'       => 'carousel_slider_content',
				'index'       => $slide_num,
				'meta_key'    => '_content_slider',
				'default'     => '#ffffff',
				'label'       => esc_html__( 'Button Text Color:', 'carousel-slider' ),
				'description' => esc_html__( 'Choose button text color.', 'carousel-slider' ),
			) );
			?>
        </div>
    </div>
</div>