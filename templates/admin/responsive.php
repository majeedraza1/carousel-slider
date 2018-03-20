<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! empty( $_settings ) ) {
	echo '<p class="description">' . esc_html__( 'Enter screen size on BreakPoint and number of items you want to show this BreakPoint. Create as many BreakPoint as you need.', 'carousel-slider' ) . '</p>';
	echo '<table class="form-breakpoint-table">';
	echo '<tr>';
	echo '<th class="col-breakpoint">' . esc_html__( 'BreakPoint', 'carousel-slider' ) . '</th>';
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
                           name="carousel_slider[_responsive_settings][<?php echo $index_number; ?>][breakpoint]"
                           value="<?php echo intval( $item['breakpoint'] ); ?>"/>
                </label>
            </td>
            <td class="col-items">
                <label>
                    <span class="screen-reader-text"><?php esc_html_e( 'Items', 'carousel-slider' ); ?></span>
                    <input type="number" class="widefat"
                           name="carousel_slider[_responsive_settings][<?php echo $index_number; ?>][items]"
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
