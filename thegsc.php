<?php
/**
 * Plugin Name: GSC Features
 * Plugin URI: https://github.com/harness-software/wp-graphql-gravity-forms
 * GitHub Plugin URI: https://github.com/harness-software/wp-graphql-gravity-forms
 * Description: Adds functions necessary for the GSC Website
 * Author: Dovid Levine
 * Author URI: https://thegsc.co.il
 * Update URI: https://github.com/harness-software/wp-graphql-gravity-forms/releases
 * Version: 0.0.1
 * Text Domain: thegsc
 * Domain Path: /languages
 * Requires at least: 5.4.1
 * Tested up to: 5.9
 * Requires PHP: 7.4+
 * WPGraphQL requires at least: 1.0.0+
 * GravityForms requires at least: 2.4.0+
 * License: GPL-3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package TheGSC
 * @author justlevine
 * @license GPL-3
 */
/**
 * Define plugin constants.
 */
function thegsc_plugin_constants() : void {
	// Plugin Folder Path.
	if ( ! defined( 'THEGSC_PLUGIN_DIR' ) ) {
		define( 'THEGSC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	}

	// Plugin Folder URL.
	if ( ! defined( 'THEGSC_PLUGIN_URL' ) ) {
		define( 'WPGRAPHQL_GF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	}

	// Plugin Root File.
	if ( ! defined( 'THEGSC_PLUGIN_FILE' ) ) {
		define( 'THEGSC_PLUGIN_FILE', __FILE__ );
	}
}

add_action(
	'plugins_loaded',
	function() {
		thegsc_plugin_constants();

		$autoload = plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

		$dependencies = [
			'Composer autoload files' => is_readable( $autoload ),
			//phpcs:disable
			// 'WPGraphQL plugin'        => class_exists( 'WPGraphQL' ),
			// 'Gravity Forms plugin'    => class_exists( 'GFAPI' ),
			//phpcs:enable
		];

		$missing_dependencies = array_keys( array_diff( $dependencies, array_filter( $dependencies ) ) );

		$display_admin_notice = function() use ( $missing_dependencies ) {
			?>
		<div class="notice notice-error">
			<p><?php esc_html_e( 'The GSC Plugin can\'t be loaded because these dependencies are missing:', 'thegsc' ); ?></p>
			<ul>
				<?php foreach ( $missing_dependencies as $missing_dependency ) : ?>
					<li><?php echo esc_html( $missing_dependency ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
			<?php
		};

		// If dependencies are missing, display admin notice and return early.
		if ( $missing_dependencies ) {
			add_action( 'network_admin_notices', $display_admin_notice );
			add_action( 'admin_notices', $display_admin_notice );

			return;
		}

		require_once $autoload;

		TheGSC\TheGSC::run();
	}
);
