<?php
/**
 * Configure WordPress Navigation menus.
 *
 * @package TheGSC
 * @since 0.0.1
 */

namespace TheGSC\BaseSetup;

use TheGSC\Interfaces\Hookable;

/**
 * Class - Menus
 */
class Menus implements Hookable {
	/**
	 * {@inheritDoc}
	 */
	public function initialize() : void {
		add_action( 'after_setup_theme', [ $this, 'register_nav_menus' ], 9 );
	}

	/**
	 * Registers nav menu locations
	 */
	public function register_nav_menus() : void {
		register_nav_menus(
			[
				'main_menu'    => __( 'Main Menu', 'thegsc' ),
				'user_menu'    => __( 'User Menu', 'thegsc' ),
				'mobile_menu'  => __( 'Mobile Menu', 'thegsc' ),
				'footer_links' => __( 'Footer Links', 'thegsc' ),
			]
		);
	}
}
