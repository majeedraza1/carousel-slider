<?php

namespace CarouselSlider\Interfaces;

interface FieldInterface {
	public function set_settings( array $settings );

	public function set_name( string $name );

	public function set_value( $value );

	public function render(): string;
}
