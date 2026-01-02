<?php
/**
 * Bloque: Quiénes somos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$rows = [
	[
		'title'         => get_field( 'row_1_title' ),
		'content'       => get_field( 'row_1_content' ),
		'text_position' => get_field( 'row_1_text_position' ) ?: 'left',
		'media_type'    => get_field( 'row_1_media_type' ) ?: 'image',
		'image'         => get_field( 'row_1_image' ),
		'video_url'     => get_field( 'row_1_video_url' ),
		'caption'       => get_field( 'row_1_media_caption' ),
	],
	[
		'title'         => get_field( 'row_2_title' ),
		'content'       => get_field( 'row_2_content' ),
		'text_position' => get_field( 'row_2_text_position' ) ?: 'left',
		'media_type'    => get_field( 'row_2_media_type' ) ?: 'image',
		'image'         => get_field( 'row_2_image' ),
		'video_url'     => get_field( 'row_2_video_url' ),
		'caption'       => get_field( 'row_2_media_caption' ),
	],
];

$block_id = ! empty( $block['anchor'] )
	? sanitize_title( $block['anchor'] )
	: 'quienes-somos-' . ( ! empty( $block['id'] ) ? sanitize_title( $block['id'] ) : wp_generate_uuid4() );
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="py-14 md:py-16">
	<div class="mx-auto w-full max-w-7xl px-4 lg:px-8">
		<?php foreach ( $rows as $index => $row ) : ?>
			<?php
			$has_text  = ! empty( $row['title'] ) || ! empty( $row['content'] );
			$has_media = ( $row['media_type'] === 'video' && ! empty( $row['video_url'] ) )
				|| ( $row['media_type'] === 'image' && ! empty( $row['image'] ) );

			if ( ! $has_text && ! $has_media ) {
				continue;
			}

			$is_text_right = ( $row['text_position'] === 'right' );
			$text_col      = $is_text_right ? 'md:col-span-7' : 'md:col-span-5';
			$media_col     = $is_text_right ? 'md:col-span-5' : 'md:col-span-7';
			$is_second_row = ( $index === 1 );
			$text_order    = $is_second_row ? 'order-1 md:order-none' : '';
			$media_order   = $is_second_row ? 'order-2 md:order-none' : '';
			$row_spacing   = $index > 0 ? 'mt-10 md:mt-12' : '';
			?>

			<div class="grid grid-cols-1 gap-8 md:grid-cols-12 md:gap-10 md:items-center <?php echo esc_attr( $row_spacing ); ?>">
				<?php if ( ! $is_text_right ) : ?>
					<div class="<?php echo esc_attr( trim( $text_col . ' ' . $text_order ) ); ?>">
						<?php if ( ! empty( $row['title'] ) ) : ?>
							<h2 class="text-5xl font-bold font-['Montserrat'] text-indigo-950 leading-tight">
								<?php echo esc_html( $row['title'] ); ?>
							</h2>
						<?php endif; ?>

						<?php if ( ! empty( $row['content'] ) ) : ?>
							<div class="mt-4 text-slate-800 text-base font-normal font-['Open_Sans'] leading-6 [&_p]:mb-4 [&_p:last-child]:mb-0 [&_strong]:font-bold [&_a]:text-primary-300 [&_a]:underline [&_span]:mt-4 [&_span]:block [&_span]:text-blue-950 [&_span]:text-2xl [&_span]:font-bold [&_span]:font-['Montserrat']">
								<?php echo wp_kses_post( wpautop( $row['content'] ) ); ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( $has_media ) : ?>
					<div class="<?php echo esc_attr( trim( $media_col . ' ' . $media_order . ' -mx-4 w-[calc(100%+2rem)] md:mx-0 md:w-auto' ) ); ?>">
						<div class="relative overflow-hidden md:rounded-[12px]">
							<?php if ( $row['media_type'] === 'video' && ! empty( $row['video_url'] ) ) : ?>
								<video
									class="w-full"
									controls
									playsinline
									preload="metadata"
								>
									<source src="<?php echo esc_url( $row['video_url'] ); ?>" type="video/mp4" />
								</video>
							<?php elseif ( ! empty( $row['image'] ) ) : ?>
								<img
									src="<?php echo esc_url( $row['image']['url'] ); ?>"
									alt="<?php echo esc_attr( $row['image']['alt'] ); ?>"
									class="w-full object-cover"
									loading="lazy"
									decoding="async"
								/>
							<?php endif; ?>

							<?php if ( ! empty( $row['caption'] ) ) : ?>
								<div class="pointer-events-none absolute inset-x-0 bottom-0 px-6 pb-4 pt-10">
									<div class="text-lg font-semibold font-['Open_Sans'] text-white">
										<?php echo esc_html( $row['caption'] ); ?>
									</div>
									<div class="mt-2 h-px w-full bg-white/70"></div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( $is_text_right ) : ?>
					<div class="<?php echo esc_attr( trim( $text_col . ' ' . $text_order ) ); ?>">
						<?php if ( ! empty( $row['title'] ) ) : ?>
							<h2 class="text-5xl font-bold font-['Montserrat'] text-indigo-950 leading-tight">
								<?php echo esc_html( $row['title'] ); ?>
							</h2>
						<?php endif; ?>

						<?php if ( ! empty( $row['content'] ) ) : ?>
							<div class="mt-4 text-slate-800 text-base font-normal font-['Open_Sans'] leading-6 [&_p]:mb-4 [&_p:last-child]:mb-0 [&_strong]:font-bold [&_a]:text-primary-300 [&_a]:underline [&_span]:mt-4 [&_span]:block [&_span]:text-blue-950 [&_span]:text-2xl [&_span]:font-bold [&_span]:font-['Montserrat']">
								<?php echo wp_kses_post( wpautop( $row['content'] ) ); ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
