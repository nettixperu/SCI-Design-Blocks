<?php
/**
 * Keep Twenty Twenty-Five's locally hosted Manrope visible while it loads.
 *
 * @package SCI\DesignBlocks
 */

namespace SCI\DesignBlocks;

add_action( 'after_setup_theme', __NAMESPACE__ . '\register_manrope_font_display_filter', 20 );

/**
 * Register the theme.json filter only when Twenty Twenty-Five is the parent theme.
 *
 * @return void
 */
function register_manrope_font_display_filter() {
	if ( 'twentytwentyfive' !== get_template() || ! function_exists( 'wp_theme_has_theme_json' ) || ! wp_theme_has_theme_json() ) {
		return;
	}

	add_filter( 'wp_theme_json_data_theme', __NAMESPACE__ . '\set_manrope_font_display' );
}

/**
 * Set font-display: swap on the theme's Manrope font faces.
 *
 * WordPress generates @font-face from theme.json and defaults font-display to
 * fallback when it is omitted. Updating the theme data keeps that generation
 * path intact and leaves the local font file and all typography settings alone.
 *
 * @param object $theme_json Theme JSON data object.
 * @return object
 */
function set_manrope_font_display( $theme_json ) {
	if ( ! is_object( $theme_json ) || ! method_exists( $theme_json, 'get_data' ) || ! method_exists( $theme_json, 'update_with' ) ) {
		return $theme_json;
	}

	$data = $theme_json->get_data();
	if ( ! is_array( $data ) || empty( $data['settings']['typography']['fontFamilies'] ) || ! is_array( $data['settings']['typography']['fontFamilies'] ) ) {
		return $theme_json;
	}

	$changed = false;
	foreach ( $data['settings']['typography']['fontFamilies'] as &$font_family ) {
		if ( 'manrope' !== ( $font_family['slug'] ?? '' ) ) {
			continue;
		}

		if ( empty( $font_family['fontFace'] ) || ! is_array( $font_family['fontFace'] ) ) {
			continue;
		}

		foreach ( $font_family['fontFace'] as &$font_face ) {
			if ( ! is_array( $font_face ) || 'swap' === ( $font_face['fontDisplay'] ?? '' ) ) {
				continue;
			}

			$font_face['fontDisplay'] = 'swap';
			$changed                 = true;
		}
		unset( $font_face );
	}
	unset( $font_family );

	return $changed ? $theme_json->update_with( $data ) : $theme_json;
}
