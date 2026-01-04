<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Registro de bloques ACF del tema
function mauswp_register_acf_blocks() {

	if ( ! function_exists( 'acf_register_block_type' ) ) {
		return;
	}

	acf_register_block_type( [
		'name'            => 'hero-main',
		'title'           => __( 'Hero principal', 'mauswp' ),
		'description'     => __( 'Hero principal con imagen/vídeo de fondo y CTA', 'mauswp' ),
		// Usa la plantilla existente en template-parts
		'render_template' => 'template-parts/blocks/hero-main.php',
		'category'        => 'home',
		'icon'            => 'cover-image',
		'keywords'        => [ 'hero', 'portada', 'cabecera' ],
		// Modo edición por defecto (sin vista previa ni sidebar de cambio)
		'mode'            => 'edit',
		'supports'        => [
			'align'  => false,
			// No permitir alternar a preview desde el toolbar
			'mode'   => false,
			'jsx'    => false,
			'anchor' => true,
		],
	] );

	acf_register_block_type( [
		'name'            => 'compromiso-postventa',
		'title'           => __( 'Compromiso postventa', 'mauswp' ),
		'description'     => __( 'Bloque de compromiso postventa', 'mauswp' ),
		'render_template' => 'template-parts/blocks/compromiso-postventa.php',
		'category'        => 'common',
		'icon'            => 'awards',
		'keywords'        => [ 'postventa', 'compromiso', 'servicio' ],
		'mode'            => 'edit',
		'supports'        => [
			'align'  => false,
			'mode'   => false,
			'jsx'    => false,
			'anchor' => true,
		],
	] );

	acf_register_block_type( [
		'name'            => 'autocaravanas-carousel-tabs',
		'title'           => __( 'Autocaravanas carrusel', 'mauswp' ),
		'description'     => __( 'Carrusel con filtro disponibles y vendidas', 'mauswp' ),
		'render_template' => 'template-parts/blocks/autocaravanas-carousel-tabs.php',
		'category'        => 'common',
		'icon'            => 'excerpt-view',
		'keywords'        => [ 'autocaravanas', 'listado', 'inventario' ],
		'mode'            => 'edit',
		'supports'        => [
			'align'  => false,
			'mode'   => false,
			'jsx'    => false,
			'anchor' => true,
		],
	] );

	acf_register_block_type( [
		'name'            => 'informacion-financiacion',
		'title'           => __( 'Información financiación', 'mauswp' ),
		'description'     => __( 'Bloque informativo de financiación a dos columnas', 'mauswp' ),
		'render_template' => 'template-parts/blocks/informacion-financiacion.php',
		'category'        => 'common',
		'icon'            => 'money-alt',
		'keywords'        => [ 'financiacion', 'cta', 'informacion' ],
		'mode'            => 'edit',
		'supports'        => [
			'align'  => false,
			'mode'   => false,
			'jsx'    => false,
			'anchor' => true,
		],
	] );

	acf_register_block_type( [
		'name'            => 'autocaravanas-categorias',
		'title'           => __( 'Autocaravanas categorías', 'mauswp' ),
		'description'     => __( 'Grid de categorías/etiquetas de autocaravanas con filtros', 'mauswp' ),
		'render_template' => 'template-parts/blocks/autocaravanas-categorias.php',
		'category'        => 'common',
		'icon'            => 'grid-view',
		'keywords'        => [ 'autocaravanas', 'categorias', 'filtros' ],
		'mode'            => 'edit',
		'supports'        => [
			'align'  => false,
			'mode'   => false,
			'jsx'    => false,
			'anchor' => true,
		],
	] );

	acf_register_block_type( [
		'name'            => 'quienes-somos',
		'title'           => __( 'Quiénes somos', 'mauswp' ),
		'description'     => __( 'Bloque de dos filas con texto y media alternable', 'mauswp' ),
		'render_template' => 'template-parts/blocks/quienes-somos.php',
		'category'        => 'common',
		'icon'            => 'groups',
		'keywords'        => [ 'quienes somos', 'empresa', 'historia' ],
		'mode'            => 'edit',
		'supports'        => [
			'align'  => false,
			'mode'   => false,
			'jsx'    => false,
			'anchor' => true,
		],
	] );

	acf_register_block_type( [
		'name'            => 'que-dicen-de-nosotros',
		'title'           => __( 'Qué dicen de nosotros', 'mauswp' ),
		'description'     => __( 'Bloque con título y shortcode de reseñas', 'mauswp' ),
		'render_template' => 'template-parts/blocks/que-dicen-de-nosotros.php',
		'category'        => 'common',
		'icon'            => 'format-quote',
		'keywords'        => [ 'reseñas', 'opiniones', 'trustindex' ],
		'mode'            => 'edit',
		'supports'        => [
			'align'  => false,
			'mode'   => false,
			'jsx'    => false,
			'anchor' => true,
		],
	] );

	acf_register_block_type( [
		'name'            => 'slider-texto-banner',
		'title'           => __( 'Slider texto banner', 'mauswp' ),
		'description'     => __( 'Banner con imagen de fondo y slider de texto', 'mauswp' ),
		'render_template' => 'template-parts/blocks/slider-texto-banner.php',
		'category'        => 'common',
		'icon'            => 'slides',
		'keywords'        => [ 'slider', 'texto', 'banner' ],
		'mode'            => 'edit',
		'supports'        => [
			'align'  => false,
			'mode'   => false,
			'jsx'    => false,
			'anchor' => true,
		],
	] );

	acf_register_block_type( [
		'name'            => 'contactanos',
		'title'           => __( 'Contáctanos', 'mauswp' ),
		'description'     => __( 'Bloque de contacto con tarjetas y datos', 'mauswp' ),
		'render_template' => 'template-parts/blocks/contactanos.php',
		'category'        => 'common',
		'icon'            => 'phone',
		'keywords'        => [ 'contacto', 'contactanos', 'equipo' ],
		'mode'            => 'edit',
		'supports'        => [
			'align'  => false,
			'mode'   => false,
			'jsx'    => false,
			'anchor' => true,
		],
	] );

	acf_register_block_type( [
		'name'            => 'autocaravanas-grid',
		'title'           => __( 'Autocaravanas grid', 'mauswp' ),
		'description'     => __( 'Grid de autocaravanas con filtros configurables', 'mauswp' ),
		'render_template' => 'template-parts/blocks/autocaravanas-grid.php',
		'category'        => 'common',
		'icon'            => 'grid-view',
		'keywords'        => [ 'autocaravanas', 'grid', 'listado' ],
		'mode'            => 'edit',
		'supports'        => [
			'align'  => false,
			'mode'   => false,
			'jsx'    => false,
			'anchor' => true,
		],
	] );
}
add_action( 'acf/init', 'mauswp_register_acf_blocks' );
