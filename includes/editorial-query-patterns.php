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
			'description' => __( 'A featured lead story followed by three text-first editorial headlines.', 'sci-design-blocks' ),
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
<!-- wp:post-title {"level":2,"isLink":true,"fontSize":"medium"} /-->
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
			'description' => __( 'A compact editorial list with a prominent thumbnail, headline, category, and Core separators.', 'sci-design-blocks' ),
			'content'     => <<<'HTML'
<!-- wp:query {"query":{"perPage":5,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"ignore","inherit":false},"displayLayout":{"type":"list"}} -->
<div class="wp-block-query"><!-- wp:post-template -->
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:post-featured-image {"isLink":true,"width":"160px","aspectRatio":"16/9","scale":"contain"} /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:post-title {"level":2,"isLink":true,"fontSize":"small","style":{"typography":{"fontWeight":"600"}}} /-->
<!-- wp:post-terms {"term":"category","fontSize":"small"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide" />
<!-- /wp:separator -->
<!-- /wp:post-template --></div>
<!-- /wp:query -->
HTML,
		),
		'sci-design-blocks/posts-editorial-grid' => array(
			'title'       => __( 'SCI Posts — Editorial Grid', 'sci-design-blocks' ),
			'description' => __( 'Three horizontal editorial cards with image, compact title, excerpt and metadata.', 'sci-design-blocks' ),
			'content'     => <<<'HTML'
<!-- wp:query {"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"ignore","inherit":false},"displayLayout":{"type":"flex","columns":3}} -->
<div class="wp-block-query"><!-- wp:post-template -->
<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3","scale":"cover"} /-->
<!-- wp:post-title {"level":2,"isLink":true,"fontSize":"medium"} /-->
<!-- wp:post-excerpt {"excerptLength":20,"fontSize":"small"} /-->
<!-- wp:post-terms {"term":"category","fontSize":"small"} /-->
<!-- wp:post-date {"fontSize":"small"} /-->
<!-- /wp:post-template --></div>
<!-- /wp:query -->
HTML,
		),
		'sci-design-blocks/posts-editorial-stack' => array(
			'title'       => __( 'SCI Posts — Editorial Stack', 'sci-design-blocks' ),
			'description' => __( 'A vertical editorial sequence with a large image, title, excerpt and metadata for each post.', 'sci-design-blocks' ),
			'content'     => <<<'HTML'
<!-- wp:query {"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"ignore","inherit":false},"displayLayout":{"type":"list"}} -->
<div class="wp-block-query"><!-- wp:post-template -->
<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9","scale":"cover"} /-->
<!-- wp:post-title {"level":2,"isLink":true,"fontSize":"medium"} /-->
<!-- wp:post-excerpt {"excerptLength":40} /-->
<!-- wp:post-terms {"term":"category","fontSize":"small"} /-->
<!-- wp:post-date {"fontSize":"small"} /-->
<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide" />
<!-- /wp:separator -->
<!-- /wp:post-template --></div>
<!-- /wp:query -->
HTML,
		),
		'sci-design-blocks/posts-editorial-sections' => array(
			'title'       => __( 'SCI Posts — Editorial Sections', 'sci-design-blocks' ),
			'description' => __( 'Three editable topic columns, each with one image-led story and two text-first headlines.', 'sci-design-blocks' ),
			'content'     => <<<'HTML'
<!-- wp:columns {"isStackedOnMobile":true,"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|20","left":"var:preset|spacing|30"}}}} -->
<div class="wp-block-columns"><!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column"><!-- wp:heading {"level":2,"fontSize":"small"} -->
<h2 class="wp-block-heading has-small-font-size">Section One</h2>
<!-- /wp:heading -->

<!-- wp:query {"query":{"perPage":1,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"ignore","inherit":false},"displayLayout":{"type":"list"}} -->
<div class="wp-block-query"><!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3","scale":"cover"} /-->
<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"medium","style":{"typography":{"fontWeight":"600"}}} /-->
<!-- /wp:post-template --></div>
<!-- /wp:query -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide" />
<!-- /wp:separator -->

<!-- wp:query {"query":{"perPage":2,"pages":0,"offset":1,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"ignore","inherit":false},"displayLayout":{"type":"list"}} -->
<div class="wp-block-query"><!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"small","style":{"typography":{"fontWeight":"600"}}} /-->
<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide" />
<!-- /wp:separator -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:column -->

<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column"><!-- wp:heading {"level":2,"fontSize":"small"} -->
<h2 class="wp-block-heading has-small-font-size">Section Two</h2>
<!-- /wp:heading -->

<!-- wp:query {"query":{"perPage":1,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"ignore","inherit":false},"displayLayout":{"type":"list"}} -->
<div class="wp-block-query"><!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3","scale":"cover"} /-->
<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"medium","style":{"typography":{"fontWeight":"600"}}} /-->
<!-- /wp:post-template --></div>
<!-- /wp:query -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide" />
<!-- /wp:separator -->

<!-- wp:query {"query":{"perPage":2,"pages":0,"offset":1,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"ignore","inherit":false},"displayLayout":{"type":"list"}} -->
<div class="wp-block-query"><!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"small","style":{"typography":{"fontWeight":"600"}}} /-->
<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide" />
<!-- /wp:separator -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:column -->

<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column"><!-- wp:heading {"level":2,"fontSize":"small"} -->
<h2 class="wp-block-heading has-small-font-size">Section Three</h2>
<!-- /wp:heading -->

<!-- wp:query {"query":{"perPage":1,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"ignore","inherit":false},"displayLayout":{"type":"list"}} -->
<div class="wp-block-query"><!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3","scale":"cover"} /-->
<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"medium","style":{"typography":{"fontWeight":"600"}}} /-->
<!-- /wp:post-template --></div>
<!-- /wp:query -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide" />
<!-- /wp:separator -->

<!-- wp:query {"query":{"perPage":2,"pages":0,"offset":1,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"ignore","inherit":false},"displayLayout":{"type":"list"}} -->
<div class="wp-block-query"><!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"small","style":{"typography":{"fontWeight":"600"}}} /-->
<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide" />
<!-- /wp:separator -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
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
