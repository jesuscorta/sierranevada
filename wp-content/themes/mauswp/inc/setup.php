<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Configuración básica del tema.
 */
function mauswp_setup() {
	// <title> automático en el head.
	add_theme_support( 'title-tag' );

	// Imágenes destacadas.
	add_theme_support( 'post-thumbnails' );

	// Menú principal.
	register_nav_menus(
		array(
			'primary' => __( 'Menú principal', 'mauswp' ),
			'topbar'  => __( 'Menú top bar (legacy)', 'mauswp' ),
			'header_topbar' => __( 'Menú top bar header', 'mauswp' ),
			'footer_col_2' => __( 'Footer columna 2', 'mauswp' ),
			'footer_col_3' => __( 'Footer columna 3', 'mauswp' ),
			'footer_col_4' => __( 'Footer columna 4', 'mauswp' ),
			'footer_col_5' => __( 'Footer columna 5', 'mauswp' ),
			'footer_legal' => __( 'Footer legal', 'mauswp' ),
		)
	);

	// Logo personalizado.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Soporte HTML5.
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
}
add_action( 'after_setup_theme', 'mauswp_setup' );

/**
 * Permitir subida de SVG.
 */
function mauswp_allow_svg_uploads( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'mauswp_allow_svg_uploads' );
