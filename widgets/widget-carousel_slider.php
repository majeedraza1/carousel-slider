<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Carousel_Slider_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'widget_carousel_slider',
			'description' => __('The easiest way to create image, video and post carousel.', 'carousel-slider'),
		);
		parent::__construct( 'widget_carousel_slider', __('Carousel Slider', 'carousel-slider'), $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		extract($args);
		
		$title 			= apply_filters( 'widget_title', esc_attr( $instance['title'] ) );
		$carousel_id 	= isset($instance['carousel_id']) ? absint( $instance['carousel_id'] ) : 0;

		if ( ! $carousel_id) {
			return;
		}

		echo $args['before_widget'];
	 
	    if ( ! empty( $title ) ) {
	        echo $args['before_title'] . $title . $args['after_title'];
	    }
	    
		echo do_shortcode('[carousel_slide id='. $carousel_id .']');
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance )
	{
		$carousels 		= $this->carousels_list();
		$carousel_id 	= ! empty( $instance['carousel_id'] ) ? absint($instance['carousel_id']) : null;
		$title 			= ! empty( $instance['title'] ) ? esc_attr($instance['title']) : '';

		if ( count( $carousels ) > 0 ) {

			echo sprintf('<p><label for="%1$s">%2$s</label>', $this->get_field_id( 'title' ), __('Title (optional):', 'carousel-slider'));
			echo sprintf('<input type="text" class="widefat" id="%1$s" name="%2$s" value="%3$s" /></p>', $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $title);

			echo sprintf('<p><label>%s</label>', __('Choose Slide', 'carousel-slider'));
			echo sprintf('<select class="widefat" name="%s">', $this->get_field_name( 'carousel_id' ));
			foreach ($carousels as $carousel ) {
				$selected = $carousel->id == $carousel_id ? 'selected="selected"' : '';
				echo sprintf(
					'<option value="%1$d" %3$s>%2$s</option>',
					$carousel->id,
					$carousel->title,
					$selected
				);
			}
			echo "</select></p>";

		} else {
			echo sprintf('<p>%1$s <a href="'. admin_url('post-new.php?post_type=carousels') .'">%3$s</a> %2$s</p>',
				__('You did not add any carousel slider yet.', 'carousel-slider'),
				__('to create a new carousel slider now.', 'carousel-slider'),
				__('click here', 'carousel-slider')
			);
		}
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] 			= sanitize_text_field( $new_instance['title'] );
		$instance['carousel_id'] 	= absint( $new_instance['carousel_id'] );

		return $instance;
	}

	private function carousels_list()
	{
		$carousels = get_posts( array(
			'post_type' 	=> 'carousels',
			'post_status' 	=> 'publish',
		) );

		if ( count($carousels) < 1) {
			return array();
		}

		return array_map(function($carousel){
			return (object) array(
				'id' 	=> absint($carousel->ID),
				'title' => esc_html($carousel->post_title),
			);
		}, $carousels);
	}
}

add_action( 'widgets_init', function(){
	register_widget( 'Carousel_Slider_Widget' );
});
