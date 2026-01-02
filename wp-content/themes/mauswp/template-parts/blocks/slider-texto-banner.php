<?php
/**
 * Bloque: Slider texto banner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Swiper assets.
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

$slides = get_field( 'slides' );

if ( ! $slides || ! is_array( $slides ) ) {
	return;
}

$block_id    = ! empty( $block['anchor'] ) ? $block['anchor'] : 'hero-timeline-slider-' . $block['id'];
$block_class = 'hero-timeline-slider';

$background = get_field( 'background_image' );
$bg_url     = $background ? $background['url'] : '';

$card_bg_attr = $bg_url
	? ' style="background-image: url(' . esc_url( $bg_url ) . ');"'
	: '';
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="py-10 md:py-16 <?php echo esc_attr( $block_class ); ?>">
	<div class="mx-auto w-full max-w-7xl px-4 lg:px-8">
		<div
			class="js-hero-timeline-slider relative -mx-4 w-[calc(100%+2rem)] rounded-none overflow-hidden text-white bg-slate-900 bg-cover bg-center bg-no-repeat md:mx-0 md:w-auto md:rounded-[28px]"
			<?php echo $card_bg_attr; ?>
		>

			<div class="relative z-10 grid grid-cols-1 md:grid-cols-[90px_minmax(0,1fr)]">
				<aside class="relative hidden md:flex items-stretch py-10 pl-6 pr-4">
					<div class="relative mx-auto flex h-full flex-col items-center justify-between">
						<span class="pointer-events-none absolute inset-y-0 left-1/2 w-px -translate-x-1/2 bg-white/25"></span>

						<?php foreach ( $slides as $index => $slide ) : ?>
							<button
								type="button"
								class="relative z-10 flex h-3 w-3 items-center justify-center rounded-full border border-white/60 bg-transparent transition-all duration-300 ease-out data-[active=true]:h-3.5 data-[active=true]:w-3.5 data-[active=true]:border-white data-[active=true]:bg-white data-[active=true]:shadow-[0_0_0_6px_rgba(255,255,255,0.18)]"
								data-hero-bullet
								data-index="<?php echo esc_attr( $index ); ?>"
								<?php echo 0 === $index ? 'data-active="true" aria-current="true"' : ''; ?>
							>
								<span class="sr-only">
									<?php
									printf(
										/* translators: %d slide number */
										esc_html__( 'Ir a la diapositiva %d', 'mauswp' ),
										(int) $index + 1
									);
									?>
								</span>
							</button>
						<?php endforeach; ?>
					</div>
				</aside>

				<div class="relative">
					<div class="swiper js-hero-timeline-swiper h-full">
						<div class="swiper-wrapper">
							<?php foreach ( $slides as $slide ) :
								$text = $slide['text'] ?? '';
								?>
								<div class="swiper-slide h-[240px] min-h-[240px] max-h-[240px] md:h-[400px] md:min-h-[400px] md:max-h-[400px]">
									<div class="flex h-full items-center justify-center px-6 md:px-16 py-10">
										<div
											class="hero-slide-content w-full max-w-full md:max-w-3xl text-left sm:text-center opacity-0 translate-y-3 transition-all duration-500 ease-out"
											data-hero-slide-content
										>
											<?php if ( $text ) : ?>
												<p class="text-base font-medium font-['Montserrat'] leading-5 text-slate-50 md:text-3xl md:leading-relaxed [&_strong]:font-bold">
													<?php echo wp_kses_post( $text ); ?>
												</p>
											<?php endif; ?>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<div class="flex md:hidden relative z-20 items-center justify-center gap-3 pb-5 pt-3">
						<?php foreach ( $slides as $index => $slide ) : ?>
							<button
								type="button"
								class="h-2 w-2 rounded-full border border-white/60 bg-transparent transition-all duration-300 ease-out data-[active=true]:h-2.5 data-[active=true]:w-2.5 data-[active=true]:border-white data-[active=true]:bg-white data-[active=true]:shadow-[0_0_0_4px_rgba(255,255,255,0.18)]"
								data-hero-bullet-mobile
								data-index="<?php echo esc_attr( $index ); ?>"
								<?php echo 0 === $index ? 'data-active="true" aria-current="true"' : ''; ?>
							>
								<span class="sr-only">
									<?php
									printf(
										esc_html__( 'Ir a la diapositiva %d', 'mauswp' ),
										(int) $index + 1
									);
									?>
								</span>
							</button>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
