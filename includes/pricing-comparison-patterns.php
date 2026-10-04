<?php
/**
 * Register the 1.4 pricing and comparison patterns from static Core markup.
 *
 * @package SCI\DesignBlocks
 */

namespace SCI\DesignBlocks;

/**
 * Register unsynced Pricing and Comparison patterns in the existing category.
 *
 * @return void
 */
function register_pricing_comparison_patterns(): void {
	$patterns = array(
		'sci-design-blocks/pricing-cards' => array(
			'title'       => __( 'SCI Pricing — Cards', 'sci-design-blocks' ),
			'description' => __( 'Three editable pricing plans using the responsive Core Grid layout.', 'sci-design-blocks' ),
			'file'        => 'sci-pricing-cards.html',
		),
		'sci-design-blocks/pricing-featured' => array(
			'title'       => __( 'SCI Pricing — Featured', 'sci-design-blocks' ),
			'description' => __( 'Three editable pricing plans with one emphasized Core card.', 'sci-design-blocks' ),
			'file'        => 'sci-pricing-featured.html',
		),
		'sci-design-blocks/pricing-compact' => array(
			'title'       => __( 'SCI Pricing — Compact', 'sci-design-blocks' ),
			'description' => __( 'A denser three-plan pricing composition using Core Grid.', 'sci-design-blocks' ),
			'file'        => 'sci-pricing-compact.html',
		),
		'sci-design-blocks/comparison-2-options' => array(
			'title'       => __( 'SCI Comparison — 2 Options', 'sci-design-blocks' ),
			'description' => __( 'Two Core cards with editable illustrative service-comparison copy.', 'sci-design-blocks' ),
			'file'        => 'sci-comparison-2-options.html',
		),
		'sci-design-blocks/comparison-feature-table' => array(
			'title'       => __( 'SCI Comparison — Feature Table', 'sci-design-blocks' ),
			'description' => __( 'An illustrative semantic comparison using the Core Table block.', 'sci-design-blocks' ),
			'file'        => 'sci-comparison-feature-table.html',
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
