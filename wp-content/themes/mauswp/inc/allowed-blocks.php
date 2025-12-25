<?php
// Whitelist de bloques permitidos
function mauswp_allowed_blocks() {
    return [
        // Añade aquí los que vayas creando
        'acf/hero-main',
        'acf/compromiso-postventa',
        'acf/autocaravanas-carousel-tabs',
        'acf/informacion-financiacion',
        'acf/autocaravanas-categorias',
        'acf/quienes-somos',
        'acf/que-dicen-de-nosotros',
        'acf/slider-texto-banner',
        'acf/contactanos',
    ];
}

// Limitar bloques en el editor
add_filter( 'allowed_block_types_all', function( $allowed_blocks, $editor_context ) {

    // Si necesitas filtrarlo por post_type, deja esto.  
    // Si no, eliminalo.
    if ( isset( $editor_context->post )
        && $editor_context->post->post_type !== 'page' ) {
        return $allowed_blocks;
    }

    return mauswp_allowed_blocks();

}, 10, 2 );
