<?php
/**
 * Static registration, Core serialization, and scalable-card checks for 1.4 patterns.
 * Run: php tests/check-pricing-comparison-patterns.php /path/to/wordpress
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
function apply_filters( $hook, $value, ...$args ) {
	return $value;
}
require $core_dir . 'blocks.php';

$GLOBALS['sci_test_hooks']      = array();
$GLOBALS['sci_test_patterns']   = array();
$GLOBALS['sci_test_categories'] = array();
$GLOBALS['sci_test_icons']      = array();
$GLOBALS['sci_test_collections'] = array();
$GLOBALS['sci_test_styles']     = array();
function add_action( $hook, $callback, $priority = 10 ) {
	$GLOBALS['sci_test_hooks'][ $hook ][ $priority ][] = $callback;
}
function __( $text, $domain = null ) { return $text; }
function esc_html__( $text, $domain = null ) { return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' ); }
function register_block_pattern_category( $slug, $args ) { $GLOBALS['sci_test_categories'][ $slug ] = $args; }
function register_block_pattern( $name, $args ) { $GLOBALS['sci_test_patterns'][ $name ] = $args; }
function plugins_url( $path, $plugin = '' ) { return 'plugin-assets/' . ltrim( $path, '/' ); }
function wp_register_style( $handle, $src, $deps = array(), $ver = false, $media = 'all' ) {
	$GLOBALS['sci_test_styles'][ $handle ] = compact( 'src', 'deps', 'ver', 'media' );
	return true;
}
function wp_register_icon_collection( $slug, $args ) { $GLOBALS['sci_test_collections'][ $slug ] = $args; return true; }
function wp_register_icon( $name, $args ) { $GLOBALS['sci_test_icons'][ $name ] = $args; return true; }

$plugin_root = isset( $argv[2] ) ? rtrim( $argv[2], '/' ) : dirname( __DIR__ );
$plugin_file = $plugin_root . '/sci-design-blocks.php';
require $plugin_file;
ksort( $GLOBALS['sci_test_hooks']['init'] );
foreach ( $GLOBALS['sci_test_hooks']['init'] as $callbacks ) {
	foreach ( $callbacks as $callback ) {
		if ( is_string( $callback ) && in_array( $callback, array( 'SCI\\DesignBlocks\\register_feature_patterns', 'SCI\\DesignBlocks\\register_editorial_query_patterns', 'SCI\\DesignBlocks\\register_pricing_comparison_patterns' ), true ) ) {
			call_user_func( $callback );
		}
	}
}

$expected = array(
	'sci-design-blocks/pricing-cards',
	'sci-design-blocks/pricing-featured',
	'sci-design-blocks/pricing-compact',
	'sci-design-blocks/comparison-2-options',
	'sci-design-blocks/comparison-feature-table',
);
$expected_titles = array(
	'sci-design-blocks/pricing-cards' => 'SCI Pricing — Cards',
	'sci-design-blocks/pricing-featured' => 'SCI Pricing — Featured',
	'sci-design-blocks/pricing-compact' => 'SCI Pricing — Compact',
	'sci-design-blocks/comparison-2-options' => 'SCI Comparison — 2 Options',
	'sci-design-blocks/comparison-feature-table' => 'SCI Comparison — Feature Table',
);
foreach ( $expected as $name ) {
	if ( empty( $GLOBALS['sci_test_patterns'][ $name ]['content'] ) ) {
		throw new RuntimeException( "Missing 1.4 pattern {$name}." );
	}
	if ( $expected_titles[ $name ] !== $GLOBALS['sci_test_patterns'][ $name ]['title'] ) {
		throw new RuntimeException( "Incorrect display title for {$name}." );
	}
}

if ( 20 !== count( $GLOBALS['sci_test_patterns'] ) || 1 !== count( $GLOBALS['sci_test_categories'] ) ) {
	throw new RuntimeException( 'Expected 20 total patterns registered under the single existing category.' );
}

function sci_test_names( $blocks ) {
	$names = array();
	foreach ( $blocks as $block ) {
		if ( null !== $block['blockName'] ) {
			$names[] = $block['blockName'];
			if ( 0 !== strpos( $block['blockName'], 'core/' ) ) {
				throw new RuntimeException( 'Non-Core block found: ' . $block['blockName'] );
			}
		}
		$names = array_merge( $names, sci_test_names( $block['innerBlocks'] ) );
	}
	return $names;
}
function sci_test_list_counts( $blocks ) {
	$counts = array();
	foreach ( $blocks as $block ) {
		if ( 'core/group' === $block['blockName'] ) {
			$list_count = 0;
			foreach ( $block['innerBlocks'] as $child ) {
				if ( 'core/list' === $child['blockName'] ) {
					foreach ( $child['innerBlocks'] as $list_item ) {
						if ( 'core/list-item' === $list_item['blockName'] ) {
							++$list_count;
						}
					}
				}
			}
			if ( $list_count > 0 ) {
				$counts[] = $list_count;
			}
		}
	}
	return $counts;
}

$parser = new WP_Block_Parser();
$contents = array();
foreach ( $expected as $name ) {
	$contents[ $name ] = $GLOBALS['sci_test_patterns'][ $name ]['content'];
	$first             = $parser->parse( $contents[ $name ] );
	$serialized        = serialize_blocks( $first );
	$second            = $parser->parse( $serialized );
	if ( serialize_blocks( $second ) !== $serialized ) {
		throw new RuntimeException( "Unstable Core parse/serialize round-trip: {$name}." );
	}
	sci_test_names( $second );
	if ( false !== strpos( $contents[ $name ], '<script' ) || false !== strpos( $contents[ $name ], '<style' ) || false !== strpos( $contents[ $name ], 'http://' ) || false !== strpos( $contents[ $name ], 'https://' ) ) {
		throw new RuntimeException( "Executable/custom assets or remote references found in {$name}." );
	}
	if ( false !== stripos( $contents[ $name ], 'nettix' ) || false !== stripos( $contents[ $name ], 'sci webhosting' ) ) {
		throw new RuntimeException( "Product-specific copy found in {$name}." );
	}
}

$cards_name = 'sci-design-blocks/pricing-cards';
$cards      = $parser->parse( $contents[ $cards_name ] );
$grid       = $cards[0];
if (
	'core/group' !== $grid['blockName'] ||
	'grid' !== ( $grid['attrs']['layout']['type'] ?? '' ) ||
	'18rem' !== ( $grid['attrs']['layout']['minimumColumnWidth'] ?? '' ) ||
	true !== ( $grid['attrs']['layout']['autoFit'] ?? false ) ||
	3 !== count( $grid['innerBlocks'] )
) {
	throw new RuntimeException( 'Pricing Cards must default to three cards within the native responsive Core Grid layout.' );
}
foreach ( array( 2, 3, 4, 5, 6 ) as $target_count ) {
	$scaled = $grid;
	$children = $scaled['innerBlocks'];
	while ( count( $children ) > $target_count ) {
		array_pop( $children );
	}
	while ( count( $children ) < $target_count ) {
		$children[] = $children[ count( $children ) - 1 ];
	}
	$scaled['innerBlocks'] = $children;
	$inner_content = array( $grid['innerContent'][0] );
	for ( $index = 0; $index < $target_count; ++$index ) {
		$inner_content[] = null;
		if ( $index < $target_count - 1 ) {
			$inner_content[] = $grid['innerContent'][2];
		}
	}
	$inner_content[]        = $grid['innerContent'][ count( $grid['innerContent'] ) - 1 ];
	$scaled['innerContent'] = $inner_content;
	$markup = serialize_blocks( array( $scaled ) );
	$roundtrip = $parser->parse( $markup );
	if ( count( $roundtrip[0]['innerBlocks'] ) !== $target_count || serialize_blocks( $roundtrip ) !== $markup ) {
		throw new RuntimeException( "Core Grid duplicate/remove serialization failed at {$target_count} plans." );
	}
	printf( "Pricing Cards scalability serialization PASS: %d card Groups (static Core fixture).\n", $target_count );
}

$featured = $parser->parse( $contents['sci-design-blocks/pricing-featured'] );
if (
	3 !== count( $featured[0]['innerBlocks'] ) ||
	array( 4, 7, 5 ) !== sci_test_list_counts( $featured[0]['innerBlocks'] ) ||
	false === strpos( $contents['sci-design-blocks/pricing-featured'], 'Implementación: S/ 800 pago único' ) ||
	false === strpos( $contents['sci-design-blocks/pricing-featured'], 'Más solicitado' )
) {
	throw new RuntimeException( 'Pricing Featured fixture lost its three cards, unequal lists, badge, or setup-price copy.' );
}
if ( false !== strpos( $contents['sci-design-blocks/pricing-featured'], 'wp:spacer' ) ) {
	throw new RuntimeException( 'Pricing Featured must not use Spacer alignment hacks.' );
}

$compact = $parser->parse( $contents['sci-design-blocks/pricing-compact'] );
if ( 3 !== count( $compact[0]['innerBlocks'] ) || '14rem' !== ( $compact[0]['attrs']['layout']['minimumColumnWidth'] ?? '' ) ) {
	throw new RuntimeException( 'Pricing Compact must insert three cards using its denser Core Grid width.' );
}
$comparison = $parser->parse( $contents['sci-design-blocks/comparison-2-options'] );
if ( 'grid' !== ( $comparison[0]['attrs']['layout']['type'] ?? '' ) || 2 !== count( $comparison[0]['innerBlocks'] ) ) {
	throw new RuntimeException( 'Comparison 2 Options must be two Core card Groups in Grid.' );
}
$table = $parser->parse( $contents['sci-design-blocks/comparison-feature-table'] );
$table_block_names = sci_test_names( $table );
$table_content = serialize_blocks( $table );
if (
	1 !== count( array_filter( $table_block_names, static function ( $name ) { return 'core/table' === $name; } ) ) ||
	false === strpos( $table_content, 'caption' ) ||
	false === strpos( $table_content, 'scope="col"' ) ||
	false === strpos( $table_content, 'scope="row"' )
) {
	throw new RuntimeException( 'Comparison Feature Table must keep Core Table caption and semantic headers.' );
}

fwrite( STDOUT, "Core parser and registration checks PASS: 5 new patterns, 20 total, Core-only, WordPress {$wp_version}.\n" );
