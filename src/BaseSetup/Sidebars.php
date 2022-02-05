<?php
/**
 * Configure WordPress Sidebars.
 *
 * @package TheGSC
 * @since 0.0.1
 */

namespace TheGSC\BaseSetup;

use WP_Post;

class Sidebars {
	public function initialize() {
		add_action( 'widgets_init', [ $this, 'register_sidebars' ] );
	}

	public function register_sidebars() {
		$sidebar_config = [
			'default'       => __( 'Default Sidebar', 'thegsc' ),
			'posts'         => __( 'Posts Sidebar', 'thegsc' ),
			'events'        => __( 'Events Sidebar', 'thegsc' ),
			'listings'      => __( 'Listings Sidebar', 'thegsc' ),
			'listings'      => __( 'Guides Sidebar', 'thegsc' ),
			'about'         => __( 'About Sidebar', 'thegsc' ),
			'footer-left'   => __( 'Left Footer Sidebar', 'thegsc' ),
			'footer-center' => __( 'Center Footer Sidebar', 'thegsc' ),
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
