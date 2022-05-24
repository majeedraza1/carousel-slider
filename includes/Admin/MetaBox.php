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

			add_action( 'add_meta_boxes', array( self::$instance, 'add_meta_boxes' ), 10, 2 );
			add_action( 'save_post', array( self::$instance, 'save_meta_box' ) );
		}

		return self::$instance;
	}

	/**
	 * Save custom meta box
	 *
	 * @param int $post_id The post ID.
	 */
	public function save_meta_box( int $post_id ) {
		// Check if user has permissions to save data.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( wp_verify_nonce( $_POST['_carousel_slider_nonce'] ?? '', 'carousel_slider_nonce' ) ) {

			foreach ( $_POST['carousel_slider'] as $key => $val ) {
				if ( is_array( $val ) ) {
					$val = implode( ',', $val );
				}

				update_post_meta( $post_id, $key, sanitize_text_field( $val ) );
			}

			update_post_meta( $post_id, '_carousel_slider_version', CAROUSEL_SLIDER_VERSION );

			do_action( 'carousel_slider/save_slider', $post_id, $_POST );
		}
	}

	/**
	 * Add carousel slider meta box
	 *
	 * @param string  $post_type The post type.
	 * @param WP_Post $post The post object.
	 */
	public function add_meta_boxes( $post_type, $post ) {
		if ( CAROUSEL_SLIDER_POST_TYPE !== $post_type ) {
			return;
		}

		$slide_type = get_post_meta( $post->ID, '_slide_type', true );
		if ( empty( $slide_type ) ) {
			add_meta_box(
				'carousel-slider-slide-types',
				__( 'Slider Type', 'carousel-slider' ),
				[ $this, 'carousel_slider_slide_types' ],
				CAROUSEL_SLIDER_POST_TYPE,
				'normal',
				'high'
			);

			return;
		}

		$slide_types = Helper::get_slide_types();

		$meta_boxes = [
			'carousel-slider-meta-boxes'          => [
				'title'    => sprintf(
				/* translators: 1 - Slider type label */
					__( 'Carousel Slider : %s', 'carousel-slider' ),
					$slide_types[ $slide_type ] ?? ''
				),
				'callback' => [ $this, 'carousel_slider_meta_boxes' ],
				'context'  => 'normal',
				'priority' => 'high',
			],
			'carousel-slider-usages-info'         => [
				'title'    => __( 'Usage (Shortcode)', 'carousel-slider' ),
				'callback' => [ $this, 'usages_callback' ],
				'priority' => 'high',
			],
			'carousel-slider-general-settings'    => [
				'title'    => __( 'General Settings', 'carousel-slider' ),
				'callback' => [ $this, 'general_settings_callback' ],
			],
			'carousel-slider-navigation-settings' => [
				'title'    => __( 'Navigation Settings', 'carousel-slider' ),
				'callback' => [ $this, 'navigation_settings_callback' ],
			],
			'carousel-slider-pagination-settings' => [
				'title'    => __( 'Pagination Settings', 'carousel-slider' ),
				'callback' => [ $this, 'pagination_settings_callback' ],
			],
			'carousel-slider-autoplay-settings'   => [
				'title'    => __( 'Autoplay Settings', 'carousel-slider' ),
				'callback' => [ $this, 'autoplay_settings_callback' ],
			],
			'carousel-slider-color-settings'      => [
				'title'    => __( 'Color Settings', 'carousel-slider' ),
				'callback' => [ $this, 'color_settings_callback' ],
			],
			'carousel-slider-responsive-settings' => [
				'title'    => __( 'Responsive Settings', 'carousel-slider' ),
				'callback' => [ $this, 'responsive_settings_callback' ],
			],
		];
		foreach ( $meta_boxes as $id => $meta_box ) {
			add_meta_box(
				$id,
				$meta_box['title'],
				$meta_box['callback'],
				CAROUSEL_SLIDER_POST_TYPE,
				$meta_box['context'] ?? 'side',
				$meta_box['priority'] ?? 'low'
			);
		}
	}

	/**
	 * Submit div html
	 *
	 * @param WP_Post $post The post object.
	 *
	 * @return void
	 */
	public function carousel_slider_submitdiv( $post ) {
		$slide_type   = get_post_meta( $post->ID, '_slide_type', true );
		$preview_link = esc_url( get_preview_post_link( $post ) );
		$btn_text     = empty( $slide_type ) ? __( 'Next', 'carousel-slider' ) : __( 'Update', 'carousel-slider' );
		?>
		<div id="major-publishing-actions">
			<?php if ( ! empty( $slide_type ) ) { ?>
				<div id="delete-action">
					<a href="<?php echo esc_url( $preview_link ); ?>"
					   target="wp-preview-<?php echo esc_attr( $post->ID ); ?>" id="post-preview" class="preview">
						<?php esc_html_e( 'Preview Changes', 'carousel-slider' ); ?>
					</a>
					<input type="hidden" name="wp-preview" id="wp-preview" value="">
				</div>
			<?php } ?>
			<div id="publishing-action">
				<input name="original_publish" type="hidden" id="original_publish" value="Publish">
				<input type="submit" name="publish" id="publish" class="button button-primary button-large"
					   value="<?php echo esc_attr( $btn_text ); ?>">
			</div>
			<div class="clear"></div>
		</div>
		<?php
	}

	/**
	 * Post type metabox.
	 *
	 * @return void
	 */
	public function carousel_slider_slide_types() {
		wp_nonce_field( 'carousel_slider_nonce', '_carousel_slider_nonce' );
		$slide_types = Helper::get_slider_types();
		$html        = '<div class="carousel-slider-slider-type-container">';
		$html       .= '<div class="shapla-columns is-multiline">';
		foreach ( $slide_types as $slug => $args ) {
			$id    = sprintf( '_slide_type__%s', $slug );
			$attrs = [
				'type'  => 'radio',
				'name'  => 'carousel_slider[_slide_type]',
				'id'    => $id,
				'class' => 'screen-reader-text',
				'value' => $slug,
			];

			if ( false === $args['enabled'] ) {
				$attrs['disabled'] = true;
			}

			$is_pro = isset( $args['pro'] ) && true === $args['pro'];

			$html .= '<div class="shapla-column is-6-tablet is-4-desktop is-3-fullhd">';
			$html .= '<input ' . implode( ' ', Helper::array_to_attribute( $attrs ) ) . '>';
			$html .= '<label for="' . esc_attr( $id ) . '" class="option-slider-type">';
			$html .= '<span class="option-slider-type__content">';
			if ( isset( $args['icon'] ) ) {
				$html .= '<span class="option-slider-type__icon">' . $args['icon'] . '</span>';
			}
			$html .= '<span class="option-slider-type__label">' . esc_html( $args['label'] ) . '</span>';
			if ( $is_pro ) {
				$html .= '<span class="option-slider-type__pro">' . esc_html__( 'Pro', 'carousel-slider' ) . '</span>';
			}
			$html .= '</span>';
			$html .= '</label>';
			$html .= '</div>';
		}
		$html .= '</div>';
		$html .= '</div>';

		Helper::print_unescaped_internal_string( $html );
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
			[
				'id'          => '_image_size',
				'label'       => esc_html__( 'Carousel Image size', 'carousel-slider' ),
				'description' => sprintf(
				/* translators: 1: setting media page link start, 2: setting media page link end */
					esc_html__( 'Choose "original uploaded image" for full size image or your desired image size for carousel image. You can change the default size for thumbnail, medium and large from %1$s Settings >> Media %2$s.', 'carousel-slider' ),
					'<a target="_blank" href="' . admin_url( 'options-media.php' ) . '">',
					'</a>'
				),
				'context'     => 'side',
			]
		);
		$form->number(
			array(
				'id'      => '_margin_right',
				'class'   => 'widefat',
				'name'    => esc_html__( 'Item Spacing.', 'carousel-slider' ),
				'desc'    => esc_html__( 'Space between two slide. Enter 10 for 10px', 'carousel-slider' ),
				'std'     => Helper::get_default_setting( 'margin_right' ),
				'context' => 'side',
			)
		);
		$form->number(
			array(
				'id'      => '_stage_padding',
				'class'   => 'widefat',
				'name'    => esc_html__( 'Stage Padding', 'carousel-slider' ),
				'desc'    => esc_html__( 'Add left and right padding on carousel slider stage wrapper.', 'carousel-slider' ),
				'std'     => '0',
				'context' => 'side',
			)
		);
		$form->switch(
			[
				'id'          => '_lazy_load_image',
				'label'       => esc_html__( 'Lazy Loading', 'carousel-slider' ),
				'description' => esc_html__( 'Enable image with lazy loading.', 'carousel-slider' ),
				'default'     => Helper::get_default_setting( 'lazy_load_image' ),
				'context'     => 'side',
			]
		);
		$form->switch(
			array(
				'id'          => '_infinity_loop',
				'label'       => esc_html__( 'Infinity loop', 'carousel-slider' ),
				'description' => esc_html__( 'Enable or disable loop(circular) of carousel.', 'carousel-slider' ),
				'default'     => 'on',
				'context'     => 'side',
			)
		);
		$form->switch(
			array(
				'id'          => '_auto_width',
				'label'       => esc_html__( 'Auto Width', 'carousel-slider' ),
				'description' => esc_html__( 'Set item width according to its content width. Use width style on item to get the result you want. ', 'carousel-slider' ),
				'default'     => 'off',
				'context'     => 'side',
			)
		);

		Helper::print_unescaped_internal_string(
			apply_filters( 'carousel_slider/admin/metabox_general_settings', ob_get_clean(), $form )
		);
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
				'desc'    => esc_html__( 'Steps to go for each navigation request. Write -1 to slide by page.', 'carousel-slider' ),
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

		Helper::print_unescaped_internal_string(
			apply_filters( 'carousel_slider/admin/metabox_navigation_settings', ob_get_clean(), $form )
		);
	}

	/**
	 * Pagination setting callback
	 *
	 * @return void
	 */
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

		Helper::print_unescaped_internal_string(
			apply_filters( 'carousel_slider/admin/metabox_pagination_settings', ob_get_clean(), $form )
		);
	}

	/**
	 * Autoplay settings
	 */
	public function autoplay_settings_callback() {
		$form = new MetaBoxForm();
		ob_start();
		$form->switch(
			[
				'id'      => '_autoplay',
				'class'   => 'small-text',
				'name'    => esc_html__( 'AutoPlay', 'carousel-slider' ),
				'desc'    => esc_html__( 'Choose whether slideshow should play automatically.', 'carousel-slider' ),
				'default' => 'on',
				'context' => 'side',
			]
		);
		$form->switch(
			[
				'id'      => '_autoplay_pause',
				'class'   => 'small-text',
				'name'    => esc_html__( 'Pause On Hover', 'carousel-slider' ),
				'desc'    => esc_html__( 'Pause automatic play on mouse hover.', 'carousel-slider' ),
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

		Helper::print_unescaped_internal_string(
			apply_filters( 'carousel_slider/admin/metabox_autoplay_settings', ob_get_clean(), $form )
		);
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

		Helper::print_unescaped_internal_string(
			apply_filters( 'carousel_slider/admin/metabox_color_settings', ob_get_clean(), $form )
		);
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

		Helper::print_unescaped_internal_string(
			apply_filters( 'carousel_slider/admin/metabox_responsive_settings', ob_get_clean(), $form )
		);
	}
}
