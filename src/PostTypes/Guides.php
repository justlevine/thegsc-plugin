<?php
/**
 * Configures the Guide PostType.
 *
 * @package TheGSC
 * @since 0.0.1
 */

namespace TheGSC\PostTypes;

use WP_Post;

class Guides {
	public function initialize() : void {
		add_action( 'init', [ $this, 'register_cpt_guides' ] );
		add_action( 'init', [ $this, 'register_taxonomy_guide_categories' ] );
		add_action( 'restrict_manage_posts', [ $this, 'filter_guides_by_taxonomies' ], 10, 1 );
		add_filter( 'manage_edit-guide_sortable_columns', [ $this, 'sortable_guide_cat_column' ] );
		add_filter( 'post_type_link', [ $this, 'guide_permalinks' ], 10, 2 );
	}

	/**
	 * Register Custom Post Type
	 */
	public function register_cpt_guides() {
		$labels = [
			'name'                  => __( 'Guides', 'thegsc' ),
			'singular_name'         => __( 'Guide', 'thegsc' ),
			'menu_name'             => __( 'Guides', 'thegsc' ),
			'name_admin_bar'        => __( 'Guides', 'thegsc' ),
			'archives'              => __( 'Guide Archives', 'thegsc' ),
			'attributes'            => __( 'Guide Attributes', 'thegsc' ),
			'parent_item_colon'     => __( 'Parent Guide:', 'thegsc' ),
			'all_items'             => __( 'All Guides', 'thegsc' ),
			'add_new_item'          => __( 'Add New Guide', 'thegsc' ),
			'add_new'               => __( 'Add New', 'thegsc' ),
			'new_item'              => __( 'New Guide', 'thegsc' ),
			'edit_item'             => __( 'Edit Guide', 'thegsc' ),
			'update_item'           => __( 'Update Guide', 'thegsc' ),
			'view_item'             => __( 'View Guide', 'thegsc' ),
			'view_items'            => __( 'View Guides', 'thegsc' ),
			'search_items'          => __( 'Search Guides', 'thegsc' ),
			'not_found'             => __( 'Not found', 'thegsc' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'thegsc' ),
			'featured_image'        => __( 'Featured Image', 'thegsc' ),
			'set_featured_image'    => __( 'Set featured image', 'thegsc' ),
			'remove_featured_image' => __( 'Remove featured image', 'thegsc' ),
			'use_featured_image'    => __( 'Use as featured image', 'thegsc' ),
			'insert_into_item'      => __( 'Insert into guide', 'thegsc' ),
			'uploaded_to_this_item' => __( 'Uploaded to this guide', 'thegsc' ),
			'items_list'            => __( 'Guides list', 'thegsc' ),
			'items_list_navigation' => __( 'Guides list navigation', 'thegsc' ),
			'filter_items_list'     => __( 'Filter guides list', 'thegsc' ),
		];
		$args   = [
			'label'               => __( 'Guide', 'thegsc' ),
			'description'         => __( 'Guides', 'thegsc' ),
			'labels'              => $labels,
			'supports'            => [ 'title', 'author', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
			'taxonomies'          => [ 'guide_cat' ],
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 3,
			'menu_icon'           => 'dashicons-analytics',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => 'guides',
			'exclude_from_search' => false,
			'query_var'           => true,
			'rewrite'             => [
				'slug'       => 'guides/%guide_cat%',
				'with_front' => false,
				'walk_dirs'  => false,
			],
			'show_in_rest'        => true,
			'show_in_graphql'     => true,
			'graphql_single_name' => 'guide',
			'graphql_plural_name' => 'guides',

		];
		register_post_type( 'guide', $args );
	}

	/**
	 * Adds the Guide Categories to the slug.
	 *
	 * @param string  $post_link
	 * @param WP_Post $post
	 */
	public function guide_permalinks( string $post_link, WP_Post $post ) : string {
		$post_type = 'guide';
		$taxonomy  = 'guide_cat';

		if ( is_object( $post ) && $post->post_type === $post_type ) {
			$terms = wp_get_object_terms( $post->ID, $taxonomy );
			if ( $terms ) {
				$hierarchical_slugs = [];
				$ancestors          = get_ancestors( $terms[0]->term_id, $taxonomy, 'taxonomy' );
				foreach ( (array) $ancestors as $ancestor ) {
					$ancestor_term        = get_term( $ancestor, $taxonomy );
					$hierarchical_slugs[] = $ancestor_term->slug;
				}
				$hierarchical_slugs   = array_reverse( $hierarchical_slugs );
				$hierarchical_slugs[] = $terms[0]->slug;

				$post_link = str_replace( "%$taxonomy%", implode( '/', $hierarchical_slugs ), $post_link );
			}
		}
		return $post_link;
	}

	/**
	 * Register Category Taxonomy for Guides
	 */
	public function register_taxonomy_guide_categories() {
		$labels = [
			'name'                       => __( 'Guide Categories', 'thegsc' ),
			'singular_name'              => __( 'Guide Category', 'thegsc' ),
			'menu_name'                  => __( 'Categories', 'thegsc' ),
			'all_items'                  => __( 'All Categories', 'thegsc' ),
			'parent_item'                => __( 'Parent Category', 'thegsc' ),
			'parent_item_colon'          => __( 'Parent Category:', 'thegsc' ),
			'new_item_name'              => __( 'New Category Name', 'thegsc' ),
			'add_new_item'               => __( 'Add New Category', 'thegsc' ),
			'edit_item'                  => __( 'Edit Category', 'thegsc' ),
			'update_item'                => __( 'Update Category', 'thegsc' ),
			'view_item'                  => __( 'View Category', 'thegsc' ),
			'separate_items_with_commas' => __( 'Separate categories with commas', 'thegsc' ),
			'add_or_remove_items'        => __( 'Add or remove categories', 'thegsc' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'thegsc' ),
			'popular_items'              => __( 'Popular Categories', 'thegsc' ),
			'search_items'               => __( 'Search Categories', 'thegsc' ),
			'not_found'                  => __( 'Not Found', 'thegsc' ),
			'no_terms'                   => __( 'No categories', 'thegsc' ),
			'items_list'                 => __( 'Category list', 'thegsc' ),
			'items_list_navigation'      => __( 'Categories list navigation', 'thegsc' ),
		];

		$args = [
			'labels'              => $labels,
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => true,
			'show_admin_column'   => true,
			'show_in_nav_menus'   => true,
			'show_in_rest'        => true,
			'show_in_graphql'     => true,
			'graphql_single_name' => 'guideCategory',
			'graphql_plural_name' => 'guideCategories',
			'show_tagcloud'       => true,
			'query_var'           => true,
			// 'has_archive'       => 'guides',
			'rewrite'             => [
				'slug'         => 'guides',
				'with_front'   => false,
				'hierarchical' => true,
			],
		];
		register_taxonomy( 'guide_cat', [ 'guide' ], $args );
	}

	/**
	 * Make Guide Tax sortable
	 *
	 * @param array $columns .
	 */
	public function sortable_guide_cat_column( array $columns ) : array {
		$columns['taxonomy-guide_cat'] = 'taxonomy-guide_cat';

		return $columns;
	}

	/**
	 * Allow filtering guides by category
	 *
	 * @param string $post_type .
	 */
	public function filter_guides_by_taxonomies( string $post_type ) {
		// Apply this only on a specific post type .
		if ( 'guide' !== $post_type ) {
			return;
		}

		// A list of taxonomy slugs to filter by .
		$taxonomies = [ 'guide_cat' ];

		foreach ( $taxonomies as $taxonomy_slug ) {
			// Retrieve taxonomy data .
			$taxonomy_obj  = get_taxonomy( $taxonomy_slug );
			$taxonomy_name = $taxonomy_obj->labels->name;

			// Retrieve taxonomy terms .
			$terms = get_terms( $taxonomy_slug );

			// Display filter HTML .
			// @todo add nonce.
			echo "<select name='{$taxonomy_slug}' id='{$taxonomy_slug}' class='postform'>";
			echo '<option value="">' . sprintf( 'Show All %s', esc_attr( $taxonomy_name ) ) . '</option>';
			foreach ( $terms as $term ) {
				printf(
					'<option value="%1$s" %2$s>%3$s (%4$s)</option>',
					esc_attr( $term->slug ),
					( ( isset( $_GET[ $taxonomy_slug ] ) && ( $_GET[ $taxonomy_slug ] === $term->slug ) ) ? ' selected="selected"' : '' ),
					esc_attr( $term->name ),
					esc_attr( (string) $term->count )
				);
			}
			echo '</select>';
		}
	}

}
