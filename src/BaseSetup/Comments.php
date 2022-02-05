<?php
/**
 * Disable WordPress comments.
 *
 * @package TheGSC
 * @since 0.0.1
 */

namespace TheGSC\BaseSetup;

use TheGSC\Interfaces\Hookable;

/**
 * Class - Comments
 */
class Comments implements Hookable {
	/**
	 * {@inheritDoc}
	 */
	public function initialize() : void {
		add_action( 'admin_menu', [ $this, 'remove_admin_menu' ] );
		add_action( 'init', [ $this, 'remove_comment_support' ] );
		add_action( 'wp_before_admin_bar_render', [ $this, 'admin_bar_render' ] );
	}

	/**
	 * Removes comments menu page.
	 */
	public function remove_admin_menu() : void {
		remove_menu_page( 'edit-comments.php' );
	}

	/**
	 * Remove post type support for comments.
	 */
	public function remove_comment_support() : void {
		remove_post_type_support( 'post', 'comments' );
		remove_post_type_support( 'page', 'comments' );
	}

	/**
	 * Remove comments from admin base.
	 */
	public function admin_bar_render() : void {
		if ( ! is_admin() ) {
			return;
		}
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'comments' );
	}
}
