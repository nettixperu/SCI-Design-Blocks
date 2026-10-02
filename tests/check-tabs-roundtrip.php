<?php
/**
 * Round-trip representative Core Tabs markup through the WordPress block parser.
 * Run: php tests/check-tabs-roundtrip.php /path/to/wordpress
 */

$wordpress_root = isset( $argv[1] ) ? rtrim( $argv[1], '/' ) : '';
$core_dir       = $wordpress_root . '/wp-includes/';
if ( '' === $wordpress_root || ! is_readable( $core_dir . 'version.php' ) ) {
	fwrite( STDERR, "Pass a WordPress installation path as the first argument.\n" );
	exit( 2 );
}

require $core_dir . 'version.php';
if ( version_compare( $wp_version, '7.1', '<' ) ) {
	fwrite( STDERR, "WordPress 7.1 or newer is required; found {$wp_version}.\n" );
	exit( 2 );
}

require $core_dir . 'class-wp-block-parser-block.php';
require $core_dir . 'class-wp-block-parser-frame.php';
require $core_dir . 'class-wp-block-parser.php';

function wp_json_encode( $value, $flags = 0, $depth = 512 ) {
	return json_encode( $value, $flags, $depth );
}

function apply_filters( $hook, $value ) {
	return $value;
}

require $core_dir . 'blocks.php';

/**
 * Build a compact fixture with Core block comment serialization.
 *
 * @param string $style SCI Core Block Style slug.
 * @return string
 */
function sci_test_tabs_fixture( $style ) {
	return '<!-- wp:tabs {"className":"is-style-' . $style . '"} -->' .
		'<div class="wp-block-tabs is-style-' . $style . '">' .
		'<!-- wp:tab-list --><div class="wp-block-tab-list"><button>Overview</button><button>Services</button><button>Security and Compliance</button></div><!-- /wp:tab-list -->' .
		'<!-- wp:tab-panels --><div class="wp-block-tab-panels">' .
		'<!-- wp:tab-panel {"label":"Overview"} --><div class="wp-block-tab-panel"><!-- wp:paragraph --><p>Overview text.</p><!-- /wp:paragraph --></div><!-- /wp:tab-panel -->' .
		'<!-- wp:tab-panel {"label":"Services"} --><div class="wp-block-tab-panel"><!-- wp:heading --><h2 class="wp-block-heading">Services</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Service details.</p><!-- /wp:paragraph --></div><!-- /wp:tab-panel -->' .
		'<!-- wp:tab-panel {"label":"Security and Compliance"} --><div class="wp-block-tab-panel"><!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Secure by design</li><!-- /wp:list-item --></ul><!-- /wp:list --><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Contact</a></div><!-- /wp:button --></div><!-- /wp:buttons --><!-- wp:group --><div class="wp-block-group"><!-- wp:paragraph --><p>Nested content.</p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:columns --><div class="wp-block-columns"><!-- wp:column --><div class="wp-block-column"><!-- wp:paragraph --><p>Column content.</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --><!-- wp:image --><figure class="wp-block-image"><img src="https://example.invalid/photo.jpg" alt="" /></figure><!-- /wp:image --></div><!-- /wp:tab-panel -->' .
		'</div><!-- /wp:tab-panels --></div><!-- /wp:tabs -->';
}

/**
 * Collect block names recursively for structural assertions.
 *
 * @param array $blocks Parsed blocks.
 * @return array
 */
function sci_test_collect_block_names( $blocks ) {
	$names = array();
	foreach ( $blocks as $block ) {
		$names[] = $block['blockName'];
		$names   = array_merge( $names, sci_test_collect_block_names( $block['innerBlocks'] ) );
	}
	return $names;
}

foreach ( array( 'sci-underline', 'sci-pills' ) as $style ) {
	$source     = sci_test_tabs_fixture( $style );
	$first      = parse_blocks( $source );
	$serialized = serialize_blocks( $first );
	$second     = parse_blocks( $serialized );

	if ( serialize_blocks( $second ) !== $serialized ) {
		throw new RuntimeException( "Unstable parse/serialize round-trip for {$style}." );
	}
	if ( 1 !== count( $first ) || 'core/tabs' !== $first[0]['blockName'] || 'is-style-' . $style !== ( $first[0]['attrs']['className'] ?? '' ) ) {
		throw new RuntimeException( "Core Tabs root or style class mismatch for {$style}." );
	}

	$names = sci_test_collect_block_names( $second );
	foreach ( array( 'core/tab-list', 'core/tab-panels', 'core/tab-panel', 'core/paragraph', 'core/heading', 'core/list', 'core/list-item', 'core/buttons', 'core/button', 'core/group', 'core/columns', 'core/column', 'core/image' ) as $required ) {
		if ( ! in_array( $required, $names, true ) ) {
			throw new RuntimeException( "Missing nested {$required} block in {$style} fixture." );
		}
	}
	if ( 3 !== count( array_filter( $names, static fn( $name ) => 'core/tab-panel' === $name ) ) ) {
		throw new RuntimeException( "Expected three Core tab panels for {$style}." );
	}

	fwrite( STDOUT, "{$style} Core parser round-trip PASS (3 panels and nested Core content; WordPress {$wp_version}).\n" );
}
