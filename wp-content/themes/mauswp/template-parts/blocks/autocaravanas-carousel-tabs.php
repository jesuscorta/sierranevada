<?php
/**
 * Bloque: Autocaravanas - Carrusel con tabs
 *
 * Reqs:
 * - CPT: autocaravanas
 * - ACF (del CPT): meta_key "estado" con values: "disponible" / "vendida"
 * - ACF (del bloque): title, subtitle, default_tab, limit
 * - JS: inicializa Swiper y cambia slides según tab (usa data-attrs)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Assets mínimos para el carrusel (Swiper).
wp_enqueue_style(
	'mauswp-swiper',
	'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css',
	[],
	'9.4.1'
);
wp_enqueue_script(
	'mauswp-swiper',
	'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js',
	[],
	'9.4.1',
	true
);

/**
 * Ajusta esto si tu campo ACF del estado usa otro name o valores.
 */
const MAUSWP_AUTOCARAVANA_STATUS_META_KEY = 'estado';
const MAUSWP_AUTOCARAVANA_STATUS_AVAILABLE = 'disponible';
const MAUSWP_AUTOCARAVANA_STATUS_SOLD      = 'vendida';

$block_title    = (string) get_field('title');
$block_subtitle = (string) get_field('subtitle');
$default_tab    = (string) ( get_field('default_tab') ?: MAUSWP_AUTOCARAVANA_STATUS_AVAILABLE );
$limit          = (int) ( get_field('limit') ?: 10 );
$limit          = max(1, min(30, $limit));

$block_id = ! empty($block['anchor'])
	? sanitize_title($block['anchor'])
	: 'autocaravanas-carousel-tabs-' . ( ! empty($block['id']) ? sanitize_title($block['id']) : wp_generate_uuid4() );

$ajax_url = admin_url('admin-ajax.php');
$nonce    = wp_create_nonce('mauswp_autocaravanas_tabs');

/**
 * Conteo de disponibles (para la línea informativa).
 */
$count_available = (int) (new WP_Query([
	'post_type'      => 'autocaravanas',
	'posts_per_page' => 1,
	'fields'         => 'ids',
	'meta_query'     => [
		[
			'key'     => MAUSWP_AUTOCARAVANA_STATUS_META_KEY,
			'value'   => MAUSWP_AUTOCARAVANA_STATUS_AVAILABLE,
			'compare' => '=',
		],
	],
]))->found_posts;

/**
 * Query inicial según tab por defecto.
 */
$initial_query = new WP_Query([
	'post_type'      => 'autocaravanas',
	'posts_per_page' => $limit,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'meta_query'     => [
		[
			'key'     => MAUSWP_AUTOCARAVANA_STATUS_META_KEY,
			'value'   => $default_tab,
			'compare' => '=',
		],
	],
]);

$tabs = [
	MAUSWP_AUTOCARAVANA_STATUS_AVAILABLE => __('Disponibles', 'mauswp'),
	MAUSWP_AUTOCARAVANA_STATUS_SOLD      => __('Vendidas', 'mauswp'),
];

function mauswp_tab_arrow_svg( $direction = 'down' ) {
	if ( $direction === 'down' ) {
		return '
<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
	<path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';
	}
	return '
<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
	<path d="M6 15l6-6 6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';
}

$nav_left_svg = '
<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
	<path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';

$nav_right_svg = '
<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
	<path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';

$icon_up   = mauswp_tab_arrow_svg( 'up' );
$icon_down = mauswp_tab_arrow_svg( 'down' );

