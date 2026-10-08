<?php
require_once __DIR__ . '/includes/functions.php';
$titulo = SITE_NAME . ' · Joyería de lujo en oro 18k';
$activo = '';
$intro  = true;
$destacados = get_productos(['destacado' => 1, 'limit' => 8]);
$ofertas    = get_productos(['oferta' => 1, 'limit' => 4]);
$cats = [
    'Anillos' => 'Sellos y solitarios que imponen presencia.',
    'Cadenas' => 'Cubanas, francos y tejidos de estatus.',
    'Dijes'   => 'La firma del dragón, en oro macizo.',
    'Relojes' => 'El tiempo, esculpido en oro.',
];
include __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO ===================== -->
<section class="relative min-h-[100svh] flex items-center overflow-hidden">
  <!-- glow + dragón de fondo con parallax -->
  <div class="absolute inset-0 pointer-events-none">
    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[80vw] max-w-[900px] aspect-square rounded-full"
         style="background:radial-gradient(circle, rgba(212,175,55,.18), transparent 60%)"></div>
  </div>
  <img src="<?= base_url('assets/img/logo.png') ?>" alt="" aria-hidden="true" data-parallax="0.18"
       class="absolute right-[-6%] top-1/2 -translate-y-1/2 h-[78vh] max-h-[760px] w-auto opacity-25 md:opacity-40 select-none pointer-events-none"
       onerror="this.remove()">

  <div class="container-x relative grid lg:grid-cols-2 gap-10 items-center py-24">
    <div>
      <p class="eyebrow mb-6" data-hero>Joyería de lujo · Medellín, Colombia</p>
      <h1 class="font-display font-bold leading-[.95] text-[clamp(3rem,9vw,7rem)]" data-hero>
        Hecho<br>para <span class="text-gold-shine">dominar</span>
      </h1>
      <p class="text-crema/70 max-w-md mt-7 text-lg leading-relaxed" data-hero>
        Piezas en oro 18k diseñadas para quienes no piden permiso.
        Anillos, cadenas y relojes con el sello del dragón Vanegas Royal.
      </p>
      <div class="flex flex-wrap gap-4 mt-9" data-hero>
        <a href="<?= base_url('tienda.php') ?>" data-magnetic data-cursor class="btn btn-gold">Explorar colección</a>
        <a href="<?= base_url('ofertas.php') ?>" data-cursor class="btn btn-ghost">Ofertas exclusivas</a>
      </div>
      <div class="flex gap-10 mt-14" data-hero>
        <div><div class="font-display text-3xl text-gold"><span data-count="18" data-suffix="k">0</span></div><div class="text-xs text-mute tracking-widest uppercase mt-1">Clientes</div></div>
        <div><div class="font-display text-3xl text-gold"><span data-count="18">0</span></div><div class="text-xs text-mute tracking-widest uppercase mt-1">Quilates</div></div>
        <div><div class="font-display text-3xl text-gold"><span data-count="100" data-suffix="%">0</span></div><div class="text-xs text-mute tracking-widest uppercase mt-1">Autenticidad</div></div>
      </div>
    </div>
  </div>

  <!-- scroll hint -->
  <div class="absolute bottom-8 left-1/2 -translate-x-1/2 text-mute text-xs tracking-[.3em] uppercase flex flex-col items-center gap-2">
    <span>Desliza</span>
    <span class="w-px h-10 bg-gradient-to-b from-gold to-transparent animate-pulse"></span>
  </div>
</section>

<!-- ===================== CATEGORÍAS ===================== -->
<section class="container-x py-24" data-reveal-group>
  <div class="flex items-end justify-between flex-wrap gap-4 mb-12">
    <div>
      <p class="eyebrow reveal">El catálogo</p>
      <h2 class="font-display text-5xl mt-2 reveal">Cada pieza, un <span class="text-gold">trono</span></h2>
    </div>
    <a href="<?= base_url('tienda.php') ?>" class="link-under text-gold reveal">Ver todo →</a>
  </div>
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
    <?php foreach ($cats as $nombre => $desc): ?>
      <a href="<?= base_url('tienda.php?cat=' . urlencode($nombre)) ?>"
         class="card-prod reveal p-7 flex flex-col justify-between min-h-[220px] group" data-cursor>
        <span class="font-display text-7xl text-gold/15 group-hover:text-gold/30 transition-colors leading-none"><?= mb_substr($nombre,0,1) ?></span>
        <div>
          <h3 class="font-display text-2xl group-hover:text-gold transition-colors"><?= e($nombre) ?></h3>
          <p class="text-mute text-sm mt-1"><?= e($desc) ?></p>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- ===================== DESTACADOS ===================== -->
<section class="container-x py-12" data-reveal-group>
  <div class="text-center mb-14">
    <p class="eyebrow reveal">Selección de la casa</p>
    <h2 class="font-display text-5xl mt-2 reveal">Piezas <span class="text-gold">destacadas</span></h2>
    <hr class="gold-rule w-24 mx-auto mt-6 reveal">
  </div>
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
    <?php foreach ($destacados as $p) product_card($p); ?>
  </div>
</section>

