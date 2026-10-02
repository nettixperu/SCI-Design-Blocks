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
	$style_handle = 'sci-design-blocks-accordion-styles';
	$plugin_file  = dirname( __DIR__ ) . '/sci-design-blocks.php';

	wp_register_style(
		$style_handle,
		plugins_url( 'assets/css/accordion-styles.css', $plugin_file ),
		array(),
		'1.1.0-dev'
	);

	register_block_style(
		'core/accordion',
		array(
			'name'         => 'sci-minimal',
			'label'        => __( 'Minimal', 'sci-design-blocks' ),
			'style_handle' => $style_handle,
		)
	);

	register_block_style(
		'core/accordion',
		array(
			'name'         => 'sci-bordered',
			'label'        => __( 'Bordered', 'sci-design-blocks' ),
			'style_handle' => $style_handle,
		)
	);
}
