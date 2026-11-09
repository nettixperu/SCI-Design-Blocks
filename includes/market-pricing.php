<?php
/**
 * Market selector that connects Core pricing patterns to Fluent Forms.
 *
 * @package SCI\DesignBlocks
 */

namespace SCI\DesignBlocks;

/** Example markets are editable and are not tied to a fixed currency list. */
function market_pricing_default_markets(): array {
	return array(
		array( 'id' => 'pe_pen', 'name' => __( 'Perú', 'sci-design-blocks' ), 'flag' => '🇵🇪', 'currency' => 'PEN', 'locale' => 'es-PE', 'formUrl' => '', 'active' => true ),
		array( 'id' => 'mx_mxn', 'name' => __( 'México', 'sci-design-blocks' ), 'flag' => '🇲🇽', 'currency' => 'MXN', 'locale' => 'es-MX', 'formUrl' => '', 'active' => true ),
	);
}

/** Keep IDs safe for HTML, attributes, and query strings. */
function market_pricing_id( $value, string $fallback = '' ): string {
	$id = sanitize_key( is_scalar( $value ) ? (string) $value : '' );
	return preg_match( '/^[a-z][a-z0-9_-]{0,63}$/', $id ) ? $id : $fallback;
}

/** Add narrowly scoped attributes to Core price paragraphs and buttons. */
function market_pricing_core_attributes( array $args, string $block_name ): array {
	if ( in_array( $block_name, array( 'core/paragraph', 'core/button' ), true ) ) {
		$args['attributes'] = isset( $args['attributes'] ) && is_array( $args['attributes'] ) ? $args['attributes'] : array();
		$args['attributes']['sciMarketPlanId'] = array( 'type' => 'string', 'default' => '' );
	}
	return $args;
}
add_filter( 'register_block_type_args', __NAMESPACE__ . '\\market_pricing_core_attributes', 20, 2 );

/** Mark explicitly linked Core blocks in their rendered HTML. */
function market_pricing_mark_core_blocks( string $html, array $block ): string {
	if ( ! in_array( $block['blockName'] ?? '', array( 'core/paragraph', 'core/button' ), true ) ) {
		return $html;
	}
	$id = market_pricing_id( $block['attrs']['sciMarketPlanId'] ?? '' );
	if ( '' === $id || ! class_exists( '\\WP_HTML_Tag_Processor' ) ) {
		return $html;
	}
	$processor = new \WP_HTML_Tag_Processor( $html );
	if ( $processor->next_tag() ) {
		$processor->set_attribute( 'data-sci-market-plan-id', $id );
		return $processor->get_updated_html();
	}
	return $html;
}
add_filter( 'render_block', __NAMESPACE__ . '\\market_pricing_mark_core_blocks', 10, 2 );

