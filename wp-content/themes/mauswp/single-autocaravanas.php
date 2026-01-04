<?php
/**
 * Single: Autocaravanas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_enqueue_style( 'mauswp-swiper' );
wp_enqueue_script( 'mauswp-swiper' );

get_header();

$post_id = get_the_ID();

$precio          = get_field( 'precio', $post_id );
$estado          = get_field( 'estado', $post_id );
$marca           = get_field( 'marca', $post_id );
$tipo_auto       = get_field( 'tipo_autocaravana', $post_id );
$tipo_cama       = get_field( 'tipo_cama', $post_id );
$plazas_viajar   = get_field( 'plazas_viajar', $post_id );
$motor           = get_field( 'motor', $post_id );
$cv              = get_field( 'cv', $post_id );
$km              = get_field( 'kilometros', $post_id );
$ano             = get_field( 'ano', $post_id );
$transmision     = get_field( 'transmision', $post_id );
$largo           = get_field( 'largo', $post_id );
$alto            = get_field( 'alto', $post_id );
$galeria         = get_field( 'galeria', $post_id ) ?: [];
$video_youtube   = get_field( 'video_youtube', $post_id );
$shortcode_vr    = get_field( 'shortcode_vr', $post_id );
$extras          = get_field( 'extras_equipamiento', $post_id );
$ficha_pdf       = get_field( 'ficha_pdf', $post_id );
$financia_text   = get_field( 'auto_financia_text', 'option' );
$info_title      = get_field( 'auto_info_title', 'option' );
$info_content    = get_field( 'auto_info_content', 'option' );
$tab_financiacion = get_field( 'auto_tab_financiacion', 'option' );
$tab_contacto     = get_field( 'auto_tab_contacto', 'option' );

$media_items = [];

if ( $galeria && is_array( $galeria ) ) {
	foreach ( $galeria as $img ) {
		if ( ! empty( $img['url'] ) ) {
			$media_items[] = [
				'type'  => 'image',
				'url'   => $img['url'],
				'alt'   => $img['alt'] ?? get_the_title( $post_id ),
				'thumb' => wp_get_attachment_image_url( $img['ID'], 'thumbnail' ),
			];
		}
	}
}

if ( $video_youtube ) {
	$media_items[] = [
		'type' => 'video',
		'url'  => $video_youtube,
		'thumb' => '',
	];
}

// Prioriza imagen principal (si no hay, usa placeholder).
$main_media = $media_items[0] ?? null;
$placeholder = get_template_directory_uri() . '/assets/img/img-placeholder.png';
?>

<main class="bg-[#F1F5F9] pt-24 pb-16 md:pt-28">
	<div class="mx-auto w-full max-w-7xl px-4 lg:px-8">
		<div class="space-y-6">
			<div class="text-sm text-slate-600">
				<?php if ( function_exists( 'aioseo_breadcrumbs' ) ) : ?>
					<?php aioseo_breadcrumbs(); ?>
				<?php else : ?>
					<nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'mauswp' ); ?>">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-slate-700 hover:text-slate-900"><?php esc_html_e( 'Inicio', 'mauswp' ); ?></a>
						<span class="px-2 text-slate-400">/</span>
						<a href="<?php echo esc_url( get_post_type_archive_link( 'autocaravanas' ) ); ?>" class="text-slate-700 hover:text-slate-900"><?php esc_html_e( 'Autocaravanas', 'mauswp' ); ?></a>
						<span class="px-2 text-slate-400">/</span>
						<span class="font-semibold text-slate-900"><?php the_title(); ?></span>
					</nav>
				<?php endif; ?>
			</div>

			<header class="space-y-4">
				<h1 class="text-3xl font-black leading-tight text-[#1A2250] md:text-4xl"><?php the_title(); ?></h1>
				<div class="flex flex-wrap items-center gap-3">
					<button class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-[#1A2250] shadow-sm transition hover:border-[#1A2250]">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 6v12m0-12a4 4 0 1 0-4 4m4-4a4 4 0 1 1 4 4m-4 8a8 8 0 0 1-7.446-4.968m14.892 0A8 8 0 0 1 12 20"/></svg>
						<?php esc_html_e( 'Añadir para comparar', 'mauswp' ); ?>
					</button>
					<button class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-[#1A2250] shadow-sm transition hover:border-[#1A2250]">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4 12v7a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-7m-9 4 2 2 6-6M4 8l8-5 8 5-8 5-8-5Z"/></svg>
						<?php esc_html_e( 'Compartir', 'mauswp' ); ?>
					</button>
				</div>
			</header>

			<div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
				<div class="space-y-6 lg:col-span-7">
					<div class="space-y-3" data-auto-gallery>
						<div class="overflow-hidden rounded-2xl bg-white shadow-md">
							<div class="swiper js-auto-gallery-main relative aspect-[16/9] bg-slate-100">
								<div class="swiper-wrapper">
									<?php if ( $media_items ) : ?>
										<?php foreach ( $media_items as $media ) : ?>
											<div class="swiper-slide">
												<?php if ( 'video' === $media['type'] ) : ?>
													<div class="h-full w-full">
														<iframe class="h-full w-full" src="<?php echo esc_url( $media['url'] ); ?>" allowfullscreen loading="lazy"></iframe>
													</div>
												<?php else : ?>
													<img src="<?php echo esc_url( $media['url'] ); ?>" alt="<?php echo esc_attr( $media['alt'] ); ?>" class="h-full w-full object-cover" loading="lazy" decoding="async" />
												<?php endif; ?>
											</div>
										<?php endforeach; ?>
									<?php else : ?>
										<div class="swiper-slide">
											<img src="<?php echo esc_url( $placeholder ); ?>" alt="" class="h-full w-full object-cover" loading="lazy" decoding="async" />
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>

						<?php if ( $media_items ) : ?>
							<div class="swiper js-auto-gallery-thumbs">
								<div class="swiper-wrapper">
									<?php foreach ( $media_items as $media ) : ?>
										<div class="swiper-slide !w-auto">
											<div class="relative h-20 w-32 overflow-hidden rounded-lg border border-slate-200 bg-white">
												<?php if ( 'video' === $media['type'] ) : ?>
													<span class="absolute inset-0 z-10 flex items-center justify-center bg-slate-900/40 text-white">
														<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
													</span>
													<div class="h-full w-full bg-slate-200"></div>
												<?php elseif ( ! empty( $media['thumb'] ) ) : ?>
													<img src="<?php echo esc_url( $media['thumb'] ); ?>" alt="<?php echo esc_attr( $media['alt'] ?? '' ); ?>" class="h-full w-full object-cover" loading="lazy" decoding="async" />
												<?php else : ?>
													<img src="<?php echo esc_url( $media['url'] ); ?>" alt="<?php echo esc_attr( $media['alt'] ?? '' ); ?>" class="h-full w-full object-cover" loading="lazy" decoding="async" />
												<?php endif; ?>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endif; ?>
					</div>

					<div class="space-y-4">
						<div class="flex flex-wrap items-center gap-6 border-b border-transparent text-base font-semibold text-slate-600">
							<button type="button" class="tab-link text-[#1A2250]" data-auto-tab-target="financiacion"><?php esc_html_e( 'Financiación', 'mauswp' ); ?></button>
							<button type="button" class="tab-link" data-auto-tab-target="ficha"><?php esc_html_e( 'Ficha técnica', 'mauswp' ); ?></button>
							<button type="button" class="tab-link" data-auto-tab-target="vr"><?php esc_html_e( 'Foto 360º', 'mauswp' ); ?></button>
							<button type="button" class="tab-link" data-auto-tab-target="contacto"><?php esc_html_e( 'Contacto', 'mauswp' ); ?></button>
						</div>

						<div class="space-y-4">
							<div class="tab-panel rounded-2xl bg-white p-5 shadow-md" data-auto-tab="financiacion" id="financiacion">
								<?php if ( $tab_financiacion ) : ?>
									<?php echo wp_kses_post( $tab_financiacion ); ?>
								<?php else : ?>
									<p class="text-slate-600"><?php esc_html_e( 'Añade información de financiación desde las opciones del tema.', 'mauswp' ); ?></p>
								<?php endif; ?>
							</div>
							<div class="tab-panel hidden rounded-2xl bg-white p-5 shadow-md" data-auto-tab="ficha" id="ficha">
								<div class="grid grid-cols-1 gap-3 md:grid-cols-2">
									<?php
									$ficha_fields = [
										__( 'Marca', 'mauswp' )             => $marca,
										__( 'Tipo', 'mauswp' )              => $tipo_auto,
										__( 'Tipo de cama', 'mauswp' )      => $tipo_cama ? ( is_array( $tipo_cama ) ? implode( ', ', $tipo_cama ) : $tipo_cama ) : '',
										__( 'Plazas viajar', 'mauswp' )     => $plazas_viajar,
										__( 'Motor', 'mauswp' )             => $motor,
										__( 'CV', 'mauswp' )                => $cv ? $cv . ' CV' : '',
										__( 'Kilómetros', 'mauswp' )        => $km ? number_format_i18n( $km ) . ' km' : '',
										__( 'Transmisión', 'mauswp' )       => $transmision,
										__( 'Año', 'mauswp' )               => $ano,
										__( 'Largo', 'mauswp' )             => $largo,
										__( 'Alto', 'mauswp' )              => $alto,
									];
									foreach ( $ficha_fields as $label => $value ) :
										if ( '' === $value || null === $value ) {
											continue;
										}
										?>
										<div class="flex items-center justify-between border-b border-slate-200 pb-2 text-sm text-slate-700">
											<span><?php echo esc_html( $label ); ?></span>
											<span class="font-semibold text-[#1A2250]"><?php echo esc_html( $value ); ?></span>
										</div>
									<?php endforeach; ?>
								</div>
								<?php if ( $ficha_pdf ) : ?>
									<div class="mt-4">
										<a class="inline-flex items-center gap-2 text-sm font-semibold text-[#1A2250] hover:underline" href="<?php echo esc_url( $ficha_pdf ); ?>" target="_blank" rel="noopener noreferrer">
											<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 5v14m7-7H5"/></svg>
											<?php esc_html_e( 'Ver ficha PDF', 'mauswp' ); ?>
										</a>
									</div>
								<?php endif; ?>
								<?php if ( $extras ) : ?>
									<div class="mt-4 prose prose-slate">
										<?php echo wp_kses_post( $extras ); ?>
									</div>
								<?php endif; ?>
							</div>
							<div class="tab-panel hidden rounded-2xl bg-white p-5 shadow-md" data-auto-tab="vr" id="vr">
								<?php if ( $shortcode_vr ) : ?>
									<?php echo do_shortcode( $shortcode_vr ); ?>
								<?php else : ?>
									<p class="text-slate-600"><?php esc_html_e( 'Añade un shortcode VR para mostrar el 360º.', 'mauswp' ); ?></p>
								<?php endif; ?>
							</div>
							<div class="tab-panel hidden rounded-2xl bg-white p-5 shadow-md" data-auto-tab="contacto" id="contacto">
								<?php if ( $tab_contacto ) : ?>
									<?php echo wp_kses_post( $tab_contacto ); ?>
								<?php else : ?>
									<p class="text-slate-600"><?php esc_html_e( 'Añade contenido de contacto desde las opciones del tema.', 'mauswp' ); ?></p>
								<?php endif; ?>
							</div>
						</div>
					</div>

					<?php if ( $info_title || $info_content ) : ?>
						<div class="rounded-2xl bg-white p-5 shadow-md">
							<?php if ( $info_title ) : ?>
								<h3 class="text-xl font-bold text-[#1A2250]"><?php echo esc_html( $info_title ); ?></h3>
							<?php endif; ?>
							<?php if ( $info_content ) : ?>
								<div class="prose prose-slate mt-3"><?php echo wp_kses_post( $info_content ); ?></div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>

				<aside class="lg:col-span-5">
					<div class="sticky top-24 rounded-2xl bg-white p-5 shadow-md">
						<div class="grid grid-cols-2 overflow-hidden rounded-xl border border-slate-200">
							<div class="bg-[#1A2250] px-4 py-3 text-center text-2xl font-bold text-white">
								<?php echo esc_html( $precio ? number_format_i18n( $precio ) . '€' : __( 'Consultar', 'mauswp' ) ); ?>
							</div>
							<div class="flex items-center justify-center px-4 py-3 text-center text-sm font-semibold text-[#1A2250]">
								<?php echo esc_html( $financia_text ?: __( 'financia desde —', 'mauswp' ) ); ?>
							</div>
						</div>

						<div class="mt-4 divide-y divide-slate-200 text-sm text-slate-700">
							<?php
							$sidebar_fields = [
								__( 'Tipo de Autocaravana', 'mauswp' ) => $tipo_auto,
								__( 'Plazas para viajar', 'mauswp' )    => $plazas_viajar,
								__( 'Tipo de cama', 'mauswp' )          => $tipo_cama ? ( is_array( $tipo_cama ) ? implode( ', ', $tipo_cama ) : $tipo_cama ) : '',
								__( 'Motor', 'mauswp' )                 => $motor,
								__( 'CV', 'mauswp' )                    => $cv ? $cv . ' CV' : '',
								__( 'Kilómetros', 'mauswp' )            => $km ? number_format_i18n( $km ) : '',
								__( 'Año', 'mauswp' )                   => $ano,
							];
							foreach ( $sidebar_fields as $label => $value ) :
								if ( '' === $value || null === $value ) {
									continue;
								}
								?>
								<div class="flex items-center justify-between py-3">
									<span class="text-slate-700"><?php echo esc_html( $label ); ?>:</span>
									<span class="font-semibold text-[#1A2250]"><?php echo esc_html( $value ); ?></span>
								</div>
							<?php endforeach; ?>
						</div>

						<div class="mt-4 flex items-center justify-between">
							<a href="#ficha" class="text-sm font-semibold text-[#1A2250] hover:underline"><?php esc_html_e( 'Ver Ficha Técnica', 'mauswp' ); ?></a>
							<a href="#contacto" class="inline-flex items-center rounded-full border border-[#1A2250] px-4 py-2 text-sm font-semibold text-[#1A2250] transition hover:bg-[#1A2250] hover:text-white">
								<?php esc_html_e( 'Llámanos', 'mauswp' ); ?>
							</a>
						</div>
					</div>
				</aside>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
