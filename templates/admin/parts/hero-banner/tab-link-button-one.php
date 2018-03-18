<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div data-id="closed" id="content_carousel_button_one" class="shapla-toggle shapla-toggle--stroke">
	<span class="shapla-toggle-title">
		<?php esc_html_e( 'Button #1', 'carousel-slider' ); ?>
	</span>
    <div class="shapla-toggle-inner">
        <div class="shapla-toggle-content">
			<?php
			$this->form->text( array(
				'id'       => 'button_one_text',
				'group'    => 'carousel_slider_content',
				'position' => $slide_num,
				'meta_key' => '_content_slider',
				'name'     => esc_html__( 'Button Text:', 'carousel-slider' ),
				'desc'     => esc_html__( 'Please enter button text.', 'carousel-slider' ),
			) );
			$this->form->text( array(
				'id'       => 'button_one_url',
				'group'    => 'carousel_slider_content',
				'position' => $slide_num,
				'meta_key' => '_content_slider',
				'name'     => esc_html__( 'Button URL:', 'carousel-slider' ),
				'desc'     => esc_html__( 'Add the button url e.g. http://example.com', 'carousel-slider' ),
			) );
			$this->form->buttonset( array(
				'id'       => 'button_one_target',
				'group'    => 'carousel_slider_content',
				'position' => $slide_num,
				'meta_key' => '_content_slider',
				'name'     => esc_html__( 'Open Button Link In New Window:', 'carousel-slider' ),
				'std'      => '_self',
				'options'  => array(
					'_blank' => esc_html__( 'Yes', 'carousel-slider' ),
					'_self'  => esc_html__( 'No', 'carousel-slider' ),
				),
			) );
			$this->form->buttonset( array(
				'id'       => 'button_one_type',
				'group'    => 'carousel_slider_content',
				'position' => $slide_num,
				'meta_key' => '_content_slider',
				'name'     => esc_html__( 'Button Type:', 'carousel-slider' ),
				'std'      => 'stroke',
				'options'  => array(
					'normal' => esc_html__( 'Normal', 'carousel-slider' ),
					'stroke' => esc_html__( 'Stroke', 'carousel-slider' ),
				),
			) );
			$this->form->buttonset( array(
				'id'       => 'button_one_size',
				'group'    => 'carousel_slider_content',
				'position' => $slide_num,
				'meta_key' => '_content_slider',
				'name'     => esc_html__( 'Button Size:', 'carousel-slider' ),
				'std'      => 'medium',
				'options'  => array(
					'large'  => esc_html__( 'Large', 'carousel-slider' ),
					'medium' => esc_html__( 'Medium', 'carousel-slider' ),
					'small'  => esc_html__( 'Small', 'carousel-slider' ),
				),
			) );
			$this->form->text( array(
				'id'       => 'button_one_border_width',
				'group'    => 'carousel_slider_content',
				'position' => $slide_num,
				'meta_key' => '_content_slider',
				'std'      => '2px',
				'name'     => esc_html__( 'Border Width:', 'carousel-slider' ),
				'desc'     => esc_html__( 'Enter border width in pixel. e.g. 2px', 'carousel-slider' ),
			) );
			$this->form->text( array(
				'id'       => 'button_one_border_radius',
				'group'    => 'carousel_slider_content',
				'position' => $slide_num,
				'meta_key' => '_content_slider',
				'std'      => '3px',
				'name'     => esc_html__( 'Border Radius:', 'carousel-slider' ),
				'desc'     => esc_html__( 'Enter border radius in pixel. e.g. 3px', 'carousel-slider' ),
			) );
			$this->form->color( array(
				'id'       => 'button_one_bg_color',
				'group'    => 'carousel_slider_content',
				'position' => $slide_num,
				'meta_key' => '_content_slider',
				'std'      => '#00d1b2',
				'name'     => esc_html__( 'Button Background Color:', 'carousel-slider' ),
				'desc'     => esc_html__( 'Choose button background color.', 'carousel-slider' ),
			) );
			$this->form->color( array(
				'id'       => 'button_one_color',
				'group'    => 'carousel_slider_content',
				'position' => $slide_num,
				'meta_key' => '_content_slider',
				'std'      => '#ffffff',
				'name'     => esc_html__( 'Button Text Color:', 'carousel-slider' ),
				'desc'     => esc_html__( 'Choose button text color.', 'carousel-slider' ),
			) );
			?>
        </div>
    </div>
</div>