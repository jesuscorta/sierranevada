<?php
/**
 * Pie de página del tema
 *
 * @package mauswp
 */

$has_acf = function_exists( 'get_field' );

$footer_logo          = $has_acf ? get_field( 'footer_logo', 'option' ) : null;
$footer_title_main    = $has_acf ? get_field( 'footer_title_main', 'option' ) : '';
$footer_description   = $has_acf ? get_field( 'footer_description', 'option' ) : '';
$footer_title_secondary = $has_acf ? get_field( 'footer_title_secondary', 'option' ) : '';
$footer_text_secondary  = $has_acf ? get_field( 'footer_text_secondary', 'option' ) : '';

$footer_cols = [
	[
		'title' => $has_acf ? get_field( 'footer_col2_title', 'option' ) : '',
		'menu'  => 'footer_col_2',
	],
	[
		'title' => $has_acf ? get_field( 'footer_col3_title', 'option' ) : '',
		'menu'  => 'footer_col_3',
	],
	[
		'title' => $has_acf ? get_field( 'footer_col4_title', 'option' ) : '',
		'menu'  => 'footer_col_4',
	],
	[
		'title' => $has_acf ? get_field( 'footer_col5_title', 'option' ) : '',
		'menu'  => 'footer_col_5',
	],
];

$footer_bottom_text = $has_acf ? get_field( 'footer_bottom_text', 'option' ) : '';

$footer_social = [
	'facebook'  => $has_acf ? get_field( 'footer_facebook', 'option' ) : '',
	'instagram' => $has_acf ? get_field( 'footer_instagram', 'option' ) : '',
	'youtube'   => $has_acf ? get_field( 'footer_youtube', 'option' ) : '',
];
?>

<footer class="bg-[#100F28] text-slate-100">
	<div class="mx-auto max-w-7xl px-4 py-12 md:py-16 lg:px-8">
		<div class="grid grid-cols-1 gap-10 md:[grid-template-columns:1.4fr_repeat(4,1fr)]">
			<div class="flex flex-col gap-4">
				<?php if ( ! empty( $footer_logo['url'] ) ) : ?>
					<img
						src="<?php echo esc_url( $footer_logo['url'] ); ?>"
						alt="<?php echo esc_attr( $footer_logo['alt'] ?? '' ); ?>"
						class="h-auto max-w-[220px]"
						loading="lazy"
						decoding="async"
					/>
				<?php endif; ?>

				<?php if ( $footer_title_main ) : ?>
					<h3 class="text-xl font-bold uppercase tracking-wide">
						<?php echo esc_html( $footer_title_main ); ?>
					</h3>
				<?php endif; ?>

				<?php if ( $footer_description ) : ?>
					<div class="space-y-2 text-sm leading-relaxed text-slate-200">
						<?php echo wp_kses_post( wpautop( $footer_description ) ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $footer_title_secondary ) : ?>
					<h4 class="pt-2 text-xl font-bold uppercase tracking-wide">
						<?php echo esc_html( $footer_title_secondary ); ?>
					</h4>
				<?php endif; ?>

				<?php if ( $footer_text_secondary ) : ?>
					<div class="space-y-2 text-sm leading-relaxed text-slate-200">
						<?php echo wp_kses_post( wpautop( $footer_text_secondary ) ); ?>
					</div>
				<?php endif; ?>
			</div>

			<?php foreach ( $footer_cols as $col ) : ?>
				<div class="flex flex-col gap-3">
					<?php if ( ! empty( $col['title'] ) ) : ?>
						<h3 class="text-lg font-semibold uppercase tracking-wide">
							<?php echo esc_html( $col['title'] ); ?>
						</h3>
					<?php endif; ?>

					<?php
					wp_nav_menu(
						[
							'theme_location' => $col['menu'],
							'container'      => '',
							'menu_class'     => 'flex flex-col gap-2 text-sm font-normal text-slate-100',
							'fallback_cb'    => false,
						]
					);
					?>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="mt-10 border-t border-slate-500/40 pt-6">
			<div class="grid grid-cols-1 gap-4 md:[grid-template-columns:minmax(0,1fr)_auto] md:items-center">
				<div class="flex flex-col gap-3 md:flex-row md:items-center md:gap-4">
					<div class="text-sm text-slate-200">
						<?php
						if ( $footer_bottom_text ) {
							echo wp_kses_post( wpautop( $footer_bottom_text ) );
						} else {
							printf(
								'&copy; %1$s %2$s',
								esc_html( date_i18n( 'Y' ) ),
								esc_html( get_bloginfo( 'name' ) )
							);
						}
						?>
					</div>

					<?php
					$legal_menu = mauswp_render_footer_menu(
						'footer_legal',
						'mt-1 md:mt-0 flex flex-wrap items-center gap-x-3 gap-y-2 text-sm text-slate-200'
					);
					if ( $legal_menu ) {
						echo wp_kses_post( $legal_menu );
					}
					?>
				</div>

				<?php if ( array_filter( $footer_social ) ) : ?>
					<div class="flex items-center gap-4 md:justify-end">
						<?php if ( ! empty( $footer_social['facebook'] ) ) : ?>
							<a href="<?php echo esc_url( $footer_social['facebook'] ); ?>" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-500 text-slate-100 transition hover:border-slate-200 hover:text-slate-50" aria-label="<?php esc_attr_e( 'Facebook', 'mauswp' ); ?>">
								<svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12.073C22 6.505 17.523 2 12 2S2 6.505 2 12.073c0 5.017 3.657 9.184 8.438 9.878v-6.988H7.898v-2.89h2.54V9.845c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.196 2.238.196v2.46h-1.26c-1.243 0-1.631.776-1.631 1.571v1.888h2.773l-.443 2.89h-2.33v6.988C18.343 21.257 22 17.09 22 12.073Z"/></svg>
							</a>
						<?php endif; ?>
						<?php if ( ! empty( $footer_social['instagram'] ) ) : ?>
							<a href="<?php echo esc_url( $footer_social['instagram'] ); ?>" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-500 text-slate-100 transition hover:border-slate-200 hover:text-slate-50" aria-label="<?php esc_attr_e( 'Instagram', 'mauswp' ); ?>">
								<svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5Zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7Zm11.25 1.25a1.25 1.25 0 1 1 0 2.5a1.25 1.25 0 0 1 0-2.5ZM12 7a5 5 0 1 1 0 10a5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6a3 3 0 0 0 0-6Z"/></svg>
							</a>
						<?php endif; ?>
						<?php if ( ! empty( $footer_social['youtube'] ) ) : ?>
							<a href="<?php echo esc_url( $footer_social['youtube'] ); ?>" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-500 text-slate-100 transition hover:border-slate-200 hover:text-slate-50" aria-label="<?php esc_attr_e( 'YouTube', 'mauswp' ); ?>">
								<svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M21.582 6.186c.255.98.418 2.014.418 2.814v6c0 .8-.163 1.834-.418 2.814c-.268 1.027-1.06 1.83-2.03 2.09C17.906 20 12 20 12 20s-5.906 0-7.552-.096c-.97-.26-1.762-1.063-2.03-2.09C2.163 18.834 2 17.8 2 17v-6c0-.8.163-1.834.418-2.814c.268-1.027 1.06-1.83 2.03-2.09C6.094 6 12 6 12 6s5.906 0 7.552.096c.97.26 1.762 1.063 2.03 2.09ZM10.5 9.75v5.5l4.75-2.75-4.75-2.75Z"/></svg>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
