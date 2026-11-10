<?php
/**
 * Plugin Name: SCI Design Blocks
 * Description: Reusable editorial and corporate compositions built from WordPress Core blocks.
 * Version: 1.8.0
 * Requires at least: 7.1
 * Requires PHP: 8.3
 * Author: Martín
 * Text Domain: sci-design-blocks
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package SCI\DesignBlocks
 */

namespace SCI\DesignBlocks;

require_once __DIR__ . '/includes/icon-library.php';
require_once __DIR__ . '/includes/accordion-styles.php';
require_once __DIR__ . '/includes/tabs-styles.php';
require_once __DIR__ . '/includes/editorial-query-patterns.php';
require_once __DIR__ . '/includes/editorial-image-styles.php';
require_once __DIR__ . '/includes/pricing-comparison-patterns.php';
require_once __DIR__ . '/includes/hero-patterns.php';
require_once __DIR__ . '/includes/commercial-patterns.php';
require_once __DIR__ . '/includes/page-title-setting.php';
require_once __DIR__ . '/includes/button-styles.php';
require_once __DIR__ . '/includes/market-pricing.php';

add_action( 'init', __NAMESPACE__ . '\register_feature_patterns' );
add_action( 'init', __NAMESPACE__ . '\register_editorial_query_patterns', 11 );
add_action( 'init', __NAMESPACE__ . '\register_pricing_comparison_patterns', 12 );
add_action( 'init', __NAMESPACE__ . '\register_hero_patterns', 13 );
add_action( 'init', __NAMESPACE__ . '\register_commercial_patterns', 14 );

/**
 * Register the M1 Feature patterns using WordPress Core blocks only.
 *
 * @return void
 */
