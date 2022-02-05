<?php
/**
 * Initializes a singleton instance of TheGSC.
 *
 * @package TheGSC
 * @since 0.0.1
 */

namespace TheGSC;

use TheGSC\Blocks;
use TheGSC\BaseSetup;
use TheGSC\PostTypes;
use TheGSC\GraphQL;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TheGSC' ) ) :
	/**
	 * Main plugin class.
	 */
	final class TheGSC {
		/**
		 * Stores the instance of the TheGSC class
		 *
		 * @var TheGSC The one true TheGSC
		 * @access public
		 */
		public static $instance;

		/**
		 * The one true instance of TheGSC.
		 *
		 * @todo not sure we're using.
		 */
		public static function instance() : self {
			if ( ! ( is_a( self::$instance, __CLASS__ ) ) ) {
				if ( ! function_exists( 'is_plugin_active' ) ) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}
				self::$instance = new self();
			}

			return self::$instance;
		}

			/**
			 * Throw error on object clone.
			 * The whole idea of the singleton design pattern is that there is a single object
			 * therefore, we don't want the object to be cloned.
			 *
			 * @since  0.0.1
			 * @access public
			 * @return void
			 */
		public function __clone() {
			// Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'TheGSC class should not be cloned.', 'thegsc' ), '0.0.1' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @since  0.0.1
		 * @access protected
		 * @return void
		 */
		public function __wakeup() {
			// De-serializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'De-serializing instances of the TheGSC class is not allowed', 'thegsc' ), '0.0.1' );
		}

		/**
		 * Create class instances.
		 *
		 * @return array
		 */
		public static function create_instances() : array {
			$instances = [
				'comments'         => new BaseSetup\Comments(),
				'images'           => new BaseSetup\Images(),
				//phpcs:ignore
				// 'menus'           => new BaseSetup\Menus(),
				'sidebars'         => new BaseSetup\Sidebars(),
				'admin'            => new BaseSetup\Admin(),
				'acf'              => new BaseSetup\ACF(),
				'post-thumbnails'  => new BaseSetup\PostThumbnails(),
				'seo'              => new BaseSetup\Seo(),
				'events'           => new PostTypes\Events(),
				'guides'           => new PostTypes\Guides(),
				'guide_categories' => new GraphQL\GuideConnections(),
				'block_overrides'  => new Blocks\BlockOverrides\BlockOverrides(),
			];
			return $instances;
		}

		/**
		 * Runs the plugin
		 */
		public static function run() : void {
			$instances = self::create_instances();

			foreach ( $instances as $instance ) {
				$instance->initialize();
			}
		}

	}

endif;
