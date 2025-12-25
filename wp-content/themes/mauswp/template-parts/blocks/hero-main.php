<?php
/**
 * Bloque Hero principal
 *
 * Block: acf/hero-main
 */

$background_type   = get_field('background_type') ?: 'image';
$background_image  = get_field('background_image');
$background_video  = get_field('background_video_url');

$subtitle_top      = get_field('subtitle_top');
$title_line_1      = get_field('title_line_1');
$title_line_2      = get_field('title_line_2');
$title_line_3      = get_field('title_line_3');
$subtitle_bottom   = get_field('subtitle_bottom');
$cta_text          = get_field('cta_text');
$cta_url           = get_field('cta_url');
$cta_target        = get_field('cta_target') ? '_blank' : '_self';
$poster_image      = null;
if ( $background_image ) {
	$poster_image = is_array( $background_image ) ? $background_image['url'] : $background_image;
}

// ID y clases del bloque
$block_id = 'hero-main-' . ($block['id'] ?? uniqid());
$classes  = 'relative w-full overflow-hidden';
?>

<section
	id="<?php echo esc_attr( $block_id ); ?>"
	class="<?php echo esc_attr( $classes ); ?> h-screen min-h-screen md:h-screen"
>
	<div class="relative h-full w-full">
		<?php if ( $background_type === 'video' && $background_video ) : ?>
			<video
				class="absolute inset-0 h-full w-full object-cover"
				data-src="<?php echo esc_url( $background_video ); ?>"
				<?php if ( $poster_image ) : ?>
					poster="<?php echo esc_url( $poster_image ); ?>"
				<?php endif; ?>
				preload="none"
				autoplay
				muted
				loop
				playsinline
				data-hero-video="<?php echo esc_attr( $block_id ); ?>"
			></video>
		<?php elseif ( $background_image ) : ?>
			<?php
			$img_url = is_array( $background_image ) ? $background_image['url'] : $background_image;
			?>
			<div
				class="absolute inset-0 bg-center bg-cover"
				style="background-image:url('<?php echo esc_url( $img_url ); ?>');"
			></div>
		<?php endif; ?>

		<!-- Overlay degradado -->
		<div
			class="pointer-events-none absolute inset-0
			bg-gradient-to-t from-primary-500 to-primary-500/0
			md:bg-gradient-to-r md:from-primary-500 md:to-primary-500/0"
		></div>

		<!-- Contenido -->
		<div class="relative z-10 flex h-full w-full items-end md:items-center">
			<div class="mx-auto w-full max-w-7xl px-4 pt-28 pb-14 md:pt-48 md:pb-20 lg:px-8">
				<div class="max-w-2xl lg:max-w-3xl">
					<?php if ( $subtitle_top ) : ?>
						<p class="font-sans font-semibold text-base md:text-xl leading-snug text-primary-75 uppercase tracking-normal mb-5">
							<?php echo esc_html( $subtitle_top ); ?>
						</p>
					<?php endif; ?>

					<?php if ( $title_line_1 || $title_line_2 || $title_line_3 ) : ?>
						<h1 class="font-display font-semibold leading-tight text-4xl md:text-6xl mb-7">
							<?php if ( $title_line_1 ) : ?>
								<span class="block text-white">
									<?php echo esc_html( $title_line_1 ); ?>
								</span>
							<?php endif; ?>
							<?php if ( $title_line_2 ) : ?>
								<span class="block text-primary-75">
									<?php echo esc_html( $title_line_2 ); ?>
								</span>
							<?php endif; ?>
							<?php if ( $title_line_3 ) : ?>
								<span class="block text-white">
									<?php echo esc_html( $title_line_3 ); ?>
								</span>
							<?php endif; ?>
						</h1>
					<?php endif; ?>

					<?php if ( $subtitle_bottom ) : ?>
						<p class="font-sans font-medium text-sm md:text-xl leading-relaxed text-primary-75 mb-10 max-w-xl">
							<?php echo esc_html( $subtitle_bottom ); ?>
						</p>
					<?php endif; ?>

					<?php if ( $cta_text && $cta_url ) : ?>
						<a
							href="<?php echo esc_url( $cta_url ); ?>"
							target="<?php echo esc_attr( $cta_target ); ?>"
							class="inline-flex items-center justify-center rounded-full border-2 border-white px-5 py-2 text-base font-open-sans font-semibold text-white transition hover:bg-white hover:text-primary-500"
						>
							<?php echo esc_html( $cta_text ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
