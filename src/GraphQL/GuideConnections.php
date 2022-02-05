<?php
/**
 * Configures the Guide connection args schema.
 *
 * @package TheGSC
 * @since 0.0.1
 */

namespace TheGSC\GraphQL;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;

class GuideConnections {

	public function initialize() : void {
		add_filter( 'graphql_wp_connection_type_config', [ __CLASS__, 'set_connection_type_config' ] );
		add_filter( 'graphql_map_input_fields_to_wp_query', [ __CLASS__, 'map_input_fields_to_wp_query' ], 10, 7 );
	}

	public static function set_connection_type_config( array $config ) : array {
		if ( $config['fromType'] !== 'RootQuery' || $config['toType'] !== 'guide' ) {
			return $config;
		}

		$config['connectionArgs'] = array_merge(
			$config['connectionArgs'],
			[
				'categoryId'    => [
					'type'        => 'Int',
					'description' => __( 'Category ID.', 'thegsc' ),
				],
				'categoryIn'    => [
					'type'        => [ 'list_of' => 'ID' ],
					'description' => __( 'Array of category IDs, used to display objects from one category OR another.', 'thegsc' ),
				],
				'categoryName'  => [
					'type'        => 'String',
					'description' => __( 'Use Event Category slug.', 'wp-graphql-tec' ),
				],
				'categoryNotIn' => [
					'type'        => [ 'list_of' => 'ID' ],
					'description' => __( 'Array of category IDs, used to display objects from one category OR another.', 'thegsc' ),
				],
			]
		);

		return $config;
	}

	/**
	 * This allows plugins/themes to hook in and alter what $args should be allowed to be passed
	 * from a GraphQL Query to the WP_Query
	 *
	 * @param array              $query_args The mapped query arguments.
	 * @param array              $where_args       Query "where" args.
	 * @param mixed              $source     The query results for a query calling this.
	 * @param array              $args   All of the arguments for the query (not just the "where" args).
	 * @param AppContext         $context    The AppContext object.
	 * @param ResolveInfo        $info       The ResolveInfo object.
	 * @param mixed|string|array $post_type  The post type for the query.
	 */
	public static function map_input_fields_to_wp_query( $query_args, $where_args, $source, $args, $context, $info, $post_type ) : array {
		if ( ! in_array( 'guide', $post_type, true ) ) {
			return $query_args;
		}

		$remove = [
			'cat',
			'category_name',
			'category__in',
			'category__not_in',
		];

		$query_args = array_diff_key( $query_args, array_flip( $remove ) );

		$tax_query = [];
		$tax_args  = [
			'category'        => 'guide_cat',
			'categoryName'    => 'guide_cat',
			'categoryIn'      => 'guide_cat',
			'categoryNotIn'   => 'guide_cat',
			'categoryId'      => 'guide_cat',
			'categoryIdIn'    => 'guide_cat',
			'categoryIdNotIn' => 'guide_cat',
		];

		foreach ( $tax_args as $field => $tax ) {
			if ( empty( $where_args[ $field ] ) ) {
				continue;
			}
			switch ( $field ) {
				case 'category':
				case 'categoryIn':
				case 'categoryId':
				case 'categoryIdIn':
					$tax_query[] = [
						'taxonomy' => $tax,
						'field'    => 'term_id',
						'terms'    => $where_args[ $field ],
						'operator' => 'IN',
					];
					break;
				case 'categoryNotIn':
				case 'categoryIdNotIn':
					$tax_query[] = [
						'taxonomy' => $tax,
						'field'    => 'term_id',
						'terms'    => $where_args[ $field ],
						'operator' => 'NOT IN',
					];
					break;
				case 'categoryName':
					$tax_query[] = [
						'taxonomy' => $tax,
						'field'    => 'slug',
						'terms'    => $where_args[ $field ],
						'operator' => 'IN',
					];
					break;
			}
		}

		if ( 1 < count( $tax_query ) ) {
			$tax_query['relation'] = 'AND';
		}

		if ( ! empty( $tax_query ) ) {
			$query_args['tax_query'] = $tax_query;
		}

		return $query_args;
	}
}
