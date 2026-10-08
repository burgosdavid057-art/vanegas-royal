<?php
require_once __DIR__ . '/includes/functions.php';
$activo = 'tienda';
$titulo = 'Finalizar compra · ' . SITE_NAME;

$errores = [];
$pedido_ok = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $telefono  = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $ciudad    = trim($_POST['ciudad'] ?? '');
    $notas     = trim($_POST['notas'] ?? '');
    $metodo    = $_POST['metodo'] ?? 'whatsapp';
    $rawItems  = json_decode($_POST['items'] ?? '[]', true);

    if ($nombre === '')   $errores[] = 'Ingresa tu nombre.';
    if ($telefono === '') $errores[] = 'Ingresa un teléfono de contacto.';
    if (!is_array($rawItems) || !$rawItems) $errores[] = 'Tu carrito está vacío.';

    // Validar precios contra la BD (no confiar en el cliente)
    $items = [];
    $total = 0;
    if (is_array($rawItems)) {
        foreach ($rawItems as $ri) {
            $prod = get_producto((int)($ri['id'] ?? 0));
            if (!$prod) continue;
            $qty   = max(1, (int)($ri['qty'] ?? 1));
            $precio = precio_final($prod);
            $sub    = $precio * $qty;
            $total += $sub;
            $items[] = ['id'=>(int)$prod['id'], 'nombre'=>$prod['nombre'], 'precio'=>$precio, 'qty'=>$qty, 'subtotal'=>$sub];
        }
    }
    if (!$items && !$errores) $errores[] = 'No pudimos validar los productos del carrito.';

    if (!$errores) {
        $st = db()->prepare(
            'INSERT INTO pedidos (cliente_nombre, email, telefono, direccion, ciudad, items, total, estado, metodo_pago, notas)
             VALUES (?,?,?,?,?,?,?,?,?,?)'
        );
        $estado = ($metodo === 'pago' && PAYMENT_ENABLED) ? 'pagado' : 'pendiente';
        $st->execute([$nombre, $email, $telefono, $direccion, $ciudad,
            json_encode($items, JSON_UNESCAPED_UNICODE), $total, $estado, $metodo, $notas]);
        $pedido_ok = ['id' => (int)db()->lastInsertId(), 'total' => $total, 'items' => $items, 'nombre' => $nombre];
    }
}

include __DIR__ . '/includes/header.php';
?>

<?php if ($pedido_ok): ?>
<!-- ===== Confirmación ===== -->
<?php
$waMsg = "Hola Vanegas Royal, acabo de hacer el pedido #{$pedido_ok['id']}:%0A%0A";
foreach ($pedido_ok['items'] as $it) $waMsg .= "• {$it['nombre']} x{$it['qty']}%0A";
$waMsg .= "%0ATotal: " . precio_cop($pedido_ok['total']) . "%0A%0AMi nombre: {$pedido_ok['nombre']}";
$waUrl = 'https://wa.me/' . WHATSAPP_NUMBER . '?text=' . $waMsg;
?>
<section class="container-x py-24 text-center max-w-2xl">
  <div class="w-20 h-20 mx-auto rounded-full grid place-items-center border-2 border-gold text-gold mb-8">
    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
  </div>
  <p class="eyebrow">Pedido #<?= $pedido_ok['id'] ?> registrado</p>
  <h1 class="font-display text-5xl mt-3">Gracias, <span class="text-gold"><?= e($pedido_ok['nombre']) ?></span></h1>
  <p class="text-crema/70 mt-5">Recibimos tu pedido por <span class="text-gold tabular-nums"><?= precio_cop($pedido_ok['total']) ?></span>.
     Confírmalo por WhatsApp y coordinamos pago y envío asegurado.</p>
  <div class="flex flex-wrap gap-4 justify-center mt-9">
    <a href="<?= $waUrl ?>" target="_blank" rel="noopener" class="btn btn-gold">Confirmar por WhatsApp</a>
    <a href="<?= base_url('tienda.php') ?>" class="btn btn-ghost">Seguir comprando</a>
  </div>
  <!-- vaciar carrito tras compra -->
  <script>try{localStorage.removeItem('vr_cart');}catch(e){}</script>
</section>

