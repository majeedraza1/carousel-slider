<?php

namespace CarouselSlider\Admin;

use CarouselSlider\Api;
use CarouselSlider\Helper;
use CarouselSlider\TrackingData;

// If this file is called directly, abort.
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

			add_action( 'admin_notices', [ self::$instance, 'admin_notice' ] );
			add_action( 'admin_init', [ self::$instance, 'handle_optin_optout' ] );

			add_filter( 'cron_schedules', [ self::$instance, 'add_weekly_schedule' ] );
			add_action( 'carousel_slider_tracker_send_event', [ self::$instance, 'send_tracking_data' ] );
		}

		return self::$instance;
	}

	/**
	 * Add weekly cron schedule
	 *
	 * @param array $schedules List of schedules.
	 *
	 * @return array
	 */
	public function add_weekly_schedule( $schedules ) {
		if ( ! isset( $schedules['weekly'] ) ) {
			$schedules['weekly'] = array(
				'interval' => WEEK_IN_SECONDS,
				'display'  => 'Once Weekly',
			);
		}

		return $schedules;
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

		if ( ! empty( $_POST[ "reason_{$reason_key}" ] ) ) {
			$reason_text = $_POST[ "reason_{$reason_key}" ];
		}

		Api::send_deactivation_feedback( $reason_key, $reason_text );

		wp_send_json_success(
			[
				'reason_key'  => $reason_key,
				'reason_text' => $reason_text,
			]
		);
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
			'no_longer_needed'       => [
				'title'             => esc_html__( 'I no longer need the plugin', 'carousel-slider' ),
				'input_placeholder' => esc_html__( 'Please share the reason', 'carousel-slider' ),
			],
			'found_a_better_plugin'  => [
				'title'             => esc_html__( 'I found a better plugin', 'carousel-slider' ),
				'input_placeholder' => esc_html__( 'Please share which plugin', 'carousel-slider' ),
			],
			'not_working'            => [
				'title'             => esc_html__( 'I couldn\'t get the plugin to work', 'carousel-slider' ),
				'input_placeholder' => esc_html__( 'Could you tell us a bit more whats not working?', 'carousel-slider' ),
			],
			'missing_a_feature'      => [
				'title'             => esc_html__( 'Missing a specific feature', 'carousel-slider' ),
				'input_placeholder' => esc_html__( 'Could you tell us more about that feature?', 'carousel-slider' ),
			],
			'temporary_deactivation' => [
				'title'             => esc_html__( 'It\'s a temporary deactivation', 'carousel-slider' ),
				'input_placeholder' => esc_html__( 'Are you facing any problem?', 'carousel-slider' ),
			],
			'carousel_slider_pro'    => [
				'title' => esc_html__( 'I have Carousel Slider Pro', 'carousel-slider' ),
				'alert' => esc_html__( 'Wait! Don\'t deactivate Carousel Slider. You have to activate both Carousel Slider and Carousel Slider Pro in order for the plugin to work.', 'carousel-slider' ),
			],
			'other'                  => [
				'title'             => esc_html__( 'Other', 'carousel-slider' ),
				'input_placeholder' => esc_html__( 'Please share the reason', 'carousel-slider' ),
			],
		];

		?>
		<shapla-dialog type="card" heading="<?php echo esc_html__( 'Quick Feedback', 'carousel-slider' ); ?>"
					   class="feedback-dialog" id="carousel-slider-deactivate-feedback-dialog-wrapper">
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
									<textarea
										class="carousel-slider-feedback-text"
										name="reason_<?php echo esc_attr( $reason_key ); ?>"
										placeholder="<?php echo esc_attr( $reason['input_placeholder'] ); ?>"
										rows="2"
									></textarea>
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
				<div>
					We collect non sensitive data to troubleshoot problems & make product improvements.
					<a href="<?php echo esc_url( Api::PRIVACY_URL ); ?>" target="_blank">Learn more</a> about how we
					handle your data.
				</div>
			</div>
			<div class="feedback-dialog__footer cs-flex cs-justify-between" slot="footer">
				<a href="#" class="button--skip-feedback">
					<?php esc_html_e( 'Skip & Deactivate', 'carousel-slider' ); ?>
				</a>
				<button class="button--submit-feedback shapla-button is-primary is-small" disabled>
					<?php esc_html_e( 'Submit & Deactivate', 'carousel-slider' ); ?>
				</button>
			</div>
		</shapla-dialog>
		<?php
	}

	/**
	 * Show tracker notice to admin
	 *
	 * @return void
	 */
	public function admin_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( $this->notice_dismissed() || $this->tracking_allowed() ) {
			return;
		}

		/* translators: 1 - Plugin name */
		$message = sprintf( __( 'Want to help make <strong>%1$s</strong> even more awesome? Allow %1$s to collect non-sensitive diagnostic data and usage information.', 'carousel-slider' ), 'Carousel Slider' );

		$message .= ' (<a class="carousel-slider-insights-data-we-collect" href="#">' . __( 'what we collect', 'carousel-slider' ) . '</a>)';
		$message .= '<p class="description" style="display:none;">' . implode( ', ', $this->data_we_collect() ) . '. No sensitive data is tracked. ';
		$message .= '<a href="' . Api::PRIVACY_URL . '" target="_blank">Learn more</a> about how Carousel Slider collects and handle your data.</p>';

		$optin_url  = add_query_arg( 'carousel_slider_tracker_optin', 'true' );
		$optout_url = add_query_arg( 'carousel_slider_tracker_optout', 'true' );

		$html  = '<div class="updated"><p>';
		$html .= $message;
		$html .= '</p><p class="submit">';
		$html .= '&nbsp;<a href="' . esc_url( $optin_url ) . '" class="button-primary button-large">' . __( 'Allow', 'carousel-slider' ) . '</a>';
		$html .= '&nbsp;<a href="' . esc_url( $optout_url ) . '" class="button-secondary button-large">' . __( 'No thanks', 'carousel-slider' ) . '</a>';
		$html .= '</p></div>';

		$html .= "<script type='text/javascript'>
			jQuery('.carousel-slider-insights-data-we-collect').on('click', function(e) {
                e.preventDefault();
                jQuery(this).parents('.updated').find('p.description').slideToggle('fast');
            });
            </script>
        ";

		Helper::print_unescaped_internal_string( $html );
	}

	/**
	 * Handle the optin/optout
	 *
	 * @return void
	 */
	public function handle_optin_optout() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['carousel_slider_tracker_optin'] ) && 'true' === $_GET['carousel_slider_tracker_optin'] ) {
			$this->optin();

			// phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
			wp_redirect( remove_query_arg( 'carousel_slider_tracker_optin' ) );
			exit;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['carousel_slider_tracker_optout'] ) && 'true' === $_GET['carousel_slider_tracker_optout'] ) {
			$this->optout();

			// phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
			wp_redirect( remove_query_arg( 'carousel_slider_tracker_optout' ) );
			exit;
		}
	}

	/**
	 * Check if the notice has been dismissed or enabled
	 *
	 * @return boolean
	 */
	public function notice_dismissed(): bool {
		$hide_notice = get_option( 'carousel_slider_tracking_notice', null );

		if ( 'hide' === $hide_notice ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if the user has opted into tracking
	 *
	 * @return bool
	 */
	public function tracking_allowed(): bool {
		return 'yes' === get_option( 'carousel_slider_allow_tracking', 'no' );
	}

	/**
	 * Explain the user which data we collect
	 *
	 * @return array
	 */
	protected function data_we_collect(): array {
		return [
			'Server environment details (php, mysql, server, WordPress versions)',
			'Number of users in your site',
			'Site language',
			'Number of active and inactive plugins',
			'Configuration of carousel slider',
			'Site name and url',
			'Your name and email address',
		];
	}

	/**
	 * Tracking optin
	 *
	 * @return void
	 */
	public function optin() {
		update_option( 'carousel_slider_allow_tracking', 'yes' );
		update_option( 'carousel_slider_tracking_notice', 'hide' );

		$this->clear_schedule_event();
		$this->schedule_event();
		$this->send_tracking_data();
	}

	/**
	 * Optout from tracking
	 *
	 * @return void
	 */
	public function optout() {
		update_option( 'carousel_slider_allow_tracking', 'no' );
		update_option( 'carousel_slider_tracking_notice', 'hide' );

		$this->send_tracking_skipped_request();
		$this->clear_schedule_event();
	}

	/**
	 * Clear any scheduled hook
	 *
	 * @return void
	 */
	private function clear_schedule_event() {
		wp_clear_scheduled_hook( 'carousel_slider_tracker_send_event' );
	}

	/**
	 * Schedule the event weekly
	 *
	 * @return void
	 */
	private function schedule_event() {
		$hook_name = 'carousel_slider_tracker_send_event';

		if ( ! wp_next_scheduled( $hook_name ) ) {
			wp_schedule_event( time(), 'weekly', $hook_name );
		}
	}

	/**
	 * Get the last time a tracking was sent
	 *
	 * @return false|string
	 */
	private function get_last_send() {
		return get_option( 'carousel_slider_tracking_last_send', false );
	}

	/**
	 * Send request to server if user skip to send tracking data
	 */
	private function send_tracking_skipped_request() {
		update_option( 'carousel_slider_tracking_skipped', 'yes' );
	}

	/**
	 * Send tracking data to server
	 *
	 * @param boolean $override Re-sent even if it is already sent data.
	 *
	 * @return void
	 */
	public function send_tracking_data( bool $override = false ) {
		// skip on AJAX Requests.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( ! $this->tracking_allowed() && ! $override ) {
			return;
		}

		// Send a maximum of once per week.
		$last_send = $this->get_last_send();

		if ( $last_send && $last_send > strtotime( '-1 week' ) ) {
			return;
		}

		$response = Api::send_tracking_data( TrackingData::all() );
		if ( ! is_wp_error( $response ) ) {
			update_option( 'carousel_slider_tracking_last_send', time() );
		}
	}
}
