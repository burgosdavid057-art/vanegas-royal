<?php
require_once __DIR__ . '/auth.php';
require_admin();

$msg = '';
// Eliminar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'eliminar') {
    $id = (int)($_POST['id'] ?? 0);
    $prod = get_producto($id) ?: db()->query("SELECT * FROM productos WHERE id=$id")->fetch();
    if ($prod) {
        if (!empty($prod['imagen']) && is_file(UPLOAD_DIR . '/' . $prod['imagen'])) @unlink(UPLOAD_DIR . '/' . $prod['imagen']);
        db()->prepare('DELETE FROM productos WHERE id=?')->execute([$id]);
        $msg = 'Producto eliminado.';
    }
}
// Activar / desactivar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'toggle') {
    $id = (int)($_POST['id'] ?? 0);
    db()->prepare('UPDATE productos SET activo = 1 - activo WHERE id=?')->execute([$id]);
    $msg = 'Estado actualizado.';
}

$q = trim($_GET['q'] ?? '');
$sql = 'SELECT * FROM productos';
$args = [];
if ($q !== '') { $sql .= ' WHERE nombre LIKE ?'; $args[] = "%$q%"; }
$sql .= ' ORDER BY id DESC';
$st = db()->prepare($sql); $st->execute($args);
$productos = $st->fetchAll();

admin_header('Productos', 'productos.php');
?>
<div class="flex items-center justify-between mb-6 gap-4 flex-wrap">
  <div>
    <h1 class="font-display text-4xl">Productos</h1>
    <p class="text-mute mt-1"><?= count($productos) ?> piezas en catálogo</p>
  </div>
  <a href="<?= base_url('admin/producto-form.php') ?>" class="btn btn-gold">+ Nuevo producto</a>
</div>

<?php if ($msg): ?><div class="glass rounded p-3 mb-5 text-sm text-gold"><?= e($msg) ?></div><?php endif; ?>

<form method="get" class="mb-5 max-w-xs">
  <input name="q" value="<?= e($q) ?>" placeholder="Buscar producto..." class="field">
</form>

<div class="panel rounded-lg border border-gold/15 overflow-x-auto">
  <table class="w-full text-sm min-w-[680px]">
    <thead class="text-mute text-xs uppercase tracking-wider border-b border-gold/10">
      <tr>
        <th class="text-left p-4 font-medium">Producto</th>
        <th class="text-left p-4 font-medium">Categoría</th>
        <th class="text-right p-4 font-medium">Precio</th>
        <th class="text-center p-4 font-medium">Stock</th>
        <th class="text-center p-4 font-medium">Estado</th>
        <th class="text-right p-4 font-medium">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($productos as $p): ?>
        <tr class="border-b border-white/5 hover:bg-white/[.02]">
          <td class="p-4">
            <div class="flex items-center gap-3">
              <img src="<?= product_image($p) ?>" class="w-12 h-12 rounded object-cover shrink-0">
              <div class="min-w-0">
                <p class="truncate font-medium flex items-center gap-2"><?= e($p['nombre']) ?>
                  <?php if ($p['destacado']): ?><span class="text-gold text-xs">★</span><?php endif; ?>
                </p>
                <?php if (tiene_oferta($p)): ?><span class="text-xs text-gold">En oferta -<?= descuento_pct($p) ?>%</span><?php endif; ?>
              </div>
            </div>
          </td>
          <td class="p-4 text-crema/70"><?= e($p['categoria']) ?></td>
          <td class="p-4 text-right tabular-nums">
            <span class="text-gold"><?= precio_cop(precio_final($p)) ?></span>
            <?php if (tiene_oferta($p)): ?><br><span class="text-xs text-mute line-through"><?= precio_cop($p['precio']) ?></span><?php endif; ?>
          </td>
          <td class="p-4 text-center <?= $p['stock']==0?'text-red-400':'' ?>"><?= (int)$p['stock'] ?></td>
          <td class="p-4 text-center">
            <form method="post" class="inline">
              <input type="hidden" name="accion" value="toggle"><input type="hidden" name="id" value="<?= $p['id'] ?>">
              <button class="text-xs px-2 py-1 rounded <?= $p['activo']?'bg-green-500/10 text-green-300':'bg-white/10 text-mute' ?>"><?= $p['activo']?'Activo':'Oculto' ?></button>
            </form>
          </td>
          <td class="p-4">
            <div class="flex items-center justify-end gap-2">
              <a href="<?= base_url('admin/producto-form.php?id=' . $p['id']) ?>" class="px-3 py-1.5 rounded glass text-xs hover:border-gold hover:text-gold">Editar</a>
              <form method="post" onsubmit="return confirm('¿Eliminar &quot;<?= e($p['nombre']) ?>&quot;? Esta acción no se puede deshacer.')">
                <input type="hidden" name="accion" value="eliminar"><input type="hidden" name="id" value="<?= $p['id'] ?>">
                <button class="px-3 py-1.5 rounded text-xs text-red-300 hover:bg-red-500/10">Eliminar</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$productos): ?>
        <tr><td colspan="6" class="p-10 text-center text-mute">No hay productos. <a href="<?= base_url('admin/producto-form.php') ?>" class="text-gold">Crea el primero</a>.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php admin_footer(); ?>
