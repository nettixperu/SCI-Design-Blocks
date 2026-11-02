<?php
/**
 * Opt-in visual styles for the existing WordPress Core Accordion block.
 *
 * @package SCI\DesignBlocks
 */

namespace SCI\DesignBlocks;

add_action( 'init', __NAMESPACE__ . '\\register_accordion_styles', 20 );

/**
 * Register SCI styles through the Core Block Styles API.
 *
 * @return void
 */
function register_accordion_styles(): void {
	$style_file = dirname( __DIR__ ) . '/assets/css/accordion-styles.css';
	$style_css  = is_readable( $style_file ) ? file_get_contents( $style_file ) : false;
	$minimal    = array(
		'name'  => 'sci-minimal',
		'label' => __( 'Minimal', 'sci-design-blocks' ),
	);

	if ( is_string( $style_css ) && '' !== trim( $style_css ) ) {
		$minimal['inline_style'] = $style_css;
	}

	register_block_style(
		'core/accordion',
		$minimal
	);

	register_block_style(
		'core/accordion',
		array(
			'name'  => 'sci-bordered',
			'label' => __( 'Bordered', 'sci-design-blocks' ),
		)
	);
}
