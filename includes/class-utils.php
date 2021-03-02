<?php

namespace Automattic\LegacyRedirector;

final class Utils {

	/**
	 * UTF-8 aware parse_url() replacement.
	 *
	 * @throws \InvalidArgumentException Malformed URL.
	 *
	 * @param string $url       The URL to parse.
	 * @param int    $component Optional. The specific component to retrieve. Use one of the
	 *                          PHP predefined constants to specify which one. Defaults
	 *                          to -1 (= return all parts as an array).
	 * @return array Exception on parse failure.
	 *               Array of URL components on success; When a specific component has been
	 *               requested: null if the component doesn't exist in the given URL; a
	 *               string (or in the case of PHP_URL_PORT, integer) when it does.
	 */
	public static function mb_parse_url( $url, $component = -1 ) {
		$encoded_url = preg_replace_callback(
			'%[^:/@?&=#]+%usD',
			function ( $matches ) {
				return urlencode( $matches[0] );
			},
			$url
		);

		$parts = wp_parse_url( $encoded_url, $component );

		if ( false === $parts ) {
			throw new \InvalidArgumentException( 'Malformed URL: ' . $url );
		}

		if ( is_array( $parts ) ) {
			foreach ( $parts as $name => $value ) {
				$parts[ $name ] = urldecode( $value );
			}
		} else {
			$parts = urldecode( $parts );
		}

		return $parts;
	}
}
