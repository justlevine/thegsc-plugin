<?php
/**
 * Filters the core WPGraphQL schema.
 *
 * @package TheGSC
 * @since 0.0.1
 */

namespace TheGSC\GraphQL;

use GraphQL\Type\Definition\ResolveInfo;
use TheGSC\Interfaces\Hookable;
use WPGraphQL\AppContext;
use WPGraphQL\Data\Connection\TermObjectConnectionResolver;
use WPGraphQL\Model\Taxonomy;

class CoreSchemaFilters implements Hookable{
	/**
	 * {@inheritDoc}
	 */
	public function initialize(): void {
		add_action( get_graphql_register_action(), [ __CLASS__, 'register_connections'] );
	}

	public static function register_connections() : void {
		register_graphql_connection(
			[
				'fromType' => 'Taxonomy',
				'toType'   => 'TermNode',
				'fromFieldName' => 'connectedTerms',
				'connectionInterfaces' => [ 'TermNodeConnection' ],
				'description'          => __( 'List of Term Nodes associated with the Taxonomy', 'wp-graphql' ),
				'resolve'              => function ( Taxonomy $source, $args, AppContext $context, ResolveInfo $info ) {
					$taxonomies = [ $source->name ];

					$resolver = new TermObjectConnectionResolver( $source, $args, $context, $info, $taxonomies );

					return $resolver->get_connection();
				},
			]
		);
	}
}
