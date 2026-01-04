<?php
/**
 * Template Name: Autocaravanas
 *
 * Página de listado de autocaravanas con filtros y carga incremental.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/autocaravanas-card.php';

get_header();

$intro = function_exists( 'get_field' ) ? get_field( 'introduccion' ) : '';

$per_page = 15;
$query    = new WP_Query(
	[
		'post_type'      => 'autocaravanas',
		'posts_per_page' => $per_page,
		'orderby'        => 'date',
		'order'          => 'DESC',
	]
);
?>

<main class="bg-[#F1F5F9] pt-28 pb-16 md:pt-32">
	<div class="mx-auto w-full max-w-7xl px-4 lg:px-8">
		<div class="flex flex-col gap-6">
			<div class="text-sm text-slate-600">
				<?php if ( function_exists( 'aioseo_breadcrumbs' ) ) : ?>
					<?php aioseo_breadcrumbs(); ?>
				<?php else : ?>
					<nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'mauswp' ); ?>">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-slate-700 hover:text-slate-900"><?php esc_html_e( 'Inicio', 'mauswp' ); ?></a>
						<span class="px-2 text-slate-400">/</span>
						<span class="font-semibold text-slate-900"><?php the_title(); ?></span>
					</nav>
				<?php endif; ?>
			</div>

			<header class="space-y-4">
				<h1 class="text-4xl font-black leading-tight text-[#1A2250] md:text-5xl"><?php the_title(); ?></h1>
				<?php if ( $intro ) : ?>
					<div class="max-w-3xl text-lg font-normal leading-relaxed text-slate-700 md:text-xl">
						<?php echo wp_kses_post( wpautop( $intro ) ); ?>
					</div>
				<?php endif; ?>
			</header>

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

				<button type="button" class="inline-flex items-center gap-2 rounded-lg border-2 border-[#1A2250] px-4 py-2 text-base font-semibold text-[#1A2250] transition hover:bg-[#1A2250] hover:text-white">
					<span><?php esc_html_e( 'Filtros', 'mauswp' ); ?></span>
					<span aria-hidden="true">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/filtro.svg' ); ?>" alt="" class="h-5 w-5" />
					</span>
				</button>
			</div>

			<div
				class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
				data-autos-grid
				data-ajax-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
				data-offset="<?php echo (int) $per_page; ?>"
				data-per-page="6"
				data-total="<?php echo (int) $query->found_posts; ?>"
			>
				<?php
				if ( $query->have_posts() ) :
					foreach ( $query->posts as $post ) :
						echo mauswp_render_autocaravana_card( $post ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					endforeach;
				else :
					?>
					<p class="text-base text-slate-600"><?php esc_html_e( 'No hay autocaravanas disponibles en este momento.', 'mauswp' ); ?></p>
					<?php
				endif;
				?>
			</div>

			<?php if ( $query->found_posts > $per_page ) : ?>
				<div class="mt-6 flex justify-center">
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
</main>

<?php get_footer(); ?>
