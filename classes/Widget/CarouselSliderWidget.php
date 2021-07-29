<?php

namespace CarouselSlider\Widget;

use WP_Widget;

defined( 'ABSPATH' ) || exit;

class CarouselSliderWidget extends WP_Widget {

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

			add_action( 'widgets_init', [ self::$instance, 'register' ] );
		}

		return self::$instance;
	}

	/**
	 * Register current class as widget
	 */
	public static function register() {
		register_widget( __CLASS__ );
	}

	/**
	 * Get the list of carousel sliders
	 *
	 * @return array
	 */
	private static function carousels_list(): array {
		$posts = get_posts( [
			'post_type'      => 'carousels',
			'post_status'    => 'publish',
			'posts_per_page' => 100,
			'orderby'        => 'date',
			'order'          => 'DESC',
		] );

		$items = [];

		if ( count( $posts ) ) {
			foreach ( $posts as $post ) {
				$items[] = [ 'id' => $post->ID, 'title' => $post->post_title ];
			}
		}

		return $items;
	}

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_carousel_slider',
			'description' => __( 'The easiest way to create image, video, post and WooCommerce product carousel.', 'carousel-slider' ),
		);
		parent::__construct( 'widget_carousel_slider', __( 'Carousel Slider', 'carousel-slider' ), $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {
		$title       = isset( $instance['title'] ) ? esc_html( $instance['title'] ) : null;
		$carousel_id = isset( $instance['carousel_id'] ) ? absint( $instance['carousel_id'] ) : 0;

		if ( ! $carousel_id ) {
			return;
		}

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo do_shortcode( '[carousel_slide id=' . $carousel_id . ']' );
		echo $args['after_widget'];
	}

	/**
	 * Outputs the settings update form.
	 *
	 * @param array $instance Current settings.
	 *
	 * @return void
	 */
	public function form( $instance ) {
		$carousels   = static::carousels_list();
		$carousel_id = ! empty( $instance['carousel_id'] ) ? absint( $instance['carousel_id'] ) : null;
		$title       = ! empty( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';

		if ( count( $carousels ) > 0 ) {

			printf( '<p><label for="%1$s">%2$s</label>', $this->get_field_id( 'title' ), __( 'Title (optional):', 'carousel-slider' ) );
			printf( '<input type="text" class="widefat" id="%1$s" name="%2$s" value="%3$s" /></p>', $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $title );

			printf( '<p><label>%s</label>', __( 'Choose Slide', 'carousel-slider' ) );
			printf( '<select class="widefat" name="%s">', $this->get_field_name( 'carousel_id' ) );
			foreach ( $carousels as $carousel ) {
				$selected = $carousel['id'] == $carousel_id ? 'selected="selected"' : '';
				printf(
					'<option value="%1$d" %3$s>%2$s</option>',
					absint( $carousel['id'] ),
					esc_html( $carousel['title'] ),
					$selected
				);
			}
			echo "</select></p>";

		} else {
			printf( '<p>%1$s <a href="' . admin_url( 'post-new.php?post_type=carousels' ) . '">%3$s</a> %2$s</p>',
				__( 'You did not add any carousel slider yet.', 'carousel-slider' ),
				__( 'to create a new carousel slider now.', 'carousel-slider' ),
				__( 'click here', 'carousel-slider' )
			);
		}
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$old_instance['title']       = sanitize_text_field( $new_instance['title'] );
		$old_instance['carousel_id'] = absint( $new_instance['carousel_id'] );

		return $old_instance;
	}
}
