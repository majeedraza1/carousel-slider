<?php

namespace CarouselSlider\Supports\FormFields;

/**
 * Radio class
 */
class Radio extends BaseField {

	/**
	 * Render html content
	 *
	 * @inheritDoc
	 */
	public function render(): string {
		$value = $this->get_value();
		$name  = $this->get_name();

		$html  = '<fieldset id="' . esc_attr( $this->get_setting( 'id' ) ) . '" class="radio-container">';
		$html .= '<legend class="screen-reader-text"><span>' . esc_html( $this->get_setting( 'label' ) ) . '</span></legend>';
		foreach ( $this->get_setting( 'choices' ) as $key => $choice ) {
			if ( ! is_array( $choice ) ) {
				$choice = [
					'value' => $key,
					'label' => $choice,
				];
			}
			$id          = sprintf( '%s-%s', $this->get_setting( 'id' ), $choice['value'] );
			$label_class = sprintf( 'radio-label radio-label-%s', ( $choice['value'] === $value ) ? 'on' : 'off' );
			$radio_attr  = [
				'type'    => 'radio',
				'name'    => $name,
				'id'      => $id,
				'class'   => 'radio-input',
				'value'   => $choice['value'],
				'checked' => $choice['value'] === $value,
			];

			$html .= '<label class="' . esc_attr( $label_class ) . '" for="' . esc_attr( $id ) . '">';
			$html .= '<input ' . $this->array_to_attributes( $radio_attr ) . ' />';
			$html .= '<span>' . esc_html( $choice['label'] ) . '</span>';
			$html .= '</label>';
			$html .= 'inline' === $this->get_setting( 'display' ) ? ' ' : '<br>';
		}

		$html .= '</fieldset>';

		return $html;
	}
}
