( function ( wp ) {
	'use strict';
	if ( ! wp || ! wp.blocks || ! wp.element || ! wp.components || ! wp.blockEditor || ! wp.hooks || ! wp.compose ) { return; }

	const el = wp.element.createElement;
	const { __ } = wp.i18n;
	const { InspectorControls, InnerBlocks, useBlockProps } = wp.blockEditor;
	const { Button, PanelBody, SelectControl, TextControl, ToggleControl } = wp.components;
	const limit = 30;
	const makeId = function ( prefix ) { return prefix + '_' + Math.random().toString( 36 ).slice( 2, 9 ); };

	wp.hooks.addFilter( 'blocks.registerBlockType', 'sci-design-blocks/market-pricing-plan-id', function ( settings, name ) {
		if ( name === 'core/paragraph' || name === 'core/button' ) {
			settings.attributes = Object.assign( {}, settings.attributes, { sciMarketPlanId: { type: 'string', default: '' } } );
		}
		return settings;
	} );

	const withPlanId = wp.compose.createHigherOrderComponent( function ( BlockEdit ) {
		return function ( props ) {
			if ( props.name !== 'core/paragraph' && props.name !== 'core/button' ) { return el( BlockEdit, props ); }
			const label = props.name === 'core/paragraph' ? __( 'Vincular como precio de plan', 'sci-design-blocks' ) : __( 'Vincular botón a plan', 'sci-design-blocks' );
			return el( wp.element.Fragment, {},
				el( BlockEdit, props ),
				el( InspectorControls, {}, el( PanelBody, { title: __( 'SCI · selector de mercado', 'sci-design-blocks' ), initialOpen: false },
					el( TextControl, { label: label, help: __( 'Usa el mismo ID que configuraste en el bloque SCI Market Pricing. Déjalo vacío para no vincular este bloque.', 'sci-design-blocks' ), value: props.attributes.sciMarketPlanId || '', onChange: function ( value ) { props.setAttributes( { sciMarketPlanId: value.toLowerCase().replace( /[^a-z0-9_-]/g, '' ).slice( 0, 64 ) } ); } } ) ) )
			);
		};
	}, 'withPlanId' );
	wp.hooks.addFilter( 'editor.BlockEdit', 'sci-design-blocks/market-pricing-plan-id', withPlanId );

	function Edit( props ) {
		const { attributes, setAttributes } = props;
		const markets = Array.isArray( attributes.markets ) ? attributes.markets : [];
		const plans = Array.isArray( attributes.plans ) ? attributes.plans : [];
		const [ selectedMarket, setSelectedMarket ] = wp.element.useState( markets[0] ? markets[0].id : '' );
		const marketOptions = markets.filter( function ( item ) { return item.active !== false; } ).map( function ( item ) { return { label: ( item.name || item.id ) + ' · ' + ( item.currency || 'USD' ), value: item.id }; } );
		const blockProps = useBlockProps( { className: 'sci-market-pricing-editor' } );

		function updateMarket( index, patch ) {
			setAttributes( { markets: markets.map( function ( item, i ) { return i === index ? Object.assign( {}, item, patch ) : item; } ) } );
		}
		function updatePlan( index, patch ) {
			setAttributes( { plans: plans.map( function ( item, i ) { return i === index ? Object.assign( {}, item, patch ) : item; } ) } );
		}
		function updatePrice( planIndex, marketId, patch ) {
			const prices = Object.assign( {}, plans[planIndex].prices || {} );
			prices[marketId] = Object.assign( {}, prices[marketId] || {}, patch );
			updatePlan( planIndex, { prices: prices } );
		}
		function addMarket() {
			if ( markets.length >= limit ) { return; }
			const id = makeId( 'market' );
			const next = markets.concat( [ { id: id, name: __( 'Nuevo mercado', 'sci-design-blocks' ), currency: 'USD', locale: 'en-US', formUrl: '', active: true } ] );
			setAttributes( { markets: next, defaultMarketId: attributes.defaultMarketId || id } );
			setSelectedMarket( id );
		}
		function removeMarket( index ) {
			if ( markets.length < 2 || ! window.confirm( __( '¿Eliminar este mercado y sus precios asociados?', 'sci-design-blocks' ) ) ) { return; }
			const removedId = markets[index].id;
			const nextMarkets = markets.filter( function ( _, i ) { return i !== index; } );
			const nextPlans = plans.map( function ( plan ) { const prices = Object.assign( {}, plan.prices || {} ); delete prices[removedId]; return Object.assign( {}, plan, { prices: prices } ); } );
			setAttributes( { markets: nextMarkets, plans: nextPlans, defaultMarketId: attributes.defaultMarketId === removedId ? nextMarkets[0].id : attributes.defaultMarketId } );
			if ( selectedMarket === removedId ) { setSelectedMarket( nextMarkets[0].id ); }
		}
		function addPlan() {
			if ( plans.length >= 50 ) { return; }
			setAttributes( { plans: plans.concat( [ { id: makeId( 'plan' ), prices: {} } ] ) } );
		}
		function removePlan( index ) {
			if ( window.confirm( __( '¿Eliminar este plan y sus precios?', 'sci-design-blocks' ) ) ) { setAttributes( { plans: plans.filter( function ( _, i ) { return i !== index; } ) } ); }
		}
		const marketPanels = markets.map( function ( market, index ) {
			return el( PanelBody, { key: market.id, title: ( market.name || __( 'Mercado', 'sci-design-blocks' ) ) + ' · ' + ( market.currency || 'USD' ), initialOpen: index === 0 },
				el( TextControl, { label: __( 'ID estable', 'sci-design-blocks' ), value: market.id, disabled: true, help: __( 'Se crea al añadir el mercado y no cambia al editar su nombre o moneda.', 'sci-design-blocks' ) } ),
				el( TextControl, { label: __( 'Nombre mostrado', 'sci-design-blocks' ), value: market.name || '', onChange: function ( value ) { updateMarket( index, { name: value } ); } } ),
				el( TextControl, { label: __( 'Moneda (ISO 4217)', 'sci-design-blocks' ), help: __( 'Ejemplos: PEN, MXN, USD, COP, EUR.', 'sci-design-blocks' ), value: market.currency || '', maxLength: 3, onChange: function ( value ) { updateMarket( index, { currency: value.toUpperCase() } ); } } ),
				el( TextControl, { label: __( 'Configuración regional', 'sci-design-blocks' ), value: market.locale || '', onChange: function ( value ) { updateMarket( index, { locale: value } ); } } ),
				el( TextControl, { label: __( 'URL del formulario Fluent Forms para este mercado', 'sci-design-blocks' ), type: 'url', value: market.formUrl || '', onChange: function ( value ) { updateMarket( index, { formUrl: value } ); } } ),
				el( ToggleControl, { label: __( 'Mostrar en el selector', 'sci-design-blocks' ), checked: market.active !== false, disabled: market.active !== false && marketOptions.length <= 1, onChange: function ( value ) {
					updateMarket( index, { active: value } );
					if ( ! value ) {
						const next = markets.find( function ( candidate, candidateIndex ) { return candidateIndex !== index && candidate.active !== false; } );
						if ( next && selectedMarket === market.id ) { setSelectedMarket( next.id ); }
						if ( next && attributes.defaultMarketId === market.id ) { setAttributes( { defaultMarketId: next.id } ); }
					}
				} } ),
				el( Button, { variant: 'tertiary', isDestructive: true, disabled: markets.length < 2, onClick: function () { removeMarket( index ); } }, __( 'Eliminar mercado', 'sci-design-blocks' ) )
			);
		} );
		const planPanels = plans.map( function ( plan, index ) {
			const current = plan.prices && plan.prices[selectedMarket] ? plan.prices[selectedMarket] : {};
			return el( PanelBody, { key: plan.id, title: ( plan.id || __( 'Plan', 'sci-design-blocks' ) ), initialOpen: index === 0 },
				el( TextControl, { label: __( 'ID del plan', 'sci-design-blocks' ), help: __( 'Debe coincidir con el ID asignado al párrafo de precio y al botón Core.', 'sci-design-blocks' ), value: plan.id || '', onChange: function ( value ) { updatePlan( index, { id: value.toLowerCase().replace( /[^a-z0-9_-]/g, '' ).slice( 0, 64 ) } ); } } ),
				el( TextControl, { label: __( 'Precio para el mercado seleccionado', 'sci-design-blocks' ), type: 'number', min: 0, step: '0.001', value: current.amount || '', onChange: function ( value ) { updatePrice( index, selectedMarket, { amount: value } ); } } ),
				el( TextControl, { label: __( 'Texto del periodo', 'sci-design-blocks' ), help: __( 'Ejemplo: / mes, / año, pago único.', 'sci-design-blocks' ), value: current.period || '', onChange: function ( value ) { updatePrice( index, selectedMarket, { period: value } ); } } ),
				el( Button, { variant: 'tertiary', isDestructive: true, onClick: function () { removePlan( index ); } }, __( 'Eliminar plan', 'sci-design-blocks' ) )
			);
		} );

		return el( wp.element.Fragment, {},
			el( InspectorControls, {},
				el( PanelBody, { title: __( 'Mercados y monedas', 'sci-design-blocks' ), initialOpen: true },
					el( SelectControl, { label: __( 'Mercado predeterminado', 'sci-design-blocks' ), value: attributes.defaultMarketId || '', options: marketOptions, onChange: function ( value ) { setAttributes( { defaultMarketId: value } ); } } ),
					marketPanels,
					el( Button, { variant: 'primary', disabled: markets.length >= limit, onClick: addMarket }, __( 'Añadir mercado o moneda', 'sci-design-blocks' ) )
				),
				el( PanelBody, { title: __( 'Planes y precios', 'sci-design-blocks' ), initialOpen: false },
					el( SelectControl, { label: __( 'Configurar precios para', 'sci-design-blocks' ), value: selectedMarket, options: marketOptions, onChange: setSelectedMarket } ),
					planPanels,
					el( Button, { variant: 'primary', disabled: plans.length >= 50, onClick: addPlan }, __( 'Añadir plan', 'sci-design-blocks' ) ),
					el( 'p', {}, __( 'El contenido y el diseño de las tarjetas se editan con bloques Core dentro de este contenedor.', 'sci-design-blocks' ) )
				)
			),
			el( 'div', blockProps,
				el( 'p', { className: 'sci-market-pricing-editor__help' }, __( 'Inserta aquí un patrón SCI Pricing. Vincula cada párrafo de precio y botón Core con el ID del plan correspondiente.', 'sci-design-blocks' ) ),
				el( InnerBlocks, { renderAppender: InnerBlocks.ButtonBlockAppender } )
			)
		);
	}

	wp.blocks.registerBlockType( 'sci-design-blocks/market-pricing', {
		edit: Edit,
		save: function () { return el( InnerBlocks.Content ); },
	} );
} )( window.wp );