/** Register a Core-first wrapper block and its small conditional assets. */
function register_market_pricing_block(): void {
	$root = dirname( __DIR__ );
	$style = $root . '/assets/css/market-pricing.css';
	$editor = $root . '/assets/js-market-pricing-editor.js';
	$view = $root . '/assets/js-market-pricing.js';
	wp_register_style( 'sci-market-pricing', plugins_url( 'assets/css/market-pricing.css', $root . '/sci-design-blocks.php' ), array(), is_file( $style ) ? (string) filemtime( $style ) : '1.7.3' );
	wp_register_script( 'sci-market-pricing-editor', plugins_url( 'assets/js-market-pricing-editor.js', $root . '/sci-design-blocks.php' ), array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-hooks', 'wp-compose', 'wp-i18n' ), is_file( $editor ) ? (string) filemtime( $editor ) : '1.7.3', true );
	wp_register_script( 'sci-market-pricing-view', plugins_url( 'assets/js-market-pricing.js', $root . '/sci-design-blocks.php' ), array(), is_file( $view ) ? (string) filemtime( $view ) : '1.7.3', true );
	register_block_type(
		'sci-design-blocks/market-pricing',
		array(
			'api_version' => 3,
			'title' => __( 'SCI Market Pricing', 'sci-design-blocks' ),
			'description' => __( 'Añade selector de mercado a una tabla de precios hecha con bloques Core.', 'sci-design-blocks' ),
			'category' => 'design',
			'icon' => 'money-alt',
			'keywords' => array( __( 'pricing', 'sci-design-blocks' ), __( 'currency', 'sci-design-blocks' ), __( 'Fluent Forms', 'sci-design-blocks' ) ),
			'attributes' => array(
				'markets' => array( 'type' => 'array', 'default' => market_pricing_default_markets() ),
				'plans' => array( 'type' => 'array', 'default' => array( array( 'id' => 'starter', 'prices' => array() ), array( 'id' => 'business', 'prices' => array() ), array( 'id' => 'enterprise', 'prices' => array() ) ) ),
				'defaultMarketId' => array( 'type' => 'string', 'default' => 'pe_pen' ),
				'selectorStyle' => array( 'type' => 'string', 'default' => 'segmented' ),
				'showFlags' => array( 'type' => 'boolean', 'default' => true ),
			),
			'supports' => array( 'align' => array( 'wide', 'full' ), 'spacing' => array( 'margin' => true, 'padding' => true ) ),
			'style' => 'sci-market-pricing',
			'editor_script' => 'sci-market-pricing-editor',
			'view_script' => 'sci-market-pricing-view',
			'render_callback' => __NAMESPACE__ . '\\render_market_pricing_block',
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\\register_market_pricing_block', 15 );

/** Normalize a saved list without silently trusting author-entered values. */
function market_pricing_normalize( $markets_raw, $plans_raw ): array {
	$markets_raw = is_array( $markets_raw ) ? array_slice( $markets_raw, 0, 30 ) : market_pricing_default_markets();
	$markets = array();
	$used = array();
	foreach ( $markets_raw as $index => $market ) {
		if ( ! is_array( $market ) ) { continue; }
		$id = market_pricing_id( $market['id'] ?? '', 'market_' . ( $index + 1 ) );
		while ( isset( $used[ $id ] ) ) { $id .= '_2'; }
		$used[ $id ] = true;
		$currency = strtoupper( preg_replace( '/[^A-Za-z]/', '', is_scalar( $market['currency'] ?? '' ) ? (string) $market['currency'] : '' ) );
		$locale = preg_replace( '/[^A-Za-z0-9-]/', '', is_scalar( $market['locale'] ?? '' ) ? (string) $market['locale'] : '' );
		$url = is_scalar( $market['formUrl'] ?? '' ) ? esc_url_raw( (string) $market['formUrl'] ) : '';
		$markets[] = array(
			'id' => $id,
			'name' => sanitize_text_field( is_scalar( $market['name'] ?? '' ) ? (string) $market['name'] : '' ),
			'currency' => preg_match( '/^[A-Z]{3}$/', $currency ) ? $currency : 'USD',
			'locale' => preg_match( '/^[A-Za-z]{2,3}(?:-[A-Za-z0-9]{2,8})*$/', $locale ) ? $locale : 'en',
			'formUrl' => $url,
			'flag' => sanitize_text_field( is_scalar( $market['flag'] ?? '' ) ? (string) $market['flag'] : '' ),
			'active' => ! empty( $market['active'] ),
		);
	}
	if ( empty( $markets ) ) { $markets = market_pricing_default_markets(); }
	if ( ! array_filter( $markets, static fn( $market ) => $market['active'] ) ) { $markets[0]['active'] = true; }
	$market_ids = array_column( $markets, 'id' );
	$plans_raw = is_array( $plans_raw ) ? array_slice( $plans_raw, 0, 50 ) : array();
	$plans = array();
	$used = array();
	foreach ( $plans_raw as $index => $plan ) {
		if ( ! is_array( $plan ) ) { continue; }
		$id = market_pricing_id( $plan['id'] ?? '', 'plan_' . ( $index + 1 ) );
		while ( isset( $used[ $id ] ) ) { $id .= '_2'; }
		$used[ $id ] = true;
		$prices = array();
		$raw_prices = is_array( $plan['prices'] ?? null ) ? $plan['prices'] : array();
		foreach ( $market_ids as $market_id ) {
			$raw = is_array( $raw_prices[ $market_id ] ?? null ) ? $raw_prices[ $market_id ] : array();
			$amount = is_scalar( $raw['amount'] ?? '' ) ? (string) $raw['amount'] : '';
			$prices[ $market_id ] = array(
				'amount' => preg_match( '/^\\d{1,12}(?:\\.\\d{1,3})?$/', $amount ) ? $amount : '',
				'period' => sanitize_text_field( is_scalar( $raw['period'] ?? '' ) ? (string) $raw['period'] : '' ),
			);
		}
		$plans[] = array( 'id' => $id, 'prices' => $prices );
	}
	return array( $markets, $plans );
}

/** Render only the selector wrapper; its pricing cards remain Core markup. */
function render_market_pricing_block( array $attributes, string $content, $block ): string {
	[ $markets, $plans ] = market_pricing_normalize( $attributes['markets'] ?? array(), $attributes['plans'] ?? array() );
	$active = array_values( array_filter( $markets, static fn( $market ) => $market['active'] ) );
	$default = market_pricing_id( $attributes['defaultMarketId'] ?? '', $active[0]['id'] );
	if ( ! in_array( $default, array_column( $active, 'id' ), true ) ) { $default = $active[0]['id']; }
	$selector_style = in_array( $attributes['selectorStyle'] ?? '', array( 'select', 'pills', 'segmented' ), true ) ? $attributes['selectorStyle'] : 'segmented';
	$show_flags = ! array_key_exists( 'showFlags', $attributes ) || ! empty( $attributes['showFlags'] );
	$payload = wp_json_encode( array( 'markets' => $active, 'plans' => $plans ), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT );
	$wrapper = function_exists( 'get_block_wrapper_attributes' ) ? get_block_wrapper_attributes( array( 'class' => 'sci-market-pricing', 'data-sci-market-pricing' => '', 'data-default-market' => $default, 'data-pricing' => $payload ?: '{}' ) ) : 'class="wp-block-sci-design-blocks-market-pricing sci-market-pricing" data-sci-market-pricing data-default-market="' . esc_attr( $default ) . '" data-pricing="' . esc_attr( $payload ?: '{}' ) . '"';
	$label = esc_html__( 'Moneda o mercado', 'sci-design-blocks' );
	$html = '<div ' . $wrapper . '><div class="sci-market-pricing__selector"><span class="sci-market-pricing__label">' . $label . '</span>';
	if ( 'select' === $selector_style ) {
		$html .= '<select data-sci-market-control aria-label="' . esc_attr( $label ) . '">';
		foreach ( $active as $market ) {
			$caption = $show_flags && '' !== $market['flag'] ? $market['flag'] . ' ' : '';
			$caption .= trim( $market['name'] . ( $market['name'] && $market['currency'] ? ' · ' : '' ) . $market['currency'] );
			$html .= '<option value="' . esc_attr( $market['id'] ) . '"' . selected( $default, $market['id'], false ) . '>' . esc_html( $caption ) . '</option>';
		}
		$html .= '</select>';
	} else {
		$instance = wp_unique_id( 'sci-market-' );
		$html .= '<fieldset class="sci-market-pricing__options sci-market-pricing__options--' . esc_attr( $selector_style ) . '"><legend class="screen-reader-text">' . $label . '</legend>';
		foreach ( $active as $market ) {
			$caption = trim( $market['name'] . ( $market['name'] && $market['currency'] ? ' · ' : '' ) . $market['currency'] );
			$html .= '<label class="sci-market-pricing__option"><input type="radio" name="' . esc_attr( $instance ) . '" value="' . esc_attr( $market['id'] ) . '" data-sci-market-control ' . checked( $default, $market['id'], false ) . '><span class="sci-market-pricing__option-content">';
			if ( $show_flags && '' !== $market['flag'] ) { $html .= '<span class="sci-market-pricing__flag" aria-hidden="true">' . esc_html( $market['flag'] ) . '</span>'; }
			$html .= '<span>' . esc_html( $caption ) . '</span></span></label>';
		}
		$html .= '</fieldset>';
	}
	$html .= '</div>' . $content . '</div>';
	return $html;
}
