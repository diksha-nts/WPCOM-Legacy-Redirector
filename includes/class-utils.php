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

	/**
	 * Takes a request URL and "normalises" it, stripping common elements.
	 * Removes scheme and host from the URL, as redirects should be independent of these.
	 *
	 * @param string $url URL to transform.
	 * @return string|WP_Error Transformed URL; error if validation failed.
	 */
	public static function normalise_url( $url ) {
		// Sanitise the URL first rather than trying to normalise a non-URL.
		$url = esc_url_raw( wp_unslash( $url ) );
		if ( empty( $url ) ) {
			return new WP_Error( 'invalid-redirect-url', 'The URL does not validate' );
		}

		// Break up the URL into it's constituent parts.
		$components = Utils::mb_parse_url( $url );

		// Avoid playing with unexpected data.
		if ( ! is_array( $components ) ) {
			return new WP_Error( 'url-parse-failed', 'The URL could not be parsed' );
		}

		// We should have at least a path or query.
		if ( ! isset( $components['path'] ) && ! isset( $components['query'] ) ) {
			return new WP_Error( 'url-parse-failed', 'The URL contains neither a path nor query string' );
		}

		// Make sure $components['query'] is set, to avoid errors.
		$components['query'] = ( isset( $components['query'] ) ) ? $components['query'] : '';

		// All we want is path and query strings
		// Note this strips hashes (#) too
		// @todo should we destory the query strings and rebuild with `add_query_arg()`?
		$normalised_url = $components['path'];

		// Only append '?' and the query if there is one.
		if ( ! empty( $components['query'] ) ) {
			$normalised_url = $components['path'] . '?' . $components['query'];
		}

		return $normalised_url;
	}

}
