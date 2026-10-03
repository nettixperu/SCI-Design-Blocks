<?php
/**
 * Core Query pattern library for the 1.3 development series.
 *
 * @package SCI\DesignBlocks
 */

namespace SCI\DesignBlocks;

/**
 * Register the editorial Query patterns in the existing plugin category.
 *
 * @return void
 */
function register_editorial_query_patterns(): void {
	$patterns = array(
		'sci-design-blocks/posts-featured-hero' => array(
			'title'       => __( 'SCI Posts — Featured Hero', 'sci-design-blocks' ),
			'description' => __( 'A featured post with its image beside the title and excerpt.', 'sci-design-blocks' ),
			'content'     => <<<'HTML'
<!-- wp:query {"query":{"perPage":1,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"ignore","inherit":false},"displayLayout":{"type":"list"}} -->
<div class="wp-block-query"><!-- wp:post-template -->
<!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"width":"55%"} -->
<div class="wp-block-column" style="flex-basis:55%"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3","scale":"cover"} /--></div>
<!-- /wp:column -->

<!-- wp:column {"width":"45%"} -->
<div class="wp-block-column" style="flex-basis:45%"><!-- wp:group {"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:post-title {"level":2,"isLink":true} /-->

<!-- wp:post-excerpt {"excerptLength":32} /--></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
<!-- /wp:post-template --></div>
<!-- /wp:query -->
HTML,
		),
	);

	foreach ( $patterns as $name => $pattern ) {
		register_block_pattern(
			$name,
			array(
				'title'       => $pattern['title'],
				'description' => $pattern['description'],
				'categories'  => array( 'sci-design-blocks' ),
				'content'     => $pattern['content'],
			)
		);
	}
}
