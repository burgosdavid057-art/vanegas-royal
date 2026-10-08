<?php
require_once __DIR__ . '/includes/functions.php';
$activo = 'contacto';
$titulo = 'Contacto y soporte · ' . SITE_NAME;
include __DIR__ . '/includes/header.php';
?>

<section class="relative py-20 overflow-hidden">
  <div class="absolute inset-0" style="background:radial-gradient(circle at 50% -10%, rgba(212,175,55,.15), transparent 55%)"></div>
  <div class="container-x relative text-center max-w-3xl">
    <p class="eyebrow reveal">Estamos para ti</p>
    <h1 class="font-display text-[clamp(2.6rem,6vw,4.5rem)] leading-none mt-3 reveal">Contacto y <span class="text-gold-shine">soporte</span></h1>
    <p class="text-crema/70 mt-5 reveal">Atención personalizada para ayudarte a elegir la pieza perfecta.</p>
  </div>
</section>

<section class="container-x pb-24" data-reveal-group>
  <div class="grid lg:grid-cols-[1fr_380px] gap-10">
    <!-- Formulario (compone mensaje de WhatsApp) -->
    <div class="panel rounded-lg p-8 reveal"
         x-data="{ nombre:'', tema:'Consulta general', mensaje:'',
                   wa(){ let m = `Hola Vanegas Royal, soy ${this.nombre||'(sin nombre)'}.\nTema: ${this.tema}\n\n${this.mensaje}`;
                         return 'https://wa.me/<?= WHATSAPP_NUMBER ?>?text='+encodeURIComponent(m); } }">
      <h2 class="font-display text-3xl mb-6">Envíanos un mensaje</h2>
      <div class="grid sm:grid-cols-2 gap-4">
        <div><label class="lbl">Tu nombre</label><input x-model="nombre" class="field" placeholder="Nombre y apellido"></div>
        <div>
          <label class="lbl">Tema</label>
          <select x-model="tema" class="field">
            <option>Consulta general</option>
            <option>Disponibilidad de una pieza</option>
            <option>Joya personalizada / grabado</option>
            <option>Estado de mi pedido</option>
            <option>Garantía y posventa</option>
          </select>
        </div>
        <div class="sm:col-span-2"><label class="lbl">Mensaje</label><textarea x-model="mensaje" rows="5" class="field" placeholder="Cuéntanos qué buscas..."></textarea></div>
      </div>
      <a :href="wa()" target="_blank" rel="noopener" class="btn btn-gold mt-6">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163a11.867 11.867 0 0 1-1.587-5.946C.16 5.335 5.495 0 12.05 0a11.817 11.817 0 0 1 8.413 3.488 11.824 11.824 0 0 1 3.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 0 1-5.688-1.448L.057 24z"/></svg>
        Enviar por WhatsApp
      </a>
      <p class="text-xs text-mute mt-3">Te responderemos lo antes posible en horario de atención.</p>
    </div>

    <!-- Datos -->
    <aside class="space-y-4">
      <?php
      $info = [
        ['WhatsApp', WHATSAPP_LABEL, whatsapp_link(), 'M.057 24l1.687-6.163a11.867 11.867 0 0 1-1.587-5.946C.16 5.335 5.495 0 12.05 0a11.817 11.817 0 0 1 8.413 3.488 11.824 11.824 0 0 1 3.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 0 1-5.688-1.448L.057 24z'],
        ['Teléfono', CONTACT_PHONE, 'tel:' . CONTACT_PHONE, ''],
        ['Correo', CONTACT_EMAIL, 'mailto:' . CONTACT_EMAIL, ''],
      ];
      foreach ($info as $it): ?>
        <a href="<?= e($it[2]) ?>" <?= str_starts_with($it[2],'http')?'target="_blank" rel="noopener"':'' ?> class="glass rounded-lg p-5 flex items-center gap-4 hover:border-gold transition-colors reveal block">
          <span class="w-11 h-11 grid place-items-center rounded-full border border-gold/40 text-gold shrink-0">
            <?php if ($it[3]): ?><svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="<?= $it[3] ?>"/></svg>
            <?php else: ?><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg><?php endif; ?>
          </span>
          <div><p class="text-xs text-mute uppercase tracking-wider"><?= e($it[0]) ?></p><p class="text-crema font-medium"><?= e($it[1]) ?></p></div>
        </a>
      <?php endforeach; ?>

      <div class="glass rounded-lg p-5 reveal">
        <p class="text-xs text-mute uppercase tracking-wider mb-1">Showroom</p>
        <p class="text-crema font-medium"><?= e(CONTACT_ADDRESS) ?></p>
        <p class="text-mute text-sm mt-2"><?= e(CONTACT_HOURS) ?></p>
      </div>
    </aside>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
