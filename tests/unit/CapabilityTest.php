<?php
namespace Automattic\LegacyRedirector\Tests\Unit;

use Automattic\LegacyRedirector\Capability;
use Brain\Monkey\Functions;
use Brain\Monkey;
use Yoast\WPTestUtils\BrainMonkey\TestCase;

/**
 * Capability Class Unit Test
 */
class CapabilityTest extends TestCase {

	/**
	 * Test Capability->register method to make sure update_option is only called once and mocking wpcom_vip_add_role_caps function
	 *
	 * @return void
	 */
	public function test_register() {

		$capability = new Capability();

		Functions\when( 'wpcom_vip_add_role_caps' )
			->justReturn( true );

		Functions\expect( 'get_option' )
			->once()
			->andReturn( 0 );

			Functions\expect( 'update_option' )
			->once()
			->andReturn( true );

		$capability->register();

		Functions\expect( 'get_option' )
			->once()
			->andReturn( $capability::CAPABILITIES_VER );

			Functions\expect( 'update_option' )
			->never();

		$capability->register();
	}
}
