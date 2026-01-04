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

			<div class="prose prose-slate max-w-none">
				<?php
				while ( have_posts() ) :
					the_post();
					the_content();
				endwhile;
				?>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
