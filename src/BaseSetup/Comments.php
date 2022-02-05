<?php
/**
 * Disable WordPress comments.
 *
 * @package TheGSC
 * @since 0.0.1
 */

namespace TheGSC\BaseSetup;

class Comments {
	public function initialize() {
		add_action( 'admin_menu', [ $this, 'remove_admin_menu' ] );
		add_action( 'init', [ $this, 'remove_comment_support' ] );
		add_action( 'wp_before_admin_bar_render', [ $this, 'admin_bar_render' ] );
	}

	public function remove_admin_menu() : void {
		remove_menu_page( 'edit-comments.php' );
	}

	public function remove_comment_support() : void {
		remove_post_type_support( 'post', 'comments' );
		remove_post_type_support( 'page', 'comments' );
	}

	public function admin_bar_render() : void {
		if ( ! is_admin() ) {
			return;
		}
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'comments' );
	}
}