$car_svg = '
<svg xmlns="http://www.w3.org/2000/svg" width="27" height="21" viewBox="0 0 27 21" fill="none">
  <g clip-path="url(#clip0_33438_1024)">
    <path d="M27 18.8689V13.7388C26.9803 13.6646 26.962 13.5904 26.9436 13.5162C26.8977 13.3307 26.8545 13.1549 26.7915 12.9955C26.4113 12.02 25.7308 11.3757 24.7697 11.0775C24.5743 11.0171 24.3068 10.8893 24.2426 10.473C24.1324 9.75998 24.0184 9.0483 23.9056 8.33525C23.84 7.92308 23.7745 7.50953 23.7089 7.09736C23.6984 7.0273 23.6971 6.96547 23.6958 6.90502C23.6958 6.88166 23.6958 6.85556 23.6932 6.82671L23.6761 6.42003H24.0642C24.1416 6.42003 24.2203 6.42416 24.2963 6.4269C24.434 6.43377 24.5651 6.44064 24.6661 6.41729C24.7828 6.39118 25.0542 6.33211 25.1656 6.22494C25.6495 5.75919 26.1215 5.25497 26.5765 4.76586L26.8413 4.48147C26.8859 4.43475 26.9305 4.36194 26.9777 4.285C26.9843 4.27401 26.9908 4.26302 26.9987 4.25203V3.98137C26.979 3.95114 26.958 3.91542 26.9397 3.87558L26.6066 3.18039C26.1635 2.25438 25.7045 1.29678 25.2601 0.351535C25.1394 0.0973647 24.986 -0.00292969 24.7094 -0.00292969C19.6285 -0.00292969 14.549 -0.00155579 9.46809 -0.00155579H0.545455C0.163899 0.001192 0 0.174303 0 0.579602C0 6.58765 0 12.5957 0 18.6037C0 19.0269 0.157343 19.1931 0.561189 19.1931H1.09878C2.00481 19.1945 2.94231 19.1959 3.86276 19.189C4.18794 19.1822 4.40166 19.3319 4.53802 19.6452C4.92483 20.5341 5.58829 20.9888 6.50874 20.9971C7.44755 21.0081 8.1215 20.5588 8.51486 19.6685C8.66171 19.336 8.88461 19.1835 9.22946 19.189C13.0004 19.1959 16.7465 19.1959 20.3615 19.189H20.3641C20.722 19.189 20.9489 19.3415 21.0983 19.6836C21.4576 20.5025 22.0804 20.9435 22.9471 20.9957C23.5686 21.0328 24.4052 20.8899 24.8601 19.9982C25.1906 19.3511 25.6482 19.1025 26.3444 19.1945C26.6525 19.2357 26.8505 19.1368 26.9974 18.8689H27ZM6.53497 20.0779C5.82955 20.0779 5.25262 19.4762 5.24869 18.7356C5.24738 18.3592 5.37719 18.0143 5.61451 17.7643C5.85184 17.5156 6.17832 17.3782 6.53365 17.3782C6.8903 17.3782 7.21678 17.5142 7.4528 17.7629C7.69012 18.013 7.81993 18.3578 7.81731 18.737C7.81337 19.4775 7.23776 20.0793 6.53365 20.0793L6.53497 20.0779ZM19.1381 6.15075V18.2479H16.6954C14.2146 18.2479 11.7351 18.2479 9.25437 18.252H9.25175C8.93706 18.252 8.72465 18.138 8.57518 17.8838C8.5542 17.8481 8.53322 17.8083 8.51486 17.767C8.11626 16.8809 7.46853 16.4508 6.53628 16.4508H6.5271C5.59746 16.4536 4.94843 16.8891 4.54327 17.7822C4.45149 17.9855 4.28103 18.2534 3.86276 18.2534C3.85752 18.2534 3.85227 18.2534 3.84703 18.2534C3.57692 18.2493 3.3042 18.2465 3.03147 18.2465C2.75874 18.2465 2.48864 18.2465 2.22115 18.2465C1.98776 18.2465 1.75568 18.2465 1.52229 18.2465C1.46066 18.2465 1.40035 18.2424 1.33348 18.2383L0.895542 18.2136V0.986275L1.3361 0.962919C1.40953 0.957424 1.48164 0.953302 1.55376 0.953302H5.79808C11.8767 0.953302 17.9554 0.953302 24.0341 0.947806C24.3894 0.947806 24.6254 1.10306 24.7775 1.43554C25.087 2.1115 25.4174 2.80394 25.7871 3.55271C25.951 3.88382 25.9012 4.17921 25.639 4.43063C25.5485 4.51856 25.458 4.62435 25.3649 4.73564C25.1591 4.98019 24.9257 5.25772 24.6058 5.39648C24.2675 5.54486 23.8885 5.51876 23.5555 5.4954C23.4532 5.48853 23.3562 5.48304 23.2631 5.48029C23.2448 5.48029 23.2251 5.47892 23.2067 5.47892C22.6206 5.48029 22.0345 5.47892 21.4471 5.47892C20.8872 5.47892 20.3274 5.47892 19.7662 5.47892C19.2705 5.47892 19.1342 5.62317 19.1342 6.148L19.1381 6.15075ZM23.4165 10.9402H20.0677V6.42965H22.6954L23.4152 10.9402H23.4165ZM23.0835 20.0779C23.0835 20.0779 23.0769 20.0779 23.073 20.0779C22.7334 20.0779 22.4135 19.9378 22.1709 19.6836C21.9218 19.4226 21.7867 19.075 21.7906 18.7068C21.7946 18.3345 21.927 17.9951 22.1656 17.7519C22.4069 17.5046 22.7399 17.3714 23.1045 17.3782C23.8151 17.3892 24.3724 17.9841 24.3711 18.7301C24.3711 19.4542 23.7797 20.0711 23.0822 20.0779H23.0835ZM26.1176 18.072L25.896 18.1751C25.7203 18.2561 25.5564 18.2658 25.4108 18.2026C25.2037 18.1119 25.1184 17.9099 25.0896 17.8426C24.6949 16.907 24.0367 16.4522 23.0769 16.4508H23.073C22.1276 16.4508 21.4655 16.9056 21.0485 17.8412C20.9869 17.98 20.8872 18.0652 20.8138 18.127C20.7981 18.1407 20.781 18.1545 20.7666 18.1682L20.5188 18.421L20.0612 17.9937V11.8868H21.6713C22.3846 11.8868 23.0979 11.8868 23.8125 11.8868C25.1892 11.8895 26.1149 12.8567 26.1176 14.2952C26.1189 15.1058 26.1176 15.9178 26.1176 16.7311V18.0707V18.072Z" fill="#0F172A"/>
    <path d="M16.5539 6.10984C16.5539 5.61936 16.4227 5.48334 15.9481 5.48197C15.5836 5.48197 15.2191 5.48197 14.8559 5.48334C14.1741 5.48609 13.4699 5.48884 12.775 5.4751C12.7685 5.4751 12.7619 5.4751 12.754 5.4751C12.5718 5.4751 12.4315 5.52456 12.3358 5.62348C12.2046 5.75812 12.1784 5.96558 12.1797 6.11533C12.1994 9.05959 12.1994 12.0203 12.1797 14.9138C12.1784 15.124 12.2296 15.2861 12.3331 15.3933C12.4629 15.5279 12.6505 15.5554 12.7855 15.5526C13.4713 15.543 14.1688 15.5444 14.8414 15.5471C15.2046 15.5471 15.5665 15.5485 15.9297 15.5471C16.4123 15.5471 16.5525 15.4015 16.5525 14.9C16.5525 13.8682 16.5525 12.8378 16.5525 11.806V10.5324V9.23545C16.5525 8.19266 16.5525 7.14988 16.5525 6.10846L16.5539 6.10984ZM15.6465 14.5758H13.0897V6.4327H15.6465V14.5758Z" fill="#0F172A"/>
    <path d="M3.47201 6.06016C3.4707 7.20462 3.4707 8.3477 3.47201 9.49078C3.47201 9.88234 3.63591 10.0582 4.00042 10.0596C4.59046 10.0623 5.17918 10.0623 5.76922 10.0609H6.54282H7.29413C7.88285 10.0609 8.47027 10.0609 9.05899 10.0596C9.43137 10.0582 9.59789 9.88509 9.59789 9.5004C9.60051 8.3477 9.60051 7.195 9.59789 6.0423C9.59789 5.66311 9.43268 5.48587 9.08128 5.4845C8.2395 5.48038 7.37936 5.479 6.51004 5.479C5.64072 5.479 4.83958 5.48038 3.99518 5.4845C3.62805 5.48587 3.47201 5.65898 3.4707 6.06016H3.47201ZM4.38329 6.43111H8.67875V9.11845H4.38329V6.43111Z" fill="#0F172A"/>
  </g>
  <defs>
    <clipPath id="clip0_33438_1024">
      <rect width="27" height="21" fill="white"/>
    </clipPath>
  </defs>
