<?php
require_once __DIR__ . '/includes/functions.php';
$slug = trim($_GET['slug'] ?? '');
$p = $slug ? get_producto_slug($slug) : null;
if (!$p && isset($_GET['id'])) $p = get_producto((int)$_GET['id']);
if (!$p) { http_response_code(404); $titulo = 'No encontrado'; include __DIR__.'/includes/header.php'; echo '<section class="container-x py-32 text-center"><h1 class="font-display text-5xl text-gold">404</h1><p class="text-mute mt-3">Esa joya no existe.</p><a href="'.base_url('tienda.php').'" class="btn btn-gold mt-8">Ir a la tienda</a></section>'; include __DIR__.'/includes/footer.php'; exit; }

$activo = 'tienda';
$titulo = $p['nombre'] . ' · ' . SITE_NAME;
$img    = product_image($p);
$final  = precio_final($p);
$oferta = tiene_oferta($p);
$relacionados = array_filter(get_productos(['categoria' => $p['categoria'], 'limit' => 5]), fn($r) => $r['id'] != $p['id']);
$relacionados = array_slice($relacionados, 0, 4);

$jsProd = htmlspecialchars(json_encode([
    'id' => (int)$p['id'], 'nombre' => $p['nombre'], 'precio' => $final, 'imagen' => $img,
], JSON_UNESCAPED_UNICODE), ENT_QUOTES);

$waMsg = "Hola Vanegas Royal, me interesa la pieza: {$p['nombre']} (" . precio_cop($final) . "). ¿Está disponible?";
include __DIR__ . '/includes/header.php';
?>

<section class="container-x pt-10 pb-6">
  <nav class="text-xs text-mute tracking-wider">
    <a href="<?= base_url('') ?>" class="hover:text-gold">Inicio</a> /
    <a href="<?= base_url('tienda.php') ?>" class="hover:text-gold">Tienda</a> /
    <a href="<?= base_url('tienda.php?cat='.urlencode($p['categoria'])) ?>" class="hover:text-gold"><?= e($p['categoria']) ?></a> /
    <span class="text-crema/70"><?= e($p['nombre']) ?></span>
  </nav>
</section>

<section class="container-x pb-20" x-data="{ qty: 1 }">
  <div class="grid lg:grid-cols-2 gap-12">
    <!-- Imagen -->
    <div class="reveal">
      <div class="card-prod overflow-hidden media">
        <?php if ($oferta): ?><span class="badge-oferta text-sm">-<?= descuento_pct($p) ?>%</span><?php endif; ?>
        <img src="<?= $img ?>" alt="<?= e($p['nombre']) ?>" class="w-full aspect-square object-cover">
      </div>
    </div>

    <!-- Info -->
    <div class="reveal">
      <p class="eyebrow"><?= e($p['categoria']) ?></p>
      <h1 class="font-display text-[clamp(2.2rem,4.5vw,3.5rem)] leading-tight mt-2"><?= e($p['nombre']) ?></h1>

      <div class="flex items-end gap-4 mt-5">
        <span class="text-gold font-semibold text-4xl tabular-nums"><?= precio_cop($final) ?></span>
        <?php if ($oferta): ?><span class="text-mute line-through text-xl mb-1"><?= precio_cop($p['precio']) ?></span><?php endif; ?>
      </div>

      <p class="text-crema/75 leading-relaxed mt-6"><?= e($p['descripcion']) ?></p>

      <!-- Especificaciones -->
      <dl class="grid grid-cols-2 gap-px bg-gold/10 mt-8 rounded overflow-hidden text-sm">
        <?php
        $specs = [
          'Material' => $p['material'],
          'Quilataje' => $p['quilataje'],
          'Peso' => $p['peso_g'] ? rtrim(rtrim(number_format($p['peso_g'],1,'.',''),'0'),'.') . ' g' : '—',
          'Disponibilidad' => ((int)$p['stock'] > 0 ? 'En stock' : 'Agotado'),
        ];
        foreach ($specs as $k => $v): ?>
          <div class="bg-bg p-4"><dt class="text-mute text-xs uppercase tracking-wider"><?= e($k) ?></dt><dd class="text-crema mt-1 font-medium"><?= e((string)$v) ?></dd></div>
        <?php endforeach; ?>
      </dl>

      <!-- Cantidad + acciones -->
      <div class="flex flex-wrap items-center gap-4 mt-9">
        <div class="flex items-center glass rounded">
          <button @click="qty = Math.max(1, qty-1)" class="w-11 h-11 grid place-items-center text-gold text-xl" aria-label="Menos">−</button>
          <span x-text="qty" class="w-10 text-center tabular-nums"></span>
          <button @click="qty++" class="w-11 h-11 grid place-items-center text-gold text-xl" aria-label="Más">+</button>
        </div>
        <button x-data @click="$store.cart.add(<?= $jsProd ?>, qty)" data-magnetic class="btn btn-gold flex-1 sm:flex-none justify-center">
          Añadir al carrito
        </button>
      </div>
      <div class="flex flex-wrap gap-4 mt-4">
        <a href="<?= base_url('carrito.php') ?>" class="btn btn-ghost flex-1 sm:flex-none justify-center">Ir al carrito</a>
        <a href="<?= whatsapp_link($waMsg) ?>" target="_blank" rel="noopener" class="btn btn-ghost flex-1 sm:flex-none justify-center" style="border-color:#1faa53;color:#5fd58a">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163a11.867 11.867 0 0 1-1.587-5.946C.16 5.335 5.495 0 12.05 0a11.817 11.817 0 0 1 8.413 3.488 11.824 11.824 0 0 1 3.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 0 1-5.688-1.448L.057 24z"/></svg>
          Consultar
        </a>
      </div>

      <div class="flex items-center gap-6 mt-8 text-xs text-mute tracking-wide">
        <span class="flex items-center gap-2"><svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg> Autenticidad garantizada</span>
        <span class="flex items-center gap-2"><svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-6"/></svg> Envío asegurado</span>
      </div>
    </div>
  </div>
</section>

<?php if ($relacionados): ?>
<section class="container-x py-16" data-reveal-group>
  <h2 class="font-display text-4xl mb-10 reveal">También te puede <span class="text-gold">dominar</span></h2>
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
    <?php foreach ($relacionados as $r) product_card($r); ?>
  </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
