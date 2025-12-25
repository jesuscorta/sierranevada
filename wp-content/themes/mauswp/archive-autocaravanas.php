<?php
/**
 * Ejemplo de index para listar Autocaravanas con CTA de comparador.
 * Copia este archivo al tema (por ejemplo, index.php o archive-autocaravanas.php) según necesites.
 */

get_header();

$query = new WP_Query([
    'post_type'      => 'autocaravanas',
    'posts_per_page' => 12,
    'paged'          => get_query_var('paged') ?: 1,
]);
?>

<main class="autocaravanas-archive mx-auto w-full max-w-6xl px-4 py-8 md:px-8">
    <header class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-3xl font-semibold text-neutral-900">Autocaravanas</h1>
        <a href="<?php echo esc_url(home_url('/comparador')); ?>" class="inline-flex items-center rounded-full border-2 border-primary-300 px-4 py-2 text-sm font-semibold text-primary-300 transition hover:border-primary-50 hover:bg-primary-50/20 hover:text-primary-50">
            Ver comparador
        </a>
    </header>

    <?php if ($query->have_posts()) : ?>
        <div class="autocaravanas-grid grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <article <?php post_class('autocaravana-card border border-neutral-200 rounded-lg p-4 shadow-sm transition hover:shadow-md'); ?>>
                    <h2 class="mt-0 text-xl font-semibold text-neutral-900 mb-2"><?php the_title(); ?></h2>
                    <div class="autocaravana-card__meta mb-3 text-sm leading-relaxed text-neutral-600">
                        <?php the_excerpt(); ?>
                    </div>
                    <div class="autocaravana-card__actions flex flex-wrap items-center gap-2">
                        <a class="autocaravana-compare-btn inline-flex items-center rounded-full bg-neutral-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-neutral-800" data-post-id="<?php the_ID(); ?>" href="<?php the_permalink(); ?>">Añadir al comparador</a>
                        <a class="autocaravana-compare-link inline-flex items-center rounded-full border border-neutral-300 px-4 py-2 text-sm font-medium text-neutral-800 transition hover:border-neutral-400 hover:text-neutral-900" href="<?php the_permalink(); ?>">Ver ficha</a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <div class="autocaravanas-pagination mt-6 text-center">
            <?php
            echo paginate_links([
                'total'   => $query->max_num_pages,
                'current' => max(1, get_query_var('paged')),
            ]);
            ?>
        </div>
    <?php else : ?>
        <p>No hay autocaravanas disponibles.</p>
    <?php endif; ?>
</main>

<?php
wp_reset_postdata();
get_footer();
