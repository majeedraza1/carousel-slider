<?php

namespace CarouselSlider;

defined( 'ABSPATH' ) || exit;

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
		}

		return self::$instance;
	}

	/**
	 * Show upgrade notice
	 */
	public function show_upgrade_notice() {
		$version = get_option( 'carousel_slider_version', '1.0.0' );
		if ( ! version_compare( $version, '1.10.0', '<' ) ) {
			return;
		}
		$message     = __( "Carousel Slider need to update database.", 'carousel-slider' );
		$message2    = __( "We strongly recommend creating a backup of your site before updating.", 'carousel-slider' );
		$button_text = __( "Update database", 'carousel-slider' );
		$update_url  = wp_nonce_url(
			add_query_arg( [ 'action' => 'carousel_slider_upgrade' ], admin_url( 'admin-ajax.php' ) ),
			'carousel_slider_upgrade'
		);
		$html        = '<div class="notice notice-info is-dismissible">';
		$html        .= '<p><strong>' . $message . '</strong> ' . $message2 . '</p>';
		$html        .= '<p><a href="' . $update_url . '" class="button">' . $button_text . '</a></p>';
		$html        .= '</div>';

		echo $html;
	}

	/**
	 * Run upgrade function
	 */
	public function upgrade() {
		$nonce       = $_REQUEST['_wpnonce'] ? $_REQUEST['_wpnonce'] : null;
		$is_verified = wp_verify_nonce( $nonce, 'carousel_slider_upgrade' );

		$message = '<h1>' . __( 'Carousel Slider', 'carousel-slider' ) . '</h1>';
		if ( ! ( current_user_can( 'manage_options' ) && $is_verified ) ) {
			$message .= '<p>' . __( 'Sorry. This link only for developer to do some testing.', 'carousel-slider' ) . '</p>';
			_default_wp_die_handler( $message, '', [ 'back_link' => true ] );
		}

		$version = get_option( 'carousel_slider_version', '1.0.0' );
		if ( version_compare( $version, '1.10.0', '<=' ) ) {
			static::fix_meta_key_typo_error();
		}

		// Add plugin version to database
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
		global $wpdb;
		$sql = "UPDATE {$wpdb->postmeta} SET `meta_key`= '_infinity_loop' WHERE `meta_key` = '_inifnity_loop'";
		$sql .= " AND post_id IN(" . implode( ',', $ids ) . ")";

		return $wpdb->query( $sql );
	}

	/**
	 * Get sliders ids
	 *
	 * @return array
	 */
	public static function get_sliders_ids(): array {
		global $wpdb;
		$sql     = $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type = %s", CAROUSEL_SLIDER_POST_TYPE );
		$results = $wpdb->get_results( $sql, ARRAY_A );
		$ids     = [];
		foreach ( $results as $result ) {
			$ids[] = intval( $result['ID'] );
		}

		return $ids;
	}
}
