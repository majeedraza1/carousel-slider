<?php

use CarouselSlider\Supports\Sanitize;

class SanitizeTest extends WP_UnitTestCase {
	public function test_it_sanitize_value_as_number() {
		$this->assertTrue( 1 === Sanitize::number( 1 ) );
		$this->assertTrue( 1 === Sanitize::number( '1' ) );
		$this->assertEquals( 1.0123, Sanitize::number( '1.0123' ) );
		$this->assertEquals( 1.0123, Sanitize::number( 1.0123 ) );
		$this->assertEquals( 0, Sanitize::number( 'not a number' ) );
		$this->assertEquals( 1234, Sanitize::int( 1234 ) );
		$this->assertEquals( 1234, Sanitize::int( '1234' ) );
		$this->assertEquals( 1234, Sanitize::int( '1234.1234' ) );
		$this->assertEquals( 0, Sanitize::int( 'not a number' ) );
		$this->assertEquals( 1234, Sanitize::float( '1234' ) );
		$this->assertEquals( 1234.1234, Sanitize::float( '1234.1234' ) );
		$this->assertEquals( 1234.1234, Sanitize::float( 1234.1234 ) );
		$this->assertEquals( 0, Sanitize::float( 'not a number' ) );
	}

	public function test_it_sanitize_value_as_email() {
		$this->assertEquals( 'mail@example.com', Sanitize::email( 'mail@example.com' ) );
		$this->assertEquals( '', Sanitize::email( 'not_an_email' ) );
	}

	public function test_it_sanitize_value_as_url() {
		$this->assertEquals( 'https://example.com', Sanitize::url( 'https://example.com' ) );
		$this->assertEquals( 'http://example.com', Sanitize::url( 'example.com' ) );
		$this->assertEquals( 'http://not_an_url', Sanitize::url( 'not_an_url' ) );
	}

	public function test_it_sanitize_value_as_color() {
		$this->assertEquals( '', Sanitize::color( '' ) );
		$this->assertEquals( 'transparent', Sanitize::color( 'transparent' ) );
		$this->assertEquals( '#fff', Sanitize::color( '#fff' ) );
		$this->assertEquals( '', Sanitize::color( '#ffg' ) );
		$this->assertEquals( 'rgb(255,255,255)', Sanitize::color( 'rgb(255,255,255)' ) );
		$this->assertEquals( 'rgba(255,255,255,0.5)', Sanitize::color( 'rgba(255,255,255,0.5)' ) );
	}

	public function test_it_sanitize_value_as_date() {
		$this->assertEquals( '2021-12-01', Sanitize::date( '2021-12-01' ) );
		$this->assertEquals( '2021-12-01', Sanitize::date( 'Dec 1, 2021' ) );
		$this->assertEquals( '', Sanitize::date( 'invalid date' ) );
	}

	public function test_it_sanitize_other_string() {
		$this->assertEquals( 'Text example', Sanitize::text( 'Text example' ) );
		$this->assertEquals( 'Text example', Sanitize::text( "Text example\n" ) );
		$this->assertEquals( "Text example\nAnother Line", Sanitize::textarea( "Text example\nAnother Line" ) );
		$this->assertEquals( 'true', Sanitize::checked( "true" ) );
		$this->assertEquals( 'false', Sanitize::checked( "false" ) );
		$this->assertEquals( 'yes', Sanitize::checked( "yes" ) );
		$this->assertEquals( '<p>It supports html tags.</p>', Sanitize::html( "<p>It supports html tags.</p>" ) );

	}
}
