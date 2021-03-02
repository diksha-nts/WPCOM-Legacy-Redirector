<?php
namespace Automattic\LegacyRedirector\Tests\Unit;

use Automattic\LegacyRedirector\Tests\Unit\MonkeyStubs;
use WPCOM_Legacy_Redirector;

/**
 * Capability Class Unit Test
 */
final class RedirectsTest extends MonkeyStubs {

	/**
	 * Tests Utils::normalise_url().
	 *
	 * @dataProvider get_protected_redirect_data_full_url_only
	 * @covers \Automattic\LegacyRedirector\Utils::normalise_url
	 *
	 * @param string $url             Full URL to parse.
	 * @param string $expected_schema Expected return schema.
	 * @param string $expected_domain Expected return domain.
	 * @param string $expected_path   Expected return path.
	 * @param string $expected_query  Expected return query.
	 * @return void
	 */
	public function test_normalise_url( $url, $expected_schema, $expected_domain, $expected_path, $expected_query ) {

		$expected_return = $expected_path . ( $expected_query ? '?' . $expected_query : '' );
		$this->assertEquals( $expected_return, WPCOM_Legacy_Redirector::normalise_url( $url ) );

	}

	/**
	 * Data provider for tests methods for normalise_url tests
	 *
	 * @return array
	 */
	public function get_protected_redirect_data_full_url_only() {
		return array(
			'redirect_simple_url_no_end_slash'           => array(
				'https://www.example1.org/',
				'https',
				'www.example1.org',
				'/',
				'',
			),
			'redirect_normal_path_with_multiple_slashes' => array(
				'https://www.example1.org///test///?test2=123&test=456',
				'https',
				'www.example1.org',
				'///test///',
				'test2=123&test=456',
			),
			'redirect_ascii_path_with_multiple_slashes_and_query' => array(
				'https://www.example1.org///test///?فوتوغرافيا/?test=فوتوغرافيا',
				'https',
				'www.example1.org',
				'///test///',
				'فوتوغرافيا/?test=فوتوغرافيا',
			),
			'redirect_ascii_path_with_multiple_slashes' => array(
				'https://www.example1.org//فوتوغرافيا/?test=فوتوغرافيا',
				'https',
				'www.example1.org',
				'//فوتوغرافيا/',
				'test=فوتوغرافيا',
			),
		);
	}
}
