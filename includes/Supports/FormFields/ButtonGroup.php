<?php

namespace CarouselSlider\Supports\FormFields;

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
			if ( isset( $choice['disabled'] ) && $choice['disabled'] ) {
				$radio_attr['disabled'] = true;
			}
			$html .= '<input ' . $this->array_to_attributes( $radio_attr ) . ' />';
			$html .= '<label class="' . esc_attr( $label_class ) . '" for="' . esc_attr( $id ) . '">' . esc_html( $choice['label'] ) . '</label>';
		}

		$html .= '</div>';

		return $html;
	}
}
