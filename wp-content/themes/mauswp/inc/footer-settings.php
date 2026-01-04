<?php
/**
 * Opciones y utilidades del footer.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Página de opciones para el footer.
 */
function mauswp_register_footer_options_page() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	if ( ! function_exists( 'mauswp_acf_root_page' ) ) {
		acf_add_options_page(
			[
				'page_title' => __( 'Opciones del tema', 'mauswp' ),
				'menu_title' => __( 'Opciones del tema', 'mauswp' ),
				'menu_slug'  => 'mauswp-theme-options',
				'capability' => 'manage_options',
				'redirect'   => true,
			]
		);
	}

	acf_add_options_sub_page(
		[
			'page_title'  => __( 'Header', 'mauswp' ),
			'menu_title'  => __( 'Header', 'mauswp' ),
			'parent_slug' => 'mauswp-theme-options',
			'menu_slug'   => 'mauswp-header',
			'capability'  => 'manage_options',
		]
	);

	acf_add_options_sub_page(
		[
			'page_title'  => __( 'Footer', 'mauswp' ),
			'menu_title'  => __( 'Footer', 'mauswp' ),
			'parent_slug' => 'mauswp-theme-options',
			'menu_slug'   => 'mauswp-footer',
			'capability'  => 'manage_options',
		]
	);

	acf_add_options_sub_page(
		[
			'page_title'  => __( 'Autocaravana', 'mauswp' ),
			'menu_title'  => __( 'Autocaravana', 'mauswp' ),
			'parent_slug' => 'mauswp-theme-options',
			'menu_slug'   => 'mauswp-autocaravana',
			'capability'  => 'manage_options',
		]
	);
}
add_action( 'acf/init', 'mauswp_register_footer_options_page' );

/**
 * Devuelve items de un menú por location.
 */
function mauswp_get_menu_items_by_location( string $location ): array {
	$locations = get_nav_menu_locations();
	if ( empty( $locations[ $location ] ) ) {
		return [];
	}

	$menu = wp_get_nav_menu_object( $locations[ $location ] );
	if ( ! $menu ) {
		return [];
	}

	$items = wp_get_nav_menu_items( $menu->term_id, [ 'update_post_term_cache' => false ] );

	return is_array( $items ) ? $items : [];
}

/**
 * Renderiza un menú del footer con separador personalizado.
 */
function mauswp_render_footer_menu( string $location, string $class = '' ): string {
	$items = mauswp_get_menu_items_by_location( $location );
	if ( ! $items ) {
		return '';
	}

	$links = [];
	foreach ( $items as $item ) {
		$links[] = sprintf(
			'<a href="%1$s" class="hover:text-slate-50 transition-colors">%2$s</a>',
			esc_url( $item->url ),
			esc_html( $item->title )
		);
	}

	return sprintf(
		'<nav class="%1$s">%2$s</nav>',
		esc_attr( $class ),
		implode( '<span class="px-2 text-slate-300">|</span>', $links )
	);
}
