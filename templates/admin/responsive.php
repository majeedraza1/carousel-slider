<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div data-id="open" class="shapla-toggle shapla-toggle--stroke">
	<span class="shapla-toggle-title">
		<?php esc_html_e( 'Responsive Settings', 'carousel-slider' ); ?>
	</span>
    <div class="shapla-toggle-inner">
        <div class="shapla-toggle-content">
			<?php
			$this->form->number( array(
				'id'   => '_items',
				'type' => 'number',
				'name' => esc_html__( 'Columns', 'carousel-slider' ),
				'desc' => esc_html__( 'The number of items you want to see on the Extra Large Desktop Layout (Screens size greater than 1921 pixels DP)', 'carousel-slider' ),
				'std'  => 4
			) );
			$this->form->number( array(
				'id'   => '_items_desktop',
				'type' => 'number',
				'name' => esc_html__( 'Columns : Desktop', 'carousel-slider' ),
				'desc' => esc_html__( 'The number of items you want to see on the Desktop Layout (Screens size from 1200 pixels DP to 1920 pixels DP)', 'carousel-slider' ),
				'std'  => 4
			) );
			$this->form->number( array(
				'id'   => '_items_small_desktop',
				'type' => 'number',
				'name' => esc_html__( 'Columns : Small Desktop', 'carousel-slider' ),
				'desc' => esc_html__( 'The number of items you want to see on the Small Desktop Layout (Screens size from 993 pixels DP to 1199 pixels DP)', 'carousel-slider' ),
				'std'  => 4
			) );
			$this->form->number( array(
				'id'   => '_items_portrait_tablet',
				'name' => esc_html__( 'Columns : Tablet', 'carousel-slider' ),
				'desc' => esc_html__( 'The number of items you want to see on the Tablet Layout (Screens size from 768 pixels DP to 992 pixels DP)', 'carousel-slider' ),
				'std'  => 3
			) );
			$this->form->number( array(
				'id'   => '_items_small_portrait_tablet',
				'name' => esc_html__( 'Columns : Small Tablet', 'carousel-slider' ),
				'desc' => esc_html__( 'The number of items you want to see on the Small Tablet Layout(Screens size from 600 pixels DP to 767 pixels DP)', 'carousel-slider' ),
				'std'  => 2
			) );
			$this->form->number( array(
				'id'   => '_items_portrait_mobile',
				'name' => esc_html__( 'Columns : Mobile', 'carousel-slider' ),
				'desc' => esc_html__( 'The number of items you want to see on the Mobile Layout (Screens size from 320 pixels DP to 599 pixels DP)', 'carousel-slider' ),
				'std'  => 1
			) );
			?>
        </div>
    </div>
</div>