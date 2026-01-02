<?php
/**
 * Bloque: Contáctanos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title          = get_field( 'title' ) ?: __( 'Contáctanos', 'mauswp' );
$subtitle       = get_field( 'subtitle' );
$background     = get_field( 'background_image' );
$contacts       = get_field( 'contacts' ) ?: [];
$schedule_title = get_field( 'schedule_title' ) ?: __( 'Horario:', 'mauswp' );
$schedule_text  = get_field( 'schedule_text' );
$email_title    = get_field( 'email_title' ) ?: __( 'Correo Administración:', 'mauswp' );
$email_text     = get_field( 'email_text' );
$location_title = get_field( 'location_title' ) ?: __( 'Ubicación:', 'mauswp' );
$location_text  = get_field( 'location_text' );

$block_id = ! empty( $block['anchor'] )
	? sanitize_title( $block['anchor'] )
	: 'contactanos-' . ( ! empty( $block['id'] ) ? sanitize_title( $block['id'] ) : wp_generate_uuid4() );

$bg_attr = $background ? ' style="background-image:url(' . esc_url( $background['url'] ) . ');"' : '';
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="py-14 md:py-16">
	<div class="mx-auto w-full max-w-7xl px-4 lg:px-8">
		<div class="relative -mx-4 w-[calc(100%+2rem)] overflow-visible md:mx-0 md:w-auto">
			<div class="absolute left-1/2 h-[315px] w-screen -translate-x-1/2 overflow-hidden rounded-none bg-center md:left-0 md:w-full md:translate-x-0 md:rounded-[20px]"<?php echo $bg_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>></div>

			<div class="relative grid max-h-[500px] gap-6 px-6 py-10 mb-[300px] md:mb-[100px] md:max-h-[300px] md:grid-cols-[1fr_1.2fr_1.5fr] md:items-stretch md:gap-8 md:px-10 md:py-12">
				<div class="text-white">
					<?php if ( $title ) : ?>
						<h2 class="text-4xl md:text-5xl font-bold font-['Montserrat']"><?php echo esc_html( $title ); ?></h2>
					<?php endif; ?>
					<?php if ( $subtitle ) : ?>
						<p class="mt-3 text-base font-normal font-['Open_Sans'] text-white/90">
							<?php echo esc_html( $subtitle ); ?>
						</p>
					<?php endif; ?>
				</div>

				<div class="grid items-stretch gap-5 grid-cols-2">
					<?php foreach ( $contacts as $contact ) : ?>
						<?php
						$name  = $contact['name'] ?? '';
						$role  = $contact['role'] ?? '';
						$phone = $contact['phone'] ?? '';
						$photo = $contact['photo'] ?? null;
						?>
						<div class="flex h-full max-h-[320px] flex-col overflow-hidden rounded-[16px] border border-slate-200/70 bg-white/95 shadow-[0_10px_20px_rgba(15,23,42,0.08)]">
							<div
								class="mb-[10px] h-[470px] w-full overflow-hidden bg-no-repeat bg-[center_12px] bg-[length:100px_100px] pt-3"
								style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo-fondo.png' ); ?>');"
							>
								<?php if ( $photo ) : ?>
									<img
										src="<?php echo esc_url( $photo['url'] ); ?>"
										alt="<?php echo esc_attr( $photo['alt'] ); ?>"
										class="h-full w-full object-cover"
										loading="lazy"
										decoding="async"
									/>
								<?php endif; ?>
							</div>
							<div class="flex flex-col gap-1 px-4 pb-4 pt-3">
								<?php if ( $name ) : ?>
									<p class="text-sm font-bold font-['Open_Sans'] text-slate-700"><?php echo esc_html( $name ); ?></p>
								<?php endif; ?>
								<?php if ( $role ) : ?>
									<p class="text-xs font-light font-['Open_Sans'] text-slate-700"><?php echo esc_html( $role ); ?></p>
								<?php endif; ?>
								<?php if ( $phone ) : ?>
									<a
										class="pt-3 inline-flex items-center gap-2 text-lg font-bold font-['Open_Sans'] text-slate-800"
										href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"
									>
										<svg class="h-5 w-5 text-slate-700" viewBox="0 0 24 24" fill="none" aria-hidden="true">
											<path d="M4 5.5C4 4.12 5.12 3 6.5 3H8.2C9 3 9.7 3.5 10 4.2L10.9 6.7C11.2 7.5 10.9 8.4 10.2 8.8L9 9.6C10.1 11.8 12.2 13.9 14.4 15L15.2 13.8C15.6 13.1 16.5 12.8 17.3 13.1L19.8 14C20.5 14.3 21 15 21 15.8V17.5C21 18.88 19.88 20 18.5 20H18C10.82 20 4 13.18 4 6V5.5Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
										<span><?php echo esc_html( $phone ); ?></span>
									</a>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="h-full max-h-[320px] rounded-[16px] border border-slate-200/60 bg-white/95 p-5 text-slate-800 shadow-[0_10px_20px_rgba(15,23,42,0.08)]">
					<div class="space-y-4">
						<?php if ( $schedule_text ) : ?>
							<div class="pb-4 border-b border-slate-200">
								<div class="flex items-center gap-2 text-lg font-bold font-['Open_Sans'] text-slate-800">
									<svg class="h-5 w-5 text-slate-700" viewBox="0 0 24 24" fill="none" aria-hidden="true">
										<rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.6"/>
										<path d="M8 3V7M16 3V7M3 10H21" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
									</svg>
									<span><?php echo esc_html( $schedule_title ); ?></span>
								</div>
								<p class="mt-2 text-base font-medium font-['Open_Sans'] text-slate-600">
									<?php echo wp_kses_post( $schedule_text ); ?>
								</p>
							</div>
						<?php endif; ?>

						<?php if ( $email_text ) : ?>
							<div class="pb-4 border-b border-slate-200">
								<div class="flex items-center gap-2 text-lg font-bold font-['Open_Sans'] text-slate-800">
									<svg class="h-5 w-5 text-slate-700" viewBox="0 0 24 24" fill="none" aria-hidden="true">
										<path d="M4 6H20V18H4V6Z" stroke="currentColor" stroke-width="1.6"/>
										<path d="M4 7L12 13L20 7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
									<span><?php echo esc_html( $email_title ); ?></span>
								</div>
								<p class="mt-2 text-base font-medium font-['Open_Sans'] text-slate-600">
									<a class="text-slate-600 hover:text-slate-700" href="<?php echo esc_url( 'mailto:' . sanitize_email( $email_text ) ); ?>">
										<?php echo wp_kses_post( $email_text ); ?>
									</a>
								</p>
							</div>
						<?php endif; ?>

						<?php if ( $location_text ) : ?>
							<div>
								<div class="flex items-center gap-2 text-lg font-bold font-['Open_Sans'] text-slate-800">
									<svg class="h-5 w-5 text-slate-700" viewBox="0 0 24 24" fill="none" aria-hidden="true">
										<path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
										<circle cx="12" cy="10" r="2.5" stroke="currentColor" stroke-width="1.6"/>
									</svg>
									<span><?php echo esc_html( $location_title ); ?></span>
								</div>
								<p class="mt-2 text-base font-medium font-['Open_Sans'] text-slate-600">
									<?php echo wp_kses_post( $location_text ); ?>
								</p>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