<?php else: ?>
<!-- ===== Formulario ===== -->
<section class="container-x py-14"
         x-data="{ items: [], total: 0,
                   load(){ try{ this.items = JSON.parse(localStorage.getItem('vr_cart'))||[] }catch(e){ this.items=[] };
                           this.total = this.items.reduce((s,i)=>s+i.precio*i.qty,0); },
                   money(n){ return '$'+(n|0).toLocaleString('es-CO'); } }"
         x-init="load()">
  <p class="eyebrow reveal">Último paso</p>
  <h1 class="font-display text-[clamp(2.4rem,5vw,4rem)] leading-none mt-2 mb-10 reveal">Finalizar <span class="text-gold">compra</span></h1>

  <?php if ($errores): ?>
    <div class="glass rounded p-4 mb-8 border-red-500/40" style="border-color:rgba(220,38,38,.4)">
      <ul class="text-sm text-red-300 space-y-1">
        <?php foreach ($errores as $err): ?><li>• <?= e($err) ?></li><?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <!-- carrito vacío en cliente -->
  <template x-if="items.length === 0">
    <div class="glass rounded p-16 text-center">
      <p class="font-display text-3xl text-gold">Tu carrito está vacío</p>
      <a href="<?= base_url('tienda.php') ?>" class="btn btn-gold mt-6">Explorar colección</a>
    </div>
  </template>

  <form method="post" x-show="items.length > 0" @submit="$refs.itemsField.value = JSON.stringify(items)"
        class="grid lg:grid-cols-[1fr_360px] gap-10">
    <input type="hidden" name="items" x-ref="itemsField">

    <!-- Datos -->
    <div class="space-y-6">
      <div class="panel rounded-lg p-6">
        <h2 class="font-display text-2xl mb-5">Datos de contacto</h2>
        <div class="grid sm:grid-cols-2 gap-4">
          <div class="sm:col-span-2"><label class="lbl">Nombre completo *</label><input name="nombre" required class="field" value="<?= e($_POST['nombre'] ?? '') ?>"></div>
          <div><label class="lbl">Teléfono *</label><input name="telefono" type="tel" required class="field" value="<?= e($_POST['telefono'] ?? '') ?>"></div>
          <div><label class="lbl">Correo</label><input name="email" type="email" class="field" value="<?= e($_POST['email'] ?? '') ?>"></div>
        </div>
      </div>
      <div class="panel rounded-lg p-6">
        <h2 class="font-display text-2xl mb-5">Envío</h2>
        <div class="grid sm:grid-cols-2 gap-4">
          <div class="sm:col-span-2"><label class="lbl">Dirección</label><input name="direccion" class="field" value="<?= e($_POST['direccion'] ?? '') ?>"></div>
          <div><label class="lbl">Ciudad</label><input name="ciudad" class="field" value="<?= e($_POST['ciudad'] ?? '') ?>"></div>
          <div class="sm:col-span-2"><label class="lbl">Notas (opcional)</label><textarea name="notas" rows="2" class="field" placeholder="Talla del anillo, grabado, indicaciones..."></textarea></div>
        </div>
      </div>

      <!-- Pago -->
      <div class="panel rounded-lg p-6">
        <h2 class="font-display text-2xl mb-2">Método de pago</h2>
        <p class="text-mute text-sm mb-5">Confirma tu pedido y coordinamos el pago por WhatsApp. Pronto: pago en línea con tarjeta y PSE.</p>
        <div class="grid sm:grid-cols-2 gap-4">
          <label class="glass rounded p-4 flex items-center gap-3 cursor-pointer has-[:checked]:border-gold">
            <input type="radio" name="metodo" value="whatsapp" checked class="accent-gold">
            <span><span class="block text-crema font-medium">Coordinar por WhatsApp</span><span class="block text-xs text-mute">Pago contra entrega / transferencia</span></span>
          </label>
          <?php /* TODO: integrar Wompi / Mercado Pago con llaves reales del cliente */ ?>
          <label class="glass rounded p-4 flex items-center gap-3 <?= PAYMENT_ENABLED ? 'cursor-pointer has-[:checked]:border-gold' : 'opacity-50 cursor-not-allowed' ?>">
            <input type="radio" name="metodo" value="pago" class="accent-gold" <?= PAYMENT_ENABLED ? '' : 'disabled' ?>>
            <span><span class="block text-crema font-medium">Pago en línea</span><span class="block text-xs text-mute"><?= PAYMENT_ENABLED ? 'Tarjeta / PSE' : 'Disponible próximamente' ?></span></span>
          </label>
        </div>
      </div>
    </div>

    <!-- Resumen -->
    <aside class="panel rounded-lg p-7 h-fit lg:sticky lg:top-28">
      <h2 class="font-display text-2xl mb-5">Tu pedido</h2>
      <div class="space-y-3 max-h-72 overflow-auto pr-1">
        <template x-for="item in items" :key="item.id">
          <div class="flex gap-3 items-center text-sm">
            <img :src="item.imagen" class="w-12 h-12 object-cover rounded shrink-0">
            <div class="flex-1 min-w-0"><p class="truncate" x-text="item.nombre"></p><p class="text-mute" x-text="'x'+item.qty"></p></div>
            <span class="tabular-nums text-gold" x-text="money(item.precio*item.qty)"></span>
          </div>
        </template>
      </div>
      <div class="flex justify-between items-end border-t border-gold/10 mt-5 pt-5">
        <span class="text-mute uppercase text-xs tracking-widest">Total</span>
        <span class="font-display text-3xl text-gold tabular-nums" x-text="money(total)"></span>
      </div>
      <button type="submit" class="btn btn-gold w-full justify-center mt-6">Confirmar pedido</button>
      <a href="<?= base_url('carrito.php') ?>" class="block text-center text-sm text-mute hover:text-gold mt-4">← Volver al carrito</a>
    </aside>
  </form>
</section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
