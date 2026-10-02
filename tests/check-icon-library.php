<?php
/**
 * Lightweight static registration smoke test. WordPress is represented by API stubs.
 * Run: php tests/check-icon-library.php
 */

$GLOBALS['sci_test_hooks']       = array();
$GLOBALS['sci_test_patterns']     = array();
$GLOBALS['sci_test_categories']   = array();
$GLOBALS['sci_test_collections']  = array();
$GLOBALS['sci_test_icons']        = array();
$GLOBALS['sci_test_block_styles'] = array();
$GLOBALS['sci_test_stylesheets']  = array();
$GLOBALS['sci_test_translations'] = array();

function add_action( $hook, $callback, $priority = 10 ) {
	$GLOBALS['sci_test_hooks'][ $hook ][ $priority ][] = $callback;
}

function __( $text, $domain = null ) {
	$GLOBALS['sci_test_translations'][] = array( $text, $domain );
	return $text;
}

function esc_html__( $text, $domain = null ) {
	return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
}

function register_block_pattern_category( $slug, $args ) {
	$GLOBALS['sci_test_categories'][ $slug ] = $args;
}

function register_block_pattern( $name, $args ) {
	$GLOBALS['sci_test_patterns'][ $name ] = $args;
}

function plugins_url( $path, $plugin = '' ) {
	return 'plugin-assets/' . ltrim( $path, '/' );
}

function wp_register_style( $handle, $src, $deps = array(), $ver = false, $media = 'all' ) {
	$GLOBALS['sci_test_stylesheets'][ $handle ] = compact( 'src', 'deps', 'ver', 'media' );
	return true;
}

function register_block_style( $block_name, $properties ) {
	$GLOBALS['sci_test_block_styles'][ $block_name ][ $properties['name'] ] = $properties;
	return true;
}

function wp_register_icon_collection( $slug, $args ) {
	$GLOBALS['sci_test_collections'][ $slug ] = $args;
	return true;
}

function wp_register_icon( $name, $args ) {
	if ( ! is_readable( $args['file_path'] ) ) {
		throw new RuntimeException( 'Unreadable SVG path for ' . $name );
	}
	if ( isset( $GLOBALS['sci_test_icons'][ $name ] ) ) {
		return false;
	}
	$GLOBALS['sci_test_icons'][ $name ] = $args;
	return true;
}

$plugin_file = dirname( __DIR__ ) . '/sci-design-blocks.php';
$plugin      = file_get_contents( $plugin_file );
if ( false === $plugin || ! preg_match( '/^ \* Version: 1\.1\.0$/m', $plugin ) || ! preg_match( '/^ \* Requires at least: 7\.1$/m', $plugin ) || ! preg_match( '/^ \* Requires PHP: 7\.4$/m', $plugin ) ) {
	throw new RuntimeException( 'Plugin release version or platform minimum is incorrect.' );
}

require $plugin_file;
ksort( $GLOBALS['sci_test_hooks']['init'] );
foreach ( $GLOBALS['sci_test_hooks']['init'] as $callbacks ) {
	foreach ( $callbacks as $callback ) {
		call_user_func( $callback );
	}
}

$expected_patterns = array(
	'sci-design-blocks/feature-icon-vertical',
	'sci-design-blocks/feature-image-vertical',
	'sci-design-blocks/feature-icon-left',
	'sci-design-blocks/feature-image-left',
	'sci-design-blocks/cta',
	'sci-design-blocks/testimonial',
	'sci-design-blocks/stats',
	'sci-design-blocks/icon-list',
	'sci-design-blocks/accordion',
);
$manifest       = require dirname( __DIR__ ) . '/icons/manifest.php';
$expected_icons = array_map(
	static function ( $name ) {
		return 'sci-design-blocks/' . $name;
	},
	array_keys( $manifest )
);

$pattern_names = array_keys( $GLOBALS['sci_test_patterns'] );
$icon_names    = array_keys( $GLOBALS['sci_test_icons'] );
sort( $pattern_names );
sort( $expected_patterns );
sort( $icon_names );
sort( $expected_icons );

if ( 1 !== count( $GLOBALS['sci_test_categories'] ) || $pattern_names !== $expected_patterns ) {
	throw new RuntimeException( 'The original single category and nine patterns must remain registered.' );
}
if (
	1 !== count( $GLOBALS['sci_test_collections'] ) ||
	! isset( $GLOBALS['sci_test_collections']['sci-design-blocks'] ) ||
	'SCI' !== $GLOBALS['sci_test_collections']['sci-design-blocks']['label']
) {
	throw new RuntimeException( 'Expected exactly one SCI icon collection.' );
}
if ( $icon_names !== $expected_icons ) {
	throw new RuntimeException( 'Registered icon handles must match the unique manifest entries.' );
}

