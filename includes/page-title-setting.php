<?php
namespace SCI\DesignBlocks;
const HIDE_TITLES_OPTION = 'sci_hide_page_titles';
add_action( 'admin_menu', __NAMESPACE__ . '\\add_title_setting_page' );
add_action( 'admin_init', __NAMESPACE__ . '\\register_title_setting' );
add_filter( 'the_title', __NAMESPACE__ . '\\filter_page_title', 10, 2 );
add_filter( 'render_block_core/post-title', __NAMESPACE__ . '\\filter_post_title_block', 10, 2 );
function add_title_setting_page(): void {
 add_options_page( __( 'SCI Design Blocks', 'sci-design-blocks' ), __( 'SCI Design Blocks', 'sci-design-blocks' ), 'manage_options', 'sci-design-blocks', __NAMESPACE__ . '\\render_title_settings' );
}
function register_title_setting(): void {
 register_setting( 'sci_design_blocks', HIDE_TITLES_OPTION, array( 'type' => 'boolean', 'sanitize_callback' => 'rest_sanitize_boolean', 'default' => false ) );
 add_settings_section( 'sci_display', __( 'Visualización', 'sci-design-blocks' ), '__return_null', 'sci-design-blocks' );
 add_settings_field( HIDE_TITLES_OPTION, __( 'Títulos de página', 'sci-design-blocks' ), __NAMESPACE__ . '\\render_title_checkbox', 'sci-design-blocks', 'sci_display' );
}
function render_title_checkbox(): void {
 echo '<input type="hidden" name="' . esc_attr( HIDE_TITLES_OPTION ) . '" value="0"><label><input type="checkbox" name="' . esc_attr( HIDE_TITLES_OPTION ) . '" value="1" ' . checked( (bool) get_option( HIDE_TITLES_OPTION, false ), true, false ) . '> ';
 esc_html_e( 'Ocultar los títulos que el tema muestra en todas las páginas', 'sci-design-blocks' );
 echo '</label><p class="description">';
 esc_html_e( 'El nombre de cada página se conserva en WordPress. No añade CSS.', 'sci-design-blocks' );
 echo '</p>';
}
function render_title_settings(): void {
 echo '<div class="wrap"><h1>' . esc_html__( 'SCI Design Blocks', 'sci-design-blocks' ) . '</h1><form method="post" action="options.php">';
 settings_fields( 'sci_design_blocks' );
 do_settings_sections( 'sci-design-blocks' );
 submit_button();
 echo '</form></div>';
}
function hide_page_titles(): bool {
 return (bool) get_option( HIDE_TITLES_OPTION, false );
}
function filter_page_title( string $title, int $post_id ): string {
 if ( hide_page_titles() && ! is_admin() && 'page' === get_post_type( $post_id ) && in_the_loop() && is_main_query() && is_singular( 'page' ) ) {
  return '';
 }
 return $title;
}
function filter_post_title_block( string $content, array $block ): string {
 $post_id = isset( $block['attrs']['postId'] ) ? (int) $block['attrs']['postId'] : (int) get_queried_object_id();
 return ! is_admin() && hide_page_titles() && 'page' === get_post_type( $post_id ) ? '' : $content;
}


add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_title_editor_setting' );
function enqueue_title_editor_setting(): void {
 $screen = get_current_screen();
 if ( ! $screen || 'page' !== $screen->post_type || ! hide_page_titles() ) { return; }
 wp_enqueue_script( 'wp-hooks' );
 wp_enqueue_script( 'sci-page-title-editor', plugins_url( 'assets/js-page-title-editor.js', dirname( __DIR__ ) . '/sci-design-blocks.php' ), array( 'wp-hooks' ), '1.6.4', true );
 wp_add_inline_script( 'sci-page-title-editor', 'window.sciDesignBlocksTitleSettings = { hideTitles: true };', 'before' );
}
