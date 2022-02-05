<?php
/**
 * Configure WordPress Seo.
 *
 * @package TheGSC
 * @since 0.0.1
 */

namespace TheGSC\Blocks\BlockOverrides;

use TheGSC\Interfaces\Hookable;

/**
 * Class - BlockOverrides
 */
class BlockOverrides implements Hookable {
	/**
	 * {@inheritDoc}
	 */
	public function initialize(): void {
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_script' ] );
	}

	/**
	 * Enques override scripts.
	 */
	public function enqueue_script(): void {
		$asset_file = include plugin_dir_path( __FILE__ ) . 'build/index.asset.php';

		wp_enqueue_script(
			'gsc/blockOverrides',
			plugins_url( 'build/index.js', __FILE__ ),
			$asset_file['dependencies'],
			$asset_file['version'],
			true
		);
	}
}
