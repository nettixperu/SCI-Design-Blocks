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

/**
 * Collect block names inside one Query subtree.
 *
 * @param array $block Parsed Query block.
 * @param array $names Collected descendant names.
 * @return void
 */
function sci_collect_query_descendant_names( array $block, array &$names ): void {
	foreach ( $block['innerBlocks'] as $inner_block ) {
		$names[] = $inner_block['blockName'];
		sci_collect_query_descendant_names( $inner_block, $names );
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
		'required' => array( 'core/post-template', 'core/group', 'core/post-featured-image', 'core/post-title', 'core/post-terms', 'core/separator' ),
	),
	'sci-design-blocks/posts-editorial-grid' => array(
		'per_page' => array( 3 ),
		'offsets'  => array( 0 ),
		'required' => array( 'core/post-featured-image', 'core/post-title', 'core/post-excerpt', 'core/post-terms', 'core/post-date' ),
	),
	'sci-design-blocks/posts-editorial-stack' => array(
		'per_page' => array( 3 ),
		'offsets'  => array( 0 ),
		'required' => array( 'core/post-featured-image', 'core/post-title', 'core/post-excerpt', 'core/post-terms', 'core/post-date', 'core/separator' ),
	),
	'sci-design-blocks/posts-editorial-sections' => array(
		'per_page' => array( 1, 2, 1, 2, 1, 2 ),
		'offsets'  => array( 0, 1, 0, 1, 0, 1 ),
		'required' => array( 'core/columns', 'core/column', 'core/heading', 'core/post-featured-image', 'core/post-title', 'core/separator' ),
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
			false !== ( $query['inherit'] ?? null ) ||
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
	if ( 'sci-design-blocks/posts-editorial-lead' === $pattern_name ) {
		$primary_blocks   = array();
		$secondary_blocks = array();
		sci_collect_query_descendant_names( $queries[0], $primary_blocks );
		sci_collect_query_descendant_names( $queries[1], $secondary_blocks );
		if (
			! in_array( 'core/post-featured-image', $primary_blocks, true ) ||
			in_array( 'core/post-featured-image', $secondary_blocks, true ) ||
			in_array( 'core/post-excerpt', $secondary_blocks, true ) ||
			array_slice( $expectation['per_page'], 0, 2 ) !== array( 1, 3 )
		) {
			throw new RuntimeException( 'Editorial Lead must have an image only in its 1-post primary query and three text-first secondary posts.' );
		}
		$fixture_ids = array( 'post-1', 'post-2', 'post-3', 'post-4', 'post-5' );
		$primary_ids = array_slice( $fixture_ids, $expectation['offsets'][0], $expectation['per_page'][0] );
		$secondary_ids = array_slice( $fixture_ids, $expectation['offsets'][1], $expectation['per_page'][1] );
		if ( array( 'post-1' ) !== $primary_ids || array( 'post-2', 'post-3', 'post-4' ) !== $secondary_ids || array_intersect( $primary_ids, $secondary_ids ) ) {
			throw new RuntimeException( 'Editorial Lead static offset fixture does not represent posts 1 then posts 2–4.' );
		}
	}
	if ( 'sci-design-blocks/posts-compact-list' === $pattern_name ) {
		$query      = $queries[0];
		$post_list  = $query['innerBlocks'][0] ?? array();
		$row        = $post_list['innerBlocks'][0] ?? array();
		$image      = $row['innerBlocks'][0] ?? array();
		$group      = $row['innerBlocks'][1] ?? array();
		$group_names = array_column( $group['innerBlocks'] ?? array(), 'blockName' );
		if (
			'core/post-template' !== ( $post_list['blockName'] ?? '' ) ||
			array( 'core/group', 'core/separator' ) !== array_column( $post_list['innerBlocks'] ?? array(), 'blockName' ) ||
			'core/group' !== ( $row['blockName'] ?? '' ) ||
			array( 'core/post-featured-image', 'core/group' ) !== array_column( $row['innerBlocks'] ?? array(), 'blockName' ) ||
			'flex' !== ( $row['attrs']['layout']['type'] ?? '' ) ||
			'nowrap' !== ( $row['attrs']['layout']['flexWrap'] ?? '' ) ||
			'center' !== ( $row['attrs']['layout']['verticalAlignment'] ?? '' ) ||
			'var:preset|spacing|20' !== ( $row['attrs']['style']['spacing']['blockGap'] ?? '' ) ||
			'core/post-featured-image' !== ( $image['blockName'] ?? '' ) ||
			'160px' !== ( $image['attrs']['width'] ?? '' ) ||
			'16/9' !== ( $image['attrs']['aspectRatio'] ?? '' ) ||
			'contain' !== ( $image['attrs']['scale'] ?? '' ) ||
			'core/group' !== ( $group['blockName'] ?? '' ) ||
			array( 'core/post-title', 'core/post-terms' ) !== array_column( $group['innerBlocks'] ?? array(), 'blockName' ) ||
			'flex' !== ( $group['attrs']['layout']['type'] ?? '' ) ||
			'vertical' !== ( $group['attrs']['layout']['orientation'] ?? '' ) ||
			'var:preset|spacing|20' !== ( $group['attrs']['style']['spacing']['blockGap'] ?? '' ) ||
			array( 'core/post-title', 'core/post-terms' ) !== $group_names ||
			'small' !== ( $group['innerBlocks'][0]['attrs']['fontSize'] ?? '' ) ||
			'600' !== ( $group['innerBlocks'][0]['attrs']['style']['typography']['fontWeight'] ?? '' ) ||
			'small' !== ( $group['innerBlocks'][1]['attrs']['fontSize'] ?? '' ) ||
			in_array( 'core/post-date', $names, true ) ||
			in_array( 'core/post-excerpt', $names, true ) ||
			! in_array( 'core/separator', $names, true )
		) {
			throw new RuntimeException( 'Compact List must use a Core Row with 160px contained 16:9 image, compact title/category stack, separator, and no date or excerpt.' );
		}
	}
	if ( 'sci-design-blocks/posts-editorial-sections' === $pattern_name ) {
		$root_columns = $parsed[0];
		if ( 'core/columns' !== $root_columns['blockName'] || 3 !== count( $root_columns['innerBlocks'] ) ) {
			throw new RuntimeException( 'Editorial Sections must use exactly three top-level Core columns.' );
		}
		$expected_labels = array( 'Section One', 'Section Two', 'Section Three' );
		foreach ( $root_columns['innerBlocks'] as $section_index => $section ) {
			$section_children = $section['innerBlocks'];
			if (
				'core/column' !== $section['blockName'] ||
			4 !== count( $section_children ) ||
			'core/heading' !== $section_children[0]['blockName'] ||
			false === strpos( $section_children[0]['innerHTML'], $expected_labels[ $section_index ] ) ||
			'core/query' !== $section_children[1]['blockName'] ||
			'core/separator' !== $section_children[2]['blockName'] ||
			'core/query' !== $section_children[3]['blockName'] ||
			'var:preset|spacing|20' !== ( $section['attrs']['style']['spacing']['blockGap'] ?? '' )
		) {
			throw new RuntimeException( 'Each section must have a generic label, primary Query, Core divider, and secondary Query.' );
		}
		$primary_names   = array();
		$secondary_names = array();
		sci_collect_query_descendant_names( $section_children[1], $primary_names );
		sci_collect_query_descendant_names( $section_children[3], $secondary_names );
		if (
			array( 'core/post-template', 'core/post-featured-image', 'core/post-title' ) !== $primary_names ||
			array( 'core/post-template', 'core/post-title', 'core/separator' ) !== $secondary_names
		) {
			throw new RuntimeException( 'Primary template must contain only image/title; secondary template must contain only generated title/separator blocks.' );
		}
	}
		$fixture_ids = array( 'post-1', 'post-2', 'post-3' );
		for ( $section_index = 0; $section_index < 3; $section_index++ ) {
			$primary_ids   = array_slice( $fixture_ids, $expectation['offsets'][ $section_index * 2 ], $expectation['per_page'][ $section_index * 2 ] );
			$secondary_ids = array_slice( $fixture_ids, $expectation['offsets'][ $section_index * 2 + 1 ], $expectation['per_page'][ $section_index * 2 + 1 ] );
			$primary_query = $section_children[1]['attrs']['query'];
			$secondary_query = $section_children[3]['attrs']['query'];
			unset( $primary_query['perPage'], $primary_query['offset'], $secondary_query['perPage'], $secondary_query['offset'] );
			if ( array( 'post-1' ) !== $primary_ids || array( 'post-2', 'post-3' ) !== $secondary_ids || array_intersect( $primary_ids, $secondary_ids ) ) {
				throw new RuntimeException( 'Editorial Sections static pair fixture must represent the lead post followed by two secondary posts.' );
			}
			if ( $primary_query !== $secondary_query ) {
				throw new RuntimeException( 'Primary and secondary Queries in each Editorial Section must use identical settings apart from perPage and offset.' );
			}
		}
	}
	if ( in_array( $pattern_name, array( 'sci-design-blocks/posts-editorial-grid' ), true ) ) {
		$pattern = $GLOBALS['sci_test_patterns'][ $pattern_name ]['content'];
		if ( false === strpos( $pattern, '"columns":3' ) || false === strpos( $pattern, '"type":"flex"' ) ) {
			throw new RuntimeException( "Grid pattern must use the three-column Core Query layout: {$pattern_name}." );
		}
	}
	if ( 'sci-design-blocks/posts-editorial-grid' === $pattern_name ) {
		$pattern = $GLOBALS['sci_test_patterns'][ $pattern_name ]['content'];
		if ( false === strpos( $pattern, '"aspectRatio":"4/3"' ) || false === strpos( $pattern, '"fontSize":"medium"' ) || false === strpos( $pattern, '"excerptLength":20' ) ) {
			throw new RuntimeException( 'Editorial Grid must use a balanced image, compact Core title preset and concise excerpt.' );
		}
	}
	if ( 'sci-design-blocks/posts-compact-list' === $pattern_name && false === strpos( $GLOBALS['sci_test_patterns'][ $pattern_name ]['content'], '"fontSize":"small"' ) ) {
		throw new RuntimeException( 'Compact List title and metadata must use a compact Core typography preset.' );
	}
	if ( 'sci-design-blocks/posts-editorial-stack' === $pattern_name ) {
		$pattern = $GLOBALS['sci_test_patterns'][ $pattern_name ]['content'];
		if ( false === strpos( $pattern, '"perPage":3' ) || false === strpos( $pattern, '"type":"list"' ) || false === strpos( $pattern, '"excerptLength":40' ) ) {
			throw new RuntimeException( 'Editorial Stack must be a three-post vertical Core list with excerpts.' );
		}
	}
	$pattern_titles = array(
		'VPN Site-to-Site vs Client-to-Site: qué tipo de VPN necesita tu empresa',
		'CAPEX vs OPEX en infraestructura TI: comprar servidores o consumir nube',
		'Housing, servidor dedicado o nube privada: ¿qué opción conviene para una empresa?',
	);
	foreach ( $pattern_titles as $title ) {
		if ( 60 > strlen( $title ) ) {
			throw new RuntimeException( 'Long-title visual fixtures must use realistic technical headlines.' );
		}
	}
	if ( false !== strpos( $GLOBALS['sci_test_patterns'][ $pattern_name ]['content'], 'text-overflow' ) || false !== strpos( $GLOBALS['sci_test_patterns'][ $pattern_name ]['content'], 'line-clamp' ) ) {
		throw new RuntimeException( "Pattern must not truncate titles: {$pattern_name}." );
	}

	fwrite( STDOUT, "Core parser structural check PASS: {$pattern_name} (WordPress {$wp_version}).\n" );
}

if ( 15 !== count( $GLOBALS['sci_test_patterns'] ) ) {
	throw new RuntimeException( 'Expected nine historical and six editorial patterns (15 total).' );
}

$sections_content = $GLOBALS['sci_test_patterns']['sci-design-blocks/posts-editorial-sections']['content'] ?? '';
$sections_parsed  = $parser->parse( $sections_content );
$sections_names   = array();
$sections_queries = array();
sci_collect_editorial_blocks( $sections_parsed, $sections_names, $sections_queries );
if (
	6 !== count( $sections_queries ) ||
	3 !== count( array_filter( $sections_names, static fn( $name ) => 'core/post-featured-image' === $name ) ) ||
	6 !== count( array_filter( $sections_names, static fn( $name ) => 'core/post-title' === $name ) ) ||
	in_array( 'core/post-date', $sections_names, true ) ||
	in_array( 'core/post-terms', $sections_names, true ) ||
	in_array( 'core/post-excerpt', $sections_names, true )
) {
	throw new RuntimeException( 'Editorial Sections must serialize three primary images and six Query-generated title blocks, with no date, terms, or excerpt.' );
}
