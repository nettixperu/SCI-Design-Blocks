( function ( wp ) {
	'use strict';
	if ( ! wp || ! wp.blocks || ! wp.element || ! wp.components || ! wp.blockEditor || ! wp.hooks || ! wp.compose ) { return; }

	const el = wp.element.createElement;
	const { __ } = wp.i18n;
	const { InspectorControls, InnerBlocks, useBlockProps } = wp.blockEditor;
	const { Button, ColorPalette, PanelBody, SelectControl, TextControl, ToggleControl } = wp.components;
	const limit = 30;
	const makeId = function ( prefix ) { return prefix + '_' + Math.random().toString( 36 ).slice( 2, 9 ); };

	wp.hooks.addFilter( 'blocks.registerBlockType', 'sci-design-blocks/market-pricing-plan-id', function ( settings, name ) {
		if ( name === 'core/paragraph' || name === 'core/button' || name === 'core/heading' ) {
			settings.attributes = Object.assign( {}, settings.attributes, { sciMarketPlanId: { type: 'string', default: '' } } );
		}
		if ( name === 'core/paragraph' ) {
			settings.attributes.sciMarketContentRole = { type: 'string', default: 'price' };
		}
		if ( name === 'core/button' ) {
			settings.attributes.sciMarketButtonPrice = { type: 'boolean', default: false };
		}
		return settings;
	} );

	function findPlanCardGroup( blocks, planId ) {
		function hasPlan( block ) {
			if ( block.attributes && block.attributes.sciMarketPlanId === planId ) { return true; }
			return ( block.innerBlocks || [] ).some( hasPlan );
		}
		function hasPriceAndButton( block ) {
			let price = false;
			let button = false;
			(function scan( node ) {
				if ( node.attributes && node.attributes.sciMarketPlanId === planId ) {
					if ( node.name === 'core/paragraph' ) { price = true; }
					if ( node.name === 'core/button' ) { button = true; }
				}
				( node.innerBlocks || [] ).forEach( scan );
			})( block );
			return price && button;
		}
		for ( const block of blocks || [] ) {
			if ( block.name === 'core/group' && hasPlan( block ) ) {
				const card = ( block.innerBlocks || [] ).find( function ( child ) { return child.name === 'core/group' && hasPlan( child ) && hasPriceAndButton( child ); } );
				if ( card ) { return card; }
			}
			const nested = findPlanCardGroup( block.innerBlocks, planId );
			if ( nested ) { return nested; }
		}
		return null;
	}

	const withPlanId = wp.compose.createHigherOrderComponent( function ( BlockEdit ) {
		return function ( props ) {
			if ( props.name !== 'core/paragraph' && props.name !== 'core/button' && props.name !== 'core/heading' ) { return el( BlockEdit, props ); }
			const isParagraph = props.name === 'core/paragraph';
			const label = isParagraph ? __( 'ID del plan', 'sci-design-blocks' ) : ( props.name === 'core/button' ? __( 'ID del plan del botón', 'sci-design-blocks' ) : __( 'ID del plan del nombre', 'sci-design-blocks' ) );
			const help = isParagraph
				? __( 'Configura primero el importe en el bloque SCI Market Pricing. Usa el ID fijo del plan; después elige si este párrafo muestra el precio o la facturación anual.', 'sci-design-blocks' )
				: ( props.name === 'core/button'
					? __( 'Escribe el mismo ID que usa el párrafo del precio. El botón abrirá la URL de Fluent Forms del mercado activo; su texto y diseño siguen siendo editables con Gutenberg.', 'sci-design-blocks' )
					: __( 'Vincula el encabezado principal de esta tarjeta con el ID fijo para editar su nombre desde Planes y precios.', 'sci-design-blocks' ) );
			return el( wp.element.Fragment, {},
				el( BlockEdit, props ),
				el( InspectorControls, {}, el( PanelBody, { title: __( 'SCI · selector de mercado', 'sci-design-blocks' ), initialOpen: false },
					el( TextControl, { label: label, help: help, value: props.attributes.sciMarketPlanId || '', onChange: function ( value ) { props.setAttributes( { sciMarketPlanId: value.toLowerCase().replace( /[^a-z0-9_-]/g, '' ).slice( 0, 64 ) } ); } } ),
					props.name === 'core/button' ? el( ToggleControl, { label: __( 'Mostrar el precio mensual en este botón', 'sci-design-blocks' ), help: __( 'Sustituye el texto del botón por el precio y el periodo del mercado seleccionado. El botón conserva su enlace al formulario.', 'sci-design-blocks' ), checked: props.attributes.sciMarketButtonPrice === true, onChange: function ( value ) { props.setAttributes( { sciMarketButtonPrice: value } ); } } ) : null,
					isParagraph ? el( SelectControl, { label: __( 'Contenido que cambia con el mercado', 'sci-design-blocks' ), value: props.attributes.sciMarketContentRole || 'price', options: [ { label: __( 'Precio del plan', 'sci-design-blocks' ), value: 'price' }, { label: __( 'Facturación anual', 'sci-design-blocks' ), value: 'annual' } ], onChange: function ( value ) { props.setAttributes( { sciMarketContentRole: value } ); } } ) : null ) )
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
		wp.element.useEffect( function () {
			let changed = false;
			const used = {};
			const migrated = plans.map( function ( plan, index ) {
				const fallback = [ 'starter', 'business', 'enterprise' ][ index ] || makeId( 'plan' );
				let id = ( plan.id || '' ).toLowerCase().replace( /[^a-z0-9_-]/g, '' ).slice( 0, 64 ) || fallback;
				while ( used[id] ) { id = id + '_2'; }
				used[id] = true;
				const name = typeof plan.name === 'string' && plan.name ? plan.name : id.charAt( 0 ).toUpperCase() + id.slice( 1 );
				if ( id !== plan.id || name !== plan.name ) { changed = true; return Object.assign( {}, plan, { id: id, name: name } ); }
				return plan;
			} );
			if ( changed ) { setAttributes( { plans: migrated } ); }
		}, [ plans ] );
		const palette = wp.data && wp.data.select ? ( wp.data.select( 'core/block-editor' ).getSettings().colors || [] ) : [];
		function colorControl( label, attribute ) {
			return el( wp.element.Fragment, { key: attribute },
				el( 'p', {}, label ),
				el( ColorPalette, { colors: palette, value: attributes[attribute] || '', clearable: true, onChange: function ( value ) { setAttributes( { [ attribute ]: value || '' } ); } } )
			);
		}

		function updateMarket( index, patch ) {
			setAttributes( { markets: markets.map( function ( item, i ) { return i === index ? Object.assign( {}, item, patch ) : item; } ) } );
		}
		function updatePlan( index, patch ) {
			setAttributes( { plans: plans.map( function ( item, i ) { return i === index ? Object.assign( {}, item, patch ) : item; } ) } );
			if ( Object.prototype.hasOwnProperty.call( patch, 'name' ) && wp.data && wp.data.select && wp.data.dispatch ) {
				const planId = plans[index] && plans[index].id;
				const editor = wp.data.select( 'core/block-editor' );
				const dispatcher = wp.data.dispatch( 'core/block-editor' );
				const card = findPlanCardGroup( editor.getBlocks( props.clientId ), planId );
				let headingUpdated = false;
				function syncHeadings( blocks ) {
					( blocks || [] ).forEach( function ( block ) {
						if ( block.name === 'core/heading' && ! headingUpdated ) {
							dispatcher.updateBlockAttributes( block.clientId, { content: patch.name, sciMarketPlanId: planId } );
							headingUpdated = true;
						}
						syncHeadings( block.innerBlocks );
					} );
				}
				if ( card ) { syncHeadings( [ card ] ); }
			}
		}
		function updatePrice( planIndex, marketId, patch ) {
			const prices = Object.assign( {}, plans[planIndex].prices || {} );
			prices[marketId] = Object.assign( {}, prices[marketId] || {}, patch );
			updatePlan( planIndex, { prices: prices } );
		}
		function addMarket() {
			if ( markets.length >= limit ) { return; }
			const id = makeId( 'market' );
			const next = markets.concat( [ { id: id, name: __( 'Nuevo mercado', 'sci-design-blocks' ), flag: '', currency: 'USD', locale: 'en-US', formUrl: '', active: true } ] );
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
			setAttributes( { plans: plans.concat( [ { id: makeId( 'plan' ), name: __( 'Nuevo plan', 'sci-design-blocks' ), prices: {} } ] ) } );
		}
		function removePlan( index ) {
			const plan = plans[index];
			if ( ! plan || plans.length <= 1 ) { return; }
			const planId = plan.id;
			const editor = wp.data && wp.data.select ? wp.data.select( 'core/block-editor' ) : null;
			const dispatcher = wp.data && wp.data.dispatch ? wp.data.dispatch( 'core/block-editor' ) : null;
			if ( editor && dispatcher ) {
				const card = findPlanCardGroup( editor.getBlocks( props.clientId ), planId );
				if ( card ) { dispatcher.removeBlock( card.clientId ); }
			}
			setAttributes( { plans: plans.filter( function ( _, i ) { return i !== index; } ) } );
		}
		const marketPanels = markets.map( function ( market, index ) {
			return el( PanelBody, { key: market.id, title: ( market.name || __( 'Mercado', 'sci-design-blocks' ) ) + ' · ' + ( market.currency || 'USD' ), initialOpen: index === 0 },
				el( TextControl, { label: __( 'ID estable', 'sci-design-blocks' ), value: market.id, disabled: true, help: __( 'Se crea al añadir el mercado y no cambia al editar su nombre o moneda.', 'sci-design-blocks' ) } ),
				el( TextControl, { label: __( 'Nombre mostrado', 'sci-design-blocks' ), value: market.name || '', onChange: function ( value ) { updateMarket( index, { name: value } ); } } ),
				el( TextControl, { label: __( 'Bandera manual (opcional)', 'sci-design-blocks' ), help: __( 'Vacío: se toma automáticamente del país en la configuración regional (por ejemplo, es-PE → 🇵🇪). Escribe un emoji solo si quieres sobrescribirlo.', 'sci-design-blocks' ), value: market.flag || '', onChange: function ( value ) { updateMarket( index, { flag: value.slice( 0, 16 ) } ); } } ),
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
			const planId = plan.id || ( [ 'starter', 'business', 'enterprise' ][ index ] || 'plan_' + ( index + 1 ) );
			return el( PanelBody, { key: planId, title: plan.name || planId, initialOpen: index === 0 },
				el( TextControl, { label: __( 'Nombre del plan', 'sci-design-blocks' ), value: plan.name || '', onChange: function ( value ) { updatePlan( index, { name: value } ); } } ),
				el( TextControl, { label: __( 'ID del plan (fijo)', 'sci-design-blocks' ), help: __( 'Este ID conecta el nombre, el precio y el botón de la tarjeta. No se puede editar para evitar que se desvinculen.', 'sci-design-blocks' ), value: planId, disabled: true } ),
				el( TextControl, { label: __( 'Precio para el mercado seleccionado', 'sci-design-blocks' ), type: 'number', min: 0, step: '0.001', value: current.amount || '', onChange: function ( value ) { updatePrice( index, selectedMarket, { amount: value } ); } } ),
				el( TextControl, { label: __( 'Texto del periodo', 'sci-design-blocks' ), help: __( 'Ejemplo: / mes, / año, pago único.', 'sci-design-blocks' ), value: current.period || '', onChange: function ( value ) { updatePrice( index, selectedMarket, { period: value } ); } } ),
				el( TextControl, { label: __( 'Facturación anual (opcional)', 'sci-design-blocks' ), help: __( 'Si lo dejas vacío, se calcula como el precio mensual × 12. Puedes indicar un importe distinto para cada moneda.', 'sci-design-blocks' ), type: 'number', min: 0, step: '0.01', value: current.annualAmount || '', onChange: function ( value ) { updatePrice( index, selectedMarket, { annualAmount: value } ); } } ),
				el( TextControl, { label: __( 'Nota de facturación (opcional)', 'sci-design-blocks' ), help: __( 'Ejemplos: + IGV, + IVA, impuestos incluidos.', 'sci-design-blocks' ), value: current.annualNote || '', onChange: function ( value ) { updatePrice( index, selectedMarket, { annualNote: value } ); } } ),
				el( Button, { variant: 'tertiary', isDestructive: true, disabled: plans.length <= 1, onClick: function () { removePlan( index ); } }, __( 'Eliminar plan y tarjeta', 'sci-design-blocks' ) )
			);
		} );

		return el( wp.element.Fragment, {},
			el( InspectorControls, {},
				el( PanelBody, { title: __( 'Selector de mercado', 'sci-design-blocks' ), initialOpen: true },
					el( SelectControl, { label: __( 'Estilo del selector', 'sci-design-blocks' ), value: attributes.selectorStyle || 'segmented', options: [
						{ label: __( 'Segmentado con banderas', 'sci-design-blocks' ), value: 'segmented' },
						{ label: __( 'Botones tipo píldora', 'sci-design-blocks' ), value: 'pills' },
						{ label: __( 'Desplegable nativo', 'sci-design-blocks' ), value: 'select' },
					], onChange: function ( value ) { setAttributes( { selectorStyle: value } ); } } ),
					el( ToggleControl, { label: __( 'Mostrar banderas', 'sci-design-blocks' ), checked: attributes.showFlags !== false, onChange: function ( value ) { setAttributes( { showFlags: value } ); } } ),
					el( ToggleControl, { label: __( 'Mostrar el texto “Moneda o mercado”', 'sci-design-blocks' ), checked: attributes.showSelectorLabel !== false, onChange: function ( value ) { setAttributes( { showSelectorLabel: value } ); } } ),
					el( 'p', {}, __( 'Colores del selector · usa la paleta del tema o elige un color personalizado.', 'sci-design-blocks' ) ),
					colorControl( __( 'Texto y etiquetas', 'sci-design-blocks' ), 'selectorTextColor' ),
					colorControl( __( 'Borde', 'sci-design-blocks' ), 'selectorBorderColor' ),
					colorControl( __( 'Fondo del selector', 'sci-design-blocks' ), 'selectorBackgroundColor' ),
					colorControl( __( 'Fondo de la opción seleccionada', 'sci-design-blocks' ), 'selectorActiveColor' ),
					colorControl( __( 'Texto de la opción seleccionada', 'sci-design-blocks' ), 'selectorActiveTextColor' )
				),
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
				el( 'div', { className: 'sci-market-pricing-editor__help' },
					el( 'strong', {}, __( 'Cómo vincular precio y botón', 'sci-design-blocks' ) ),
					el( 'ol', {},
						el( 'li', {}, __( 'En Planes y precios, elige el mercado y configura el precio y el periodo del plan.', 'sci-design-blocks' ) ),
						el( 'li', {}, __( 'Si el precio va fuera del botón, selecciona su párrafo, escribe el ID fijo y elige “Precio del plan”. Si quieres el precio dentro del botón verde, selecciona el botón, escribe ese ID y activa “Mostrar el precio mensual en este botón”.', 'sci-design-blocks' ) ),
						el( 'li', {}, __( 'El botón vinculado conserva su enlace al formulario del plan; al activar precio dinámico, su texto se reemplaza por el importe y periodo del mercado activo.', 'sci-design-blocks' ) ),
						el( 'li', {}, __( 'Para una línea anual, vincula otro párrafo con ese ID y elige “Facturación anual”.', 'sci-design-blocks' ) )
					),
					el( 'p', {}, __( 'El patrón SCI Pricing — Market Selector ya incluye estos vínculos.', 'sci-design-blocks' ) )
				),
				el( InnerBlocks, { renderAppender: InnerBlocks.ButtonBlockAppender } )
			)
		);
	}

	wp.blocks.registerBlockType( 'sci-design-blocks/market-pricing', {
		edit: Edit,
		save: function () { return el( InnerBlocks.Content ); },
	} );
} )( window.wp );
