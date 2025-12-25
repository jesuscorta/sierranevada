<?php
/**
 * Bloque: Información Financiación
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$subtitle   = get_field( 'subtitle' );
$title      = get_field( 'title' );
$content    = get_field( 'content' );
$cta_text   = get_field( 'cta_text' );
$cta_url    = get_field( 'cta_url' );
$cta_target = get_field( 'cta_target' ) ? '_blank' : '_self';
$image      = get_field( 'image' );

$bg_left      = 'var(--color-primary-500)';
$subtitle_col = 'var(--color-primary-75)';
$title_col    = 'var(--color-secondary-50)';
$text_col     = 'var(--color-primary-75)';
$cta_border   = 'var(--color-secondary-50)';
$cta_text_col = 'var(--color-secondary-50)';

?>

<section class="relative w-full overflow-hidden bg-[<?php echo esc_attr( $bg_left ); ?>] min-h-[420px] py-10 md:py-0">
	<?php if ( $image ) : ?>
		<div class="pointer-events-none absolute inset-0 flex items-end justify-end">
			<img
				src="<?php echo esc_url( $image['url'] ); ?>"
				alt="<?php echo esc_attr( $image['alt'] ); ?>"
				class="h-full w-full max-w-[390px] md:max-w-[700px] object-cover object-bottom"
				loading="lazy"
				decoding="async"
			/>
		</div>
	<?php endif; ?>

	<div class="relative mx-auto flex w-full max-w-7xl flex-col px-4 md:flex-row md:items-stretch">
		<div class="flex w-full md:w-7/12 items-stretch bg-[<?php echo esc_attr( $bg_left ); ?>] rounded-br-[100px] min-h-[420px]">
			<div class="flex w-full max-w-xl md:max-w-4xl flex-col gap-4 px-6 md:px-12">
				<?php if ( $subtitle ) : ?>
					<p class="font-sans text-lg font-bold leading-normal" style="color: <?php echo esc_attr( $subtitle_col ); ?>;">
						<?php echo esc_html( $subtitle ); ?>
					</p>
				<?php endif; ?>

			<?php if ( $title ) : ?>
				<h2 class="font-display text-[30px] font-bold leading-normal" style="color: <?php echo esc_attr( $title_col ); ?>;">
					<?php echo esc_html( $title ); ?>
				</h2>
			<?php endif; ?>

				<?php if ( $content ) : ?>
					<div class="font-sans text-sm font-normal leading-normal" style="color: <?php echo esc_attr( $text_col ); ?>;">
						<?php echo wp_kses_post( wpautop( $content ) ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $cta_text && $cta_url ) : ?>
					<div class="mt-2">
						<a
							href="<?php echo esc_url( $cta_url ); ?>"
							target="<?php echo esc_attr( $cta_target ); ?>"
							class="inline-flex items-center justify-center px-5 py-3 text-base font-sans font-semibold leading-normal transition hover:opacity-90"
							style="border: 2px solid <?php echo esc_attr( $cta_border ); ?>; border-radius: 54px; color: <?php echo esc_attr( $cta_text_col ); ?>;"
						>
							<?php echo esc_html( $cta_text ); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<div class="hidden md:block md:w-5/12"></div>
	</div>
</section>
