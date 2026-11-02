<?php
/**
 * Lightweight per-button hover controls for the Core Button block.
 *
 * @package SCI\DesignBlocks
 */

namespace SCI\DesignBlocks;

add_filter( 'register_block_type_args', __NAMESPACE__ . '\\register_button_hover_attributes', 10, 2 );
add_filter( 'render_block_core/button', __NAMESPACE__ . '\\render_button_hover_styles', 10, 2 );
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_button_hover_editor_script' );
add_action( 'init', __NAMESPACE__ . '\\register_button_hover_styles', 20 );

/**
 * Register editor attributes on the existing Core Button block.
 *
 * @param array  $args       Block registration arguments.
 * @param string $block_name Block name.
 * @return array
 */
function register_button_hover_attributes( array $args, string $block_name ): array {
	if ( 'core/button' !== $block_name ) {
		return $args;
	}

	$args['attributes'] = $args['attributes'] ?? array();
	$args['attributes'] = array_merge(
		$args['attributes'],
		array(
			'sciHoverEnabled'          => array( 'type' => 'boolean', 'default' => false ),
			'sciHoverBackgroundType'   => array( 'type' => 'string', 'default' => 'none' ),
			'sciHoverBackground'       => array( 'type' => 'string', 'default' => '' ),
			'sciHoverTextColor'        => array( 'type' => 'string', 'default' => '' ),
			'sciHoverBorderColor'      => array( 'type' => 'string', 'default' => '' ),
			'sciHoverShadow'           => array( 'type' => 'string', 'default' => 'keep' ),
			'sciHoverLift'             => array( 'type' => 'boolean', 'default' => false ),
		)
	);

	return $args;
}

/**
 * Register the small stylesheet and load it with Core Button blocks.
 *
 * @return void
 */
function register_button_hover_styles(): void {
	$plugin_file = dirname( __DIR__ ) . '/sci-design-blocks.php';
	$style_file  = dirname( __DIR__ ) . '/assets/css/button-styles.css';
	$handle      = 'sci-design-blocks-button-hover';
	$version     = is_readable( $style_file ) ? (string) filemtime( $style_file ) : '1.6.4';
	$style_url   = plugins_url( 'assets/css/button-styles.css', $plugin_file );

	wp_register_style( $handle, $style_url, array(), $version );

	wp_enqueue_block_style(
		'core/button',
		array(
			'handle' => $handle,
			'src'    => $style_url,
			'path'   => $style_file,
			'ver'    => $version,
		)
	);
}

/**
 * Add the selected hover settings to the rendered Core Button wrapper.
 *
 * @param string $content Rendered button markup.
 * @param array  $block   Parsed button block.
 * @return string
 */
function render_button_hover_styles( string $content, array $block ): string {
	$attributes = $block['attrs'] ?? array();
	if ( empty( $attributes['sciHoverEnabled'] ) || ! class_exists( '\\WP_HTML_Tag_Processor' ) ) {
		return $content;
	}

	$styles = array();
	$type   = $attributes['sciHoverBackgroundType'] ?? 'none';
	$value  = $attributes['sciHoverBackground'] ?? '';

	if ( 'color' === $type ) {
		$color = sanitize_hover_color( $value );
		if ( '' !== $color ) {
			$styles[] = '--sci-hover-background:' . $color;
		}
	} elseif ( 'gradient' === $type ) {
		$gradient = sanitize_hover_gradient( $value );
		if ( '' !== $gradient ) {
			$styles[] = '--sci-hover-background:' . $gradient;
		}
	}

	$text_color = sanitize_hover_color( $attributes['sciHoverTextColor'] ?? '' );
	if ( '' !== $text_color ) {
		$styles[] = '--sci-hover-text-color:' . $text_color;
	}

	$border_color = sanitize_hover_color( $attributes['sciHoverBorderColor'] ?? '' );
	if ( '' !== $border_color ) {
		$styles[] = '--sci-hover-border-color:' . $border_color;
	}

	$shadows = array(
		'none'   => 'none',
		'subtle' => '0 4px 12px rgba(0,0,0,.18)',
		'medium' => '0 8px 20px rgba(0,0,0,.22)',
	);
	$shadow  = $attributes['sciHoverShadow'] ?? 'keep';
	if ( isset( $shadows[ $shadow ] ) ) {
		$styles[] = '--sci-hover-shadow:' . $shadows[ $shadow ];
	}

	if ( ! empty( $attributes['sciHoverLift'] ) ) {
		$styles[] = '--sci-hover-lift:-2px';
	} else {
		$styles[] = '--sci-hover-lift:0px';
	}

	$processor = new \WP_HTML_Tag_Processor( $content );
	if ( ! $processor->next_tag( array( 'tag_name' => 'DIV' ) ) ) {
		return $content;
	}

	$processor->add_class( 'sci-hover-enabled' );
	if ( ! empty( $styles ) ) {
		$current_style = trim( (string) $processor->get_attribute( 'style' ) );
		$processor->set_attribute( 'style', rtrim( $current_style, ';' ) . ( '' !== $current_style ? ';' : '' ) . implode( ';', $styles ) . ';' );
	}

	return $processor->get_updated_html();
}

/**
 * Accept only simple CSS color values or WordPress color preset variables.
 *
 * @param mixed $value Requested color value.
 * @return string
 */
function sanitize_hover_color( $value ): string {
	if ( ! is_string( $value ) ) {
		return '';
	}

	$value = trim( $value );
	$valid = preg_match( '/\\A(?:#(?:[0-9a-f]{3}|[0-9a-f]{4}|[0-9a-f]{6}|[0-9a-f]{8})|rgba?\\([0-9\\s.,%+\\/-]+\\)|hsla?\\([0-9\\s.,%+\\/-]+\\)|var\\(--wp--preset--color--[a-z0-9-]+\\)|transparent|currentcolor|white|black)\\z/i', $value );

	return $valid ? $value : '';
}

/**
 * Accept CSS gradients from the native Gutenberg gradient picker.
 *
 * @param mixed $value Requested gradient value.
 * @return string
 */
function sanitize_hover_gradient( $value ): string {
	if ( ! is_string( $value ) ) {
		return '';
	}

	$value = trim( $value );
	if ( preg_match( '/\\Avar\\(--wp--preset--gradient--[a-z0-9-]+\\)\\z/i', $value ) ) {
		return $value;
	}

	if ( preg_match( '/\\A(?:linear|radial|conic)-gradient\\([a-z0-9\\s#.,%()+\\/-]+\\)\\z/i', $value ) && ! preg_match( '/url|expression|javascript/i', $value ) ) {
		return $value;
	}

	return '';
}

/**
 * Load the editor controls for Core Buttons only.
 *
 * @return void
 */
function enqueue_button_hover_editor_script(): void {
	$plugin_file = dirname( __DIR__ ) . '/sci-design-blocks.php';
	$script_file = dirname( __DIR__ ) . '/assets/js-button-hover-editor.js';
	$version     = is_readable( $script_file ) ? (string) filemtime( $script_file ) : '1.6.4';

	wp_enqueue_script(
		'sci-design-blocks-button-hover-editor',
		plugins_url( 'assets/js-button-hover-editor.js', $plugin_file ),
		array( 'wp-block-editor', 'wp-components', 'wp-compose', 'wp-data', 'wp-element', 'wp-hooks' ),
		$version,
		true
	);
}
