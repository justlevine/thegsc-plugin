<?php
/**
 * Configure WordPress Post Thumbnails.
 *
 * @package TheGSC
 * @since 0.0.1
 */

namespace TheGSC\BaseSetup;

class PostThumbnails {
	public function initialize() {
		add_action( 'after_setup_theme', [ $this, 'action_add_post_thumbnail_support' ] );
		add_action( 'after_setup_theme', [ $this, 'action_add_image_sizes' ] );
		add_filter( 'image_size_names_choose', [ $this, 'add_image_sizes_names' ] );
		add_filter(
			'max_srcset_image_width',
			function() {
				return 1920;
			},
			10,
			2
		);
	}
	/**
	 * Adds support for post thumbnails.
	 */
	public function action_add_post_thumbnail_support() {
		add_theme_support( 'post-thumbnails' );
	}

	/**
	 * Adds custom image sizes.
	 */
	public function action_add_image_sizes() {
		// Image Sizes.
		add_image_size( 'full' );
		add_image_size( 'background-img-1920w', 1920, 1080, true ); // 16:9
		add_image_size( 'background-img-1600w', 1600, 900, true ); // 16:9
		add_image_size( 'background-img-1260w', 1260, 709, true ); // 16:9

		add_image_size( 'page-header-1365w', 1365, 9999, false ); // page-header.
		add_image_size( 'page-header-992w', 992, 9999, false ); // page-header.
		add_image_size( 'page-header-768w', 768, 9999, false ); // page-header.
		add_image_size( 'page-header-480w', 480, 9999, false ); // page-header.
		add_image_size( 'post-full-1305w', 1305, 734, true ); // 16:9
		add_image_size( 'featured-image-fb-1200w', 1200, 675, true ); // featured-image 16:9.
		add_image_size( 'post-large-930w', 930, 523, true ); // 16:9
		add_image_size( 'post-med-615w', 615, 346, true ); // 16:9
		add_image_size( 'card-320w', 320, 180, true ); // 16:9
		add_image_size( 'post-small-300w', 300, 168, true ); // 16:9
		add_image_size( 'action-card-270w', 270, 100, true ); // Action Card.

		// remove image sizes.
		remove_image_size( 'medium_large' );
		remove_image_size( 'shop_thumbnail' );
		remove_image_size( 'woocommerce_thumbnail' );
		remove_image_size( 'woocommerce_single' );
		remove_image_size( 'woocommerce_gallery_thumbnail' );
		remove_image_size( 'shop_catalog' );
		remove_image_size( 'shop_single' );
	}
	/**
	 * Add names to post attachments
	 *
	 * @param array $sizes image sizes array.
	 */
	public function add_image_sizes_names( $sizes ) {
		$custom_sizes = [
			'full'                    => 'Full Size',
			'background-img-1920w'    => 'Full Screen (Background) 16:9',
			'page-header-1365w'       => 'Full Row',
			'post-full-1305w'         => 'Post - Full Width 16:9',
			'featured-image-fb-1200w' => 'Featured Image 16:9',
			'post-large-930w'         => 'Post - Large 16:9',
			'post-med-615w'           => 'Post - Medium 16:9',
			'post-small-300w'         => 'Post - Small 16:9',
			'action-card-270w'        => 'Action Card 270x100',
		];
		unset( $sizes['small'] );
		unset( $sizes['medium'] );
		unset( $sizes['medium_large'] );
		unset( $sizes['large'] );

		return array_merge( $sizes, $custom_sizes );
	}
}
