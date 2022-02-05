<?php
/**
 * Configure WordPress images.
 *
 * @package TheGSC
 * @since 0.0.1
 */

namespace TheGSC\BaseSetup;

use WP_Post;

class Images {
	public function initialize() {
		add_filter( 'wp_calculate_image_sizes', [ $this, 'filter_content_image_sizes_attr' ], 10, 2 );
		add_filter( 'wp_get_attachment_image_attributes', [ $this, 'filter_post_thumbnail_sizes_attr' ], 10, 3 );
	}
	/**
	 * Adds custom image sizes attribute to enhance responsive image functionality for content images.
	 *
	 * @param string $sizes A source size value for use in a 'sizes' attribute.
	 * @param array  $size  Image size. Accepts an array of width and height values in pixels (in that order).
	 *
	 * @return string A source size value for use in a content image 'sizes' attribute.
	 */
	public function filter_content_image_sizes_attr( string $sizes, array $size ) : string {
		$width = $size[0];

		$sizes = "(max-width{$width}) 100vh, {$width}px)";

		return $sizes;
	}

	/**
	 * Adds custom image sizes attribute to enhance responsive image functionality for post thumbnails.
	 *
	 * @param array        $attr       Attributes for the image markup.
	 * @param WP_Post      $attachment Attachment post object.
	 * @param string|array $size       Registered image size or flat array of height and width dimensions.
	 *
	 * @return array The filtered attributes for the image markup.
	 */
	public function filter_post_thumbnail_sizes_attr( array $attr, WP_Post $attachment, $size ) : array {
		$attr['sizes'] = '100vw';

		if ( 'card-320w' === $size ) {
			$attr['sizes'] = '(max-width: 320px) 100vw, 320px';
		}

		return $attr;
	}
}
