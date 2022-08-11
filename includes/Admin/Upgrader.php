<?php
/**
 * The upgrade-specific functionality of the plugin.
 *
 * @package CarouselSlider
 */

namespace CarouselSlider\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Upgrader class
 */
class Upgrader {

	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	protected static $instance;

	/**
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_action( 'admin_notices', [ self::$instance, 'show_upgrade_notice' ] );
			add_action( 'wp_ajax_carousel_slider_upgrade', [ self::$instance, 'upgrade' ] );

			add_action(
				'in_plugin_update_message-carousel-slider/carousel-slider.php',
				[ self::$instance, 'in_plugin_update_message' ]
			);
		}

		return self::$instance;
	}


	/**
	 * Show in plugin update message
	 *
	 * @param array $plugin_data plugin info data.
	 */
	public function in_plugin_update_message( array $plugin_data ) {
		$current_version       = CAROUSEL_SLIDER_VERSION;
		$current_version_array = explode( '.', $current_version );
		$new_version           = $plugin_data['new_version'];
		$new_version_array     = explode( '.', $new_version );

		$html = '';
		if ( version_compare( $current_version_array[0], $new_version_array[0], '<' ) ) {
			$html .= '</p><div class="cs_plugin_upgrade_notice extensions_warning major_update">';
			$html .= '<div class="cs_plugin_upgrade_notice__title">';
			$html .= sprintf(
			/* translators: 1: plugin title, 2: plugin new version number */
				__( '%1$s version %2$s is a major update.', 'carousel-slider' ),
				'<strong>' . $plugin_data['Title'] . '</strong>',
				'<strong>' . $new_version . '</strong>'
			);
			$html .= '</div>';
			$html .= '<div class="cs_plugin_upgrade_notice__description">';
			$html .= __( 'We made a lot of major changes to this version.', 'carousel-slider' ) . ' ';
			$html .= __( 'We believe that all functionality will remain same after update (remember to refresh you cache plugin).', 'carousel-slider' ) . ' ';
			$html .= __( 'Still make sure that you took a backup so you can role back if anything happen wrong to you.', 'carousel-slider' );
			$html .= '</div>';
			$html .= '</div><p class="dummy" style="display: none">';
		}

		$message = apply_filters( 'carousel_slider/in_plugin_update_message', $html, $plugin_data );
		echo wp_kses_post( $message );
	}

	/**
	 * Show upgrade notice
	 */
	public function show_upgrade_notice() {
		$version = get_option( 'carousel_slider_version' );
		if ( ! ( false !== $version && version_compare( $version, '2.0', '<' ) ) ) {
			return;
		}
		$message     = __( 'Carousel Slider need to update database.', 'carousel-slider' );
		$message2    = __( 'We strongly recommend creating a backup of your site before updating.', 'carousel-slider' );
		$button_text = __( 'Update database', 'carousel-slider' );
		$update_url  = wp_nonce_url(
			add_query_arg( [ 'action' => 'carousel_slider_upgrade' ], admin_url( 'admin-ajax.php' ) ),
			'carousel_slider_upgrade'
		);
		$html        = '<div class="notice notice-info is-dismissible">';
		$html       .= '<p><strong>' . $message . '</strong> ' . $message2 . '</p>';
		$html       .= '<p><a href="' . $update_url . '" class="button">' . $button_text . '</a></p>';
		$html       .= '</div>';

		echo wp_kses_post( $html );
	}

	/**
	 * Run upgrade function
	 */
	public function upgrade() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$nonce       = $_REQUEST['_wpnonce'] ? sanitize_text_field( $_REQUEST['_wpnonce'] ) : null;
		$is_verified = wp_verify_nonce( $nonce, 'carousel_slider_upgrade' );

		$message = '<h1>' . __( 'Carousel Slider', 'carousel-slider' ) . '</h1>';
		if ( ! ( current_user_can( 'manage_options' ) && $is_verified ) ) {
			$message .= '<p>' . __( 'Sorry. This link only for admin to perform upgrade tasks.', 'carousel-slider' ) . '</p>';
			_default_wp_die_handler( $message, '', [ 'back_link' => true ] );
		}

		$version = get_option( 'carousel_slider_version', '1.0.0' );
		if ( version_compare( $version, '1.10.0', '<=' ) ) {
			static::fix_meta_key_typo_error();
			static::fix_product_query_type_typo_error();
		}

		// Add plugin version to database.
		update_option( 'carousel_slider_version', CAROUSEL_SLIDER_VERSION );

		$message .= '<p>' . __( 'Database upgrade process has been started.', 'carousel-slider' ) . '</p>';
		_default_wp_die_handler( $message, '', [ 'back_link' => true ] );
	}

	/**
	 * Fix meta key typo error
	 *
	 * @return bool|int
	 */
	public function fix_meta_key_typo_error() {
		$ids = static::get_sliders_ids();
		if ( count( $ids ) ) {
			global $wpdb;
			$sql  = "UPDATE {$wpdb->postmeta} SET `meta_key`= '_infinity_loop' WHERE `meta_key` = '_inifnity_loop'";
			$sql .= ' AND post_id IN(' . implode( ',', $ids ) . ')';

			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			return $wpdb->query( $sql );
		}

		return false;
	}

	/**
	 * Fix meta key typo error
	 *
	 * @return bool|int
	 */
	public function fix_product_query_type_typo_error() {
		$ids = static::get_sliders_ids();
		if ( count( $ids ) ) {
			global $wpdb;
			$sql  = "UPDATE {$wpdb->postmeta} SET `meta_value`= 'query_product' WHERE `meta_value` = 'query_porduct'";
			$sql .= " AND `meta_key` = '_product_query_type' AND post_id IN(" . implode( ',', $ids ) . ')';

			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			return $wpdb->query( $sql );
		}

		return false;
	}

	/**
	 * Get sliders ids
	 *
	 * @return array
	 */
	public static function get_sliders_ids(): array {
		global $wpdb;
		$sql = $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type = %s", CAROUSEL_SLIDER_POST_TYPE );
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$results = $wpdb->get_results( $sql, ARRAY_A );
		$ids     = [];
		foreach ( $results as $result ) {
			$ids[] = intval( $result['ID'] );
		}

		return $ids;
	}
}
