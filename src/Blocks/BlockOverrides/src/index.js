/**
 * Edits the block attributes to expose fields on header.
 */
function addHeadingAttribute( settings, name ){
	if (typeof settings.attributes !== 'undefined') {
		if (name == 'core/heading') {
			settings.attributes = Object.assign(settings.attributes, {
				navTitle: {
					type: 'string',
				},
				includeInNav: {
					type: 'boolean',
				}
			});
		}
	}
	return settings;
}

const headingAdvancedControls = wp.compose.createHigherOrderComponent( ( BlockEdit) => {
	return ( props) => {
		const {Fragment} = wp.element;
		const {ToggleControl, TextControl, PanelBody, } = wp.components;
		const {InspectorAdvancedControls, InspectorControls} = wp.blockEditor;
		const { attributes, setAttributes, isSelected } = props;

		return (
			<Fragment>
				<BlockEdit { ...props} />
				{isSelected && (props.name === 'core/heading') && (
					<InspectorControls>
						<PanelBody title={wp.i18n.__('Article Navigation')}>
							<ToggleControl 
								label={wp.i18n.__('Include heading in article nav')}
								checked={!!attributes.includeInNav}
								onChange={(newValue) => setAttributes({ includeInNav: !attributes.includeInNav})}
							/>
							<TextControl
								label={wp.i18n.__('Nav Title', 'awp')}
								help={wp.i18n.__('Overwrites the title in the article navigation.')}
								value={attributes.navTitle}
								onChange={(newValue) => setAttributes({ navTitle: newValue })}
							/>
						</PanelBody>
					</InspectorControls>
				)
				}
			</Fragment>
		)
	}
});

function addHeadingFrontendAttributes( props, block, attributes ){
	if( block.name !== 'core/heading'){
		return props;
	}

	const {includeInNav, navTitle} = attributes;
	if ( typeof includeInNav !== 'undefined' && !!includeInNav ){
		props.includeInNav = 'true';
		props.navTitle = navTitle;
	}
	return props;
}

wp.hooks.addFilter(
	'blocks.registerBlockType',
	'gsc/heading-custom-attributes',
	addHeadingAttribute
);

wp.hooks.addFilter(
	'editor.BlockEdit',
	'gsc/heading-advanced-control',
	headingAdvancedControls
);

wp.hooks.addFilter(
	'blocks.getSaveContent.extraProps',
	'gsc/heading-frontend-attributes',
	addHeadingFrontendAttributes
)
