<?php
require_once __DIR__ . '/auth.php';
require_admin();

$nProductos = (int)db()->query('SELECT COUNT(*) FROM productos')->fetchColumn();
$nActivos   = (int)db()->query('SELECT COUNT(*) FROM productos WHERE activo=1')->fetchColumn();
$nOfertas   = (int)db()->query('SELECT COUNT(*) FROM productos WHERE precio_oferta IS NOT NULL')->fetchColumn();
$nPedidos   = (int)db()->query('SELECT COUNT(*) FROM pedidos')->fetchColumn();
$ventas     = (int)db()->query("SELECT COALESCE(SUM(total),0) FROM pedidos")->fetchColumn();
$pendientes = (int)db()->query("SELECT COUNT(*) FROM pedidos WHERE estado='pendiente'")->fetchColumn();
$ultimos    = db()->query('SELECT * FROM pedidos ORDER BY id DESC LIMIT 6')->fetchAll();
$bajos      = db()->query('SELECT * FROM productos WHERE activo=1 AND stock <= 2 ORDER BY stock ASC LIMIT 6')->fetchAll();

admin_header('Dashboard', 'index.php');
?>
<div class="flex items-center justify-between mb-8">
  <div>
    <h1 class="font-display text-4xl">Hola de nuevo 👋</h1>
    <p class="text-mute mt-1">Resumen de tu joyería</p>
  </div>
  <a href="<?= base_url('admin/producto-form.php') ?>" class="btn btn-gold">+ Nuevo producto</a>
</div>

<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
  <?php
  $cards = [
    ['Productos', $nProductos, "$nActivos activos", 'productos.php'],
    ['En oferta', $nOfertas, 'con descuento', 'ofertas.php'],
    ['Pedidos', $nPedidos, "$pendientes pendientes", 'pedidos.php'],
    ['Ventas registradas', precio_cop($ventas), 'acumulado', 'pedidos.php'],
  ];
  foreach ($cards as $c): ?>
    <a href="<?= base_url('admin/' . $c[3]) ?>" class="panel rounded-lg p-6 border border-gold/15 hover:border-gold/40 transition-colors">
      <p class="text-xs text-mute uppercase tracking-wider"><?= e($c[0]) ?></p>
      <p class="font-display text-3xl text-gold mt-2 tabular-nums"><?= e((string)$c[1]) ?></p>
      <p class="text-xs text-mute mt-1"><?= e($c[2]) ?></p>
    </a>
  <?php endforeach; ?>
</div>

<div class="grid lg:grid-cols-2 gap-6">
  <!-- Últimos pedidos -->
  <div class="panel rounded-lg border border-gold/15 overflow-hidden">
    <div class="flex items-center justify-between p-5 border-b border-gold/10">
      <h2 class="font-display text-2xl">Últimos pedidos</h2>
      <a href="<?= base_url('admin/pedidos.php') ?>" class="text-sm text-gold link-under">Ver todos</a>
    </div>
    <?php if (!$ultimos): ?>
      <p class="text-mute text-sm p-6">Aún no hay pedidos.</p>
    <?php else: ?>
      <table class="w-full text-sm">
        <tbody>
          <?php foreach ($ultimos as $o): ?>
            <tr class="border-b border-white/5">
              <td class="p-4">#<?= $o['id'] ?></td>
              <td class="p-4 text-crema/80"><?= e($o['cliente_nombre']) ?></td>
              <td class="p-4 text-gold tabular-nums text-right"><?= precio_cop($o['total']) ?></td>
              <td class="p-4 text-right"><span class="text-xs px-2 py-1 rounded <?= $o['estado']==='pendiente'?'bg-gold/10 text-gold':'bg-green-500/10 text-green-300' ?>"><?= e($o['estado']) ?></span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

  <!-- Stock bajo -->
  <div class="panel rounded-lg border border-gold/15 overflow-hidden">
    <div class="p-5 border-b border-gold/10"><h2 class="font-display text-2xl">Stock bajo</h2></div>
    <?php if (!$bajos): ?>
      <p class="text-mute text-sm p-6">Todo bien surtido.</p>
    <?php else: ?>
      <?php foreach ($bajos as $p): ?>
        <a href="<?= base_url('admin/producto-form.php?id=' . $p['id']) ?>" class="flex items-center gap-4 p-4 border-b border-white/5 hover:bg-white/5">
          <img src="<?= product_image($p) ?>" class="w-12 h-12 rounded object-cover">
          <div class="flex-1 min-w-0"><p class="truncate"><?= e($p['nombre']) ?></p><p class="text-xs text-mute"><?= e($p['categoria']) ?></p></div>
          <span class="text-sm <?= $p['stock']==0?'text-red-400':'text-gold' ?>"><?= (int)$p['stock'] ?> u.</span>
        </a>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<?php admin_footer(); ?>
