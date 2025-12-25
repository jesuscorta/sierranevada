<?php
// Añade la categoría "mausWP" a las categorías existentes
function mauswp_register_block_category( $categories, $editor_context ) {

    $custom_category = [
        [
            'slug'  => 'mauswp',
            'title' => 'MAUS_WP',
            'icon'  => null,
        ],

    ];

    return array_merge( $custom_category, $categories );
}

// WP 5.8+ usa block_categories_all
add_filter( 'block_categories_all', 'mauswp_register_block_category', 10, 2 );
// Fallback por si acaso
add_filter( 'block_categories', 'mauswp_register_block_category', 10, 2 );
