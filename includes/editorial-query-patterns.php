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
		'sci-design-blocks/posts-editorial-lead' => array(
			'title'       => __( 'SCI Posts — Editorial Lead', 'sci-design-blocks' ),
			'description' => __( 'A leading post followed by a coordinated list of three secondary posts.', 'sci-design-blocks' ),
			'content'     => <<<'HTML'
<!-- wp:columns {"verticalAlignment":"top"} -->
<div class="wp-block-columns are-vertically-aligned-top"><!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%"><!-- wp:query {"query":{"perPage":1,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"ignore","inherit":false},"displayLayout":{"type":"list"}} -->
<div class="wp-block-query"><!-- wp:post-template -->
<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9","scale":"cover"} /-->
<!-- wp:post-title {"level":2,"isLink":true} /-->
<!-- wp:post-terms {"term":"category"} /-->
<!-- wp:post-date /-->
<!-- wp:post-excerpt {"excerptLength":24} /-->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%"><!-- wp:query {"query":{"perPage":3,"pages":0,"offset":1,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"ignore","inherit":false},"displayLayout":{"type":"list"}} -->
<div class="wp-block-query"><!-- wp:post-template -->
<!-- wp:post-title {"level":2,"isLink":true} /-->
<!-- wp:post-terms {"term":"category"} /-->
<!-- wp:post-date /-->
<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide" />
<!-- /wp:separator -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
HTML,
		),
		'sci-design-blocks/posts-compact-list' => array(
			'title'       => __( 'SCI Posts — Compact List', 'sci-design-blocks' ),
			'description' => __( 'A compact post list with a linked, uncropped featured image and Core metadata.', 'sci-design-blocks' ),
			'content'     => <<<'HTML'
<!-- wp:query {"query":{"perPage":5,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"ignore","inherit":false},"displayLayout":{"type":"list"}} -->
<div class="wp-block-query"><!-- wp:post-template -->
<!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"width":"30%"} -->
<div class="wp-block-column" style="flex-basis:30%"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9","scale":"contain"} /--></div>
<!-- /wp:column -->

<!-- wp:column {"width":"70%"} -->
<div class="wp-block-column" style="flex-basis:70%"><!-- wp:group {"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:post-title {"level":2,"isLink":true} /-->

<!-- wp:post-terms {"term":"category"} /-->

<!-- wp:post-date /--></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide" />
<!-- /wp:separator -->
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