<!-- ===================== EL SELLO ===================== -->
<section class="relative py-28 mt-20 overflow-hidden">
  <div class="absolute inset-0 bg-gradient-to-b from-black/0 via-gold/[.04] to-black/0"></div>
  <div class="container-x relative grid md:grid-cols-2 gap-14 items-center" data-reveal-group>
    <div class="reveal">
      <p class="eyebrow">El sello Vanegas</p>
      <h2 class="font-display text-5xl mt-3 leading-tight">Lujo que se<br><span class="text-gold">hereda</span></h2>
      <p class="text-crema/70 mt-6 leading-relaxed">
        No fabricamos joyas: forjamos símbolos. Cada pieza nace en oro 18k certificado,
        trabajada a mano hasta el último detalle, con la garantía de autenticidad que lleva el nombre Vanegas Royal.
      </p>
      <ul class="mt-8 space-y-4">
        <?php foreach ([
          ['Oro 18k certificado', 'Pureza garantizada por escrito en cada compra.'],
          ['Diseño exclusivo', 'Piezas de edición limitada con la firma del dragón.'],
          ['Envío asegurado', 'A toda Colombia, con seguimiento y protección total.'],
        ] as $b): ?>
          <li class="flex gap-4">
            <span class="shrink-0 w-9 h-9 grid place-items-center rounded-full border border-gold/40 text-gold">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
            </span>
            <div><h4 class="font-semibold text-crema"><?= e($b[0]) ?></h4><p class="text-mute text-sm"><?= e($b[1]) ?></p></div>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="reveal relative">
      <div class="glass rounded p-10 text-center">
        <div class="font-display text-[8rem] leading-none text-gold-shine">VR</div>
        <p class="font-display text-2xl text-crema mt-4 italic">"Hecho para dominar"</p>
        <hr class="gold-rule w-16 mx-auto my-6">
        <p class="text-mute text-sm">Cada joya Vanegas Royal incluye certificado de autenticidad y estuche de presentación premium.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===================== OFERTAS BANNER ===================== -->
<?php if ($ofertas): ?>
<section class="container-x py-16" data-reveal-group>
  <div class="glass rounded-lg overflow-hidden">
    <div class="grid lg:grid-cols-5">
      <div class="lg:col-span-2 p-10 lg:p-14 flex flex-col justify-center reveal">
        <p class="eyebrow">Por tiempo limitado</p>
        <h2 class="font-display text-5xl mt-3">Ofertas <span class="text-gold">exclusivas</span></h2>
        <p class="text-mute mt-4">Piezas selectas con descuentos que no se repiten. Mientras haya stock.</p>
        <a href="<?= base_url('ofertas.php') ?>" data-magnetic class="btn btn-gold mt-8 self-start">Ver ofertas</a>
      </div>
      <div class="lg:col-span-3 grid grid-cols-2 sm:grid-cols-4 gap-px bg-gold/10">
        <?php foreach ($ofertas as $p): $img = product_image($p); ?>
          <a href="<?= base_url('producto.php?slug=' . urlencode($p['slug'])) ?>" class="relative bg-bg group overflow-hidden reveal media" data-cursor>
            <img src="<?= $img ?>" alt="<?= e($p['nombre']) ?>" loading="lazy" class="w-full aspect-square object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition duration-700">
            <span class="badge-oferta">-<?= descuento_pct($p) ?>%</span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===================== TESTIMONIOS ===================== -->
<section class="container-x py-20" data-reveal-group>
  <div class="text-center mb-14">
    <p class="eyebrow reveal">Lo que dicen</p>
    <h2 class="font-display text-5xl mt-2 reveal">Quienes ya <span class="text-gold">dominan</span></h2>
  </div>
  <div class="grid md:grid-cols-3 gap-5">
    <?php foreach ([
      ['La cadena cubana superó todo lo que esperaba. Oro de verdad, acabado impecable.', 'Andrés M.', 'Medellín'],
      ['Mi anillo sello dragón es una obra de arte. La atención por WhatsApp fue de otro nivel.', 'Carolina R.', 'Bogotá'],
      ['Compré un reloj para mi papá y llegó perfecto, con certificado y estuche. Recomendadísimos.', 'Julián P.', 'Cali'],
    ] as $t): ?>
      <figure class="card-prod p-8 reveal">
        <div class="text-gold text-2xl mb-3">★★★★★</div>
        <blockquote class="font-display text-xl text-crema/90 italic leading-snug">"<?= e($t[0]) ?>"</blockquote>
        <figcaption class="mt-5 text-sm"><span class="text-gold font-semibold"><?= e($t[1]) ?></span> <span class="text-mute">· <?= e($t[2]) ?></span></figcaption>
      </figure>
    <?php endforeach; ?>
  </div>
</section>

<!-- ===================== CTA FINAL ===================== -->
<section class="relative py-28 overflow-hidden">
  <div class="absolute inset-0" style="background:radial-gradient(circle at 50% 120%, rgba(212,175,55,.16), transparent 60%)"></div>
  <div class="container-x relative text-center" data-reveal-group>
    <h2 class="font-display text-[clamp(2.5rem,6vw,5rem)] leading-none reveal">¿Listo para <span class="text-gold-shine">tu corona</span>?</h2>
    <p class="text-crema/70 max-w-xl mx-auto mt-6 reveal">Escríbenos por WhatsApp y un asesor te ayuda a elegir la pieza perfecta. Atención personalizada, sin compromiso.</p>
    <div class="flex flex-wrap gap-4 justify-center mt-10 reveal">
      <a href="<?= whatsapp_link() ?>" target="_blank" rel="noopener" data-magnetic class="btn btn-gold">Hablar por WhatsApp</a>
      <a href="<?= base_url('tienda.php') ?>" class="btn btn-ghost">Ver la tienda</a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
