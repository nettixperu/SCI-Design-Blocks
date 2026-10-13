<?php
/**
 * Static registration and Core serialization checks for Pricing, Comparison, and Hero patterns.
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
function add_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ) {}
function wp_style_engine_get_stylesheet_from_css_rules( $css_rules, $options = array() ) {
	$GLOBALS['sci_test_layout_css_rules'] = $css_rules;
	return '';
}
class WP_Block_Supports {
	public static function get_instance() {
		return new self();
	}
	public function register( $block_support_name, $block_support_config ) {}
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
require $core_dir . 'block-supports/layout.php';

$plugin_root = isset( $argv[2] ) ? rtrim( $argv[2], '/' ) : dirname( __DIR__ );
$plugin_file = $plugin_root . '/sci-design-blocks.php';
require $plugin_file;
ksort( $GLOBALS['sci_test_hooks']['init'] );
foreach ( $GLOBALS['sci_test_hooks']['init'] as $callbacks ) {
	foreach ( $callbacks as $callback ) {
		if ( is_string( $callback ) && in_array( $callback, array( 'SCI\\DesignBlocks\\register_feature_patterns', 'SCI\\DesignBlocks\\register_editorial_query_patterns', 'SCI\\DesignBlocks\\register_pricing_comparison_patterns', 'SCI\\DesignBlocks\\register_hero_patterns', 'SCI\\DesignBlocks\\register_commercial_patterns' ), true ) ) {
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
	'sci-design-blocks/hero-cover',
	'sci-design-blocks/hero-split',
	'sci-design-blocks/section-intro',
	'sci-design-blocks/benefits-grid',
	'sci-design-blocks/service-cards',
	'sci-design-blocks/pricing-split',
	'sci-design-blocks/cta-horizontal',
);
$expected_titles = array(
	'sci-design-blocks/pricing-cards' => 'SCI Pricing — Cards',
	'sci-design-blocks/pricing-featured' => 'SCI Pricing — Featured',
	'sci-design-blocks/pricing-compact' => 'SCI Pricing — Compact',
	'sci-design-blocks/comparison-2-options' => 'SCI Comparison — 2 Options',
	'sci-design-blocks/comparison-feature-table' => 'SCI Comparison — Feature Table',
	'sci-design-blocks/hero-cover' => 'SCI Hero — Cover',
	'sci-design-blocks/hero-split' => 'SCI Hero — Split',
	'sci-design-blocks/section-intro' => 'SCI Section — Intro',
	'sci-design-blocks/benefits-grid' => 'SCI Benefits — Grid',
	'sci-design-blocks/service-cards' => 'SCI Services — Image Cards',
	'sci-design-blocks/pricing-split' => 'SCI Pricing — Split Offer',
	'sci-design-blocks/cta-horizontal' => 'SCI CTA — Horizontal',
);
foreach ( $expected as $name ) {
	if ( empty( $GLOBALS['sci_test_patterns'][ $name ]['content'] ) ) {
		throw new RuntimeException( "Missing 1.4 pattern {$name}." );
	}
	if ( $expected_titles[ $name ] !== $GLOBALS['sci_test_patterns'][ $name ]['title'] ) {
		throw new RuntimeException( "Incorrect display title for {$name}." );
	}
}

if ( 27 !== count( $GLOBALS['sci_test_patterns'] ) || 1 !== count( $GLOBALS['sci_test_categories'] ) ) {
	throw new RuntimeException( 'Expected 27 total patterns registered under the single existing category.' );
}

$allowed_self_closing_blocks = array(
	'core/icon',
	'core/post-date',
	'core/post-excerpt',
	'core/post-featured-image',
	'core/post-terms',
	'core/post-title',
);
$self_closing_count = 0;
foreach ( $GLOBALS['sci_test_patterns'] as $pattern_name => $pattern ) {
	if ( preg_match_all( '~<!--\s+wp:([a-z0-9-]+(?:/[a-z0-9-]+)?)(?:\s+\{[^>]*?\})?\s+/-->~', $pattern['content'], $matches ) ) {
		foreach ( $matches[1] as $block_name ) {
			if ( false === strpos( $block_name, '/' ) ) {
				$block_name = 'core/' . $block_name;
			}
			++$self_closing_count;
			if ( 'core/image' === $block_name || ! in_array( $block_name, $allowed_self_closing_blocks, true ) ) {
				throw new RuntimeException( "Unreviewed self-closing static block {$block_name} in {$pattern_name}." );
			}
		}
	}
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
function sci_test_find_block( $blocks, $name ) {
	foreach ( $blocks as $block ) {
		if ( $name === $block['blockName'] ) {
			return $block;
		}
		$found = sci_test_find_block( $block['innerBlocks'], $name );
		if ( null !== $found ) {
			return $found;
		}
	}
	return null;
}
function sci_test_find_grid( $blocks ) {
	foreach ( $blocks as $block ) {
		if ( 'core/group' === $block['blockName'] && 'grid' === ( $block['attrs']['layout']['type'] ?? '' ) ) {
			return $block;
		}
		$found = sci_test_find_grid( $block['innerBlocks'] );
		if ( null !== $found ) {
			return $found;
		}
	}
	return null;
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
$all_pattern_count = 0;
foreach ( $GLOBALS['sci_test_patterns'] as $pattern_name => $pattern ) {
	$parsed     = $parser->parse( $pattern['content'] );
	$serialized = serialize_blocks( $parsed );
	$reparsed   = $parser->parse( $serialized );
	if ( serialize_blocks( $reparsed ) !== $serialized ) {
		throw new RuntimeException( "Unstable Core parse/serialize round-trip in registered pattern {$pattern_name}." );
	}
	sci_test_names( $reparsed );
	++$all_pattern_count;
}
if ( 27 !== $all_pattern_count ) {
	throw new RuntimeException( 'Expected Core parser/serializer regression coverage for all 27 registered patterns.' );
}

// The permissive PHP parser can recover from unclosed block comments. Strictly
// check block-comment nesting in each M8 addition so malformed parent/child
// boundaries (for example, a second Column nested in the first) cannot pass.
foreach ( array( 'section-intro', 'benefits-grid', 'service-cards', 'pricing-split', 'cta-horizontal' ) as $slug ) {
	$content = $GLOBALS['sci_test_patterns'][ 'sci-design-blocks/' . $slug ]['content'];
	preg_match_all( '~<!--\s+(/)?wp:([a-z0-9-]+(?:/[a-z0-9-]+)?)(?:\s+\{.*?\})?\s*(/)?-->~s', $content, $comment_tokens, PREG_SET_ORDER );
	$open_blocks = array();
	foreach ( $comment_tokens as $token ) {
		$closing = ! empty( $token[1] );
		$self_closing = ! empty( $token[3] );
		$name = $token[2];
		if ( $closing ) {
			$expected_close = array_pop( $open_blocks );
			if ( $name !== $expected_close ) {
				throw new RuntimeException( "Mismatched or missing block boundary in {$slug}: closing {$name}, expected " . ( $expected_close ?? 'no open block' ) . '.' );
			}
		} elseif ( ! $self_closing ) {
			$open_blocks[] = $name;
		}
	}
	if ( ! empty( $open_blocks ) ) {
		throw new RuntimeException( "Unclosed block comment in {$slug}: " . end( $open_blocks ) . '.' );
	}
}

$new_pattern_expectations = array(
	'sci-design-blocks/section-intro' => array(
		'root' => 'core/group',
		'required' => array( 'core/heading', 'core/paragraph' ),
	),
	'sci-design-blocks/benefits-grid' => array(
		'root' => 'core/group',
		'required' => array( 'core/group', 'core/heading', 'core/paragraph' ),
	),
	'sci-design-blocks/service-cards' => array(
		'root' => 'core/group',
		'required' => array( 'core/group', 'core/image', 'core/heading', 'core/paragraph', 'core/buttons', 'core/button' ),
	),
	'sci-design-blocks/pricing-split' => array(
		'root' => 'core/group',
		'required' => array( 'core/columns', 'core/column', 'core/list', 'core/list-item', 'core/buttons', 'core/button' ),
	),
	'sci-design-blocks/cta-horizontal' => array(
		'root' => 'core/group',
		'required' => array( 'core/columns', 'core/column', 'core/heading', 'core/paragraph', 'core/buttons', 'core/button' ),
	),
);
foreach ( $new_pattern_expectations as $name => $expectation ) {
	$tree = $parser->parse( $GLOBALS['sci_test_patterns'][ $name ]['content'] );
	$top_level_blocks = array_values( array_filter( $tree, static function ( $block ) { return null !== $block['blockName']; } ) );
	if ( 1 !== count( $top_level_blocks ) || $expectation['root'] !== $top_level_blocks[0]['blockName'] ) {
		throw new RuntimeException( "Unexpected root block in {$name}." );
	}
	if ( in_array( $name, array( 'sci-design-blocks/section-intro', 'sci-design-blocks/benefits-grid', 'sci-design-blocks/service-cards', 'sci-design-blocks/pricing-split', 'sci-design-blocks/cta-horizontal' ), true ) && 'full' !== ( $top_level_blocks[0]['attrs']['align'] ?? '' ) ) {
		throw new RuntimeException( "M8 section {$name} must use the Core full-width alignment at its root." );
	}
	$names = sci_test_names( $tree );
	foreach ( $expectation['required'] as $required_block ) {
		if ( ! in_array( $required_block, $names, true ) ) {
			throw new RuntimeException( "Missing {$required_block} in {$name}." );
		}
	}
}
foreach ( array( 'benefits-grid', 'service-cards' ) as $grid_slug ) {
	$grid_content = $GLOBALS['sci_test_patterns'][ 'sci-design-blocks/' . $grid_slug ]['content'];
	if ( false === strpos( $grid_content, '"type":"grid"' ) || false === strpos( $grid_content, '"autoFit":true' ) ) {
		throw new RuntimeException( "{$grid_slug} must use the Core auto-fit Grid layout." );
	}
}
$benefits_grid = sci_test_find_grid( $parser->parse( $GLOBALS['sci_test_patterns']['sci-design-blocks/benefits-grid']['content'] ) );
if ( null === $benefits_grid || 'wide' !== ( $benefits_grid['attrs']['align'] ?? '' ) ) {
	throw new RuntimeException( 'Benefits Grid must use Core wide alignment inside the full-width section.' );
}
$service_content = $GLOBALS['sci_test_patterns']['sci-design-blocks/service-cards']['content'];
$service_tree = $parser->parse( $service_content );
$service_names = sci_test_names( $service_tree );
$service_images = array_filter( $service_names, static function ( $name ) { return 'core/image' === $name; } );
$valid_empty_image = '<!-- wp:image {"aspectRatio":"4/3","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->' . "\n" . '<figure class="wp-block-image size-large"><img alt="" style="aspect-ratio:4/3;object-fit:cover"/></figure>' . "\n" . '<!-- /wp:image -->';
if ( 3 !== count( $service_images ) || 3 !== substr_count( serialize_blocks( $service_tree ), $valid_empty_image ) ) {
	throw new RuntimeException( 'Service card images must use three editable, Gutenberg-compatible empty Image serializations.' );
}
$service_grid = sci_test_find_grid( $service_tree );
if ( null === $service_grid || 'wide' !== ( $service_grid['attrs']['align'] ?? '' ) || '16rem' !== ( $service_grid['attrs']['layout']['minimumColumnWidth'] ?? '' ) ) {
	throw new RuntimeException( 'Service card Grid must use Core wide alignment inside the full-width section.' );
}
foreach ( array( 'pricing-split', 'cta-horizontal' ) as $stack_slug ) {
	$stack_tree = $parser->parse( $GLOBALS['sci_test_patterns'][ 'sci-design-blocks/' . $stack_slug ]['content'] );
	$columns_block = sci_test_find_block( $stack_tree, 'core/columns' );
	if ( null === $columns_block || true !== ( $columns_block['attrs']['isStackedOnMobile'] ?? false ) ) {
		throw new RuntimeException( "Core Columns must stack on mobile in {$stack_slug}." );
	}
	if ( 'wide' !== ( $columns_block['attrs']['align'] ?? '' ) ) {
		throw new RuntimeException( "Core Columns in {$stack_slug} must use wide alignment inside the full-width section." );
	}
	$direct_columns = array_values( array_filter( $columns_block['innerBlocks'], static function ( $block ) { return 'core/column' === $block['blockName']; } ) );
	if ( 2 !== count( $direct_columns ) || count( $columns_block['innerBlocks'] ) !== count( $direct_columns ) ) {
		throw new RuntimeException( "{$stack_slug} must contain exactly two sibling Core Column blocks." );
	}
	foreach ( $direct_columns as $column ) {
		foreach ( $column['innerBlocks'] as $child ) {
			if ( 'core/column' === $child['blockName'] ) {
				throw new RuntimeException( "Nested Core Column found in {$stack_slug}." );
			}
		}
	}
}

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

$pricing_card_patterns = array(
	'sci-design-blocks/pricing-cards',
	'sci-design-blocks/pricing-featured',
	'sci-design-blocks/pricing-compact',
);
foreach ( $pricing_card_patterns as $pattern_name ) {
	$pattern_blocks = $parser->parse( $contents[ $pattern_name ] );
	if ( 3 !== count( $pattern_blocks[0]['innerBlocks'] ) ) {
		throw new RuntimeException( "{$pattern_name} must retain its three default Core card Groups." );
	}
	foreach ( $pattern_blocks[0]['innerBlocks'] as $card_index => $card ) {
		$layout = $card['attrs']['layout'] ?? array();
		if (
			'flex' !== ( $layout['type'] ?? '' ) ||
			'vertical' !== ( $layout['orientation'] ?? '' ) ||
			'space-between' !== ( $layout['verticalAlignment'] ?? '' ) ||
			isset( $layout['justifyContent'] )
		) {
			throw new RuntimeException( "{$pattern_name} card " . ( $card_index + 1 ) . ' must use the Core vertical Flex verticalAlignment attribute to distribute content.' );
		}
		wp_get_layout_style( '.sci-pricing-card', $layout );
		$declarations = array();
		foreach ( $GLOBALS['sci_test_layout_css_rules'] as $rule ) {
			$declarations = array_merge( $declarations, $rule['declarations'] );
		}
		if ( 'column' !== ( $declarations['flex-direction'] ?? '' ) || 'space-between' !== ( $declarations['justify-content'] ?? '' ) ) {
			throw new RuntimeException( "WordPress {$wp_version} Core must emit column Flex with justify-content:space-between for {$pattern_name} card " . ( $card_index + 1 ) . '.' );
		}
	}
}
fwrite( STDOUT, "Pricing CTA alignment contract PASS: Core 7.1 emits vertical Flex and justify-content:space-between for all nine pricing cards.\n" );

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

$cover_name    = 'sci-design-blocks/hero-cover';
$cover_content = $contents[ $cover_name ];
$cover_blocks  = array_values(
	array_filter(
		$parser->parse( $cover_content ),
		static function ( $block ) {
			return null !== $block['blockName'];
		}
	)
);
$cover_names   = sci_test_names( $cover_blocks );
if (
	1 !== count( $cover_blocks ) ||
	'core/cover' !== $cover_blocks[0]['blockName'] ||
	'full' !== ( $cover_blocks[0]['attrs']['align'] ?? '' ) ||
	'center left' !== ( $cover_blocks[0]['attrs']['contentPosition'] ?? '' ) ||
	620 !== ( $cover_blocks[0]['attrs']['minHeight'] ?? 0 ) ||
	2 !== ( $cover_blocks[0]['innerBlocks'][0]['innerBlocks'][1]['attrs']['level'] ?? 0 ) ||
	! in_array( 'core/buttons', $cover_names, true ) ||
	false === strpos( $cover_content, 'href="#example-details"' ) ||
	false === strpos( $cover_content, 'href="#example-start"' ) ||
	false !== strpos( $cover_content, 'http://' ) ||
	false !== strpos( $cover_content, 'https://' )
) {
	throw new RuntimeException( 'Hero Cover must use full-width Core Cover settings, editable H2, local example CTAs, and no remote asset.' );
}

$split_name    = 'sci-design-blocks/hero-split';
$split_content = $contents[ $split_name ];
$split_blocks  = array_values(
	array_filter(
		$parser->parse( $split_content ),
		static function ( $block ) {
			return null !== $block['blockName'];
		}
	)
);
$split_names   = sci_test_names( $split_blocks );
$split_image   = $split_blocks[0]['innerBlocks'][1]['innerBlocks'][0] ?? array();
$expected_empty_image_markup = '<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->' . "\n" . '<figure class="wp-block-image size-large"><img alt=""/></figure>' . "\n" . '<!-- /wp:image -->';
if (
	1 !== count( $split_blocks ) ||
	'core/columns' !== $split_blocks[0]['blockName'] ||
	true !== ( $split_blocks[0]['attrs']['isStackedOnMobile'] ?? false ) ||
	2 !== count( $split_blocks[0]['innerBlocks'] ) ||
	'core/image' !== $split_blocks[0]['innerBlocks'][1]['innerBlocks'][0]['blockName'] ||
	isset( $split_blocks[0]['innerBlocks'][1]['innerBlocks'][0]['attrs']['url'] ) ||
	$expected_empty_image_markup !== serialize_blocks( array( $split_image ) ) ||
	! in_array( 'core/buttons', $split_names, true ) ||
	false === strpos( $split_content, 'href="#example-details"' ) ||
	false === strpos( $split_content, 'href="#example-start"' ) ||
	false !== strpos( $split_content, 'http://' ) ||
	false !== strpos( $split_content, 'https://' )
) {
	throw new RuntimeException( 'Hero Split must use stacked Core Columns, an empty editable Core Image, local example CTAs, and no remote asset.' );
}

fwrite( STDOUT, "Hero Split and service-card empty Image serialization regression PASS: explicit Core figure/img markup matches the confirmed WordPress 7.1.3 save output.\n" );
fwrite( STDOUT, "Self-closing block review PASS: {$self_closing_count} instances across 27 patterns are limited to reviewed no-save/dynamic Core blocks; no self-closing core/image remains.\n" );

fwrite( STDOUT, "Core parser and registration checks PASS: 5 new patterns, 27 total, Core-only, WordPress {$wp_version}.\n" );
