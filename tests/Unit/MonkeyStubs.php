<?php

namespace Automattic\LegacyRedirector\Tests\Unit;

use Brain\Monkey;

final class MonkeyStubs {

	public function __construct() {
		Monkey\Functions\stubs(
			array(
				'wp_parse_url' => static function ( $url, $component ) {
					return parse_url( $url, $component );
				},
			)
		);
	}
}
