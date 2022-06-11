<?php

namespace CarouselSlider\Supports\FormFields;

use CarouselSlider\Helper;

/**
 * ButtonGroup class
 */
class ButtonGroup extends BaseField {

	/**
	 * Render field html
	 *
	 * @inheritDoc
	 */
	public function render(): string {
		$value = $this->get_value();
		$name  = $this->get_name();

		$html = '<div id="' . esc_attr( $this->get_setting( 'id' ) ) . '" class="buttonset">';
		foreach ( $this->get_setting( 'choices' ) as $key => $choice ) {
			if ( ! is_array( $choice ) ) {
				$choice = [
					'value' => $key,
					'label' => $choice,
				];
			}
			$is_pro_only = isset( $choice['pro_only'] ) && $choice['pro_only'];
			if ( $is_pro_only && Helper::show_pro_features() === false ) {
				continue;
			}
			if ( $is_pro_only && ! Helper::is_pro_active() ) {
				$name = sprintf( 'need_pro_%s', $name );
			}
			$id          = sprintf( '%s-%s', $this->get_setting( 'id' ), $choice['value'] );
			$label_class = sprintf( 'switch-label switch-label-%s', ( $choice['value'] === $value ) ? 'on' : 'off' );
			$radio_attr  = [
				'type'    => 'radio',
				'name'    => $name,
				'id'      => $id,
				'class'   => 'switch-input screen-reader-text',
				'value'   => $choice['value'],
				'checked' => $choice['value'] === $value,
			];
			if (
				( isset( $choice['disabled'] ) && $choice['disabled'] ) ||
				( $is_pro_only && ! Helper::is_pro_active() )
			) {
				$radio_attr['disabled'] = true;
			}
			if ( $is_pro_only ) {
				$label_class .= ' has-pro-tag';
				$label        = esc_html( $choice['label'] ) . '<span class="pro-only">' . esc_html__( 'pro', 'carousel-slider' ) . '</span>';
			} else {
				$label = esc_html( $choice['label'] );
			}
			$html .= '<input ' . $this->array_to_attributes( $radio_attr ) . ' />';
			$html .= '<label class="' . esc_attr( $label_class ) . '" for="' . esc_attr( $id ) . '">' . $label . '</label>';
		}

		$html .= '</div>';

		return $html;
	}
}
