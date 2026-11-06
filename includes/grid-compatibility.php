<?php
/**
 * Keep Core Group Grid layouts compatible with WebKit's inline-size containment.
 *
 * @package SCI\DesignBlocks
 */

namespace SCI\DesignBlocks;

add_filter( 'render_block_data', __NAMESPACE__ . '\use_auto_fill_for_grid_compatibility', 10, 3 );

/**
 * Convert auto-fit Group grids to Core's auto-fill mode while rendering.
 *
 * WebKit versions used by iPadOS 18 can collapse auto-fit tracks when Core adds
 * container-type: inline-size to a responsive Group Grid. Core's auto-fill mode
 * avoids that browser bug and preserves each block's minimumColumnWidth.
 * The filter only changes rendered attributes; it does not rewrite saved posts.
 *
 * @param array<string, mixed> $parsed_block Parsed block data.
 * @param array<string, mixed> $source_block Original serialized block data.
 * @param object|null          $parent_block Parent block instance, when present.
 * @return array<string, mixed>
 */
function use_auto_fill_for_grid_compatibility( array $parsed_block, array $source_block, $parent_block ): array {
	if (
		'core/group' !== ( $parsed_block['blockName'] ?? '' ) ||
		'grid' !== ( $parsed_block['attrs']['layout']['type'] ?? '' ) ||
		true !== ( $parsed_block['attrs']['layout']['autoFit'] ?? false )
	) {
		return $parsed_block;
	}

	$parsed_block['attrs']['layout']['autoFit'] = false;

	return $parsed_block;
}
