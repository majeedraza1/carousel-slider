<?php

use Elementor\Plugin;
use Elementor\Widget_Base;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Carousel_Slider_Hero_Slider extends Widget_Base {

	/**
	 * Retrieve the name.
	 *
	 * @return string The name.
	 */
	public function get_name() {
		return 'carousel-slider-hero-carousel';
	}

	/**
	 * Retrieve element title.
	 *
	 * @return string Element title.
	 */
	public function get_title() {
		return __( 'Hero Carousel', 'carousel-slider' );
	}

	/**
	 * Retrieve element icon.
	 *
	 * @return string Element icon.
	 */
	public function get_icon() {
		return 'eicon-slideshow';
	}

	/**
	 * Retrieve widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'carousel-slider-elements' );
	}

	/**
	 * Retrieve script dependencies.
	 * Get the list of script dependencies the element requires.
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'imagesloaded', 'jquery-slick' ];
	}

	public static function get_button_sizes() {
		return [
			'xs' => __( 'Extra Small', 'carousel-slider' ),
			'sm' => __( 'Small', 'carousel-slider' ),
			'md' => __( 'Medium', 'carousel-slider' ),
			'lg' => __( 'Large', 'carousel-slider' ),
			'xl' => __( 'Extra Large', 'carousel-slider' ),
		];
	}

	/**
	 * Register controls.
	 *
	 * Used to add new controls to any element type. For example, external
	 * developers use this method to register controls in a widget.
	 *
	 * Should be inherited and register new controls using `add_control()`,
	 * `add_responsive_control()` and `add_group_control()`, inside control
	 * wrappers like `start_controls_section()`, `start_controls_tabs()` and
	 * `start_controls_tab()`.
	 */
	protected function _register_controls() {
		include 'controls/section_slides.php';
		include 'controls/section_slider_options.php';
		include 'controls/section_style_slides.php';
		include 'controls/section_style_title.php';
		include 'controls/section_style_description.php';
		include 'controls/section_style_button.php';
		include 'controls/section_style_navigation.php';
	}

	/**
	 * Render element.
	 * Generates the final HTML on the frontend.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();

		if ( empty( $settings['slides'] ) ) {
			return;
		}

		$this->add_render_attribute( 'button', 'class', [ 'elementor-button', 'elementor-slide-button' ] );

		if ( ! empty( $settings['button_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['button_size'] );
		}

		$slides      = [];
		$slide_count = 0;
		foreach ( $settings['slides'] as $slide ) {
			$slide_html  = $slide_attributes = $btn_attributes = '';
			$btn_element = $slide_element = 'div';
			$slide_url   = $slide['link']['url'];

			if ( ! empty( $slide_url ) ) {
				$this->add_render_attribute( 'slide_link' . $slide_count, 'href', $slide_url );

				if ( $slide['link']['is_external'] ) {
					$this->add_render_attribute( 'slide_link' . $slide_count, 'target', '_blank' );
				}

				if ( 'button' === $slide['link_click'] ) {
					$btn_element    = 'a';
					$btn_attributes = $this->get_render_attribute_string( 'slide_link' . $slide_count );
				} else {
					$slide_element    = 'a';
					$slide_attributes = $this->get_render_attribute_string( 'slide_link' . $slide_count );
				}
			}

			if ( 'yes' === $slide['background_overlay'] ) {
				$slide_html .= '<div class="elementor-background-overlay"></div>';
			}

			$slide_html .= '<div class="elementor-slide-content">';

			if ( $slide['heading'] ) {
				$slide_html .= '<div class="elementor-slide-heading">' . $slide['heading'] . '</div>';
			}

			if ( $slide['description'] ) {
				$slide_html .= '<div class="elementor-slide-description">' . $slide['description'] . '</div>';
			}

			if ( $slide['button_text'] ) {
				$slide_html .= '<' . $btn_element . ' ' . $btn_attributes . ' ' . $this->get_render_attribute_string( 'button' ) . '>' . $slide['button_text'] . '</' . $btn_element . '>';
			}

			$ken_class = '';

			if ( '' != $slide['background_ken_burns'] ) {
				$ken_class = ' elementor-ken-' . $slide['zoom_direction'];
			}

			$slide_html .= '</div>';
			$slide_html = '<div class="slick-slide-bg' . $ken_class . '"></div><' . $slide_element . ' ' . $slide_attributes . ' class="slick-slide-inner">' . $slide_html . '</' . $slide_element . '>';
			$slides[]   = '<div class="elementor-repeater-item-' . $slide['_id'] . ' slick-slide">' . $slide_html . '</div>';
			$slide_count ++;
		}

		$is_rtl      = is_rtl();
		$direction   = $is_rtl ? 'rtl' : 'ltr';
		$show_dots   = ( in_array( $settings['navigation'], [ 'dots', 'both' ] ) );
		$show_arrows = ( in_array( $settings['navigation'], [ 'arrows', 'both' ] ) );

		$slick_options = [
			'slidesToShow'  => absint( 1 ),
			'autoplaySpeed' => absint( $settings['autoplay_speed'] ),
			'autoplay'      => ( 'yes' === $settings['autoplay'] ),
			'infinite'      => ( 'yes' === $settings['infinite'] ),
			'pauseOnHover'  => ( 'yes' === $settings['pause_on_hover'] ),
			'speed'         => absint( $settings['transition_speed'] ),
			'arrows'        => $show_arrows,
			'dots'          => $show_dots,
			'rtl'           => $is_rtl,
		];

		if ( 'fade' === $settings['transition'] ) {
			$slick_options['fade'] = true;
		}

		$carousel_classes = [ 'elementor-slides' ];

		if ( $show_arrows ) {
			$carousel_classes[] = 'slick-arrows-' . $settings['arrows_position'];
		}

		if ( $show_dots ) {
			$carousel_classes[] = 'slick-dots-' . $settings['dots_position'];
		}

		$this->add_render_attribute( 'slides', [
			'class'               => $carousel_classes,
			'data-slider_options' => wp_json_encode( $slick_options ),
			'data-animation'      => $settings['content_animation'],
		] );

		?>
        <div class="elementor-slides-wrapper elementor-slick-slider" dir="<?php echo $direction; ?>">
            <div <?php echo $this->get_render_attribute_string( 'slides' ); ?>>
				<?php echo implode( '', $slides ); ?>
            </div>
        </div>
		<?php
	}

	/**
	 * Render element output in the editor.
	 * Used to generate the live preview, using a Backbone JavaScript template.
	 *
	 * @access protected
	 */
	protected function _content_template() {
		include 'template.php';
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Carousel_Slider_Hero_Slider() );