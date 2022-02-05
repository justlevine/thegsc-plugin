<?php
/**
 * Configures WordPress Admin Backend.
 *
 * @package TheGSC
 * @since 0.0.1
 */

namespace TheGSC\BaseSetup;

use TheGSC\Interfaces\Hookable;

/**
 * Class - Admin
 */
class Admin implements Hookable {
	/**
	 * {@inheritDoc}
	 */
	public function initialize() : void {
		remove_action( 'admin_notices', 'woothemes_updater_notice' );
		add_action( 'admin_init', [ $this, 'remove_dashboard_widgets' ] );
		add_filter( 'admin_footer_text', [ $this, 'modify_footer' ] );
		add_filter( 'custom_menu_order', [ $this, 'reorder_admin_menu' ] );
		add_filter( 'menu_order', [ $this, 'reorder_admin_menu' ] );
		add_filter( 'manage_edit-page_columns', [ $this, 'last_modified_column' ] );
		add_filter( 'manage_edit-page_sortable_columns', [ $this, 'last_modified_column' ] );
		add_action( 'manage_pages_custom_column', [ $this, 'last_modified_column_content' ] );
	}

	/**
	 * Remove dashboard widgets
	 */
	public function remove_dashboard_widgets() : void {
		remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
	}

	/**
	 * Reorders the Admin Menu
	 */
	public function reorder_admin_menu() :array {
		return [
			'index.php', // Dashboard.
			'separator1', // --Space--
			'edit.php', // Posts.
			'edit.php?post_type=tribe_events', // Events.
			'edit.php?post_type=guide', // Guides.
			'edit.php?post_type=directory_dir_ltg', // Local.
			'edit.php?post_type=services_dir_ltg', // Services.
			'edit.php?post_type=page', // Pages.
			'separator2', // --Space--
			'woocommerce', // Pages.
			'separator2', // --Space--
			'gf_edit_forms', // Forms.
			'wp-pro-advertising', // Advertising.
			'upload.php', // Media.
			'separator2', // --Space--
			'gsc-site-settings', // Site Settings.
			'users.php', // Users.
			'separator2', // --Space--
			'themes.php', // Appearance.
			'plugins.php', // Plugins.
			'separator2', // --Space--
			'options-general.php', // Settings.
			'drts/directories', // Directories.
			'edit.php?post_type=acf-field-group', // Pages.
			'tools.php', // Tools.
		];
	}

	/**
	 * Adds a last modified column
	 *
	 * @param array $columns list of columns.
	 */
	public function last_modified_column( $columns ) : array {
		$columns['modified-last'] = __( 'Last Modified', 'thegsc' );
		return $columns;
	}

	/**
	 * Populates the Last modified column
	 *
	 * @param string $column_name the column name.
	 */
	public function last_modified_column_content( $column_name ) : void {
		// Do not continue if this is not the modified column.
		if ( 'modified-last' !== $column_name ) {
			return;
		}
		$modified_date   = get_the_modified_date( 'Y/m/d - g:i A' );
		$modified_author = get_the_modified_author();

		echo is_string( $modified_date ) ? esc_attr( $modified_date ) : null;
		echo '<br>';
		echo is_string( $modified_author ) ? '<strong>' . esc_attr( $modified_author ) . '</strong>' : null;
	}

	/**
	 * Modify the footer so users know to contact the webadmin
	 */
	public function modify_footer() : void {
		echo 'Need help? Contact <a href="mailto:dovidl@thegsc.co.il">Dovid Levine</a>!.';
	}
}
