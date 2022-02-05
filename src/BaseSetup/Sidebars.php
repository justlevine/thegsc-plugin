<?php
/**
 * Configure WordPress Sidebars.
 *
 * @package TheGSC
 * @since 0.0.1
 */

namespace TheGSC\BaseSetup;

use TheGSC\Interfaces\Hookable;
use WP_Post;

/**
 * Class - Sidebars
 */
class Sidebars implements Hookable {
	/**
	 * {@inheritDoc}
	 */
	public function initialize() : void {
		add_action( 'widgets_init', [ $this, 'register_sidebars' ] );
	}

	/**
	 * Registers sidebar areas.
	 */
	public function register_sidebars() : void {
		$sidebar_config = [
			'about'         => __( 'About Sidebar', 'thegsc' ),
			'default'       => __( 'Default Sidebar', 'thegsc' ),
			'events'        => __( 'Events Sidebar', 'thegsc' ),
			'guides'        => __( 'Guides Sidebar', 'thegsc' ),
			'listings'      => __( 'Listings Sidebar', 'thegsc' ),
			'posts'         => __( 'Posts Sidebar', 'thegsc' ),
			'footer-center' => __( 'Center Footer Sidebar', 'thegsc' ),
			'footer-left'   => __( 'Left Footer Sidebar', 'thegsc' ),
			'footer-right'  => __( 'Right Footer Sidebar', 'thegsc' ),
		];

		foreach ( $sidebar_config as $key => $title ) {
			register_sidebar(
				[
					'name' => $title,
					'id'   => 'sidebar-' . $key,
				]
			);
		}
	}
}
