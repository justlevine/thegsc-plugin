<?php
/**
 * Configures Advanced CustomFields Options.
 *
 * @package TheGSC
 * @since 0.0.1
 */

namespace TheGSC\BaseSetup;

use TheGSC\Interfaces\Hookable;

/**
 * Class - ACF
 */
class ACF implements Hookable {
	/**
	 * ACF Page options
	 *
	 * @var array
	 */
	public $acf_page;

	/**
	 * ACF Site options
	 *
	 * @var array
	 */
	public $acf_site;

	/**
	 * {@inheritDoc}
	 */
	public function initialize() : void {
		add_action( 'acf/init', [ $this, 'set_site_options' ] );
		add_action( 'acf/init', [ $this, 'set_page_options' ] );

		// Disable default Metabox.
		// add_filter( 'acf/settings/remove_wp_meta_box', '__return_true' );
		// Add Options Page.
		add_filter( 'acf/include_fields', [ $this, 'setup_site_options_page' ] );
		// Delete transient on save.
		add_filter( 'acf/save_post', [ $this, 'clear_options_transient_on_save' ] );
	}

		/**
		 * Returns page options.
		 */
	public function get_page_options() : array {
		if ( empty( $this->acf_page ) ) {
			$this->set_page_options();
		};
		return $this->acf_page;
	}

	/**
	 * Returns site options.
	 */
	public function get_site_options() : array {
		return $this->acf_site;
	}

	/**
	 * Sets $acf_site.
	 */
	public function set_site_options() : void {
		$this->acf_site = get_transient( 'acf_site' );
		if ( false === $this->acf_site ) {
			set_transient( 'acf_site', get_fields( 'options' ) );
			$this->acf_site = get_transient( 'acf_site' );
		}
	}

	/**
	 * Sets $acf_page.
	 */
	public function set_page_options() : void {
		global $post;

		$this->acf_page = isset( $post->ID ) ? get_fields( $post->ID ) : false;
	}

	/**
	 * Create Site Config page.
	 */
	public function setup_site_options_page() : void {
		if ( function_exists( '\acf_add_options_page' ) ) {
			$options_page = [
				'page_title'      => 'Site Config',
				'menu_title'      => 'Site Config',
				'menu_slug'       => 'site-config',
				'show_in_graphql' => true,
			];
			acf_add_options_page( $options_page );
		}
	}

		/**
		 * Delete acf_site transient when options page is saved.
		 *
		 * @param string $id .
		 */
	public function clear_options_transient_on_save( $id ) : void {
		if ( 'options' === $id ) {
			delete_transient( 'acf_site' );
		}
	}

}
