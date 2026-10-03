<?php
/**
 * Opt-in visual styles for the WordPress Core Tabs block.
 *
 * @package SCI\DesignBlocks
 */

namespace SCI\DesignBlocks;

add_action( 'init', __NAMESPACE__ . '\\register_tabs_styles', 20 );

/**
 * Register SCI styles through the Core Block Styles API.
 *
 * @return void
 */
function register_tabs_styles(): void {
	$style_handle  = 'sci-design-blocks-tabs-styles';
	$plugin_file   = dirname( __DIR__ ) . '/sci-design-blocks.php';
	$style_file    = dirname( __DIR__ ) . '/assets/css/tabs-styles.css';
	$style_version = file_exists( $style_file ) ? (string) filemtime( $style_file ) : '1.1.0';

	wp_register_style(
		$style_handle,
		plugins_url( 'assets/css/tabs-styles.css', $plugin_file ),
		array(),
		$style_version
	);

	register_block_style(
		'core/tabs',
		array(
			'name'         => 'sci-underline',
			'label'        => __( 'Underline', 'sci-design-blocks' ),
			'style_handle' => $style_handle,
		)
	);

	register_block_style(
		'core/tabs',
		array(
			'name'         => 'sci-pills',
			'label'        => __( 'Pills', 'sci-design-blocks' ),
			'style_handle' => $style_handle,
		)
	);

	register_block_style(
		'core/tabs',
		array(
			'name'         => 'sci-connected',
			'label'        => __( 'Connected', 'sci-design-blocks' ),
			'style_handle' => $style_handle,
		)
	);

	register_block_style(
		'core/tabs',
		array(
			'name'         => 'sci-filled',
			'label'        => __( 'Filled', 'sci-design-blocks' ),
			'style_handle' => $style_handle,
		)
	);
}
