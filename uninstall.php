<?php

/**
 * Fired when the plugin is uninstalled.
 * @since      1.7.3
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Delete plugin data on uninstall
 *
 * @since   1.7.3
 */
function carousel_slider_delete_plugin_data() {
	$_posts = get_posts( array(
		'posts_per_page' => - 1,
		'post_type'      => 'carousels',
		'post_status'    => 'any',
	) );

	foreach ( $_posts as $_post ) {
		wp_delete_post( $_post->ID, true );
	}

	// Delete plugin options
	if ( get_option( 'carousel_slider_version' ) !== false ) {
		delete_option( 'carousel_slider_version' );
	}
}

carousel_slider_delete_plugin_data();
