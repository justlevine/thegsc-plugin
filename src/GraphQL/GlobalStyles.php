<?php
/**
 * Adds global styles to the GraphQL schema.
 *
 * Temporary until supported by WPGraphQL / REST API.
 *
 * @package TheGSC
 * @since 0.0.1
 */

namespace TheGSC\GraphQL;

use TheGSC\Interfaces\Hookable;

/**
 * Class - GlobalStyles
 */
class GlobalStyles implements Hookable {

	/**
	 * {@inheritDoc}
	 */
	public function initialize() : void {
		add_action( get_graphql_register_action(), [ __CLASS__, 'register_global_styles_field' ] );
		add_filter( 'render_block', [ __CLASS__, 'render_layout_styles' ], 10, 2 );
	}

	/**
	 * Registers the global stylesheet to the schema.
	 */
	public static function register_global_styles_field() : void {
		register_graphql_field(
			'RootQuery',
			'globalStylesheet',
			[
				'type'    => 'String',
				'resolve' => fn() => wp_get_global_stylesheet(),
			]
		);
	}

	/**
	 * Filters the content to keep layout styles inline.
	 *
	 * @param string $block_content .
	 * @param array  $block .
	 */
	public static function render_layout_styles( string $block_content, array $block ) : string {
		$block_type     = \WP_Block_Type_Registry::get_instance()->get_registered( $block['blockName'] );
		$support_layout = block_has_support( $block_type, [ '__experimentalLayout' ], false );
		if ( ! $support_layout ) {
			return $block_content;
		}
		$block_gap             = wp_get_global_settings( [ 'spacing', 'blockGap' ] );
		$default_layout        = wp_get_global_settings( [ 'layout' ] );
		$has_block_gap_support = isset( $block_gap ) ? null !== $block_gap : false;
		$default_block_layout  = _wp_array_get( $block_type->supports, [ '__experimentalLayout', 'default' ], [] );
		$used_layout           = isset( $block['attrs']['layout'] ) ? $block['attrs']['layout'] : $default_block_layout;
		if ( isset( $used_layout['inherit'] ) && $used_layout['inherit'] ) {
			if ( ! $default_layout ) {
				return $block_content;
			}
			$used_layout = $default_layout;
		}
		$id        = uniqid();
		$gap_value = _wp_array_get( $block, [ 'attrs', 'style', 'spacing', 'blockGap' ] );
		// Skip if gap value contains unsupported characters.
		// Regex for CSS value borrowed from `safecss_filter_attr`, and used here
		// because we only want to match against the value, not the CSS attribute.
		$gap_value = preg_match( '%[\\\(&=}]|/\*%', $gap_value ) ? null : $gap_value;
		$style     = wp_get_layout_style( ".wp-container-$id", $used_layout, $has_block_gap_support, $gap_value );
		// This assumes the hook only applies to blocks with a single wrapper.
		// I think this is a reasonable limitation for that particular hook.
		$content = preg_replace(
			'/' . preg_quote( 'class="', '/' ) . '/',
			'class="wp-container-' . $id . ' ',
			$block_content,
			1
		);

		// This is all that's really being modified here.
		return $content . ( $style ? '<style>' . $style . '</style>' : '' );
	}
}
