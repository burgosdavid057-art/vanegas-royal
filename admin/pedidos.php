<?php
require_once __DIR__ . '/auth.php';
require_admin();

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'estado') {
    db()->prepare('UPDATE pedidos SET estado=? WHERE id=?')->execute([$_POST['estado'] ?? 'pendiente', (int)($_POST['id'] ?? 0)]);
    $msg = 'Pedido actualizado.';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'eliminar') {
    db()->prepare('DELETE FROM pedidos WHERE id=?')->execute([(int)($_POST['id'] ?? 0)]);
    $msg = 'Pedido eliminado.';
}

$pedidos = db()->query('SELECT * FROM pedidos ORDER BY id DESC')->fetchAll();
$estados = ['pendiente','confirmado','enviado','entregado','cancelado'];

admin_header('Pedidos', 'pedidos.php');
?>
<h1 class="font-display text-4xl mb-1">Pedidos</h1>
<p class="text-mute mb-7"><?= count($pedidos) ?> pedidos registrados</p>

<?php if ($msg): ?><div class="glass rounded p-3 mb-5 text-sm text-gold"><?= e($msg) ?></div><?php endif; ?>

<?php if (!$pedidos): ?>
  <div class="panel rounded-lg p-16 text-center border border-gold/15">
    <p class="font-display text-2xl text-gold">Aún no hay pedidos</p>
    <p class="text-mute mt-2">Los pedidos del checkout aparecerán aquí.</p>
  </div>
<?php else: ?>
  <div class="space-y-3" x-data="{ open: null }">
    <?php foreach ($pedidos as $o): $items = json_decode($o['items'], true) ?: []; ?>
      <div class="panel rounded-lg border border-gold/15 overflow-hidden">
        <button @click="open === <?= $o['id'] ?> ? open=null : open=<?= $o['id'] ?>" class="w-full flex items-center gap-4 p-4 text-left hover:bg-white/[.02]">
          <div class="font-display text-xl text-gold w-14 shrink-0">#<?= $o['id'] ?></div>
          <div class="flex-1 min-w-0">
            <p class="font-medium truncate"><?= e($o['cliente_nombre']) ?></p>
            <p class="text-xs text-mute"><?= e($o['telefono']) ?> · <?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></p>
          </div>
          <div class="text-right shrink-0">
            <p class="text-gold tabular-nums font-semibold"><?= precio_cop($o['total']) ?></p>
            <span class="text-xs px-2 py-0.5 rounded <?= $o['estado']==='pendiente'?'bg-gold/10 text-gold':($o['estado']==='cancelado'?'bg-red-500/10 text-red-300':'bg-green-500/10 text-green-300') ?>"><?= e($o['estado']) ?></span>
          </div>
          <svg class="w-5 h-5 text-mute transition-transform" :class="open===<?= $o['id'] ?> && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
        </button>

        <div x-show="open === <?= $o['id'] ?>" x-cloak x-transition.opacity class="border-t border-gold/10 p-5 grid md:grid-cols-[1fr_280px] gap-6">
          <!-- items -->
          <div>
            <h4 class="lbl mb-3">Artículos</h4>
            <div class="space-y-2">
              <?php foreach ($items as $it): ?>
                <div class="flex justify-between text-sm border-b border-white/5 pb-2">
                  <span><?= e($it['nombre'] ?? '') ?> <span class="text-mute">x<?= (int)($it['qty'] ?? 1) ?></span></span>
                  <span class="tabular-nums text-gold"><?= precio_cop($it['subtotal'] ?? (($it['precio'] ?? 0)*($it['qty'] ?? 1))) ?></span>
                </div>
              <?php endforeach; ?>
            </div>
            <?php if (!empty($o['notas'])): ?><p class="text-sm text-mute mt-4"><strong class="text-crema">Notas:</strong> <?= e($o['notas']) ?></p><?php endif; ?>
          </div>
          <!-- gestión -->
          <div class="space-y-4">
            <div class="text-sm space-y-1">
              <?php if ($o['email']): ?><p><span class="text-mute">Correo:</span> <?= e($o['email']) ?></p><?php endif; ?>
              <?php if ($o['direccion']): ?><p><span class="text-mute">Dirección:</span> <?= e($o['direccion']) ?><?= $o['ciudad']?', '.e($o['ciudad']):'' ?></p><?php endif; ?>
              <p><span class="text-mute">Método:</span> <?= e($o['metodo_pago']) ?></p>
            </div>
            <form method="post" class="flex gap-2">
              <input type="hidden" name="accion" value="estado"><input type="hidden" name="id" value="<?= $o['id'] ?>">
              <select name="estado" class="field !py-2 text-sm">
                <?php foreach ($estados as $es): ?><option <?= $o['estado']===$es?'selected':'' ?>><?= $es ?></option><?php endforeach; ?>
              </select>
              <button class="btn btn-ghost !py-2 !px-4 text-xs">Guardar</button>
            </form>
            <div class="flex gap-2">
              <a href="https://wa.me/<?= preg_replace('/\D/','',$o['telefono']) ?>" target="_blank" rel="noopener" class="btn btn-gold !py-2 !px-4 text-xs flex-1 justify-center">WhatsApp</a>
              <form method="post" onsubmit="return confirm('¿Eliminar pedido #<?= $o['id'] ?>?')">
                <input type="hidden" name="accion" value="eliminar"><input type="hidden" name="id" value="<?= $o['id'] ?>">
                <button class="px-3 py-2 rounded text-xs text-red-300 hover:bg-red-500/10">Eliminar</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php admin_footer(); ?>
