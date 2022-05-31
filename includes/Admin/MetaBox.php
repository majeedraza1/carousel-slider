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
	 * @param string $post_type The post type.
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
		<input
			type="text"
			onmousedown="this.clicked = 1;"
			onfocus="if (!this.clicked) this.select(); else this.clicked = 2;"
			onclick="if (this.clicked === 2) this.select(); this.clicked = 0;"
			value="[carousel_slide id='<?php echo absint( $post->ID ); ?>']"
			style="background-color: #f1f1f1; width: 100%; padding: 8px;"
		>
		<?php
		Helper::print_unescaped_internal_string( ob_get_clean() );
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
		$html        .= '<div class="shapla-columns is-multiline">';
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
		 * Allow third-party plugin to add custom fields
		 */
		do_action( 'carousel_slider/meta_box_content', $post->ID, $slide_type );
	}

	/**
	 * General settings
	 */
	public function general_settings_callback() {
		$settings = MetaBoxConfig::get_general_settings();
		$html     = '';
		foreach ( $settings as $setting ) {
			$html .= MetaBoxForm::field(
				array_merge(
					$setting,
					[
						'field_class' => 'widefat',
						'context'     => 'side',
					]
				)
			);
		}

		Helper::print_unescaped_internal_string(
			apply_filters( 'carousel_slider/admin/metabox_general_settings', $html )
		);
	}

	/**
	 * Navigation settings callback
	 *
	 * @return void
	 */
	public function navigation_settings_callback() {
		$settings = MetaBoxConfig::get_navigation_settings();
		$html     = '';
		foreach ( $settings as $setting ) {
			$html .= MetaBoxForm::field(
				array_merge(
					$setting,
					[
						'field_class' => 'small-text',
						'context'     => 'side',
					]
				)
			);
		}

		Helper::print_unescaped_internal_string(
			apply_filters( 'carousel_slider/admin/metabox_navigation_settings', $html )
		);
	}

	/**
	 * Pagination setting callback
	 *
	 * @return void
	 */
	public function pagination_settings_callback() {
		$settings = MetaBoxConfig::get_pagination_settings();
		$html     = '';
		foreach ( $settings as $setting ) {
			$html .= MetaBoxForm::field(
				array_merge(
					$setting,
					[
						'field_class' => 'small-text',
						'context'     => 'side',
					]
				)
			);
		}

		Helper::print_unescaped_internal_string(
			apply_filters( 'carousel_slider/admin/metabox_pagination_settings', $html )
		);
	}

	/**
	 * Autoplay settings
	 */
	public function autoplay_settings_callback() {
		$settings = MetaBoxConfig::get_autoplay_settings();
		$html     = '';
		foreach ( $settings as $setting ) {
			$html .= MetaBoxForm::field(
				array_merge(
					$setting,
					[
						'field_class' => 'small-text',
						'context'     => 'side',
					]
				)
			);
		}

		Helper::print_unescaped_internal_string(
			apply_filters( 'carousel_slider/admin/metabox_autoplay_settings', $html )
		);
	}

	/**
	 * Metabox color settings callback
	 *
	 * @return void
	 */
	public function color_settings_callback() {
		$settings = MetaBoxConfig::get_color_settings();
		$html     = '';
		foreach ( $settings as $setting ) {
			$html .= MetaBoxForm::field(
				array_merge(
					$setting,
					[
						'field_class' => 'small-text',
						'context'     => 'side',
					]
				)
			);
		}

		Helper::print_unescaped_internal_string(
			apply_filters( 'carousel_slider/admin/metabox_color_settings', $html )
		);
	}

	/**
	 * Renders the meta box.
	 */
	public function responsive_settings_callback() {
		$settings = MetaBoxConfig::get_responsive_settings();
		$html     = '';
		foreach ( $settings as $setting ) {
			$html .= MetaBoxForm::field(
				array_merge(
					$setting,
					[
						'context' => 'side',
						'class'   => 'small-text',
					]
				)
			);
		}

		Helper::print_unescaped_internal_string(
			apply_filters( 'carousel_slider/admin/metabox_responsive_settings', $html )
		);
	}
}
