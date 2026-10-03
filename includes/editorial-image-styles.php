<?php
/**
 * Opt-in editorial image styles for WordPress Core blocks.
 *
 * @package SCI\DesignBlocks
 */

namespace SCI\DesignBlocks;

add_action( 'init', __NAMESPACE__ . '\\register_editorial_image_styles', 20 );

/**
 * Register the optional Hover Zoom style for Core Featured Image blocks.
 *
 * @return void
 */
function register_editorial_image_styles(): void {
	$style_handle = 'sci-design-blocks-editorial-image-styles';
	$plugin_file  = dirname( __DIR__ ) . '/sci-design-blocks.php';
	$style_file   = dirname( __DIR__ ) . '/assets/css/editorial-image-styles.css';
	$version      = file_exists( $style_file ) ? (string) filemtime( $style_file ) : '1.3.0';

	wp_register_style(
		$style_handle,
		plugins_url( 'assets/css/editorial-image-styles.css', $plugin_file ),
		array(),
		$version
	);

	register_block_style(
		'core/post-featured-image',
		array(
			'name'         => 'sci-hover-zoom',
			'label'        => __( 'SCI — Hover Zoom', 'sci-design-blocks' ),
			'style_handle' => $style_handle,
		)
	);
}
