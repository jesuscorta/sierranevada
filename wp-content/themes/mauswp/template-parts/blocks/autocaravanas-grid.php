<?php
/**
 * Bloque: Grid de autocaravanas con configuración de filtros.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/autocaravanas-card.php';

$show_filters_button = (bool) get_field( 'mostrar_boton_filtros' );

$use_tipo   = (bool) get_field( 'activar_filtro_tipo' );
$use_plazas = (bool) get_field( 'activar_filtro_plazas' );
$use_motor  = (bool) get_field( 'activar_filtro_motor' );
$use_marca  = (bool) get_field( 'activar_filtro_marca' );

$filter_tipo   = $use_tipo ? ( get_field( 'filtro_tipo' ) ?: '' ) : '';
$filter_plazas = $use_plazas ? ( get_field( 'filtro_plazas' ) ?: '' ) : '';
$filter_motor  = $use_motor ? ( get_field( 'filtro_motor' ) ?: '' ) : '';
$filter_marca  = $use_marca ? ( get_field( 'filtro_marca' ) ?: '' ) : '';
$incluir_vendidas = (bool) get_field( 'incluir_vendidas' );

$hide_sold = ! $incluir_vendidas;

$meta_query = [ 'relation' => 'AND' ];

if ( $filter_tipo ) {
	$meta_query[] = [
		'key'     => 'tipo',
		'value'   => $filter_tipo,
		'compare' => '=',
	];
}

if ( $filter_plazas ) {
	$meta_query[] = [
		'key'     => 'plazas',
		'value'   => $filter_plazas,
		'compare' => '=',
	];
}

if ( $filter_motor ) {
	$meta_query[] = [
		'key'     => 'motor',
		'value'   => $filter_motor,
		'compare' => '=',
	];
}

if ( $filter_marca ) {
	$meta_query[] = [
		'key'     => 'marca',
		'value'   => $filter_marca,
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

$initial_per_page = 12;
$load_more_per    = 6;

$query = new WP_Query(
	[
		'post_type'      => 'autocaravanas',
		'posts_per_page' => $initial_per_page,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'meta_query'     => $meta_query,
	]
);

$block_id = 'autocaravanas-grid-' . ( $block['id'] ?? wp_generate_uuid4() );
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="w-full bg-[#F1F5F9] py-12 md:py-16">
	<div class="mx-auto w-full max-w-7xl px-4 lg:px-8">
		<div class="flex flex-col gap-6">
			<div class="flex flex-col items-start gap-4 md:flex-row md:items-center md:justify-between">
				<div class="inline-flex items-center gap-2 rounded-md bg-white px-4 py-2 text-sm font-semibold text-[#1A2250] shadow-sm">
					<span class="inline-flex h-5 w-5 items-center justify-center text-[#1A2250]">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 17h16M4 12h16M4 7h16M9 17v-2m0-3V7m6 10v-4m0-3V7" />
						</svg>
					</span>
					<?php
					printf(
						/* translators: %d number of autocaravanas */
						esc_html__( 'Disponibles %d Autocaravanas', 'mauswp' ),
						(int) $query->found_posts
					);
					?>
				</div>

				<?php if ( $show_filters_button ) : ?>
					<button type="button" class="inline-flex items-center gap-2 rounded-lg border-2 border-[#1A2250] px-4 py-2 text-base font-semibold text-[#1A2250] transition hover:bg-[#1A2250] hover:text-white">
						<span><?php esc_html_e( 'Filtros', 'mauswp' ); ?></span>
						<span aria-hidden="true">
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/filtro.svg' ); ?>" alt="" class="h-5 w-5" />
						</span>
					</button>
				<?php endif; ?>
			</div>

			<div
				class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
				data-autos-grid
				data-ajax-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
				data-offset="<?php echo (int) $initial_per_page; ?>"
				data-per-page="<?php echo (int) $load_more_per; ?>"
				data-total="<?php echo (int) $query->found_posts; ?>"
				data-filter-tipo="<?php echo esc_attr( $filter_tipo ); ?>"
				data-filter-plazas="<?php echo esc_attr( $filter_plazas ); ?>"
				data-filter-motor="<?php echo esc_attr( $filter_motor ); ?>"
				data-filter-marca="<?php echo esc_attr( $filter_marca ); ?>"
				data-hide-sold="<?php echo $hide_sold ? '1' : '0'; ?>"
			>
				<?php
				if ( $query->have_posts() ) :
					foreach ( $query->posts as $post ) :
						echo mauswp_render_autocaravana_card( $post ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					endforeach;
				else :
					?>
					<p class="text-base text-slate-600"><?php esc_html_e( 'No hay autocaravanas disponibles con este filtro.', 'mauswp' ); ?></p>
					<?php
				endif;
				?>
			</div>

			<?php if ( $query->found_posts > $initial_per_page ) : ?>
				<div class="flex justify-center">
					<button
						type="button"
						class="inline-flex items-center gap-2 rounded-full bg-[#1A2250] px-6 py-3 text-base font-semibold text-white shadow-md transition hover:bg-[#13193b]"
						data-autos-load-more
					>
						<span><?php esc_html_e( 'Mostrar más', 'mauswp' ); ?></span>
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none">
							<path d="M12 5v14m-7-7h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</button>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
