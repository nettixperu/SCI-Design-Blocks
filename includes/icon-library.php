<?php
/**
 * WordPress Core Icon API registration for the curated SCI collection.
 *
 * @package SCI\DesignBlocks
 */

namespace SCI\DesignBlocks;

if ( ! function_exists( 'wp_register_icon_collection' ) || ! function_exists( 'wp_register_icon' ) ) {
	return;
}

add_action( 'init', __NAMESPACE__ . '\\register_icon_library', 20 );

/**
 * Register the SCI icon collection and its static SVG source files.
 *
 * @return void
 */
function register_icon_library(): void {
	$icons = require __DIR__ . '/../icons/manifest.php';

	if ( ! is_array( $icons ) || empty( $icons ) ) {
		return;
	}

	$collection = 'sci-design-blocks';
	if ( ! wp_register_icon_collection(
		$collection,
		array(
			'label'       => __( 'SCI', 'sci-design-blocks' ),
			'description' => __( 'Icons provided by SCI Design Blocks.', 'sci-design-blocks' ),
		)
	) ) {
		return;
	}

	foreach ( $icons as $icon ) {
		$name = $icon['local_name'] ?? '';
		if ( ! is_string( $name ) || ! preg_match( '/^[a-z0-9](?:[a-z0-9_-]*[a-z0-9])?$/', $name ) ) {
			continue;
		}

		$file_path = __DIR__ . '/../icons/svg/' . $name . '.svg';
		if ( ! is_readable( $file_path ) || empty( $icon['label'] ) || ! is_string( $icon['label'] ) ) {
			continue;
		}

		wp_register_icon(
			$collection . '/' . $name,
			array(
				'label'     => $icon['label'],
				'file_path' => $file_path,
			)
		);
	}
}
