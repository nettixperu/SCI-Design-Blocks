<?php
/**
 * Register static Core-only commercial composition patterns.
 *
 * @package SCI\DesignBlocks
 */

namespace SCI\DesignBlocks;

/** Register the M7 section, service, pricing and CTA patterns. */
function register_commercial_patterns(): void {
	$patterns = array(
		'sci-design-blocks/section-intro' => array(
			'title'       => __( 'SCI Section — Intro', 'sci-design-blocks' ),
			'description' => __( 'Centered static introduction for a page section using editable Core text blocks.', 'sci-design-blocks' ),
			'file'        => 'sci-section-intro.html',
		),
		'sci-design-blocks/benefits-grid' => array(
			'title'       => __( 'SCI Benefits — Grid', 'sci-design-blocks' ),
			'description' => __( 'A section introduction and three editable benefit cards in a responsive Core Grid.', 'sci-design-blocks' ),
			'file'        => 'sci-benefits-grid.html',
		),
		'sci-design-blocks/service-cards' => array(
			'title'       => __( 'SCI Services — Image Cards', 'sci-design-blocks' ),
			'description' => __( 'Three image-led service cards with editable descriptions and example links.', 'sci-design-blocks' ),
			'file'        => 'sci-service-cards.html',
		),
		'sci-design-blocks/pricing-split' => array(
			'title'       => __( 'SCI Pricing — Split Offer', 'sci-design-blocks' ),
			'description' => __( 'One editable offer beside a supporting feature explanation and list.', 'sci-design-blocks' ),
			'file'        => 'sci-pricing-split.html',
		),
		'sci-design-blocks/cta-horizontal' => array(
			'title'       => __( 'SCI CTA — Horizontal', 'sci-design-blocks' ),
			'description' => __( 'A compact message and action composition that stacks on mobile.', 'sci-design-blocks' ),
			'file'        => 'sci-cta-horizontal.html',
		),
	);

	foreach ( $patterns as $name => $pattern ) {
		$content = file_get_contents( dirname( __DIR__ ) . '/patterns/' . $pattern['file'] );
		if ( false === $content ) {
			continue;
		}

		register_block_pattern(
			$name,
			array(
				'title'       => $pattern['title'],
				'description' => $pattern['description'],
				'categories'  => array( 'sci-design-blocks' ),
				'content'     => $content,
			)
		);
	}
}