</svg>';
?>

<section id="<?php echo esc_attr($block_id); ?>" class="py-16 md:py-16">
	<div class="mx-auto w-full max-w-7xl px-4 lg:px-8">

		<header class="flex flex-col gap-4">
			<?php if ( $block_title ) : ?>
				<h2 class="font-['Montserrat'] text-4xl md:text-5xl font-bold leading-tight bg-gradient-to-r from-indigo-900 to-indigo-950 bg-clip-text text-transparent">
					<?php echo esc_html($block_title); ?>
				</h2>
			<?php endif; ?>

			<?php if ( $block_subtitle ) : ?>
				<p class="font-display text-2xl font-medium leading-tight text-neutral-700">
					<?php echo esc_html($block_subtitle); ?>
				</p>
			<?php endif; ?>

			<div class="mt-2 flex flex-wrap items-center gap-3"
				data-mauswp-tabs
				data-default-tab="<?php echo esc_attr($default_tab); ?>"
				data-limit="<?php echo esc_attr($limit); ?>"
				data-ajax-url="<?php echo esc_url($ajax_url); ?>"
				data-nonce="<?php echo esc_attr($nonce); ?>"
				data-icon-up="<?php echo esc_attr( $icon_up ); ?>"
				data-icon-down="<?php echo esc_attr( $icon_down ); ?>"
			>
				<?php foreach ( $tabs as $tab_value => $tab_label ) : ?>
					<?php
					$is_active = ($tab_value === $default_tab);

					$btn_classes = $is_active
						? 'inline-flex items-center gap-2 rounded-lg border border-primary-300 bg-neutral-50 px-4 py-2 font-sans text-base font-semibold leading-none text-neutral-900'
						: 'inline-flex items-center gap-2 rounded-lg border border-neutral-400 bg-white px-4 py-2 font-sans text-base font-medium leading-none text-neutral-600';
					?>
					<button
						type="button"
						class="<?php echo esc_attr($btn_classes); ?>"
						data-mauswp-tab
						data-tab="<?php echo esc_attr($tab_value); ?>"
						aria-pressed="<?php echo $is_active ? 'true' : 'false'; ?>"
					>
						<?php echo esc_html($tab_label); ?>
						<span class="mauswp-tab-icon" aria-hidden="true">
							<?php
							echo $is_active
								? mauswp_tab_arrow_svg( 'down' )
								: mauswp_tab_arrow_svg( 'up' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</span>
					</button>
				<?php endforeach; ?>
			</div>

			<p class="mt-2 inline-flex items-center gap-2 font-sans text-sm font-normal leading-none text-neutral-700">
				<?php echo $car_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php
				printf(
					/* translators: %s: number of available vehicles */
					esc_html__('Disponibles %s Autocaravanas', 'mauswp'),
					'<span class="font-semibold">' . esc_html( number_format_i18n($count_available) ) . '</span>'
				);
				?>
			</p>
		</header>

		<div>
			<div
				class="relative pb-6"
				data-mauswp-autocaravanas-carousel
				data-active-tab="<?php echo esc_attr($default_tab); ?>"
				data-limit="<?php echo esc_attr($limit); ?>"
				data-ajax-url="<?php echo esc_url($ajax_url); ?>"
				data-nonce="<?php echo esc_attr($nonce); ?>"
			>
				<div class="mb-3 mt-4 flex justify-start gap-2 md:mt-0 md:justify-end">
					<button
						type="button"
						class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-neutral-300 bg-white text-neutral-900 shadow-sm transition hover:border-neutral-400 hover:shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-neutral-900/20 disabled:opacity-50"
						data-mauswp-carousel-prev
						aria-label="<?php echo esc_attr__('Anterior', 'mauswp'); ?>"
					>
						<?php echo $nav_left_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
					<button
						type="button"
						class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-neutral-300 bg-white text-neutral-900 shadow-sm transition hover:border-neutral-400 hover:shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-neutral-900/20 disabled:opacity-50"
						data-mauswp-carousel-next
						aria-label="<?php echo esc_attr__('Siguiente', 'mauswp'); ?>"
					>
						<?php echo $nav_right_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
				</div>

				<div class="py-5 swiper overflow-x-hidden overflow-y-visible" style="padding-bottom:10px;overflow-x: hidden; overflow-y: visible;">
					<div class="swiper-wrapper" data-mauswp-carousel-wrapper>
						<?php if ( $initial_query->have_posts() ) : ?>
							<?php foreach ( $initial_query->posts as $post ) : ?>
								<div class="swiper-slide !w-auto">
									<?php echo mauswp_render_autocaravana_card($post); ?>
								</div>
							<?php endforeach; ?>
						<?php else : ?>
							<div class="swiper-slide !w-auto">
								<div class="rounded-2xl border border-neutral-200 bg-neutral-50 p-6">
									<p class="text-sm text-neutral-700">
										<?php esc_html_e('No hay resultados para este filtro.', 'mauswp'); ?>
									</p>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div class="sr-only" data-mauswp-carousel-loading>
					<?php esc_html_e('Cargando…', 'mauswp'); ?>
				</div>
			</div>
		</div>

		<div class="mt-6 flex justify-center md:justify-end">
			<a
				class="inline-flex h-12 w-full items-center justify-center rounded-[133px] bg-[var(--color-primary-500)] px-5 py-3 text-center font-sans text-base font-semibold text-white shadow-[0_4px_8px_0_rgba(0,0,0,0.08)] transition hover:opacity-90 md:w-auto"
				href="<?php echo esc_url( get_post_type_archive_link( 'autocaravanas' ) ); ?>"
			>
				<?php esc_html_e( 'Ver todos los modelos', 'mauswp' ); ?>
			</a>
		</div>

	</div>
</section>
