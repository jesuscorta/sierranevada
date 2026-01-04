<?php
/**
 * Funciones de listados y AJAX para autocaravanas.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Aseguramos que la card está disponible.
require_once get_template_directory() . '/inc/autocaravanas-card.php';

/**
 * AJAX carga más autocaravanas (grid simple).
 */
function mauswp_ajax_autocaravanas_load_more() {
	$offset   = isset( $_GET['offset'] ) ? max( 0, (int) $_GET['offset'] ) : 0;
	$per_page = isset( $_GET['per_page'] ) ? max( 1, (int) $_GET['per_page'] ) : 8;
	$tipo     = isset( $_GET['tipo'] ) ? sanitize_text_field( wp_unslash( $_GET['tipo'] ) ) : '';
	$plazas   = isset( $_GET['plazas'] ) ? sanitize_text_field( wp_unslash( $_GET['plazas'] ) ) : '';
	$motor    = isset( $_GET['motor'] ) ? sanitize_text_field( wp_unslash( $_GET['motor'] ) ) : '';
	$marca    = isset( $_GET['marca'] ) ? sanitize_text_field( wp_unslash( $_GET['marca'] ) ) : '';
	$hide_sold = isset( $_GET['hide_sold'] ) ? (bool) intval( $_GET['hide_sold'] ) : false;

	$meta_query = [ 'relation' => 'AND' ];

	if ( $tipo ) {
		$meta_query[] = [
			'key'     => 'tipo_autocaravana',
			'value'   => $tipo,
			'compare' => '=',
		];
	}

	if ( $plazas ) {
		$meta_query[] = [
			'key'     => 'plazas_viajar',
			'value'   => $plazas,
			'compare' => '=',
		];
	}

	if ( $motor ) {
		$meta_query[] = [
			'key'     => 'motor',
			'value'   => $motor,
			'compare' => '=',
		];
	}

	if ( $marca ) {
		$meta_query[] = [
			'key'     => 'marca',
			'value'   => $marca,
			'compare' => '=',
		];
	}

	if ( $hide_sold ) {
		$meta_query[] = [
			'relation' => 'OR',
			[
				'key'     => 'estado',
				'compare' => 'NOT EXISTS',
			],
			[
				'key'     => 'estado',
				'value'   => 'vendida',
				'compare' => '!=',
			],
		];
	}

	$query = new WP_Query(
		[
			'post_type'      => 'autocaravanas',
			'posts_per_page' => $per_page,
			'offset'         => $offset,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'meta_query'     => $meta_query,
		]
	);

	$html = '';
	if ( $query->have_posts() ) {
		foreach ( $query->posts as $post ) {
			$html .= mauswp_render_autocaravana_card( $post );
		}
	}

	wp_send_json_success(
		[
			'html'     => $html,
			'has_more' => ( $query->found_posts > $offset + $per_page ),
		]
	);
}
add_action( 'wp_ajax_mauswp_autocaravanas_load_more', 'mauswp_ajax_autocaravanas_load_more' );
add_action( 'wp_ajax_nopriv_mauswp_autocaravanas_load_more', 'mauswp_ajax_autocaravanas_load_more' );

/**
 * AJAX: obtiene autocaravanas filtradas por estado (para tabs/carrusel).
 */
function mauswp_ajax_autocaravanas_tabs() {
	$estado = isset( $_GET['estado'] ) ? sanitize_text_field( wp_unslash( $_GET['estado'] ) ) : '';
	$limit  = isset( $_GET['limit'] ) ? max( 1, (int) $_GET['limit'] ) : 10;
	$nonce  = isset( $_GET['nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['nonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, 'mauswp_autocaravanas_tabs' ) ) {
		wp_send_json_error( [ 'message' => 'Invalid nonce' ], 403 );
	}

	$query = new WP_Query(
		[
			'post_type'      => 'autocaravanas',
			'posts_per_page' => $limit,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'meta_query'     => $estado ?
				[
					[
						'key'     => 'estado',
						'value'   => $estado,
						'compare' => '=',
					],
				] : [],
		]
	);

	$html = '';
	if ( $query->have_posts() ) {
		foreach ( $query->posts as $post ) {
			$html .= '<div class="swiper-slide !w-auto">' . mauswp_render_autocaravana_card( $post ) . '</div>';
		}
	} else {
		$html .= '<div class="swiper-slide !w-auto"><div class="rounded-2xl border border-neutral-200 bg-neutral-50 p-6"><p class="text-sm text-neutral-700">' . esc_html__( 'No hay resultados para este filtro.', 'mauswp' ) . '</p></div></div>';
	}

	wp_send_json_success(
		[
			'html'      => $html,
			'found'     => $query->found_posts,
			'has_posts' => $query->have_posts(),
		]
	);
}
add_action( 'wp_ajax_mauswp_autocaravanas_tabs', 'mauswp_ajax_autocaravanas_tabs' );
add_action( 'wp_ajax_nopriv_mauswp_autocaravanas_tabs', 'mauswp_ajax_autocaravanas_tabs' );
