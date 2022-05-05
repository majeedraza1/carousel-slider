<?php
/**
 * The slider meta box specific file of the plugin
 *
 * @package CarouselSlider/Admin
 */

namespace CarouselSlider\Admin;

use CarouselSlider\Helper;
use CarouselSlider\Supports\MetaBoxForm;
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * MetaBox class
 */
class MetaBox {

	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	private static $instance = null;

	/**
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @return MetaBox
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_action( 'add_meta_boxes', array( self::$instance, 'add_meta_boxes' ) );
			add_action( 'save_post', array( self::$instance, 'save_meta_box' ) );
		}

		return self::$instance;
	}

	/**
	 * Check current user can save slider
	 *
	 * @param int $post_id post id.
	 *
	 * @return bool
	 */
	public function current_user_can_save( int $post_id ): bool {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return false;
		}

		return false !== wp_verify_nonce( $_POST['_carousel_slider_nonce'] ?? '', 'carousel_slider_nonce' );
	}

	/**
	 * Save custom meta box
	 *
	 * @param int $post_id The post ID.
	 */
	public function save_meta_box( int $post_id ) {
		// Check if user has permissions to save data.
		if ( ! $this->current_user_can_save( $post_id ) ) {
			return;
		}

		// phpcs:ignore: WordPress.Security.NonceVerification.Missing
		foreach ( $_POST['carousel_slider'] as $key => $val ) {
			if ( is_array( $val ) ) {
				$val = implode( ',', $val );
			}

			if ( '_margin_right' === $key && 0 === $val ) {
				$val = 'zero';
			}
			update_post_meta( $post_id, $key, sanitize_text_field( $val ) );
		}

		do_action( 'carousel_slider/save_slider', $post_id, $_POST );
	}

	/**
	 * Add carousel slider meta box
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'carousel-slider-meta-boxes',
			__( 'Carousel Slider', 'carousel-slider' ),
			array( $this, 'carousel_slider_meta_boxes' ),
			CAROUSEL_SLIDER_POST_TYPE,
			'normal',
			'high'
		);
		add_meta_box(
			'carousel-slider-usages-info',
			__( 'Usage (Shortcode)', 'carousel-slider' ),
			array( $this, 'usages_callback' ),
			CAROUSEL_SLIDER_POST_TYPE,
			'side',
			'high'
		);
		add_meta_box(
			'carousel-slider-navigation-settings',
			__( 'Navigation Settings', 'carousel-slider' ),
			array( $this, 'navigation_settings_callback' ),
			CAROUSEL_SLIDER_POST_TYPE,
			'side',
			'low'
		);
		add_meta_box(
			'carousel-slider-pagination-settings',
			__( 'Pagination Settings', 'carousel-slider' ),
			array( $this, 'pagination_settings_callback' ),
			CAROUSEL_SLIDER_POST_TYPE,
			'side',
			'low'
		);
		add_meta_box(
			'carousel-slider-autoplay-settings',
			__( 'Autoplay Settings', 'carousel-slider' ),
			array( $this, 'autoplay_settings_callback' ),
			CAROUSEL_SLIDER_POST_TYPE,
			'side',
			'low'
		);
		add_meta_box(
			'carousel-slider-color-settings',
			__( 'Color Settings', 'carousel-slider' ),
			array( $this, 'color_settings_callback' ),
			CAROUSEL_SLIDER_POST_TYPE,
			'side',
			'low'
		);
		add_meta_box(
			'carousel-slider-responsive-settings',
			__( 'Responsive Settings', 'carousel-slider' ),
			array( $this, 'responsive_settings_callback' ),
			CAROUSEL_SLIDER_POST_TYPE,
			'side',
			'low'
		);
		add_meta_box(
			'carousel-slider-general-settings',
			__( 'General Settings', 'carousel-slider' ),
			array( $this, 'general_settings_callback' ),
			CAROUSEL_SLIDER_POST_TYPE,
			'advanced',
			'low'
		);
	}

	/**
	 * Load meta box content
	 *
	 * @param WP_Post $post The WP_Post object.
	 */
	public function carousel_slider_meta_boxes( WP_Post $post ) {
		wp_nonce_field( 'carousel_slider_nonce', '_carousel_slider_nonce' );

		$slide_type = get_post_meta( $post->ID, '_slide_type', true );
		$slide_type = array_key_exists( $slide_type, Helper::get_slide_types() ) ? $slide_type : 'image-carousel';

		$slide_types = Helper::get_slide_types();
		?>
		<div class="sp-input-group" style="margin: 10px 0 30px;">
			<div class="sp-input-label">
				<label for="_carousel_slider_slide_type">
					<?php esc_html_e( 'Slide Type', 'carousel-slider' ); ?>
				</label>
			</div>
			<div class="sp-input-field">
				<select name="carousel_slider[_slide_type]" id="_carousel_slider_slide_type" class="sp-input-text">
					<?php
					foreach ( $slide_types as $slug => $label ) {
						$selected = ( $slug === $slide_type ) ? 'selected' : '';

						if ( 'product-carousel' === $slug ) {
							$disabled = Helper::is_woocommerce_active() ? '' : 'disabled';
							echo sprintf(
								'<option value="%s" %s %s>%s</option>',
								esc_attr( $slug ),
								esc_attr( $selected ),
								esc_attr( $disabled ),
								esc_html( $label )
							);
							continue;
						}

						echo '<option value="' . esc_attr( $slug ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $label ) . '</option>';
					}
					?>
				</select>
			</div>
		</div>
		<?php

		/**
		 * Allow third part plugin to add custom fields
		 */
		do_action( 'carousel_slider/meta_box_content', $post->ID, $slide_type );
	}

	/**
	 * General settings
	 */
	public function general_settings_callback() {
		$form = new MetaBoxForm();
		ob_start();
		$form->image_sizes(
			array(
				'id'   => esc_html__( '_image_size', 'carousel-slider' ),
				'name' => esc_html__( 'Carousel Image size', 'carousel-slider' ),
				'desc' => sprintf(
				/* translators: 1: setting media page link start, 2: setting media page link end */
					esc_html__( 'Choose "original uploaded image" for full size image or your desired image size for carousel image. You can change the default size for thumbnail, medium and large from %1$s Settings >> Media %2$s.', 'carousel-slider' ),
					'<a target="_blank" href="' . get_admin_url() . 'options-media.php">',
					'</a>'
				),
			)
		);
		$form->select(
			array(
				'id'      => '_lazy_load_image',
				'name'    => esc_html__( 'Lazy Loading', 'carousel-slider' ),
				'desc'    => esc_html__( 'Enable image with lazy loading.', 'carousel-slider' ),
				'std'     => Helper::get_default_setting( 'lazy_load_image' ),
				'options' => array(
					'on'  => esc_html__( 'Enable', 'carousel-slider' ),
					'off' => esc_html__( 'Disable', 'carousel-slider' ),
				),
			)
		);
		$form->number(
			array(
				'id'   => '_margin_right',
				'name' => esc_html__( 'Item Spacing.', 'carousel-slider' ),
				'desc' => esc_html__( 'Space between two slide. Enter 10 for 10px', 'carousel-slider' ),
				'std'  => Helper::get_default_setting( 'margin_right' ),
			)
		);
		$form->select(
			array(
				'id'      => '_infinity_loop',
				'name'    => esc_html__( 'Infinity loop', 'carousel-slider' ),
				'desc'    => esc_html__( 'Enable or disable loop(circular) of carousel.', 'carousel-slider' ),
				'std'     => 'on',
				'options' => array(
					'on'  => esc_html__( 'Enable', 'carousel-slider' ),
					'off' => esc_html__( 'Disable', 'carousel-slider' ),
				),
			)
		);
		$form->number(
			array(
				'id'   => '_stage_padding',
				'name' => esc_html__( 'Stage Padding', 'carousel-slider' ),
				'desc' => esc_html__( 'Add left and right padding on carousel slider stage wrapper.', 'carousel-slider' ),
				'std'  => '0',
			)
		);
		$form->select(
			array(
				'id'      => '_auto_width',
				'name'    => esc_html__( 'Auto Width', 'carousel-slider' ),
				'desc'    => esc_html__( 'Set item width according to its content width. Use width style on item to get the result you want. ', 'carousel-slider' ),
				'std'     => 'off',
				'options' => array(
					'on'  => esc_html__( 'Enable', 'carousel-slider' ),
					'off' => esc_html__( 'Disable', 'carousel-slider' ),
				),
			)
		);

		echo apply_filters( 'carousel_slider/admin/metabox_general_settings', ob_get_clean(), $form );
	}

	/**
	 * Render short code meta box content
	 *
	 * @param WP_Post $post The WP_Post object.
	 */
	public function usages_callback( WP_Post $post ) {
		ob_start();
		?>
		<p>
			<strong>
				<?php esc_html_e( 'Copy the following shortcode and paste in post or page where you want to show.', 'carousel-slider' ); ?>
			</strong>
		</p>
		<input type="text" onmousedown="this.clicked = 1;"
			   onfocus="if (!this.clicked) this.select(); else this.clicked = 2;"
			   onclick="if (this.clicked === 2) this.select(); this.clicked = 0;"
			   value="[carousel_slide id='<?php echo absint( $post->ID ); ?>']"
			   style="background-color: #f1f1f1; width: 100%; padding: 8px;"
		>
		<?php
		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Navigation settings callback
	 *
	 * @return void
	 */
	public function navigation_settings_callback() {
		$form = new MetaBoxForm();
		ob_start();
		$form->select(
			[
				'type'    => 'select',
				'id'      => '_nav_button',
				'class'   => 'small-text',
				'name'    => esc_html__( 'Show Arrow Nav', 'carousel-slider' ),
				'desc'    => esc_html__( 'Choose when to show arrow navigator.', 'carousel-slider' ),
				'options' => [
					'off'    => esc_html__( 'Never', 'carousel-slider' ),
					'on'     => esc_html__( 'Mouse Over', 'carousel-slider' ),
					'always' => esc_html__( 'Always', 'carousel-slider' ),
				],
				'std'     => 'on',
				'context' => 'side',
			]
		);
		$form->text(
			[
				'type'    => 'text',
				'id'      => '_slide_by',
				'class'   => 'small-text',
				'name'    => esc_html__( 'Arrow Steps', 'carousel-slider' ),
				'desc'    => esc_html__( 'Steps to go for each navigation request. Write "page" with inverted comma to slide by page.', 'carousel-slider' ),
				'std'     => 1,
				'context' => 'side',
			]
		);
		$form->select(
			[
				'id'      => '_arrow_position',
				'class'   => 'small-text',
				'name'    => esc_html__( 'Arrow Position', 'carousel-slider' ),
				'desc'    => esc_html__( 'Choose where to show arrow. Inside slider or outside slider.', 'carousel-slider' ),
				'options' => [
					'outside' => esc_html__( 'Outside', 'carousel-slider' ),
					'inside'  => esc_html__( 'Inside', 'carousel-slider' ),
				],
				'std'     => 'outside',
				'context' => 'side',
			]
		);
		$form->number(
			[
				'id'      => '_arrow_size',
				'class'   => 'small-text',
				'name'    => esc_html__( 'Arrow Size', 'carousel-slider' ),
				'desc'    => esc_html__( 'Enter arrow size in pixels.', 'carousel-slider' ),
				'std'     => 48,
				'context' => 'side',
			]
		);

		echo apply_filters( 'carousel_slider/admin/metabox_navigation_settings', ob_get_clean(), $form );
	}

	public function pagination_settings_callback() {
		$form = new MetaBoxForm();
		ob_start();
		$form->select(
			[
				'id'      => '_dot_nav',
				'class'   => 'small-text',
				'name'    => esc_html__( 'Show Bullet Nav', 'carousel-slider' ),
				'desc'    => esc_html__( 'Choose when to show bullet navigator.', 'carousel-slider' ),
				'options' => [
					'off'   => esc_html__( 'Never', 'carousel-slider' ),
					'on'    => esc_html__( 'Always', 'carousel-slider' ),
					'hover' => esc_html__( 'Mouse Over', 'carousel-slider' ),
				],
				'std'     => 'off',
				'context' => 'side',
			]
		);
		$form->select(
			[
				'id'      => '_bullet_position',
				'class'   => 'small-text',
				'name'    => esc_html__( 'Bullet Position', 'carousel-slider' ),
				'desc'    => esc_html__( 'Choose where to show bullets.', 'carousel-slider' ),
				'options' => [
					'left'   => esc_html__( 'Left', 'carousel-slider' ),
					'center' => esc_html__( 'Center', 'carousel-slider' ),
					'right'  => esc_html__( 'Right', 'carousel-slider' ),
				],
				'std'     => 'center',
				'context' => 'side',
			]
		);
		$form->number(
			[
				'id'      => '_bullet_size',
				'class'   => 'small-text',
				'name'    => esc_html__( 'Bullet Size', 'carousel-slider' ),
				'desc'    => esc_html__( 'Enter bullet size in pixels.', 'carousel-slider' ),
				'std'     => 10,
				'context' => 'side',
			]
		);
		$form->select(
			[
				'id'      => '_bullet_shape',
				'class'   => 'small-text',
				'name'    => esc_html__( 'Bullet Shape', 'carousel-slider' ),
				'desc'    => esc_html__( 'Choose bullet nav shape.', 'carousel-slider' ),
				'options' => [
					'square' => esc_html__( 'Square', 'carousel-slider' ),
					'circle' => esc_html__( 'Circle', 'carousel-slider' ),
				],
				'std'     => 'circle',
				'context' => 'side',
			]
		);

		echo apply_filters( 'carousel_slider/admin/metabox_pagination_settings', ob_get_clean(), $form );
	}

	/**
	 * Autoplay settings
	 */
	public function autoplay_settings_callback() {
		$form = new MetaBoxForm();
		ob_start();
		$form->select(
			[
				'id'      => '_autoplay',
				'class'   => 'small-text',
				'name'    => esc_html__( 'AutoPlay', 'carousel-slider' ),
				'desc'    => esc_html__( 'Choose whether slideshow should play automatically.', 'carousel-slider' ),
				'options' => [
					'on'  => esc_html__( 'Enable', 'carousel-slider' ),
					'off' => esc_html__( 'Disable', 'carousel-slider' ),
				],
				'std'     => 'on',
				'context' => 'side',
			]
		);
		$form->select(
			[
				'id'      => '_autoplay_pause',
				'class'   => 'small-text',
				'name'    => esc_html__( 'Pause On Hover', 'carousel-slider' ),
				'desc'    => esc_html__( 'Pause automatic play on mouse hover.', 'carousel-slider' ),
				'options' => [
					'on'  => esc_html__( 'Enable', 'carousel-slider' ),
					'off' => esc_html__( 'Disable', 'carousel-slider' ),
				],
				'std'     => 'on',
				'context' => 'side',
			]
		);
		$form->number(
			[
				'id'      => '_autoplay_timeout',
				'class'   => 'small-text',
				'name'    => esc_html__( 'Autoplay Timeout', 'carousel-slider' ),
				'desc'    => esc_html__( 'Automatic play interval timeout in millisecond.', 'carousel-slider' ),
				'std'     => 5000,
				'context' => 'side',
			]
		);
		$form->number(
			[
				'id'      => '_autoplay_speed',
				'class'   => 'small-text',
				'name'    => esc_html__( 'Autoplay Speed', 'carousel-slider' ),
				'desc'    => esc_html__( 'Automatic play speed in millisecond.', 'carousel-slider' ),
				'std'     => 500,
				'context' => 'side',
			]
		);

		echo apply_filters( 'carousel_slider/admin/metabox_autoplay_settings', ob_get_clean(), $form );
	}

	/**
	 * Metabox color settings callback
	 *
	 * @return void
	 */
	public function color_settings_callback() {
		$form = new MetaBoxForm();
		ob_start();
		$form->color(
			[
				'id'      => '_nav_color',
				'name'    => esc_html__( 'Arrows & Dots Color', 'carousel-slider' ),
				'std'     => Helper::get_default_setting( 'nav_color' ),
				'context' => 'side',
			]
		);
		$form->color(
			[
				'id'      => '_nav_active_color',
				'name'    => esc_html__( 'Arrows & Dots Hover Color', 'carousel-slider' ),
				'std'     => Helper::get_default_setting( 'nav_active_color' ),
				'context' => 'side',
			]
		);

		echo apply_filters( 'carousel_slider/admin/metabox_color_settings', ob_get_clean(), $form );
	}

	/**
	 * Renders the meta box.
	 */
	public function responsive_settings_callback() {
		$form = new MetaBoxForm();
		ob_start();
		$form->number(
			[
				'id'      => '_items',
				'class'   => 'small-text',
				'name'    => esc_html__( 'Columns', 'carousel-slider' ),
				'desc'    => esc_html__( 'The number of items you want to see on the Extra Large Desktop Layout (Screens size greater than 1921 pixels DP)', 'carousel-slider' ),
				'std'     => 4,
				'context' => 'side',
			]
		);
		$form->number(
			[
				'id'      => '_items_desktop',
				'class'   => 'small-text',
				'name'    => esc_html__( 'Columns : Desktop', 'carousel-slider' ),
				'desc'    => esc_html__( 'The number of items you want to see on the Desktop Layout (Screens size from 1200 pixels DP to 1920 pixels DP)', 'carousel-slider' ),
				'std'     => 4,
				'context' => 'side',
			]
		);
		$form->number(
			[
				'id'      => '_items_small_desktop',
				'class'   => 'small-text',
				'name'    => esc_html__( 'Columns : Small Desktop', 'carousel-slider' ),
				'desc'    => esc_html__( 'The number of items you want to see on the Small Desktop Layout (Screens size from 993 pixels DP to 1199 pixels DP)', 'carousel-slider' ),
				'std'     => 3,
				'context' => 'side',
			]
		);
		$form->number(
			[
				'id'      => '_items_portrait_tablet',
				'class'   => 'small-text',
				'name'    => esc_html__( 'Columns : Tablet', 'carousel-slider' ),
				'desc'    => esc_html__( 'The number of items you want to see on the Tablet Layout (Screens size from 768 pixels DP to 992 pixels DP)', 'carousel-slider' ),
				'std'     => 2,
				'context' => 'side',
			]
		);
		$form->number(
			[
				'id'      => '_items_small_portrait_tablet',
				'class'   => 'small-text',
				'name'    => esc_html__( 'Columns : Small Tablet', 'carousel-slider' ),
				'desc'    => esc_html__( 'The number of items you want to see on the Small Tablet Layout(Screens size from 600 pixels DP to 767 pixels DP)', 'carousel-slider' ),
				'std'     => 2,
				'context' => 'side',
			]
		);
		$form->number(
			[
				'id'      => '_items_portrait_mobile',
				'class'   => 'small-text',
				'name'    => esc_html__( 'Columns : Mobile', 'carousel-slider' ),
				'desc'    => esc_html__( 'The number of items you want to see on the Mobile Layout (Screens size from 320 pixels DP to 599 pixels DP)', 'carousel-slider' ),
				'std'     => 1,
				'context' => 'side',
			]
		);

		echo apply_filters( 'carousel_slider/admin/metabox_responsive_settings', ob_get_clean(), $form );
	}
}
