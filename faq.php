<?php
require_once __DIR__ . '/includes/functions.php';
$activo = 'faq';
$titulo = 'Preguntas frecuentes · ' . SITE_NAME;
$faqs = [
    ['¿El oro es auténtico y certificado?', 'Sí. Todas nuestras piezas son en oro 18k y se entregan con certificado de autenticidad. La pureza está garantizada por escrito en cada compra.'],
    ['¿Hacen envíos a toda Colombia?', 'Sí, enviamos a todo el país con transportadora asegurada y número de seguimiento. El costo y tiempo de envío se coordinan según la ciudad.'],
    ['¿Cómo puedo pagar?', 'Por ahora coordinamos el pago por WhatsApp: transferencia, consignación o pago contra entrega según la ciudad. Muy pronto habilitaremos pago en línea con tarjeta y PSE.'],
    ['¿Puedo personalizar una joya o pedir un grabado?', 'Claro. Trabajamos piezas personalizadas y grabados. Escríbenos por WhatsApp con tu idea y te asesoramos sin compromiso.'],
    ['¿Tienen garantía?', 'Sí. Toda pieza Vanegas Royal cuenta con garantía sobre el material y la fabricación. Si presenta algún defecto de origen, lo resolvemos.'],
    ['¿Puedo ver la joya antes de comprar?', 'Podemos enviarte fotos y videos reales por WhatsApp. Si estás en Medellín, agendamos una cita en nuestro punto del Poblado.'],
    ['¿Cómo cuido mi joya de oro?', 'Evita el contacto con perfumes y cloro, guárdala en su estuche y límpiala con un paño suave. Así conserva su brillo por generaciones.'],
];
include __DIR__ . '/includes/header.php';
?>

<section class="relative py-20 overflow-hidden">
  <div class="absolute inset-0" style="background:radial-gradient(circle at 50% -10%, rgba(212,175,55,.15), transparent 55%)"></div>
  <div class="container-x relative text-center max-w-3xl">
    <p class="eyebrow reveal">Soporte</p>
    <h1 class="font-display text-[clamp(2.6rem,6vw,4.5rem)] leading-none mt-3 reveal">Preguntas <span class="text-gold-shine">frecuentes</span></h1>
    <p class="text-crema/70 mt-5 reveal">Todo lo que necesitas saber antes de elegir tu corona.</p>
  </div>
</section>

<section class="container-x pb-16 max-w-3xl" data-reveal-group>
  <div class="panel rounded-lg px-6 sm:px-9" x-data="{ open: 0 }">
    <?php foreach ($faqs as $i => $f): ?>
      <div class="faq-item reveal">
        <button type="button" class="faq-q flex items-center justify-between gap-4 py-6"
                @click="open === <?= $i ?> ? open = null : open = <?= $i ?>" :aria-expanded="open === <?= $i ?>">
          <span class="font-display text-xl sm:text-2xl text-crema text-left"><?= e($f[0]) ?></span>
          <svg class="w-5 h-5 text-gold shrink-0 transition-transform duration-300" :class="open === <?= $i ?> && 'rotate-45'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        </button>
        <div class="faq-a" :style="open === <?= $i ?> ? 'max-height:300px' : 'max-height:0'">
          <p class="text-crema/70 leading-relaxed pb-6 pr-8"><?= e($f[1]) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="text-center mt-12 reveal">
    <p class="text-mute">¿No encuentras tu respuesta?</p>
    <a href="<?= whatsapp_link('Hola Vanegas Royal, tengo una pregunta.') ?>" target="_blank" rel="noopener" class="btn btn-gold mt-4">Escríbenos por WhatsApp</a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
