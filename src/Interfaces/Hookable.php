<?php
/**
 * Hookable Interface
 *
 * @package TheGSC
 */

namespace TheGSC\Interfaces;

/**
 * Interface - Hookable
 */
interface Hookable {
	/**
	 * Register hooks to WP.
	 */
	public function initialize() : void;
}
