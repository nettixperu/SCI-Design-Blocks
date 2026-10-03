<?php
/**
 * Validate editorial patterns with the WordPress block parser and a registration smoke.
 * Run: php tests/check-editorial-query-patterns.php /path/to/wordpress
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

function wp_json_encode( $value, $flags = 0, $depth = 512 ) {
	return json_encode( $value, $flags, $depth );
}

function apply_filters( $hook, $value, ...$args ) {
	return $value;
}

require __DIR__ . '/check-icon-library.php';

require $core_dir . 'class-wp-block-parser-block.php';
require $core_dir . 'class-wp-block-parser-frame.php';
require $core_dir . 'class-wp-block-parser.php';
$parser = new WP_Block_Parser();

/**
 * Recursively collect parsed blocks.
 *
 * @param array $blocks Parsed block tree.
 * @param array $names  Collected block names.
 * @param array $queries Collected Query blocks.
 * @return void
 */
function sci_collect_editorial_blocks( array $blocks, array &$names, array &$queries ): void {
	foreach ( $blocks as $block ) {
		$names[] = $block['blockName'];
		if ( 'core/query' === $block['blockName'] ) {
			$queries[] = $block;
		}
		sci_collect_editorial_blocks( $block['innerBlocks'], $names, $queries );
	}
}

$expected = array(
	'sci-design-blocks/posts-featured-hero' => array(
		'per_page' => array( 1 ),
	'offsets'  => array( 0 ),
	'required' => array( 'core/post-featured-image', 'core/post-title', 'core/post-excerpt' ),
	),
	'sci-design-blocks/posts-editorial-lead' => array(
		'per_page' => array( 1, 3 ),
		'offsets'  => array( 0, 1 ),
		'required' => array( 'core/post-featured-image', 'core/post-title', 'core/post-terms', 'core/post-date', 'core/post-excerpt' ),
	),
	'sci-design-blocks/posts-compact-list' => array(
		'per_page' => array( 5 ),
		'offsets'  => array( 0 ),
		'required' => array( 'core/post-featured-image', 'core/post-title', 'core/post-terms', 'core/post-date', 'core/separator' ),
	),
);

foreach ( $expected as $pattern_name => $expectation ) {
	$content = $GLOBALS['sci_test_patterns'][ $pattern_name ]['content'] ?? '';
	if ( '' === $content ) {
		throw new RuntimeException( "Missing registered pattern {$pattern_name}." );
	}

	$parsed = $parser->parse( $content );

	$names  = array();
	$queries = array();
	sci_collect_editorial_blocks( $parsed, $names, $queries );
	foreach ( $names as $block_name ) {
		if ( ! is_string( $block_name ) || 0 !== strpos( $block_name, 'core/' ) ) {
			throw new RuntimeException( "Non-Core block found in {$pattern_name}." );
		}
	}
	if ( 1 !== count( $parsed ) || count( $expectation['per_page'] ) !== count( $queries ) ) {
		throw new RuntimeException( "Unexpected Core Query count or pattern root in {$pattern_name}." );
	}

	$per_page = array();
	$offsets  = array();
	foreach ( $queries as $query_block ) {
		$query = $query_block['attrs']['query'] ?? array();
		if (
			true === ( $query['inherit'] ?? null ) ||
			'ignore' !== ( $query['sticky'] ?? null ) ||
			'desc' !== ( $query['order'] ?? null ) ||
			'date' !== ( $query['orderBy'] ?? null )
		) {
			throw new RuntimeException( "Unexpected query defaults in {$pattern_name}: " . json_encode( $query ) );
		}
		$per_page[] = $query['perPage'] ?? null;
		$offsets[]  = $query['offset'] ?? null;
	}
	if ( $expectation['per_page'] !== $per_page || $expectation['offsets'] !== $offsets ) {
		throw new RuntimeException( "Unexpected perPage/offset sequencing in {$pattern_name}." );
	}
	foreach ( $expectation['required'] as $required_block ) {
		if ( ! in_array( $required_block, $names, true ) ) {
			throw new RuntimeException( "Missing {$required_block} in {$pattern_name}." );
		}
	}
	if ( in_array( 'core/query-pagination', $names, true ) ) {
		throw new RuntimeException( "Pagination must not be included in {$pattern_name}." );
	}
	if ( 'sci-design-blocks/posts-compact-list' === $pattern_name ) {
		$pattern = $GLOBALS['sci_test_patterns'][ $pattern_name ]['content'];
		if ( false === strpos( $pattern, '"scale":"contain"' ) || false === strpos( $pattern, '"aspectRatio":"16/9"' ) ) {
			throw new RuntimeException( 'Compact List must preserve the Core contain fit and 16:9 image ratio.' );
		}
	}

	fwrite( STDOUT, "Core parser structural check PASS: {$pattern_name} (WordPress {$wp_version}).\n" );
}
