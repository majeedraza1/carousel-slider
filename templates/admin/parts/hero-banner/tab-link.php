<?php

use CarouselSlider\Supports\Form;

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
?>
<div id="carousel-slider-tab-link" class="shapla-tab tab-content-link">
    <?php
    echo Form::field(array(
        'type' => 'buttonset',
        'group' => 'carousel_slider_content',
        'index' => $slide_num,
        'meta_key' => '_content_slider',
        'id' => 'link_type',
        'label' => esc_html__('Slide Link Type:', 'carousel-slider'),
        'description' => esc_html__('Choose how the slide will link.', 'carousel-slider'),
        'default' => 'none',
        'choices' => array(
            'none' => esc_html__('No Link', 'carousel-slider'),
            'full' => esc_html__('Full Slide', 'carousel-slider'),
            'button' => esc_html__('Button', 'carousel-slider'),
        ),
        'input_attributes' => array('class' => 'link_type',),
    ));
    ?>

    <div class="ContentCarouselLinkFull" style="display: <?php echo ($_link_type == 'full') ? 'block' : 'none'; ?>">
        <?php
        echo Form::field(array(
            'type' => 'text',
            'group' => 'carousel_slider_content',
            'index' => $slide_num,
            'meta_key' => '_content_slider',
            'id' => 'slide_link',
            'label' => esc_html__('Slide Link:', 'carousel-slider'),
            'description' => esc_html__('Please enter your URL that will be used to link the full slide.', 'carousel-slider'),
            'input_attributes' => array('class' => 'sp-input-text',),
        ));
        echo Form::field(array(
            'type' => 'buttonset',
            'group' => 'carousel_slider_content',
            'index' => $slide_num,
            'meta_key' => '_content_slider',
            'id' => 'link_target',
            'label' => esc_html__('Open Slide Link In New Window:', 'carousel-slider'),
            'default' => '_self',
            'choices' => array(
                '_blank' => esc_html__('Yes', 'carousel-slider'),
                '_self' => esc_html__('No', 'carousel-slider'),
            ),
        ));
        ?>
    </div>

    <div class="ContentCarouselLinkButtons"
         style="display: <?php echo ($_link_type == 'button') ? 'block' : 'none'; ?>">
        <div data-id="closed" id="content_carousel_button_one" class="shapla-toggle shapla-toggle--stroke">
	<span class="shapla-toggle-title">
		<?php esc_html_e('Button #1', 'carousel-slider'); ?>
	</span>
            <div class="shapla-toggle-inner">
                <div class="shapla-toggle-content">
                    <?php
                    echo Form::text(array(
                        'type' => 'text',
                        'id' => 'button_one_text',
                        'group' => 'carousel_slider_content',
                        'index' => $slide_num,
                        'meta_key' => '_content_slider',
                        'label' => esc_html__('Button Text:', 'carousel-slider'),
                        'description' => esc_html__('Please enter button text.', 'carousel-slider'),
                        'input_attributes' => array('class' => 'sp-input-text'),
                    ));

                    echo Form::text(array(
                        'type' => 'text',
                        'id' => 'button_one_url',
                        'group' => 'carousel_slider_content',
                        'index' => $slide_num,
                        'meta_key' => '_content_slider',
                        'label' => esc_html__('Button URL:', 'carousel-slider'),
                        'description' => esc_html__('Add the button url e.g. http://example.com', 'carousel-slider'),
                        'input_attributes' => array('class' => 'sp-input-text'),
                    ));

                    echo Form::buttonset(array(
                        'id' => 'button_one_target',
                        'group' => 'carousel_slider_content',
                        'index' => $slide_num,
                        'meta_key' => '_content_slider',
                        'label' => esc_html__('Open Button Link In New Window:', 'carousel-slider'),
                        'default' => '_self',
                        'choices' => array(
                            '_blank' => esc_html__('Yes', 'carousel-slider'),
                            '_self' => esc_html__('No', 'carousel-slider'),
                        ),
                    ));

                    echo Form::buttonset(array(
                        'id' => 'button_one_type',
                        'group' => 'carousel_slider_content',
                        'index' => $slide_num,
                        'meta_key' => '_content_slider',
                        'label' => esc_html__('Button Type:', 'carousel-slider'),
                        'default' => 'stroke',
                        'choices' => array(
                            'normal' => esc_html__('Normal', 'carousel-slider'),
                            'stroke' => esc_html__('Stroke', 'carousel-slider'),
                        ),
                    ));

                    echo Form::buttonset(array(
                        'id' => 'button_one_size',
                        'group' => 'carousel_slider_content',
                        'index' => $slide_num,
                        'meta_key' => '_content_slider',
                        'label' => esc_html__('Button Size:', 'carousel-slider'),
                        'default' => 'medium',
                        'choices' => array(
                            'large' => esc_html__('Large', 'carousel-slider'),
                            'medium' => esc_html__('Medium', 'carousel-slider'),
                            'small' => esc_html__('Small', 'carousel-slider'),
                        ),
                    ));

                    echo Form::text(array(
                        'type' => 'text',
                        'id' => 'button_one_border_width',
                        'group' => 'carousel_slider_content',
                        'index' => $slide_num,
                        'meta_key' => '_content_slider',
                        'default' => '2px',
                        'label' => esc_html__('Border Width:', 'carousel-slider'),
                        'description' => esc_html__('Enter border width in pixel. e.g. 2px', 'carousel-slider'),
                        'input_attributes' => array('class' => 'sp-input-text'),
                    ));

                    echo Form::text(array(
                        'type' => 'text',
                        'id' => 'button_one_border_radius',
                        'group' => 'carousel_slider_content',
                        'index' => $slide_num,
                        'meta_key' => '_content_slider',
                        'default' => '3px',
                        'label' => esc_html__('Border Radius:', 'carousel-slider'),
                        'description' => esc_html__('Enter border radius in pixel. e.g. 3px', 'carousel-slider'),
                        'input_attributes' => array('class' => 'sp-input-text'),
                    ));

                    echo Form::color(array(
                        'id' => 'button_one_bg_color',
                        'group' => 'carousel_slider_content',
                        'index' => $slide_num,
                        'meta_key' => '_content_slider',
                        'default' => '#00d1b2',
                        'label' => esc_html__('Button Background Color:', 'carousel-slider'),
                        'description' => esc_html__('Choose button background color.', 'carousel-slider'),
                    ));

                    echo Form::color(array(
                        'id' => 'button_one_color',
                        'group' => 'carousel_slider_content',
                        'index' => $slide_num,
                        'meta_key' => '_content_slider',
                        'default' => '#ffffff',
                        'label' => esc_html__('Button Text Color:', 'carousel-slider'),
                        'description' => esc_html__('Choose button text color.', 'carousel-slider'),
                    ));
                    ?>
                </div>
            </div>
        </div><!-- #content_carousel_button_one -->

        <div data-id="closed" id="content_carousel_button_two" class="shapla-toggle shapla-toggle--stroke">
	        <span class="shapla-toggle-title">
		        <?php esc_html_e('Button #2', 'carousel-slider'); ?>
	        </span>
            <div class="shapla-toggle-inner">
                <div class="shapla-toggle-content">
                    <?php
                    echo Form::text(array(
                        'type' => 'text',
                        'id' => 'button_two_text',
                        'group' => 'carousel_slider_content',
                        'index' => $slide_num,
                        'meta_key' => '_content_slider',
                        'label' => esc_html__('Button Text:', 'carousel-slider'),
                        'description' => esc_html__('Please enter button text.', 'carousel-slider'),
                        'input_attributes' => array('class' => 'sp-input-text'),
                    ));

                    echo Form::text(array(
                        'type' => 'text',
                        'id' => 'button_two_url',
                        'group' => 'carousel_slider_content',
                        'index' => $slide_num,
                        'meta_key' => '_content_slider',
                        'label' => esc_html__('Button URL:', 'carousel-slider'),
                        'description' => esc_html__('Add the button url e.g. http://example.com', 'carousel-slider'),
                        'input_attributes' => array('class' => 'sp-input-text'),
                    ));

                    echo Form::buttonset(array(
                        'id' => 'button_two_target',
                        'group' => 'carousel_slider_content',
                        'index' => $slide_num,
                        'meta_key' => '_content_slider',
                        'label' => esc_html__('Open Button Link In New Window:', 'carousel-slider'),
                        'default' => '_self',
                        'choices' => array(
                            '_blank' => esc_html__('Yes', 'carousel-slider'),
                            '_self' => esc_html__('No', 'carousel-slider'),
                        ),
                    ));

                    echo Form::buttonset(array(
                        'id' => 'button_two_type',
                        'group' => 'carousel_slider_content',
                        'index' => $slide_num,
                        'meta_key' => '_content_slider',
                        'label' => esc_html__('Button Type:', 'carousel-slider'),
                        'default' => 'stroke',
                        'choices' => array(
                            'normal' => esc_html__('Normal', 'carousel-slider'),
                            'stroke' => esc_html__('Stroke', 'carousel-slider'),
                        ),
                    ));

                    echo Form::buttonset(array(
                        'id' => 'button_two_size',
                        'group' => 'carousel_slider_content',
                        'index' => $slide_num,
                        'meta_key' => '_content_slider',
                        'label' => esc_html__('Button Size:', 'carousel-slider'),
                        'default' => 'medium',
                        'choices' => array(
                            'large' => esc_html__('Large', 'carousel-slider'),
                            'medium' => esc_html__('Medium', 'carousel-slider'),
                            'small' => esc_html__('Small', 'carousel-slider'),
                        ),
                    ));

                    echo Form::text(array(
                        'type' => 'text',
                        'id' => 'button_two_border_width',
                        'group' => 'carousel_slider_content',
                        'index' => $slide_num,
                        'meta_key' => '_content_slider',
                        'default' => '2px',
                        'label' => esc_html__('Border Width:', 'carousel-slider'),
                        'description' => esc_html__('Enter border width in pixel. e.g. 2px', 'carousel-slider'),
                        'input_attributes' => array('class' => 'sp-input-text'),
                    ));

                    echo Form::text(array(
                        'type' => 'text',
                        'id' => 'button_two_border_radius',
                        'group' => 'carousel_slider_content',
                        'index' => $slide_num,
                        'meta_key' => '_content_slider',
                        'default' => '3px',
                        'label' => esc_html__('Border Radius:', 'carousel-slider'),
                        'description' => esc_html__('Enter border radius in pixel. e.g. 3px', 'carousel-slider'),
                        'input_attributes' => array('class' => 'sp-input-text'),
                    ));

                    echo Form::color(array(
                        'id' => 'button_two_bg_color',
                        'group' => 'carousel_slider_content',
                        'index' => $slide_num,
                        'meta_key' => '_content_slider',
                        'default' => '#00d1b2',
                        'label' => esc_html__('Button Background Color:', 'carousel-slider'),
                        'description' => esc_html__('Choose button background color.', 'carousel-slider'),
                    ));

                    echo Form::color(array(
                        'id' => 'button_two_color',
                        'group' => 'carousel_slider_content',
                        'index' => $slide_num,
                        'meta_key' => '_content_slider',
                        'default' => '#ffffff',
                        'label' => esc_html__('Button Text Color:', 'carousel-slider'),
                        'description' => esc_html__('Choose button text color.', 'carousel-slider'),
                    ));
                    ?>
                </div>
            </div>
        </div><!-- #content_carousel_button_two -->
    </div>
</div><!-- .tab-content-link -->