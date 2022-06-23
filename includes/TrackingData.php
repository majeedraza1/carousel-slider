<?php

namespace CarouselSlider;

/**
 * TrackingData class
 */
class TrackingData {
	/**
	 * Get the tracking data points
	 *
	 * @return array
	 */
	public static function all(): array {
		$all_plugins = self::get_all_plugins();

		$users = get_users(
			[
				'role'    => 'administrator',
				'orderby' => 'ID',
				'order'   => 'ASC',
				'number'  => 1,
				'paged'   => 1,
			]
		);

		$admin_user = ( is_array( $users ) && ! empty( $users ) ) ? $users[0] : false;
		$first_name = '';
		$last_name  = '';

		if ( $admin_user ) {
			$first_name = $admin_user->first_name ?? $admin_user->display_name;
			$last_name  = $admin_user->last_name;
		}

		$data = [
			'url'              => esc_url( home_url() ),
			'site'             => self::get_site_name(),
			'admin_email'      => get_option( 'admin_email' ),
			'first_name'       => $first_name,
			'last_name'        => $last_name,
			'hash'             => '',
			'server'           => self::get_server_info(),
			'wp'               => self::get_wp_info(),
			'users'            => self::get_user_counts(),
			'active_plugins'   => count( $all_plugins['active_plugins'] ),
			'inactive_plugins' => count( $all_plugins['inactive_plugins'] ),
			'ip_address'       => self::get_user_ip_address(),
			'project_version'  => CAROUSEL_SLIDER_VERSION,
			'tracking_skipped' => 'no',
		];

		// Add metadata.
		$extra = self::get_extra_data();
		if ( count( $extra ) ) {
			$data['extra'] = $extra;
		}

		// Check this has previously skipped tracking.
		$skipped = get_option( 'carousel_slider_tracking_skipped' );

		if ( 'yes' === $skipped ) {
			delete_option( 'carousel_slider_tracking_skipped' );

			$data['tracking_skipped'] = 'yes';
		}

		return apply_filters( 'carousel_slider_tracker_data', $data );
	}

	/**
	 * Get the list of active and inactive plugins
	 *
	 * @return array
	 */
	public static function get_all_plugins(): array {
		// Ensure get_plugins function is loaded.
		if ( ! function_exists( 'get_plugins' ) ) {
			include ABSPATH . '/wp-admin/includes/plugin.php';
		}

		$plugins             = get_plugins();
		$active_plugins_keys = get_option( 'active_plugins', array() );
		$active_plugins      = array();

		foreach ( $plugins as $k => $v ) {
			// Take care of formatting the data how we want it.
			$formatted         = array();
			$formatted['name'] = wp_strip_all_tags( $v['Name'] );

			if ( isset( $v['Version'] ) ) {
				$formatted['version'] = wp_strip_all_tags( $v['Version'] );
			}

			if ( isset( $v['Author'] ) ) {
				$formatted['author'] = wp_strip_all_tags( $v['Author'] );
			}

			if ( isset( $v['Network'] ) ) {
				$formatted['network'] = wp_strip_all_tags( $v['Network'] );
			}

			if ( isset( $v['PluginURI'] ) ) {
				$formatted['plugin_uri'] = wp_strip_all_tags( $v['PluginURI'] );
			}

			if ( in_array( $k, $active_plugins_keys, true ) ) {
				// Remove active plugins from list so we can show active and inactive separately.
				unset( $plugins[ $k ] );
				$active_plugins[ $k ] = $formatted;
			} else {
				$plugins[ $k ] = $formatted;
			}
		}

		return [
			'active_plugins'   => $active_plugins,
			'inactive_plugins' => $plugins,
		];
	}

	/**
	 * Get site name
	 *
	 * @return string|void
	 */
	private static function get_site_name() {
		$site_name = get_bloginfo( 'name' );

		if ( empty( $site_name ) ) {
			$site_name = get_bloginfo( 'description' );
			$site_name = wp_trim_words( $site_name, 3, '' );
		}

		if ( empty( $site_name ) ) {
			$site_name = esc_url( home_url() );
		}

		return $site_name;
	}

