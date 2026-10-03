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
	'sci-design-blocks/posts-featured-hero',
);

foreach ( $expected as $pattern_name ) {
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
	if ( 1 !== count( $parsed ) || 1 !== count( $queries ) ) {
		throw new RuntimeException( "Expected one Core Query root in {$pattern_name}." );
	}

	$query = $queries[0]['attrs']['query'] ?? array();
	if ( 1 !== ( $query['perPage'] ?? null ) || 0 !== ( $query['offset'] ?? null ) || false !== ( $query['inherit'] ?? null ) || 'ignore' !== ( $query['sticky'] ?? null ) ) {
		throw new RuntimeException( "Unexpected M1 query defaults in {$pattern_name}." );
	}
	if ( in_array( 'core/query-pagination', $names, true ) ) {
		throw new RuntimeException( "Pagination must not be included in {$pattern_name}." );
	}

	fwrite( STDOUT, "Core parser structural check PASS: {$pattern_name} (WordPress {$wp_version}).\n" );
}
