( function () {
	'use strict';
	const rootSelector = '[data-sci-market-pricing]';
	const controlSelector = '[data-sci-market-control]';

	function parse( value ) {
		try { return JSON.parse( value || '{}' ); } catch ( error ) { return {}; }
	}
	function format( amount, market ) {
		try { return new Intl.NumberFormat( market.locale || 'en', { style: 'currency', currency: market.currency || 'USD' } ).format( Number( amount ) ); }
		catch ( error ) { return String( amount ) + ' ' + ( market.currency || '' ); }
	}
	function setRoute( wrapper, planId, market ) {
		const marker = Array.from( wrapper.querySelectorAll( '[data-sci-market-plan-id]' ) ).find( function ( node ) { return node.dataset.sciMarketPlanId === planId && node.matches( '.wp-block-button' ); } );
		const target = marker && marker.querySelector( 'a.wp-block-button__link' );
		if ( ! target ) { return; }
		if ( ! market.formUrl ) {
			target.removeAttribute( 'href' );
			target.setAttribute( 'aria-disabled', 'true' );
			target.setAttribute( 'tabindex', '-1' );
			return;
		}
		try {
			const url = new URL( market.formUrl, window.location.href );
			url.searchParams.set( 'plan', planId );
			url.searchParams.set( 'market', market.id );
			target.href = url.toString();
			target.removeAttribute( 'aria-disabled' );
			target.removeAttribute( 'tabindex' );
		} catch ( error ) {
			target.removeAttribute( 'href' );
			target.setAttribute( 'aria-disabled', 'true' );
			target.setAttribute( 'tabindex', '-1' );
		}
	}
	function update( wrapper, marketId ) {
		const data = parse( wrapper.getAttribute( 'data-pricing' ) );
		const market = ( data.markets || [] ).find( function ( item ) { return item.id === marketId && item.active; } ) || ( data.markets || [] ).find( function ( item ) { return item.id === wrapper.dataset.defaultMarket; } );
		if ( ! market ) { return; }
		wrapper.querySelectorAll( controlSelector ).forEach( function ( control ) {
			if ( control.type === 'radio' ) { control.checked = control.value === market.id; }
			else { control.value = market.id; }
		} );
		( data.plans || [] ).forEach( function ( plan ) {
			const price = plan.prices && plan.prices[market.id] ? plan.prices[market.id] : {};
			Array.from( wrapper.querySelectorAll( '[data-sci-market-plan-id]' ) ).filter( function ( node ) { return node.dataset.sciMarketPlanId === plan.id; } ).forEach( function ( node ) {
				if ( node.matches( 'h1, h2, h3, h4, h5, h6' ) ) {
					if ( plan.name ) { node.textContent = plan.name; }
				} else if ( node.matches( 'p' ) && node.dataset.sciMarketContentRole === 'annual' ) {
					const monthly = Number( price.amount );
					const annual = price.annualAmount !== '' && price.annualAmount !== undefined ? Number( price.annualAmount ) : monthly * 12;
					const hasAnnual = Number.isFinite( annual ) && annual > 0;
					node.hidden = ! hasAnnual && ! price.annualNote;
					if ( node.hidden ) { node.style.display = 'none'; } else { node.style.removeProperty( 'display' ); }
					if ( ! node.hidden ) {
						const annualText = hasAnnual ? 'Facturación anual ' + format( annual, market ) : '';
						node.textContent = [ annualText, price.annualNote || '' ].filter( Boolean ).join( ' ' );
					}
				} else if ( node.matches( 'p' ) ) {
					const formatted = price.amount ? format( price.amount, market ) : 'Consultar';
					node.textContent = formatted + ( price.amount && price.period ? ' ' + price.period : '' );
				}
			} );
			setRoute( wrapper, plan.id, market );
		} );
		wrapper.dataset.currentMarket = market.id;
	}
	function initialize() {
		document.querySelectorAll( rootSelector ).forEach( function ( wrapper ) { update( wrapper, wrapper.dataset.defaultMarket ); } );
		document.addEventListener( 'change', function ( event ) {
			if ( ! event.target.matches || ! event.target.matches( controlSelector ) ) { return; }
			const wrapper = event.target.closest( rootSelector );
			if ( wrapper ) { update( wrapper, event.target.value ); }
		} );
	}
	if ( document.readyState === 'loading' ) { document.addEventListener( 'DOMContentLoaded', initialize, { once: true } ); } else { initialize(); }
} )();
