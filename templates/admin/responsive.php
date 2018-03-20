<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$this->form->number( array(
	'id'          => '_items',
	'context'     => 'side',
	'input_class' => 'small-text',
	'name'        => esc_html__( 'Columns', 'carousel-slider' ),
	'desc'        => esc_html__( 'The number of items you want to see on the Extra Large Desktop Layout (Screens size greater than 1921 pixels DP)', 'carousel-slider' ),
	'std'         => 4
) );
$this->form->number( array(
	'id'          => '_items_desktop',
	'context'     => 'side',
	'input_class' => 'small-text',
	'name'        => esc_html__( 'Columns : Desktop', 'carousel-slider' ),
	'desc'        => esc_html__( 'The number of items you want to see on the Desktop Layout (Screens size from 1200 pixels DP to 1920 pixels DP)', 'carousel-slider' ),
	'std'         => 4
) );
$this->form->number( array(
	'id'          => '_items_small_desktop',
	'context'     => 'side',
	'input_class' => 'small-text',
	'name'        => esc_html__( 'Columns : Small Desktop', 'carousel-slider' ),
	'desc'        => esc_html__( 'The number of items you want to see on the Small Desktop Layout (Screens size from 993 pixels DP to 1199 pixels DP)', 'carousel-slider' ),
	'std'         => 4
) );
$this->form->number( array(
	'id'          => '_items_portrait_tablet',
	'context'     => 'side',
	'input_class' => 'small-text',
	'name'        => esc_html__( 'Columns : Tablet', 'carousel-slider' ),
	'desc'        => esc_html__( 'The number of items you want to see on the Tablet Layout (Screens size from 768 pixels DP to 992 pixels DP)', 'carousel-slider' ),
	'std'         => 3
) );
$this->form->number( array(
	'id'          => '_items_small_portrait_tablet',
	'context'     => 'side',
	'input_class' => 'small-text',
	'name'        => esc_html__( 'Columns : Small Tablet', 'carousel-slider' ),
	'desc'        => esc_html__( 'The number of items you want to see on the Small Tablet Layout(Screens size from 600 pixels DP to 767 pixels DP)', 'carousel-slider' ),
	'std'         => 2
) );
$this->form->number( array(
	'id'          => '_items_portrait_mobile',
	'context'     => 'side',
	'input_class' => 'small-text',
	'name'        => esc_html__( 'Columns : Mobile', 'carousel-slider' ),
	'desc'        => esc_html__( 'The number of items you want to see on the Mobile Layout (Screens size from 320 pixels DP to 599 pixels DP)', 'carousel-slider' ),
	'std'         => 1
) );

// Development
if ( ! empty( $_settings ) ) {
	echo '<table class="form-breakpoint-table">';
	echo '<tr>';
	echo '<th class="col-breakpoint">' . esc_html__( 'Break Point', 'carousel-slider' ) . '</th>';
	echo '<th class="col-items">' . esc_html__( 'Items', 'carousel-slider' ) . '</th>';
	echo '<th class="col-actions"></th>';
	echo '</tr>';
	foreach ( $_settings as $index_number => $item ) {
		?>
        <tr>
            <td class="col-breakpoint">
                <label>
                    <span class="screen-reader-text"><?php esc_html_e( 'Break Point', 'carousel-slider' ); ?></span>
                    <input type="number" class="widefat"
                           name="carousel_slider[responsive][<?php echo $index_number; ?>][breakpoint]"
                           value="<?php echo intval( $item['breakpoint'] ); ?>"/>
                </label>
            </td>
            <td class="col-items">
                <label>
                    <span class="screen-reader-text"><?php esc_html_e( 'Items', 'carousel-slider' ); ?></span>
                    <input type="number" class="widefat"
                           name="carousel_slider[responsive][<?php echo $index_number; ?>][items]"
                           value="<?php echo intval( $item['items'] ); ?>"/>
                </label>
            </td>
            <td class="col-actions">
                <a href="#" class="add-breakpoint"><span class="dashicons dashicons-plus-alt"></span></a>
                <a href="#" class="delete-breakpoint"><span class="dashicons dashicons-trash"></span></a>
            </td>
        </tr>
		<?php
	}
	echo '</table>';
}
