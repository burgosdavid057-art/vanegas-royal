<?php
require_once __DIR__ . '/includes/functions.php';
$activo = 'ofertas';
$titulo = 'Ofertas exclusivas · ' . SITE_NAME;
$enOferta = get_productos(['oferta' => 1, 'orden' => 'precio_desc']);
$banners  = get_ofertas();
include __DIR__ . '/includes/header.php';
?>

<section class="relative py-20 overflow-hidden">
  <div class="absolute inset-0" style="background:radial-gradient(circle at 50% -10%, rgba(212,175,55,.18), transparent 55%)"></div>
  <div class="container-x relative text-center">
    <p class="eyebrow reveal">Por tiempo limitado</p>
    <h1 class="font-display text-[clamp(2.8rem,7vw,5.5rem)] leading-none mt-3 reveal">Ofertas <span class="text-gold-shine">exclusivas</span></h1>
    <p class="text-crema/70 max-w-xl mx-auto mt-5 reveal">Piezas selectas en oro 18k con descuentos que no se repiten. Mientras haya stock.</p>
  </div>
</section>

<?php if ($banners): ?>
<section class="container-x pb-8" data-reveal-group>
  <div class="grid md:grid-cols-2 gap-5">
    <?php foreach ($banners as $b): ?>
      <div class="glass rounded-lg p-8 flex items-center gap-6 reveal">
        <div class="font-display text-5xl text-gold-shine shrink-0">%</div>
        <div>
          <h3 class="font-display text-2xl text-crema"><?= e($b['titulo']) ?></h3>
          <p class="text-mute text-sm mt-1"><?= e($b['descripcion']) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<section class="container-x py-12">
  <?php if (!$enOferta): ?>
    <div class="glass rounded p-16 text-center">
      <p class="font-display text-3xl text-gold">Pronto, nuevas ofertas</p>
      <p class="text-mute mt-2">Síguenos en WhatsApp para enterarte primero.</p>
      <a href="<?= whatsapp_link() ?>" target="_blank" rel="noopener" class="btn btn-gold mt-6">Escríbenos</a>
    </div>
  <?php else: ?>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5" data-reveal-group>
      <?php foreach ($enOferta as $p) product_card($p); ?>
    </div>
  <?php endif; ?>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
