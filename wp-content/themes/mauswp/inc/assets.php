<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Carga de estilos y scripts del tema.
 */
function mauswp_enqueue_assets() {

	$theme_version = wp_get_theme()->get( 'Version' );

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
