<?php
/**
 * Bloque: ¿Qué dicen de nosotros?
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title     = get_field( 'title' ) ?: __( '¿Qué dicen de nosotros?', 'mauswp' );
$shortcode = get_field( 'shortcode' ) ?: '[trustindex no-registration=google]';

$block_id = ! empty( $block['anchor'] )
	? sanitize_title( $block['anchor'] )
	: 'que-dicen-de-nosotros-' . ( ! empty( $block['id'] ) ? sanitize_title( $block['id'] ) : wp_generate_uuid4() );
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="py-14 md:py-16">
	<div class="mx-auto w-full max-w-7xl px-4 lg:px-8">
		<?php if ( $title ) : ?>
			<h2 class="text-4xl font-bold font-['Montserrat'] text-indigo-950 leading-tight">
				<?php echo esc_html( $title ); ?>
			</h2>
		<?php endif; ?>

		<?php if ( $shortcode ) : ?>
			<div class="mt-6 mauswp-trustindex">
				<?php echo do_shortcode( wp_kses_post( $shortcode ) ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
