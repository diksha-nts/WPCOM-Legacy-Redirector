<?php

namespace Automattic\LegacyRedirector;

final class Capability {
	const MANAGE_REDIRECTS_CAPABILITY = 'manage_redirects';

	/**
	 * Add custom capability onto some existing roles using VIP Helpers with fallbacks.
	 */
	public function register() {
		if ( function_exists( 'wpcom_vip_add_role_caps' ) ) {
			wpcom_vip_add_role_caps( 'administrator', self::MANAGE_REDIRECTS_CAPABILITY );
			wpcom_vip_add_role_caps( 'editor', self::MANAGE_REDIRECTS_CAPABILITY );
		} else {
			$roles = array( 'administrator', 'editor' );
			foreach ( $roles as $role ) {
				$role_obj = get_role( $role );
				$role_obj->add_cap( self::MANAGE_REDIRECTS_CAPABILITY );
			}
		}
	}
}
