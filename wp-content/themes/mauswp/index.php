<?php
/**
 * Plantilla principal de respaldo.
 *
 * @package mauswp
 */

get_header();
?>

<main id="primary" class="site-main">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>

			<?php
		endwhile;
	else :
		?>
		<p><?php esc_html_e( 'No hay contenido disponible.', 'mauswp' ); ?></p>
	<?php endif; ?>
</main>


<?php
get_footer();
