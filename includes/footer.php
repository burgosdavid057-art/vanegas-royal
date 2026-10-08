</main>

<footer class="relative z-10 mt-24 border-t border-gold/15 bg-gradient-to-b from-transparent to-black/40">
  <!-- Marquee de valores -->
  <div class="marquee py-5 border-b border-gold/10 text-gold/70 font-display text-2xl italic">
    <div class="marquee__track" aria-hidden="true">
      <span>Oro 18k certificado</span><span>·</span><span>Hecho para dominar</span><span>·</span>
      <span>Diseño exclusivo</span><span>·</span><span>Garantía de autenticidad</span><span>·</span>
      <span>Envío asegurado</span><span>·</span>
    </div>
    <div class="marquee__track" aria-hidden="true">
      <span>Oro 18k certificado</span><span>·</span><span>Hecho para dominar</span><span>·</span>
      <span>Diseño exclusivo</span><span>·</span><span>Garantía de autenticidad</span><span>·</span>
      <span>Envío asegurado</span><span>·</span>
    </div>
  </div>

  <div class="container-x py-16 grid gap-12 md:grid-cols-4">
    <div class="md:col-span-1">
      <div class="font-display text-2xl">VANEGAS <span class="text-gold">ROYAL</span></div>
      <p class="text-[.6rem] tracking-[.3em] text-mute uppercase mt-1">Hecho para dominar</p>
      <p class="text-mute text-sm mt-5 leading-relaxed max-w-xs"><?= e(SITE_DESC) ?></p>
    </div>

    <div>
      <h4 class="eyebrow mb-4">Tienda</h4>
      <ul class="space-y-2.5 text-sm text-crema/75">
        <li><a href="<?= base_url('tienda.php') ?>" class="link-under hover:text-gold">Colección completa</a></li>
        <li><a href="<?= base_url('ofertas.php') ?>" class="link-under hover:text-gold">Ofertas exclusivas</a></li>
        <li><a href="<?= base_url('tienda.php?cat=Anillos') ?>" class="link-under hover:text-gold">Anillos</a></li>
        <li><a href="<?= base_url('tienda.php?cat=Cadenas') ?>" class="link-under hover:text-gold">Cadenas</a></li>
        <li><a href="<?= base_url('tienda.php?cat=Relojes') ?>" class="link-under hover:text-gold">Relojes</a></li>
      </ul>
    </div>

    <div>
      <h4 class="eyebrow mb-4">Soporte</h4>
      <ul class="space-y-2.5 text-sm text-crema/75">
        <li><a href="<?= base_url('faq.php') ?>" class="link-under hover:text-gold">Preguntas frecuentes</a></li>
        <li><a href="<?= base_url('contacto.php') ?>" class="link-under hover:text-gold">Contacto</a></li>
        <li><a href="<?= whatsapp_link() ?>" target="_blank" rel="noopener" class="link-under hover:text-gold">WhatsApp</a></li>
        <li><a href="<?= base_url('contacto.php') ?>" class="link-under hover:text-gold">Envíos y garantía</a></li>
      </ul>
    </div>

    <div>
      <h4 class="eyebrow mb-4">Contacto</h4>
      <ul class="space-y-2.5 text-sm text-crema/75">
        <li><?= e(CONTACT_ADDRESS) ?></li>
        <li><a href="tel:<?= e(CONTACT_PHONE) ?>" class="hover:text-gold"><?= e(CONTACT_PHONE) ?></a></li>
        <li><a href="mailto:<?= e(CONTACT_EMAIL) ?>" class="hover:text-gold"><?= e(CONTACT_EMAIL) ?></a></li>
        <li class="text-mute"><?= e(CONTACT_HOURS) ?></li>
      </ul>
      <div class="flex gap-3 mt-5">
        <a href="<?= e(SOCIAL_INSTAGRAM) ?>" target="_blank" rel="noopener" aria-label="Instagram" class="w-10 h-10 grid place-items-center glass rounded hover:border-gold transition-colors">
          <svg class="w-5 h-5 text-gold" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.2c3.2 0 3.6 0 4.9.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s0 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.9.07s-3.63 0-4.9-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 0 1-1.38-.9 3.7 3.7 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.21 15.58 2.2 15.2 2.2 12s0-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.46 2.21 8.84 2.2 12 2.2Zm0 1.8c-3.14 0-3.5 0-4.74.07-.9.04-1.38.19-1.7.32-.43.16-.74.36-1.06.68-.32.32-.52.63-.68 1.06-.13.32-.28.8-.32 1.7C3.23 8.95 3.22 9.3 3.22 12s0 3.05.07 4.29c.04.9.19 1.38.32 1.7.16.43.36.74.68 1.06.32.32.63.52 1.06.68.32.13.8.28 1.7.32 1.24.07 1.6.07 4.74.07s3.5 0 4.74-.07c.9-.04 1.38-.19 1.7-.32.43-.16.74-.36 1.06-.68.32-.32.52-.63.68-1.06.13-.32.28-.8.32-1.7.07-1.24.07-1.6.07-4.29s0-3.05-.07-4.29c-.04-.9-.19-1.38-.32-1.7a2.85 2.85 0 0 0-.68-1.06 2.85 2.85 0 0 0-1.06-.68c-.32-.13-.8-.28-1.7-.32C15.5 4 15.14 4 12 4Zm0 3.05A4.95 4.95 0 1 1 12 17a4.95 4.95 0 0 1 0-9.9Zm0 1.8a3.15 3.15 0 1 0 0 6.3 3.15 3.15 0 0 0 0-6.3Zm5.15-.9a1.15 1.15 0 1 1-2.3 0 1.15 1.15 0 0 1 2.3 0Z"/></svg>
        </a>
        <a href="<?= e(SOCIAL_FACEBOOK) ?>" target="_blank" rel="noopener" aria-label="Facebook" class="w-10 h-10 grid place-items-center glass rounded hover:border-gold transition-colors">
          <svg class="w-5 h-5 text-gold" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.78-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12Z"/></svg>
        </a>
        <a href="<?= e(SOCIAL_TIKTOK) ?>" target="_blank" rel="noopener" aria-label="TikTok" class="w-10 h-10 grid place-items-center glass rounded hover:border-gold transition-colors">
          <svg class="w-5 h-5 text-gold" fill="currentColor" viewBox="0 0 24 24"><path d="M16.6 5.82a4.28 4.28 0 0 1-1.06-2.82h-3.2v12.97a2.43 2.43 0 0 1-2.43 2.36 2.43 2.43 0 1 1 .7-4.76V10.3a5.6 5.6 0 1 0 4.93 5.56V9.01a7.42 7.42 0 0 0 4.32 1.38V7.2a4.28 4.28 0 0 1-2.96-1.38Z"/></svg>
        </a>
      </div>
    </div>
  </div>

  <div class="border-t border-gold/10">
    <div class="container-x py-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-mute">
      <p>© <?= date('Y') ?> Vanegas Royal. Todos los derechos reservados.</p>
      <p>Diseño exclusivo · <a href="<?= base_url('admin/login.php') ?>" class="hover:text-gold">Admin</a></p>
    </div>
  </div>
</footer>

</body>
</html>
