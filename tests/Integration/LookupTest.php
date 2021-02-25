<?php

namespace Automattic\LegacyRedirector\Tests\Integration;

use Automattic\LegacyRedirector\Lookup;
use WPCOM_Legacy_Redirector;

/**
 * CapabilityTest class.
 */
final class LookTest extends TestCase {
	/**
	 * Test Lookup::get_redirect_uri
	 *
	 * @covers Lookup::get_redirect_uri
	 * @return void
	 */
	public function test_get_redirect_uri() {

        $from_url = '/فوتوغرافيا/?test=فوتوغرافيا';
		$to_url   = '/';
		$this->assertTrue( WPCOM_Legacy_Redirector::insert_legacy_redirect( $from_url, $to_url, true ) );

		$redirect_data = Lookup::get_redirect_data( $from_url );

		$this->assertEquals( $to_url, $redirect_data['redirect_uri'] );
		$this->assertEquals( '301', $redirect_data['redirect_status'] );

	}
}
