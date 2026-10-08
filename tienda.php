<?php
require_once __DIR__ . '/includes/functions.php';
$activo = 'tienda';
$cat    = trim($_GET['cat'] ?? '');
$q      = trim($_GET['q'] ?? '');
$orden  = $_GET['orden'] ?? 'recientes';
$titulo = ($cat ?: 'Tienda') . ' · ' . SITE_NAME;

$productos  = get_productos(['categoria' => $cat, 'buscar' => $q, 'orden' => $orden]);
$categorias = get_categorias();
include __DIR__ . '/includes/header.php';
?>

<!-- Encabezado -->
<section class="container-x pt-16 pb-10">
  <p class="eyebrow reveal">La colección</p>
  <h1 class="font-display text-[clamp(2.5rem,6vw,4.5rem)] leading-none mt-2 reveal">
    <?= $cat ? e($cat) : 'Toda la <span class="text-gold">joyería</span>' ?>
  </h1>
  <p class="text-mute mt-4 reveal"><?= count($productos) ?> piezas en oro 18k</p>
</section>

<section class="container-x pb-24">
  <div class="grid lg:grid-cols-[230px_1fr] gap-10">
    <!-- Filtros -->
    <aside class="space-y-8">
      <form method="get" class="space-y-3">
        <label class="lbl">Buscar</label>
        <div class="relative">
          <input type="search" name="q" value="<?= e($q) ?>" placeholder="Anillo, cadena, dragón..." class="field pr-10">
          <button class="absolute right-3 top-1/2 -translate-y-1/2 text-gold" aria-label="Buscar">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M17 10.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z"/></svg>
          </button>
          <?php if ($cat): ?><input type="hidden" name="cat" value="<?= e($cat) ?>"><?php endif; ?>
        </div>
      </form>

      <div>
        <h3 class="lbl mb-3">Categorías</h3>
        <ul class="space-y-1.5 text-sm">
          <li><a href="<?= base_url('tienda.php') ?>" class="block py-1.5 <?= !$cat ? 'text-gold' : 'text-crema/70 hover:text-gold' ?>">Todas</a></li>
          <?php foreach ($categorias as $c): ?>
            <li><a href="<?= base_url('tienda.php?cat=' . urlencode($c['categoria'])) ?>"
                   class="flex justify-between py-1.5 <?= $cat === $c['categoria'] ? 'text-gold' : 'text-crema/70 hover:text-gold' ?>">
              <span><?= e($c['categoria']) ?></span><span class="text-mute"><?= $c['c'] ?></span>
            </a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <a href="<?= base_url('ofertas.php') ?>" class="block glass rounded p-4 text-center hover:border-gold transition-colors">
        <span class="text-gold font-display text-xl">Ofertas exclusivas</span>
        <span class="block text-mute text-xs mt-1">Hasta -30%</span>
      </a>
    </aside>

    <!-- Grid -->
    <div>
      <div class="flex items-center justify-between mb-6 gap-4">
        <p class="text-mute text-sm hidden sm:block"><?= count($productos) ?> resultados</p>
        <form method="get" class="ml-auto flex items-center gap-2">
          <?php if ($cat): ?><input type="hidden" name="cat" value="<?= e($cat) ?>"><?php endif; ?>
          <?php if ($q): ?><input type="hidden" name="q" value="<?= e($q) ?>"><?php endif; ?>
          <label class="lbl !mb-0">Ordenar</label>
          <select name="orden" onchange="this.form.submit()" class="field !py-2 !w-auto">
            <option value="recientes" <?= $orden==='recientes'?'selected':'' ?>>Recientes</option>
            <option value="precio_asc" <?= $orden==='precio_asc'?'selected':'' ?>>Precio: menor</option>
            <option value="precio_desc" <?= $orden==='precio_desc'?'selected':'' ?>>Precio: mayor</option>
            <option value="nombre" <?= $orden==='nombre'?'selected':'' ?>>Nombre A-Z</option>
          </select>
        </form>
      </div>

      <?php if (!$productos): ?>
        <div class="glass rounded p-16 text-center">
          <p class="font-display text-3xl text-gold">Sin resultados</p>
          <p class="text-mute mt-2">No encontramos piezas con esos filtros.</p>
          <a href="<?= base_url('tienda.php') ?>" class="btn btn-ghost mt-6">Ver toda la colección</a>
        </div>
      <?php else: ?>
        <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-5" data-reveal-group>
          <?php foreach ($productos as $p) product_card($p); ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
