( function ( wp ) {
	if ( ! wp || ! wp.hooks || ! wp.element || ! wp.components || ! wp.blockEditor || ! wp.data || ! wp.compose ) {
		return;
	}

	const { addFilter } = wp.hooks;
	const { createElement: el, Fragment } = wp.element;
	const { createHigherOrderComponent } = wp.compose;
	const { InspectorControls } = wp.blockEditor;
	const { ColorPalette, GradientPicker, PanelBody, SelectControl, ToggleControl } = wp.components;
	const { useSelect } = wp.data;

	const hoverAttributes = {
		sciHoverEnabled: { type: 'boolean', default: false },
		sciHoverBackgroundType: { type: 'string', default: 'none' },
		sciHoverBackground: { type: 'string', default: '' },
		sciHoverTextColor: { type: 'string', default: '' },
		sciHoverBorderColor: { type: 'string', default: '' },
		sciHoverShadow: { type: 'string', default: 'keep' },
		sciHoverLift: { type: 'boolean', default: false },
	};

	addFilter( 'blocks.registerBlockType', 'sci-design-blocks/button-hover-attributes', function ( settings, name ) {
		if ( name !== 'core/button' ) {
			return settings;
		}

		return Object.assign( {}, settings, {
			attributes: Object.assign( {}, settings.attributes, hoverAttributes ),
		} );
	} );

	function ColorControl( props ) {
		return el(
			'div',
			{ className: 'sci-button-hover-color-control' },
			el( 'p', { className: 'components-base-control__label' }, props.label ),
			el( ColorPalette, {
				colors: props.colors,
				value: props.value || undefined,
				clearable: true,
				onChange: function ( color ) {
					props.onChange( color || '' );
				},
			} )
		);
	}

	function ButtonHoverControls( props ) {
		const palettes = useSelect( function ( select ) {
			const settings = select( 'core/block-editor' ).getSettings();
			return {
				colors: settings.colors || [],
				gradients: settings.gradients || [],
			};
		}, [] );
		const attributes = props.attributes;
		const set = function ( key, value ) {
			const update = {};
			update[ key ] = value;
			props.setAttributes( update );
		};
		const children = [
			el( ToggleControl, {
				key: 'enabled',
				label: 'Personalizar hover de este botón',
				checked: !! attributes.sciHoverEnabled,
				onChange: function ( value ) { set( 'sciHoverEnabled', value ); },
			} ),
		];

		if ( attributes.sciHoverEnabled ) {
			children.push(
				el( SelectControl, {
					key: 'background-type',
					label: 'Fondo al pasar el cursor',
					value: attributes.sciHoverBackgroundType || 'none',
					options: [
						{ label: 'Sin cambio', value: 'none' },
						{ label: 'Color sólido', value: 'color' },
						{ label: 'Degradado', value: 'gradient' },
					],
					onChange: function ( value ) { set( 'sciHoverBackgroundType', value ); },
				} )
			);

			if ( attributes.sciHoverBackgroundType === 'color' ) {
				children.push( el( ColorControl, {
					key: 'background-color',
					label: 'Color de fondo Hover',
					colors: palettes.colors,
					value: attributes.sciHoverBackground,
					onChange: function ( value ) { set( 'sciHoverBackground', value ); },
				} ) );
			} else if ( attributes.sciHoverBackgroundType === 'gradient' ) {
				children.push(
					el( 'div', { key: 'background-gradient', className: 'sci-button-hover-gradient-control' },
						el( 'p', { className: 'components-base-control__label' }, 'Degradado Hover' ),
						el( GradientPicker, {
							gradients: palettes.gradients,
							value: attributes.sciHoverBackground || undefined,
							onChange: function ( value ) { set( 'sciHoverBackground', value || '' ); },
						} )
					)
				);
			}

			children.push(
				el( ColorControl, {
					key: 'text-color',
					label: 'Color del texto Hover',
					colors: palettes.colors,
					value: attributes.sciHoverTextColor,
					onChange: function ( value ) { set( 'sciHoverTextColor', value ); },
				} ),
				el( ColorControl, {
					key: 'border-color',
					label: 'Color del borde Hover',
					colors: palettes.colors,
					value: attributes.sciHoverBorderColor,
					onChange: function ( value ) { set( 'sciHoverBorderColor', value ); },
				} ),
				el( SelectControl, {
					key: 'shadow',
					label: 'Sombra Hover',
					value: attributes.sciHoverShadow || 'keep',
					options: [
						{ label: 'Conservar sombra actual', value: 'keep' },
						{ label: 'Quitar sombra', value: 'none' },
						{ label: 'Sombra suave', value: 'subtle' },
						{ label: 'Sombra marcada', value: 'medium' },
					],
					onChange: function ( value ) { set( 'sciHoverShadow', value ); },
				} ),
				el( ToggleControl, {
					key: 'lift',
					label: 'Elevar ligeramente al pasar el cursor',
					checked: !! attributes.sciHoverLift,
					onChange: function ( value ) { set( 'sciHoverLift', value ); },
				} )
			);
		}

		return el( InspectorControls, null,
			el( PanelBody, { title: 'SCI — Hover del botón', initialOpen: false }, children )
		);
	}

	const withButtonHoverControls = createHigherOrderComponent( function ( BlockEdit ) {
		return function ( props ) {
			return el( Fragment, null,
				el( BlockEdit, props ),
				props.name === 'core/button' ? el( ButtonHoverControls, props ) : null
			);
		};
	}, 'withSCIButtonHoverControls' );

	addFilter( 'editor.BlockEdit', 'sci-design-blocks/button-hover-controls', withButtonHoverControls );

	const withButtonHoverPreview = createHigherOrderComponent( function ( BlockListBlock ) {
		return function ( props ) {
			if ( props.name !== 'core/button' || ! props.attributes || ! props.attributes.sciHoverEnabled ) {
				return el( BlockListBlock, props );
			}

			const attributes = props.attributes;
			const wrapperProps = Object.assign( {}, props.wrapperProps || {} );
			const style = Object.assign( {}, wrapperProps.style || {} );
			[
				'--sci-hover-background',
				'--sci-hover-text-color',
				'--sci-hover-border-color',
				'--sci-hover-shadow',
				'--sci-hover-lift',
			].forEach( function ( variable ) { delete style[ variable ]; } );

			if ( attributes.sciHoverBackground && attributes.sciHoverBackgroundType !== 'none' ) {
				style[ '--sci-hover-background' ] = attributes.sciHoverBackground;
			}
			if ( attributes.sciHoverTextColor ) {
				style[ '--sci-hover-text-color' ] = attributes.sciHoverTextColor;
			}
			if ( attributes.sciHoverBorderColor ) {
				style[ '--sci-hover-border-color' ] = attributes.sciHoverBorderColor;
			}
			if ( attributes.sciHoverShadow === 'none' ) {
				style[ '--sci-hover-shadow' ] = 'none';
			} else if ( attributes.sciHoverShadow === 'subtle' ) {
				style[ '--sci-hover-shadow' ] = '0 4px 12px rgba(0,0,0,.18)';
			} else if ( attributes.sciHoverShadow === 'medium' ) {
				style[ '--sci-hover-shadow' ] = '0 8px 20px rgba(0,0,0,.22)';
			}
			if ( attributes.sciHoverLift ) {
				style[ '--sci-hover-lift' ] = '-2px';
			} else {
				style[ '--sci-hover-lift' ] = '0px';
			}

			wrapperProps.style = style;
			return el( BlockListBlock, Object.assign( {}, props, {
				className: ( props.className || '' ) + ' sci-hover-enabled',
				wrapperProps: wrapperProps,
			} ) );
		};
	}, 'withSCIButtonHoverPreview' );

	addFilter( 'editor.BlockListBlock', 'sci-design-blocks/button-hover-preview', withButtonHoverPreview );
} )( window.wp );
