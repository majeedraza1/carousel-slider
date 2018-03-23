<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! empty( $_settings ) ) {
	?>
    <p class="description">
		<?php esc_html__( 'Enter screen size on BreakPoint and number of items you want to show this BreakPoint. Create as many BreakPoint as you need.', 'carousel-slider' ); ?>
    </p>
    <table class="form-breakpoint-table">
        <thead>
        <tr>
            <th class="col-breakpoint"><?php esc_html_e( 'BreakPoint', 'carousel-slider' ); ?></th>
            <th class="col-items"><?php esc_html_e( 'Items', 'carousel-slider' ); ?></th>
            <th class="col-actions"></th>
        </tr>
        </thead>
		<?php
		foreach ( $_settings as $index_number => $item ) {
			?>
            <tr>
                <td class="col-breakpoint">
                    <label>
                        <span class="screen-reader-text"><?php esc_html_e( 'Break Point', 'carousel-slider' ); ?></span>
                        <input type="number" class="widefat input-responsive-breakpoint"
                               name="_responsive_settings[<?php echo $index_number; ?>][breakpoint]"
                               value="<?php echo intval( $item['breakpoint'] ); ?>"/>
                    </label>
                </td>
                <td class="col-items">
                    <label>
                        <span class="screen-reader-text"><?php esc_html_e( 'Items', 'carousel-slider' ); ?></span>
                        <input type="number" class="widefat input-responsive-items"
                               name="_responsive_settings[<?php echo $index_number; ?>][items]"
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
		?>
    </table>
	<?php
}
