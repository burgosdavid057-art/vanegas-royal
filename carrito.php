<?php
require_once __DIR__ . '/includes/functions.php';
$activo = 'tienda';
$titulo = 'Carrito · ' . SITE_NAME;
include __DIR__ . '/includes/header.php';
?>

<section class="container-x py-16" x-data>
  <p class="eyebrow reveal">Tu selección</p>
  <h1 class="font-display text-[clamp(2.4rem,5vw,4rem)] leading-none mt-2 mb-10 reveal">El <span class="text-gold">carrito</span></h1>

  <!-- Vacío -->
  <template x-if="$store.cart.items.length === 0">
    <div class="glass rounded p-16 text-center">
      <p class="font-display text-3xl text-gold">Tu carrito está vacío</p>
      <p class="text-mute mt-2">Aún no has elegido tu corona.</p>
      <a href="<?= base_url('tienda.php') ?>" class="btn btn-gold mt-6">Explorar colección</a>
    </div>
  </template>

  <!-- Con items -->
  <div x-show="$store.cart.items.length > 0" class="grid lg:grid-cols-[1fr_360px] gap-10">
    <!-- Lista -->
    <div class="space-y-4">
      <template x-for="item in $store.cart.items" :key="item.id">
        <div class="glass rounded p-4 flex gap-4 items-center">
          <img :src="item.imagen" :alt="item.nombre" class="w-24 h-24 object-cover rounded shrink-0">
          <div class="flex-1 min-w-0">
            <h3 class="font-display text-xl truncate" x-text="item.nombre"></h3>
            <p class="text-gold tabular-nums mt-1" x-text="$store.cart.money(item.precio)"></p>
          </div>
          <div class="flex items-center glass rounded">
            <button @click="$store.cart.dec(item.id)" class="w-9 h-9 grid place-items-center text-gold" aria-label="Menos">−</button>
            <span class="w-8 text-center tabular-nums" x-text="item.qty"></span>
            <button @click="$store.cart.inc(item.id)" class="w-9 h-9 grid place-items-center text-gold" aria-label="Más">+</button>
          </div>
          <div class="text-right shrink-0 w-28">
            <p class="font-semibold tabular-nums" x-text="$store.cart.money(item.precio * item.qty)"></p>
            <button @click="$store.cart.remove(item.id)" class="text-xs text-mute hover:text-gold mt-1">Quitar</button>
          </div>
        </div>
      </template>
      <div class="flex justify-between pt-2">
        <a href="<?= base_url('tienda.php') ?>" class="link-under text-mute hover:text-gold text-sm">← Seguir comprando</a>
        <button @click="$store.cart.clear()" class="text-sm text-mute hover:text-gold">Vaciar carrito</button>
      </div>
    </div>

    <!-- Resumen -->
    <aside class="panel rounded-lg p-7 h-fit lg:sticky lg:top-28">
      <h2 class="font-display text-2xl mb-5">Resumen</h2>
      <div class="space-y-3 text-sm border-b border-gold/10 pb-5">
        <div class="flex justify-between"><span class="text-mute">Subtotal</span><span class="tabular-nums" x-text="$store.cart.money($store.cart.total)"></span></div>
        <div class="flex justify-between"><span class="text-mute">Envío</span><span class="text-gold">A coordinar</span></div>
      </div>
      <div class="flex justify-between items-end py-5">
        <span class="text-mute uppercase text-xs tracking-widest">Total</span>
        <span class="font-display text-3xl text-gold tabular-nums" x-text="$store.cart.money($store.cart.total)"></span>
      </div>
      <a href="<?= base_url('checkout.php') ?>" class="btn btn-gold w-full justify-center">Finalizar compra</a>
      <a :href="$store.cart.waMessage('<?= WHATSAPP_NUMBER ?>')" target="_blank" rel="noopener"
         class="btn btn-ghost w-full justify-center mt-3" style="border-color:#1faa53;color:#5fd58a">
        Pedir por WhatsApp
      </a>
      <p class="text-xs text-mute text-center mt-4">Pago seguro · Garantía de autenticidad</p>
    </aside>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