	/**
	 * Get server related info.
	 *
	 * @return array
	 */
	private static function get_server_info(): array {
		global $wpdb;

		$server_data = array();

		if ( isset( $_SERVER['SERVER_SOFTWARE'] ) && ! empty( $_SERVER['SERVER_SOFTWARE'] ) ) {
			$server_data['software'] = $_SERVER['SERVER_SOFTWARE'];
		}

		if ( function_exists( 'phpversion' ) ) {
			$server_data['php_version'] = phpversion();
		}

		$server_data['mysql_version'] = $wpdb->db_version();

		$server_data['php_max_upload_size']  = size_format( wp_max_upload_size() );
		$server_data['php_default_timezone'] = date_default_timezone_get();
		$server_data['php_soap']             = class_exists( 'SoapClient' ) ? 'Yes' : 'No';
		$server_data['php_fsockopen']        = function_exists( 'fsockopen' ) ? 'Yes' : 'No';
		$server_data['php_curl']             = function_exists( 'curl_init' ) ? 'Yes' : 'No';

		return $server_data;
	}

	/**
	 * Get WordPress related data.
	 *
	 * @return array
	 */
	private static function get_wp_info(): array {
		$wp_data = [];

		$wp_data['memory_limit'] = WP_MEMORY_LIMIT;
		$wp_data['debug_mode']   = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? 'Yes' : 'No';
		$wp_data['locale']       = get_locale();
		$wp_data['version']      = get_bloginfo( 'version' );
		$wp_data['multisite']    = is_multisite() ? 'Yes' : 'No';
		$wp_data['theme_slug']   = get_stylesheet();

		$theme = wp_get_theme( $wp_data['theme_slug'] );

		$wp_data['theme_name']    = $theme->get( 'Name' );
		$wp_data['theme_version'] = $theme->get( 'Version' );
		$wp_data['theme_uri']     = $theme->get( 'ThemeURI' );
		$wp_data['theme_author']  = $theme->get( 'Author' );

		return $wp_data;
	}

	/**
	 * Get user totals based on user role.
	 *
	 * @return array
	 */
	public static function get_user_counts(): array {
		$user_count          = array();
		$user_count_data     = count_users();
		$user_count['total'] = $user_count_data['total_users'];

		// Get user count based on user role.
		foreach ( $user_count_data['avail_roles'] as $role => $count ) {
			if ( ! $count ) {
				continue;
			}

			$user_count[ $role ] = $count;
		}

		return $user_count;
	}

	/**
	 * Get user IP Address
	 *
	 * @return string
	 */
	private static function get_user_ip_address(): string {
		$response = wp_remote_get( 'https://icanhazip.com/' );

		if ( is_wp_error( $response ) ) {
			return '';
		}

		$ip = trim( wp_remote_retrieve_body( $response ) );

		if ( ! filter_var( $ip, FILTER_VALIDATE_IP ) ) {
			return '';
		}

		return $ip;
	}

	/**
	 * Get extra data
	 *
	 * @return array
	 */
	public static function get_extra_data(): array {
		return [
			'sliders_count'    => Helper::get_sliders_count(),
			'sliders_settings' => wp_json_encode( self::get_sliders_settings() ),
		];
	}

	/**
	 * Get slider settings
	 *
	 * @return array
	 */
	public static function get_sliders_settings(): array {
		$items = Helper::get_sliders();
		$data  = [];
		foreach ( $items as $item ) {
			$settings = [];
			$meta     = get_post_meta( $item->ID );
			foreach ( $meta as $key => $value ) {
				if ( in_array( $key, [ '_edit_lock', '_edit_last' ], true ) ) {
					continue;
				}
				$settings[ $key ] = maybe_unserialize( $value[0] );
			}
			$data[] = [
				'id'             => $item->ID,
				'title'          => $item->post_title,
				'module'         => $settings['_slide_type'] ?? '',
				'plugin_version' => $settings['_carousel_slider_version'] ?? '',
				'settings'       => $settings,
			];
		}

		return $data;
	}
}
