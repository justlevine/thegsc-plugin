<?php
/**
 * Configure WordPress Seo.
 *
 * @package TheGSC
 * @since 0.0.1
 */

namespace TheGSC\BaseSetup;

use TheGSC\Interfaces\Hookable;

if ( ! defined( 'WPSEO_VERSION' ) ) {
	return;
}

/**
 * Class - Seo
 */
class Seo implements Hookable {
	/**
	 * {@inheritDoc}
	 */
	public function initialize() : void {
		add_filter( 'wpseo_breadcrumb_links', [ $this, 'filter_breadcrumb_links' ], 20, 1 );
	}

	/**
	 * Filters Yoast Seo Breadcrumb urls.
	 *
	 * @param array $breadcrumbs .
	 */
	public function filter_breadcrumb_links( array $breadcrumbs ) : array {
		$frontend_uri = function_exists( 'wpe_headless_get_setting' ) ? wpe_headless_get_setting( 'frontend_uri' ) : null;
		if ( empty( $frontend_uri ) ) {
			return $breadcrumbs;
		}
				$frontend_uri = trailingslashit( $frontend_uri );
		$home_url             = trailingslashit( home_url() );

		$return = array_map(
			function( &$breadcrumb ) use ( $frontend_uri, $home_url ) {
				$breadcrumb['url'] = str_replace( $home_url, $frontend_uri, $breadcrumb['url'] );

				return $breadcrumb;
			},
			$breadcrumbs
		);
		return $return;
	}
}
