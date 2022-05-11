<?php

namespace CarouselSlider\Interfaces;

/**
 * TemplateParserInterface class
 */
interface TemplateParserInterface {
	/**
	 * Render template to HTML.
	 * Generates the final HTML on the frontend.
	 *
	 * @return string
	 */
	public function render(): string;
}
