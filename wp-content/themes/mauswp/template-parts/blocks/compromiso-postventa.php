<?php
/**
 * Bloque: Compromiso postventa
 * Template para ACF Block (acf/compromiso-postventa)
 */

$subtitle = get_field('subtitle');
$title    = get_field('title');
$items    = get_field('items');
$image    = get_field('image');
?>

<section class="py-16">
	<div class="mx-auto w-full max-w-7xl px-4 lg:px-8">
		
		<?php if ($subtitle): ?>
			<p class="mb-3 text-xl font-semibold uppercase leading-none text-primary-400 font-sans">
				<?= esc_html($subtitle); ?>
			</p>
		<?php endif; ?>

	<?php if ($title): ?>
		<h2 class="mb-10 text-3xl md:text-4xl font-bold leading-none font-['Montserrat'] bg-gradient-to-r from-indigo-900 to-indigo-950 bg-clip-text text-transparent">
			<?= esc_html($title); ?>
		</h2>
	<?php endif; ?>

		<div class="grid grid-cols-1 md:grid-cols-12 gap-10">

			<!-- Columna 1: 7/12 -->
			<div class="md:col-span-7 space-y-10">
				<?php if ($items): ?>
					<?php foreach ($items as $index => $item): ?>
						<?php
							$icon  = $item['icon'];
							$item_title = $item['item_title'];
							$list  = $item['list_items'];

							// delay incremental para animaciones
							$delay = $index * 150;
						?>
						
						<div 
							class="opacity-0 -translate-x-8 transition-all duration-700 scroll-fade-left"
							data-delay="<?= $delay; ?>"
						>
							<div class="flex items-center gap-4 mb-3">
								<?php if ($icon): ?>
									<img 
										src="<?= esc_url($icon['url']); ?>"
										alt=""
										loading="lazy"
										decoding="async"
										class="h-6 w-6 object-contain"
									>
								<?php endif; ?>

                                <?php if ($item_title): ?>
									<h3 class="text-xl font-semibold leading-none text-neutral-700 font-sans">
										<?= esc_html($item_title); ?>
									</h3>
                                <?php endif; ?>
							</div>

							<?php if ($list): ?>
								<ul class="space-y-2 list-disc pl-6">
									<?php foreach ($list as $li): ?>
										<li class="text-base font-medium leading-normal text-neutral-600 font-sans">
											<?= wp_kses_post($li['text']); ?>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</div>

					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<!-- Columna 2: 5/12 -->
			<div class="md:col-span-5 flex justify-center md:justify-end">
				<?php if ($image): ?>
					<img 
						src="<?= esc_url($image['url']); ?>"
						alt="<?= esc_attr($image['alt']); ?>"
						loading="lazy"
						decoding="async"
						class="w-full max-w-[450px] min-h-[475px] rounded-xl object-cover"
					>
				<?php endif; ?>
			</div>

		</div>
	</div>
</section>
