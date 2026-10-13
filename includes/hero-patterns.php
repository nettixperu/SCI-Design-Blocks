<?php
/**
 * Register the Core-only Hero patterns from static block markup.
 *
 * @package SCI\DesignBlocks
 */

namespace SCI\DesignBlocks;

/**
 * Register Hero Cover and Hero Split in the existing SCI pattern category.
 *
 * @return void
 */
function register_hero_patterns(): void {
	$patterns = array(
		'sci-design-blocks/hero-cover' => array(
			'title'       => __( 'SCI Hero — Cover', 'sci-design-blocks' ),
			'description' => __( 'Full-width Cover composition with editable image, overlay, heading, copy, and Core buttons.', 'sci-design-blocks' ),
			'file'        => 'sci-hero-cover.html',
		),
		'sci-design-blocks/hero-split' => array(
			'title'       => __( 'SCI Hero — Split', 'sci-design-blocks' ),
			'description' => __( 'Two-column text and image composition with editable Core content and buttons.', 'sci-design-blocks' ),
			'file'        => 'sci-hero-split.html',
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
