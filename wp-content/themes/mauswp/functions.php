<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Carga de archivos del tema.
 */
require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/assets.php';
require_once get_template_directory() . '/inc/acf-blocks.php';
require_once get_template_directory() . '/inc/allowed-blocks.php';
require_once get_template_directory() . '/inc/block-categories.php';
require_once get_template_directory() . '/inc/autocaravanas-card.php';
require_once get_template_directory() . '/inc/autocaravanas-list.php';
require_once get_template_directory() . '/inc/footer-settings.php';
