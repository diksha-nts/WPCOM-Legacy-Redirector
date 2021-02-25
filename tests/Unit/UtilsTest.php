<?php
namespace Automattic\LegacyRedirector\Tests\Unit;

use Automattic\LegacyRedirector\Utils;
use Brain\Monkey;
use Yoast\WPTestUtils\BrainMonkey\TestCase;

/**
 * Capability Class Unit Test
 */
final class UtilsTest extends TestCase {

	/**
	 * Utils::mb_parse_url test method
	 *
	 * @covers \Automattic\LegacyRedirector\Utils::mb_parse_url
	 */
	public function test_mb_parse_url() {

		Monkey\Functions\stubs(
			array(
				'wp_parse_url' => static function ( $url, $component ) {
					return parse_url( $url, $component );
				},
			)
		);

		$url = 'https://www.example.org';
		$this->do_assetion_mb_parse_url( $url, 'https', 'www.example.org', '', '' );

		$url = 'https://www.example.org/';
		$this->do_assetion_mb_parse_url( $url, 'https', 'www.example.org', '/', '' );

		$url = 'https://www.example.com/test';
		$this->do_assetion_mb_parse_url( $url, 'https', 'www.example.com', '/test', '' );

		$url = 'http://www.example.com//فوتوغرافيا/?test=فوتوغرافيا';
		$this->do_assetion_mb_parse_url( $url, 'http', 'www.example.com', '//فوتوغرافيا/', 'test=فوتوغرافيا' );

		$url = '/فوتوغرافيا/?test=فوتوغرافيا';
		$this->do_assetion_mb_parse_url( $url, '', '', '/فوتوغرافيا/', 'test=فوتوغرافيا' );

		$url = '/فوتوغرافيا/?test2=فوتوغرافيا&test=فوتوغرافيا';
		$this->do_assetion_mb_parse_url( $url, '', '', '/فوتوغرافيا/', 'test2=فوتوغرافيا&test=فوتوغرافيا' );

	}

	/**
	 * Do assertion method for testing mb_parse_url
	 *
	 * @param string $url
	 * @param string $expected_scheme
	 * @param string $expected_host
	 * @param string $expected_path
	 * @param string $expected_query
	 * @return void
	 */
	private function do_assetion_mb_parse_url( $url, $expected_scheme, $expected_host, $expected_path, $expected_query ) {
		$path_info = Utils::mb_parse_url( $url );

		if ( ! isset( $path_info['scheme'] ) ) {
			$path_info['scheme'] = '';
		}
		if ( ! isset( $path_info['host'] ) ) {
			$path_info['host'] = '';
		}
		if ( ! isset( $path_info['path'] ) ) {
			$path_info['path'] = '';
		}
		if ( ! isset( $path_info['query'] ) ) {
			$path_info['query'] = '';
		}

		$this->assertTrue( is_array( $path_info ) );
		$this->assertTrue( sizeof( $path_info ) > 1 ? true : false );
		$this->assertSame( $expected_host, $path_info['host'] );
		$this->assertSame( $expected_path, $path_info['path'] );
		$this->assertSame( $expected_query, $path_info['query'] );

	}
}