function register_feature_patterns() {
	register_block_pattern_category(
		'sci-design-blocks',
		array(
			'label' => __( 'SCI Design Blocks', 'sci-design-blocks' ),
		)
	);

	$example = array(
		'feature_prefix'       => esc_html__( 'Feature', 'sci-design-blocks' ),
		'feature_heading'      => esc_html__( 'Feature heading', 'sci-design-blocks' ),
		'feature_description'  => esc_html__( 'Describe this feature.', 'sci-design-blocks' ),
		'learn_more'           => esc_html__( 'Learn more (configure link before publishing)', 'sci-design-blocks' ),
		'cta_heading'          => esc_html__( 'Take the next step', 'sci-design-blocks' ),
		'cta_description'      => esc_html__( 'Talk to our team about your infrastructure.', 'sci-design-blocks' ),
		'cta_button'           => esc_html__( 'Contact us (configure link before publishing)', 'sci-design-blocks' ),
		'testimonial_quote'    => esc_html__( '“Working with the team simplified our infrastructure.”', 'sci-design-blocks' ),
		'testimonial_citation' => esc_html__( 'Person Name — Role / Company', 'sci-design-blocks' ),
		'stat_one'             => esc_html__( '+15', 'sci-design-blocks' ),
		'stat_one_label'       => esc_html__( 'Years of experience', 'sci-design-blocks' ),
		'stat_two'             => esc_html__( '99.9%', 'sci-design-blocks' ),
		'stat_two_label'       => esc_html__( 'Availability', 'sci-design-blocks' ),
		'stat_three'           => esc_html__( '24/7', 'sci-design-blocks' ),
		'stat_three_label'     => esc_html__( 'Monitoring', 'sci-design-blocks' ),
		'icon_item_one'        => esc_html__( 'First feature', 'sci-design-blocks' ),
		'icon_item_two'        => esc_html__( 'Second feature', 'sci-design-blocks' ),
		'icon_item_three'      => esc_html__( 'Third feature', 'sci-design-blocks' ),
		'accordion_question_a' => esc_html__( '¿Qué incluye este servicio?', 'sci-design-blocks' ),
		'accordion_answer_a'   => esc_html__( 'Describe aquí qué incluye el servicio y qué recibe el cliente.', 'sci-design-blocks' ),
		'accordion_question_b' => esc_html__( '¿Cómo funciona el proceso?', 'sci-design-blocks' ),
		'accordion_answer_b'   => esc_html__( 'Explica aquí los pasos principales y qué debe esperar el cliente.', 'sci-design-blocks' ),
		'accordion_question_c' => esc_html__( '¿Cómo puedo obtener ayuda?', 'sci-design-blocks' ),
		'accordion_answer_c'   => esc_html__( 'Indica aquí los canales de soporte y sus horarios de atención.', 'sci-design-blocks' ),
	);

	$patterns = array(
		'sci-design-blocks/features-grid' => array(
			'title'       => __( 'SCI Features — Grid', 'sci-design-blocks' ),
			'description' => __( 'Four feature cards in a responsive grid: four columns on wide screens, fewer columns as space narrows, and no forced word breaks.', 'sci-design-blocks' ),
			'content'     => <<<'HTML'
<!-- wp:group {"align":"wide","className":"sci-features-grid","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"grid","minimumColumnWidth":"13rem","columnCount":4,"autoFit":true}} -->
<div class="wp-block-group alignwide sci-features-grid">
<!-- wp:group {"className":"sci-feature-card","style":{"color":{"background":"#ffffff","text":"#211832"},"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group sci-feature-card has-text-color has-background" style="color:#211832;background-color:#ffffff;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><!-- wp:icon /-->
<!-- wp:heading {"level":3,"fontSize":"medium"} -->
<h3 class="wp-block-heading has-medium-font-size">Correo empresarial</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Profesional, seguro y siempre disponible para tu empresa.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<!-- wp:group {"className":"sci-feature-card","style":{"color":{"background":"#ffffff","text":"#211832"},"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group sci-feature-card has-text-color has-background" style="color:#211832;background-color:#ffffff;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><!-- wp:icon /-->
<!-- wp:heading {"level":3,"fontSize":"medium"} -->
<h3 class="wp-block-heading has-medium-font-size">WordPress administrado</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Rápido, actualizado y protegido.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<!-- wp:group {"className":"sci-feature-card","style":{"color":{"background":"#ffffff","text":"#211832"},"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group sci-feature-card has-text-color has-background" style="color:#211832;background-color:#ffffff;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><!-- wp:icon /-->
<!-- wp:heading {"level":3,"fontSize":"medium"} -->
<h3 class="wp-block-heading has-medium-font-size">Documentos privados</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Comparte y sincroniza documentos con tu equipo.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<!-- wp:group {"className":"sci-feature-card","style":{"color":{"background":"#ffffff","text":"#211832"},"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group sci-feature-card has-text-color has-background" style="color:#211832;background-color:#ffffff;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><!-- wp:icon /-->
<!-- wp:heading {"level":3,"fontSize":"medium"} -->
<h3 class="wp-block-heading has-medium-font-size">Gestión de dominios DNS</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Tu dominio, custodiado y gestionado por especialistas.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
HTML,
		),
		'sci-design-blocks/feature-icon-vertical' => array(

			'title'       => __( 'SCI Feature — Icon Vertical', 'sci-design-blocks' ),
			'description' => __( 'A vertical feature composition with a Core icon, prefix, heading, description, and button.', 'sci-design-blocks' ),
			'content'     => <<<HTML
<!-- wp:group {"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
<div class="wp-block-group"><!-- wp:icon /-->

<!-- wp:heading {"level":6} -->
<h6 class="wp-block-heading">{$example['feature_prefix']}</h6>
<!-- /wp:heading -->

<!-- wp:heading -->
<h2 class="wp-block-heading">{$example['feature_heading']}</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>{$example['feature_description']}</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">{$example['learn_more']}</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
HTML,
		),
		'sci-design-blocks/feature-image-vertical' => array(
			'title'       => __( 'SCI Feature — Image Vertical', 'sci-design-blocks' ),
			'description' => __( 'A vertical feature composition with a Core image, prefix, heading, description, and button.', 'sci-design-blocks' ),
			'content'     => <<<HTML
<!-- wp:group {"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
<div class="wp-block-group"><!-- wp:image -->
<figure class="wp-block-image"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAACklEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg==" alt="" /></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":6} -->
<h6 class="wp-block-heading">{$example['feature_prefix']}</h6>
<!-- /wp:heading -->

<!-- wp:heading -->
<h2 class="wp-block-heading">{$example['feature_heading']}</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>{$example['feature_description']}</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">{$example['learn_more']}</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
HTML,
		),
		'sci-design-blocks/feature-icon-left' => array(
			'title'       => __( 'SCI Feature — Icon Left', 'sci-design-blocks' ),
			'description' => __( 'A feature with its prefix above a responsive Core icon and content row.', 'sci-design-blocks' ),
			'content'     => <<<HTML
<!-- wp:group -->
<div class="wp-block-group"><!-- wp:heading {"level":6} -->
<h6 class="wp-block-heading">{$example['feature_prefix']}</h6>
<!-- /wp:heading -->

<!-- wp:columns {"verticalAlignment":"center","isStackedOnMobile":true} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"width":"14%","verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:14%"><!-- wp:icon /--></div>
<!-- /wp:column -->

<!-- wp:column {"width":"86%","verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:86%"><!-- wp:group {"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
<div class="wp-block-group"><!-- wp:heading -->
<h2 class="wp-block-heading">{$example['feature_heading']}</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>{$example['feature_description']}</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">{$example['learn_more']}</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
HTML,
		),
		'sci-design-blocks/feature-image-left' => array(
			'title'       => __( 'SCI Feature — Image Left', 'sci-design-blocks' ),
			'description' => __( 'A feature with its prefix above a responsive Core image and content row.', 'sci-design-blocks' ),
			'content'     => <<<HTML
<!-- wp:group -->
<div class="wp-block-group"><!-- wp:heading {"level":6} -->
<h6 class="wp-block-heading">{$example['feature_prefix']}</h6>
<!-- /wp:heading -->

<!-- wp:columns {"verticalAlignment":"center","isStackedOnMobile":true} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"width":"33%","verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:33%"><!-- wp:image -->
<figure class="wp-block-image"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAACklEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg==" alt="" /></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"67%","verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:67%"><!-- wp:group {"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
<div class="wp-block-group"><!-- wp:heading -->
<h2 class="wp-block-heading">{$example['feature_heading']}</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>{$example['feature_description']}</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">{$example['learn_more']}</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
HTML,
		),
		'sci-design-blocks/cta' => array(
			'title'       => __( 'SCI CTA', 'sci-design-blocks' ),
			'description' => __( 'A simple call to action using Core heading, paragraph, and button blocks.', 'sci-design-blocks' ),
			'content'     => <<<HTML
<!-- wp:group {"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
<div class="wp-block-group"><!-- wp:heading -->
<h2 class="wp-block-heading">{$example['cta_heading']}</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>{$example['cta_description']}</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">{$example['cta_button']}</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
HTML,
		),
		'sci-design-blocks/testimonial' => array(
			'title'       => __( 'SCI Testimonial', 'sci-design-blocks' ),
			'description' => __( 'A static testimonial using the semantic Core Quote block.', 'sci-design-blocks' ),
			'content'     => <<<HTML
<!-- wp:quote -->
<blockquote class="wp-block-quote"><!-- wp:paragraph -->
<p>{$example['testimonial_quote']}</p>
<!-- /wp:paragraph -->

<cite>{$example['testimonial_citation']}</cite></blockquote>
<!-- /wp:quote -->
HTML,
		),
		'sci-design-blocks/stats' => array(
			'title'       => __( 'SCI Stats', 'sci-design-blocks' ),
			'description' => __( 'A static three-column set of metrics using Core blocks.', 'sci-design-blocks' ),
			'content'     => <<<HTML
<!-- wp:columns {"verticalAlignment":"center","isStackedOnMobile":true} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p class="has-large-font-size">{$example['stat_one']}</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center"} -->
<p>{$example['stat_one_label']}</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p class="has-large-font-size">{$example['stat_two']}</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center"} -->
<p>{$example['stat_two_label']}</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p class="has-large-font-size">{$example['stat_three']}</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center"} -->
<p>{$example['stat_three_label']}</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
HTML,
		),
		'sci-design-blocks/icon-list' => array(
			'title'       => __( 'SCI Icon List', 'sci-design-blocks' ),
			'description' => __( 'A compact list of editable Core icon and paragraph rows.', 'sci-design-blocks' ),
			'content'     => <<<HTML
<!-- wp:group {"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
<div class="wp-block-group"><!-- wp:group {"layout":{"type":"flex","orientation":"horizontal","justifyContent":"left"}} -->
<div class="wp-block-group"><!-- wp:icon /-->

<!-- wp:paragraph -->
<p>{$example['icon_item_one']}</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"flex","orientation":"horizontal","justifyContent":"left"}} -->
<div class="wp-block-group"><!-- wp:icon /-->

<!-- wp:paragraph -->
<p>{$example['icon_item_two']}</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"flex","orientation":"horizontal","justifyContent":"left"}} -->
<div class="wp-block-group"><!-- wp:icon /-->

<!-- wp:paragraph -->
<p>{$example['icon_item_three']}</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
HTML,
		),
		'sci-design-blocks/accordion' => array(
			'title'       => __( 'SCI FAQ — Acordeón desplegable', 'sci-design-blocks' ),
			'description' => __( 'Sección de preguntas frecuentes con tres respuestas desplegables y editables.', 'sci-design-blocks' ),
			'content'     => <<<HTML
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Preguntas frecuentes</h2>
<!-- /wp:heading -->

<!-- wp:accordion {"align":"wide","autoclose":true} -->
<div role="group" class="wp-block-accordion alignwide"><!-- wp:accordion-item -->
<div class="wp-block-accordion-item"><!-- wp:accordion-heading -->
<h3 class="wp-block-accordion-heading has-icon has-icon-right"><button type="button" class="wp-block-accordion-heading__toggle"><span class="wp-block-accordion-heading__toggle-title">{$example['accordion_question_a']}</span><span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span></button></h3>
<!-- /wp:accordion-heading -->

<!-- wp:accordion-panel -->
<div role="region" class="wp-block-accordion-panel"><!-- wp:paragraph -->
<p>{$example['accordion_answer_a']}</p>
<!-- /wp:paragraph --></div>
<!-- /wp:accordion-panel --></div>
<!-- /wp:accordion-item -->

<!-- wp:accordion-item -->
<div class="wp-block-accordion-item"><!-- wp:accordion-heading -->
<h3 class="wp-block-accordion-heading has-icon has-icon-right"><button type="button" class="wp-block-accordion-heading__toggle"><span class="wp-block-accordion-heading__toggle-title">{$example['accordion_question_b']}</span><span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span></button></h3>
<!-- /wp:accordion-heading -->

<!-- wp:accordion-panel -->
<div role="region" class="wp-block-accordion-panel"><!-- wp:paragraph -->
<p>{$example['accordion_answer_b']}</p>
<!-- /wp:paragraph --></div>
<!-- /wp:accordion-panel --></div>
<!-- /wp:accordion-item -->

<!-- wp:accordion-item -->
<div class="wp-block-accordion-item"><!-- wp:accordion-heading -->
<h3 class="wp-block-accordion-heading has-icon has-icon-right"><button type="button" class="wp-block-accordion-heading__toggle"><span class="wp-block-accordion-heading__toggle-title">{$example['accordion_question_c']}</span><span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span></button></h3>
<!-- /wp:accordion-heading -->

<!-- wp:accordion-panel -->
<div role="region" class="wp-block-accordion-panel"><!-- wp:paragraph -->
<p>{$example['accordion_answer_c']}</p>
<!-- /wp:paragraph --></div>
<!-- /wp:accordion-panel --></div>
<!-- /wp:accordion-item --></div>
<!-- /wp:accordion -->
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

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'sci-product-story', plugins_url( 'assets/css/product-story.css', __FILE__ ), array(), '1.5.1' );
} );
