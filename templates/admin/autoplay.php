<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div data-id="open" class="shapla-toggle shapla-toggle--stroke">
	<span class="shapla-toggle-title">
		<?php esc_html_e( 'Autoplay Settings', 'carousel-slider' ); ?>
	</span>
    <div class="shapla-toggle-inner">
        <div class="shapla-toggle-content">
			<?php
			$this->form->checkbox( array(
				'id'    => '_autoplay',
				'name'  => esc_html__( 'Autoplay', 'carousel-slider' ),
				'label' => esc_html__( 'Autoplay.', 'carousel-slider' ),
				'desc'  => esc_html__( 'Check to enable autoplay', 'carousel-slider' ),
				'std'   => 'on'
			) );
			$this->form->number( array(
				'id'   => '_autoplay_timeout',
				'name' => esc_html__( 'Autoplay Timeout', 'carousel-slider' ),
				'desc' => esc_html__( 'Autoplay interval timeout in millisecond. Default: 5000', 'carousel-slider' ),
				'std'  => 5000
			) );

			$this->form->number( array(
				'id'   => '_autoplay_speed',
				'name' => esc_html__( 'Autoplay Speed', 'carousel-slider' ),
				'desc' => esc_html__( 'Autoplay speed in millisecond. Default: 500', 'carousel-slider' ),
				'std'  => 500
			) );
			$this->form->checkbox( array(
				'id'    => '_autoplay_pause',
				'name'  => esc_html__( 'Autoplay Hover Pause', 'carousel-slider' ),
				'label' => esc_html__( 'Pause on mouse hover.', 'carousel-slider' ),
				'desc'  => esc_html__( 'Pause autoplay on mouse hover.', 'carousel-slider' ),
			) );
			?>
        </div>
    </div>
</div>