$accordion_styles = $GLOBALS['sci_test_block_styles']['core/accordion'] ?? array();
$style_names      = array_keys( $accordion_styles );
sort( $style_names );
if ( array( 'sci-bordered', 'sci-minimal' ) !== $style_names ) {
	throw new RuntimeException( 'Expected exactly the opt-in Minimal and Bordered Core Accordion styles.' );
}
if (
	2 !== count( $GLOBALS['sci_test_stylesheets'] ) ||
	! isset( $GLOBALS['sci_test_stylesheets']['sci-design-blocks-accordion-styles'] ) ||
	! isset( $GLOBALS['sci_test_stylesheets']['sci-design-blocks-tabs-styles'] )
) {
	throw new RuntimeException( 'Expected one registered stylesheet handle for Accordion and one for Tabs.' );
}
foreach ( array( 'sci-minimal' => 'Minimal', 'sci-bordered' => 'Bordered' ) as $name => $label ) {
	$style = $accordion_styles[ $name ];
	if ( $label !== $style['label'] || 'sci-design-blocks-accordion-styles' !== $style['style_handle'] || isset( $style['is_default'] ) ) {
		throw new RuntimeException( 'Accordion style label/asset is invalid or changes the default style.' );
	}
	if ( ! in_array( array( $label, 'sci-design-blocks' ), $GLOBALS['sci_test_translations'], true ) ) {
		throw new RuntimeException( 'Accordion style labels must use the plugin text domain.' );
	}
}
if ( 'plugin-assets/assets/css/accordion-styles.css' !== $GLOBALS['sci_test_stylesheets']['sci-design-blocks-accordion-styles']['src'] ) {
	throw new RuntimeException( 'Accordion style handle must point to the local SCI stylesheet.' );
}

$tabs_styles = $GLOBALS['sci_test_block_styles']['core/tabs'] ?? array();
$tabs_names  = array_keys( $tabs_styles );
sort( $tabs_names );
if ( array( 'sci-pills', 'sci-underline' ) !== $tabs_names ) {
	throw new RuntimeException( 'Expected exactly the opt-in Underline and Pills Core Tabs styles.' );
}
if ( 'plugin-assets/assets/css/tabs-styles.css' !== $GLOBALS['sci_test_stylesheets']['sci-design-blocks-tabs-styles']['src'] ) {
	throw new RuntimeException( 'Tabs style handle must point to the local SCI stylesheet.' );
}
foreach ( array( 'sci-underline' => 'Underline', 'sci-pills' => 'Pills' ) as $name => $label ) {
	$style = $tabs_styles[ $name ];
	if ( $label !== $style['label'] || 'sci-design-blocks-tabs-styles' !== $style['style_handle'] || isset( $style['is_default'] ) ) {
		throw new RuntimeException( 'Tabs style label/asset is invalid or changes the default style.' );
	}
	if ( ! in_array( array( $label, 'sci-design-blocks' ), $GLOBALS['sci_test_translations'], true ) ) {
		throw new RuntimeException( 'Tabs style labels must use the plugin text domain.' );
	}
}

$accordion_pattern = $GLOBALS['sci_test_patterns']['sci-design-blocks/accordion']['content'] ?? '';
if ( false === strpos( $accordion_pattern, '<!-- wp:accordion {"autoclose":true} -->' ) || false !== strpos( $accordion_pattern, 'is-style-sci-' ) ) {
	throw new RuntimeException( 'The original SCI Accordion pattern must keep Core markup and autoclose unchanged.' );
}

if ( 83 !== count( $manifest ) ) {
	throw new RuntimeException( 'Expected exactly 83 curated manifest entries.' );
}
$categories = array(
	'Infrastructure & Cloud',
	'Security',
	'Web & Communication',
	'Files & Backup',
	'Business & SMEs',
	'Sales & Marketing',
	'People & Productivity',
	'Community & Events',
	'Photography & Multimedia',
);
$required_fields = array( 'local_name', 'label', 'source', 'source_version', 'source_name', 'conceptual_category', 'modified' );
foreach ( $manifest as $key => $entry ) {
	if ( $key !== $entry['local_name'] ) {
		throw new RuntimeException( 'Manifest key and local_name differ for ' . $key );
	}
	foreach ( $required_fields as $field ) {
		if ( ! array_key_exists( $field, $entry ) ) {
			throw new RuntimeException( 'Missing manifest field ' . $field . ' for ' . $key );
		}
	}
	if ( 'Bootstrap Icons' !== $entry['source'] || 'v1.13.1' !== $entry['source_version'] || false !== $entry['modified'] ) {
		throw new RuntimeException( 'Unexpected source provenance for ' . $key );
	}
	if ( ! in_array( $entry['conceptual_category'], $categories, true ) || '' === $entry['label'] ) {
		throw new RuntimeException( 'Invalid category or label for ' . $key );
	}
}

$notice = file_get_contents( dirname( __DIR__ ) . '/THIRD-PARTY-NOTICES.md' );
if ( false === $notice || false === strpos( $notice, 'Bootstrap Icons' ) || false === strpos( $notice, 'Version: v1.13.1' ) || false === strpos( $notice, 'THE SOFTWARE IS PROVIDED "AS IS"' ) ) {
	throw new RuntimeException( 'Bootstrap MIT third-party notice is missing or incomplete.' );
}

fwrite( STDOUT, "Static registration checks PASS: WordPress 7.1, one SCI collection, 83 icons, two Core Accordion styles, two opt-in Core Tabs styles, nine unchanged patterns, provenance and MIT notice.\n" );
