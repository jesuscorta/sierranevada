<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Carga de estilos y scripts del tema.
 */
function mauswp_enqueue_assets() {

	$theme_version = wp_get_theme()->get( 'Version' );

	// Registrar recursos externos para usarlos solo cuando haga falta.
	wp_register_style(
		'mauswp-swiper',
		'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css',
		[],
		'9.4.1'
	);
	wp_register_script(
		'mauswp-swiper',
		'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js',
		[],
		'9.4.1',
		true
	);

	// Tipografías (hosted) optimizadas con display=swap y pesos acotados.
	wp_enqueue_style(
		'mauswp-fonts',
		'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600;700&display=swap',
		[],
		null
	);

	// CSS principal (Tailwind compilado).
	wp_enqueue_style(
		'mauswp-style',
		get_template_directory_uri() . '/dist/app.css',
		[],
		$theme_version
	);

	// JS principal (interacciones ligeras).
	wp_enqueue_script(
		'mauswp-scripts',
		get_template_directory_uri() . '/assets/src/js/app.js',
		[],
		$theme_version,
		true
	);

}
add_action( 'wp_enqueue_scripts', 'mauswp_enqueue_assets' );

/**
 * Resource hints para acelerar fuentes y CDN.
 */
function mauswp_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = [
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		];
		$urls[] = 'https://cdn.jsdelivr.net';
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'mauswp_resource_hints', 10, 2 );

/**
 * Marca scripts como defer para reducir bloqueo de render.
 */
function mauswp_defer_scripts( $tag, $handle ) {
	$defer_handles = [
		'mauswp-scripts',
		'mauswp-swiper',
	];

	if ( in_array( $handle, $defer_handles, true ) ) {
		if ( false === strpos( $tag, ' defer ' ) ) {
			$tag = str_replace( ' src', ' defer src', $tag );
		}
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'mauswp_defer_scripts', 10, 2 );

/**
 * Carga no bloqueante de CSS secundarios (fonts/swiper).
 */
function mauswp_async_styles( $html, $handle, $href, $media ) {
	if ( 'mauswp-fonts' === $handle ) {
		$href = esc_url( $href );
		return sprintf(
			'<link rel="preload" as="style" href="%1$s" onload="this.onload=null;this.rel=\'stylesheet\'">' .
			'<noscript><link rel="stylesheet" href="%1$s"></noscript>' . "\n",
			$href
		);
	}

	if ( 'mauswp-swiper' === $handle ) {
		$href = esc_url( $href );
		return sprintf(
			'<link rel="stylesheet" href="%s" media="print" onload="this.media=\'all\'">' . "\n",
			$href
		);
	}

	return $html;
}
add_filter( 'style_loader_tag', 'mauswp_async_styles', 10, 4 );

/**
 * Desactiva scripts de emoji para ahorrar bytes.
 */
function mauswp_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', function( $plugins ) {
		if ( ! is_array( $plugins ) ) {
			return $plugins;
		}
		return array_diff( $plugins, [ 'wpemoji' ] );
	} );
}
add_action( 'init', 'mauswp_disable_emojis' );

/**
 * Descarga estilos de plugins cuando no se usan en la pagina.
 */
function mauswp_dequeue_unused_styles() {
	if ( is_admin() ) {
		return;
	}

	$has_instagram = false;
	if ( is_singular() ) {
		$post = get_post();
		if ( $post instanceof WP_Post ) {
			$has_instagram = has_shortcode( $post->post_content, 'instagram-feed' )
				|| has_shortcode( $post->post_content, 'sbi' )
				|| has_shortcode( $post->post_content, 'sb_instagram' );
		}
	}

	if ( ! $has_instagram ) {
		wp_dequeue_style( 'sbi-styles' );
	}
}
add_action( 'wp_enqueue_scripts', 'mauswp_dequeue_unused_styles', 100 );

/**
 * Preload de la imagen LCP cuando un bloque la define.
 */
function mauswp_preload_lcp_image() {
	if ( empty( $GLOBALS['mauswp_lcp_image'] ) ) {
		return;
	}
	$href = esc_url( $GLOBALS['mauswp_lcp_image'] );
	if ( '' === $href ) {
		return;
	}

	printf(
		'<link rel="preload" as="image" href="%s" fetchpriority="high" />' . "\n",
		$href
	);
}
add_action( 'wp_head', 'mauswp_preload_lcp_image', 2 );
