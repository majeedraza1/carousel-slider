<?php

namespace CarouselSlider\Admin;

// If this file is called directly, abort.
use CarouselSlider\Api;

defined( 'ABSPATH' ) || exit;

/**
 * Feedback class
 */
class Feedback {
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

			add_action(
				'current_screen',
				function () {
					if ( ! self::$instance->is_plugins_screen() ) {
						return;
					}

					add_action( 'admin_enqueue_scripts', [ self::$instance, 'enqueue_feedback_dialog_scripts' ] );
				}
			);

			add_action( 'wp_ajax_carousel_slider_deactivate_feedback', [ self::$instance, 'deactivate_feedback' ] );
		}

		return self::$instance;
	}

	/**
	 * If this is plugins screen?
	 *
	 * @return bool
	 * @since 2.1.0
	 */
	private function is_plugins_screen(): bool {
		return in_array( get_current_screen()->id, [ 'plugins', 'plugins-network' ], true );
	}

	/**
	 * Ajax Carousel Slider deactivate feedback.
	 *
	 * Send the user feedback when Carousel Slider is deactivated.
	 *
	 * Fired by `wp_ajax_carousel_slider_deactivate_feedback` action.
	 *
	 * @since 2.1.0
	 */
	public function deactivate_feedback() {
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], '_carousel_slider_deactivate_feedback_nonce' ) ) {
			wp_send_json_error();
		}

		$reason_text = '';
		$reason_key  = '';

		if ( ! empty( $_POST['reason_key'] ) ) {
			$reason_key = $_POST['reason_key'];
		}

		if ( ! empty( $_POST["reason_{$reason_key}"] ) ) {
			$reason_text = $_POST["reason_{$reason_key}"];
		}

		Api::send_feedback( $reason_key, $reason_text );

		wp_send_json_success();
	}

	/**
	 * Enqueue feedback dialog scripts.
	 *
	 * Registers the feedback dialog scripts and enqueues them.
	 *
	 * @since 2.1.0
	 */
	public function enqueue_feedback_dialog_scripts() {
		add_action( 'admin_footer', [ $this, 'print_deactivate_feedback_dialog' ] );

		wp_register_script(
			'carousel-slider-admin-feedback',
			CAROUSEL_SLIDER_ASSETS . '/js/admin-feedback.js',
			[],
			CAROUSEL_SLIDER_VERSION,
			true
		);

		wp_enqueue_script( 'carousel-slider-admin-feedback' );
	}

	/**
	 * Print deactivate feedback dialog.
	 *
	 * Display a dialog box to ask the user why he deactivated Elementor.
	 *
	 * Fired by `admin_footer` filter.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function print_deactivate_feedback_dialog() {
		$deactivate_reasons = [
			'no_longer_needed'                 => [
				'title'             => esc_html__( 'I no longer need the plugin', 'carousel-slider' ),
				'input_placeholder' => '',
			],
			'found_a_better_plugin'            => [
				'title'             => esc_html__( 'I found a better plugin', 'carousel-slider' ),
				'input_placeholder' => esc_html__( 'Please share which plugin', 'carousel-slider' ),
			],
			'could_not_get_the_plugin_to_work' => [
				'title'             => esc_html__( 'I couldn\'t get the plugin to work', 'carousel-slider' ),
				'input_placeholder' => '',
			],
			'temporary_deactivation'           => [
				'title'             => esc_html__( 'It\'s a temporary deactivation', 'carousel-slider' ),
				'input_placeholder' => '',
			],
			'carousel_slider_pro'              => [
				'title'             => esc_html__( 'I have Carousel Slider Pro', 'carousel-slider' ),
				'input_placeholder' => '',
				'alert'             => esc_html__( 'Wait! Don\'t deactivate Carousel Slider. You have to activate both Carousel Slider and Carousel Slider Pro in order for the plugin to work.', 'carousel-slider' ),
			],
			'other'                            => [
				'title'             => esc_html__( 'Other', 'carousel-slider' ),
				'input_placeholder' => esc_html__( 'Please share the reason', 'carousel-slider' ),
			],
		];

		?>
		<dialog class="feedback-dialog" id="carousel-slider-deactivate-feedback-dialog-wrapper">
			<div class="feedback-dialog__header">
				<span class="feedback-dialog__title">
					<?php echo esc_html__( 'Quick Feedback', 'carousel-slider' ); ?>
				</span>
				<span class="feedback-dialog__cross shapla-delete-icon is-medium"></span>
			</div>
			<div class="feedback-dialog__body">
				<form id="carousel-slider-deactivate-feedback-dialog-form" class="feedback-dialog__form" method="post">
					<?php
					wp_nonce_field( '_carousel_slider_deactivate_feedback_nonce' );
					?>
					<input type="hidden" name="action" value="carousel_slider_deactivate_feedback"/>

					<div class="feedback-dialog__form-caption">
						<?php echo esc_html__( 'If you have a moment, please share why you are deactivating Carousel Slider:', 'carousel-slider' ); ?>
					</div>
					<div class="feedback-dialog__form-body">
						<?php foreach ( $deactivate_reasons as $reason_key => $reason ) : ?>
							<div class="feedback-dialog__form-control">
								<input type="radio" name="reason_key"
									   id="elementor-deactivate-feedback-<?php echo esc_attr( $reason_key ); ?>"
									   class="feedback-dialog__form-input"
									   value="<?php echo esc_attr( $reason_key ); ?>"/>
								<label for="elementor-deactivate-feedback-<?php echo esc_attr( $reason_key ); ?>"
									   class="feedback-dialog__form-label"><?php echo esc_html( $reason['title'] ); ?></label>
								<?php if ( ! empty( $reason['input_placeholder'] ) ) : ?>
									<input class="carousel-slider-feedback-text" type="text"
										   name="reason_<?php echo esc_attr( $reason_key ); ?>"
										   placeholder="<?php echo esc_attr( $reason['input_placeholder'] ); ?>"/>
								<?php endif; ?>
								<?php if ( ! empty( $reason['alert'] ) ) : ?>
									<div class="carousel-slider-feedback-alert">
										<?php echo esc_html( $reason['alert'] ); ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</form>
			</div>
			<div class="feedback-dialog__footer">
			</div>
		</dialog>
		<?php
	}
}
