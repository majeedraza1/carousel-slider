<?php
/**
 * The slider meta box specific file of the plugin
 *
 * @package CarouselSlider/Admin
 */

namespace CarouselSlider\Admin;

use CarouselSlider\Abstracts\SliderSetting;
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
			$slider_type = get_post_meta( $post_id, '_slide_type', true );

			if ( apply_filters( 'carousel_slider/save_common_settings', true ) ) {
				$settings = new SliderSetting( $post_id, false );
				$settings->get_slider_type();
				$settings->read_http_post_variables( $_POST['carousel_slider'] );
				$settings->write_metadata();
			}

			update_post_meta( $post_id, '_carousel_slider_version', CAROUSEL_SLIDER_VERSION );

			/**
			 * Fires once a post has been saved.
			 *
			 * @param int $post_id Slider post ID.
			 * @param array $_POST User submitted data.
			 */
			do_action( "carousel_slider/save_slider/{$slider_type}", $post_id, $_POST );

			/**
			 * Fires once a post has been saved.
			 *
			 * @param int $post_id Slider post ID.
			 * @param array $_POST User submitted data.
			 * @param string $slider_type Slider type.
			 */
			do_action( 'carousel_slider/save_slider', $post_id, $_POST, $slider_type );
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
			'carousel-slider-meta-boxes' => [
				'title'    => sprintf(
				/* translators: 1 - Slider type label */
					__( 'Carousel Slider : %s', 'carousel-slider' ),
					$slide_types[ $slide_type ] ?? ''
				),
				'callback' => [ $this, 'carousel_slider_meta_boxes' ],
				'context'  => 'normal',
				'priority' => 'high',
			],
			'carousel-slider-settings'   => [
				'title'    => __( 'Slider Settings', 'carousel-slider' ),
				'callback' => [ $this, 'carousel_slider_settings' ],
				'context'  => 'normal',
				'priority' => 'low',
			],
			'carousel-slider-usages'     => [
				'title'    => __( 'Usage', 'carousel-slider' ),
				'callback' => [ $this, 'usages_callback' ],
				'priority' => 'low',
			],
		];
		foreach ( $meta_boxes as $id => $meta_box ) {
			add_meta_box(
				$id,
				$meta_box['title'],
				$meta_box['callback'],
				CAROUSEL_SLIDER_POST_TYPE,
				'normal',
				'low'
			);
		}
	}

	/**
	 * Render short code meta box content
	 *
	 * @param WP_Post $post The WP_Post object.
	 */
	public function usages_callback( WP_Post $post ) {
		$shortcode    = sprintf( '[carousel_slide id="%s"]', absint( $post->ID ) );
		$shortcode_in = sprintf( 'echo do_shortcode( \'[carousel_slide id="%s"]\' );', absint( $post->ID ) );
		ob_start();
		?>
		<div class="shapla-columns">
			<div class="shapla-column is-6-tablet">
				<strong><?php esc_html_e( 'Shortcode:', 'carousel-slider' ); ?></strong>
				<div
					class="input-copy-to-clipboard"><?php Helper::print_unescaped_internal_string( $shortcode ); ?></div>
			</div>
			<div class="shapla-column is-6-tablet">
				<strong><?php esc_html_e( 'Template Include:', 'carousel-slider' ); ?></strong>
				<div
					class="input-copy-to-clipboard"><?php Helper::print_unescaped_internal_string( $shortcode_in ); ?></div>
			</div>
		</div>
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
	 * Get slider settings
	 *
	 * @return void
	 */
	public function carousel_slider_settings() {
		$sections = MetaBoxConfig::get_sections_settings();
		$fields   = MetaBoxConfig::get_fields_settings();

		$html  = '<div class="shapla-section shapla-tabs shapla-tabs--normal">';
		$html .= '<div class="shapla-tab-inner">';
		$html .= '<ul class="shapla-nav shapla-clearfix">';
		foreach ( $sections as $section ) {
			$html .= '<li><a href="#' . esc_attr( $section['id'] ) . '">' . esc_html( $section['label'] ) . '</a></li>';
		}
		$html .= '</ul>';
		foreach ( $sections as $section ) {
			$html .= '<div id="' . esc_attr( $section['id'] ) . '" class="shapla-tab tab-content">';

			$section_html = '';
			foreach ( $fields as $field ) {
				if ( $field['section'] === $section['id'] ) {
					$section_html .= MetaBoxForm::field( $field );
				}
			}

			$html .= apply_filters( 'carousel_slider/admin/' . $section['hook'], $section_html );
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

		do_action( 'carousel_slider/meta_box_content/' . $slide_type, $post->ID );
		/**
		 * Allow third-party plugin to add custom fields
		 */
		do_action( 'carousel_slider/meta_box_content', $post->ID, $slide_type );
	}
}
