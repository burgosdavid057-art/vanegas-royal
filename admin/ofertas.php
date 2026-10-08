<?php
require_once __DIR__ . '/auth.php';
require_admin();

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    if ($accion === 'crear') {
        $titulo = trim($_POST['titulo'] ?? '');
        $desc   = trim($_POST['descripcion'] ?? '');
        $pct    = (int)($_POST['descuento_pct'] ?? 0);
        if ($titulo !== '') {
            db()->prepare('INSERT INTO ofertas (titulo, descripcion, descuento_pct, activo) VALUES (?,?,?,1)')
                ->execute([$titulo, $desc, $pct]);
            $msg = 'Oferta creada.';
        }
    } elseif ($accion === 'eliminar') {
        db()->prepare('DELETE FROM ofertas WHERE id=?')->execute([(int)($_POST['id'] ?? 0)]);
        $msg = 'Oferta eliminada.';
    } elseif ($accion === 'toggle') {
        db()->prepare('UPDATE ofertas SET activo = 1 - activo WHERE id=?')->execute([(int)($_POST['id'] ?? 0)]);
        $msg = 'Estado actualizado.';
    }
}

$ofertas   = db()->query('SELECT * FROM ofertas ORDER BY id DESC')->fetchAll();
$enOferta  = db()->query('SELECT * FROM productos WHERE precio_oferta IS NOT NULL ORDER BY id DESC')->fetchAll();

admin_header('Ofertas', 'ofertas.php');
?>
<h1 class="font-display text-4xl mb-1">Ofertas</h1>
<p class="text-mute mb-7">Banners promocionales y productos con descuento</p>

<?php if ($msg): ?><div class="glass rounded p-3 mb-5 text-sm text-gold"><?= e($msg) ?></div><?php endif; ?>

<div class="grid lg:grid-cols-[360px_1fr] gap-6">
  <!-- Crear banner -->
  <div class="panel rounded-lg p-6 border border-gold/15 h-fit">
    <h2 class="font-display text-2xl mb-4">Nuevo banner</h2>
    <form method="post" class="space-y-4">
      <input type="hidden" name="accion" value="crear">
      <div><label class="lbl">Título *</label><input name="titulo" required class="field" placeholder="Colección Dragón -20%"></div>
      <div><label class="lbl">Descripción</label><textarea name="descripcion" rows="2" class="field" placeholder="Por tiempo limitado..."></textarea></div>
      <div><label class="lbl">Descuento (%)</label><input name="descuento_pct" type="number" min="0" max="90" class="field" placeholder="20"></div>
      <button class="btn btn-gold w-full justify-center">Crear banner</button>
    </form>
    <p class="text-xs text-mute mt-4">Los banners aparecen en la página de Ofertas. Para poner un producto en oferta, edítalo y asígnale un <strong>precio de oferta</strong>.</p>
  </div>

  <div class="space-y-8">
    <!-- Banners -->
    <div>
      <h2 class="font-display text-2xl mb-4">Banners activos</h2>
      <div class="space-y-3">
        <?php if (!$ofertas): ?>
          <p class="text-mute text-sm">No hay banners.</p>
        <?php else: foreach ($ofertas as $o): ?>
          <div class="panel rounded-lg p-5 border border-gold/15 flex items-center gap-4">
            <div class="font-display text-3xl text-gold-shine shrink-0"><?= (int)$o['descuento_pct'] ?>%</div>
            <div class="flex-1 min-w-0">
              <p class="font-medium truncate"><?= e($o['titulo']) ?></p>
              <p class="text-sm text-mute truncate"><?= e($o['descripcion']) ?></p>
            </div>
            <form method="post"><input type="hidden" name="accion" value="toggle"><input type="hidden" name="id" value="<?= $o['id'] ?>">
              <button class="text-xs px-2 py-1 rounded <?= $o['activo']?'bg-green-500/10 text-green-300':'bg-white/10 text-mute' ?>"><?= $o['activo']?'Activo':'Oculto' ?></button>
            </form>
            <form method="post" onsubmit="return confirm('¿Eliminar este banner?')"><input type="hidden" name="accion" value="eliminar"><input type="hidden" name="id" value="<?= $o['id'] ?>">
              <button class="text-xs text-red-300 hover:bg-red-500/10 px-2 py-1 rounded">Eliminar</button>
            </form>
          </div>
        <?php endforeach; endif; ?>
      </div>
    </div>

    <!-- Productos en oferta -->
    <div>
      <h2 class="font-display text-2xl mb-4">Productos en oferta (<?= count($enOferta) ?>)</h2>
      <div class="grid sm:grid-cols-2 gap-3">
        <?php if (!$enOferta): ?>
          <p class="text-mute text-sm">Ningún producto tiene precio de oferta todavía.</p>
        <?php else: foreach ($enOferta as $p): ?>
          <a href="<?= base_url('admin/producto-form.php?id=' . $p['id']) ?>" class="panel rounded-lg p-3 border border-gold/15 flex items-center gap-3 hover:border-gold/40">
            <img src="<?= product_image($p) ?>" class="w-12 h-12 rounded object-cover">
            <div class="flex-1 min-w-0">
              <p class="truncate text-sm"><?= e($p['nombre']) ?></p>
              <p class="text-xs"><span class="text-gold"><?= precio_cop($p['precio_oferta']) ?></span> <span class="text-mute line-through"><?= precio_cop($p['precio']) ?></span></p>
            </div>
            <span class="text-xs text-gold shrink-0">-<?= descuento_pct($p) ?>%</span>
          </a>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>
</div>

<?php admin_footer(); ?>
