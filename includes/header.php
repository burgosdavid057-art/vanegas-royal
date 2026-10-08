<?php
require_once __DIR__ . '/functions.php';
$titulo   = $titulo   ?? SITE_NAME . ' · Joyería de lujo';
$activo   = $activo   ?? '';
$intro    = $intro    ?? false;
$nav = [
    ''         => 'Inicio',
    'tienda'   => 'Tienda',
    'ofertas'  => 'Ofertas',
    'faq'      => 'FAQ',
    'contacto' => 'Contacto',
];
?>
<!DOCTYPE html>
<html lang="es" class="no-js">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="<?= e(SITE_DESC) ?>">
<title><?= e($titulo) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700;800;900&family=Cinzel+Decorative:wght@400;700;900&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  theme: { extend: {
    colors: {
      bg: '#060503', panel: '#110d08',
      gold: { DEFAULT: '#d4af37', light: '#f7e08a', dark: '#8a6a14' },
      crema: '#f5efe0', mute: '#9c9486',
    },
    fontFamily: {
      display: ['Cinzel', 'Georgia', 'serif'],
      deco: ['"Cinzel Decorative"', 'serif'],
      sans: ['Jost', 'system-ui', 'sans-serif'],
    },
    maxWidth: { '8xl': '1280px' },
  }}
}
</script>
<link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
<script>document.documentElement.classList.remove('no-js');</script>
<!-- GSAP -->
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<!-- App -->
<script defer src="<?= base_url('assets/js/app.js') ?>"></script>
<!-- Alpine -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans bg-bg text-crema antialiased">

<?php if ($intro): ?>
<!-- Intro loader -->
<div id="vr-intro" aria-hidden="true">
  <div class="text-center">
    <div class="vr-intro__mark">VR</div>
    <div class="vr-intro__line"></div>
    <div class="vr-intro__name">VANEGAS ROYAL</div>
  </div>
</div>
<?php endif; ?>

<!-- Auras de ambiente -->
<div class="aura" style="background:#d4af37; width:520px; height:520px; top:-160px; right:-120px;"></div>
<div class="aura" style="background:#7a5c12; width:480px; height:480px; bottom:10%; left:-160px;"></div>

<!-- Barra superior anuncio -->
<div class="relative z-20 bg-gradient-to-r from-gold-dark/30 via-gold/20 to-gold-dark/30 text-center text-[.7rem] tracking-[.25em] uppercase py-2 text-gold">
  Envío asegurado a toda Colombia · Garantía de autenticidad en oro 18k
</div>

<!-- NAV -->
<header x-data="{ open:false, scrolled:false }"
        x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 30)"
        :class="scrolled ? 'bg-bg/90 backdrop-blur-md border-gold/20' : 'bg-transparent border-transparent'"
        class="sticky top-0 z-40 border-b transition-all duration-300">
  <nav class="container-x flex items-center justify-between h-20">
    <!-- Logo -->
    <a href="<?= base_url('') ?>" data-cursor class="flex items-center gap-3 shrink-0">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="<?= e(SITE_NAME) ?>" class="h-12 w-auto"
           onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
      <span style="display:none" class="font-display text-2xl text-gold-shine font-bold leading-none">VR</span>
      <span class="hidden sm:block leading-tight">
        <span class="block font-display text-xl text-crema tracking-wide">VANEGAS <span class="text-gold">ROYAL</span></span>
        <span class="block text-[.55rem] tracking-[.3em] text-mute uppercase">Hecho para dominar</span>
      </span>
    </a>

    <!-- Links desktop -->
    <ul class="hidden lg:flex items-center gap-9 text-[.78rem] tracking-[.18em] uppercase">
      <?php foreach ($nav as $slug => $label): ?>
        <li><a href="<?= base_url($slug ? $slug . '.php' : '') ?>"
               class="link-under transition-colors <?= $activo === $slug ? 'text-gold' : 'text-crema/80 hover:text-gold' ?>"><?= e($label) ?></a></li>
      <?php endforeach; ?>
    </ul>

    <!-- Acciones -->
    <div class="flex items-center gap-4">
      <a href="<?= base_url('carrito.php') ?>" class="relative p-2 text-crema hover:text-gold transition-colors" aria-label="Carrito">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
        <span x-data x-show="$store.cart.count > 0" x-text="$store.cart.count"
              class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-gradient-to-br from-gold-light to-gold-dark text-bg text-[.65rem] font-bold flex items-center justify-center"></span>
      </a>
      <button @click="open=!open" class="lg:hidden p-2 text-crema" aria-label="Menú">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/></svg>
      </button>
      <a href="<?= base_url('tienda.php') ?>" data-magnetic data-cursor class="btn btn-gold hidden xl:inline-flex">Ver colección</a>
    </div>
  </nav>

  <!-- Menú móvil -->
  <div x-show="open" x-cloak x-transition.opacity class="lg:hidden border-t border-gold/15 bg-bg/95 backdrop-blur-md">
    <ul class="container-x py-4 flex flex-col gap-1 text-sm tracking-[.15em] uppercase">
      <?php foreach ($nav as $slug => $label): ?>
        <li><a href="<?= base_url($slug ? $slug . '.php' : '') ?>" class="block py-3 border-b border-white/5 <?= $activo === $slug ? 'text-gold' : 'text-crema/80' ?>"><?= e($label) ?></a></li>
      <?php endforeach; ?>
      <li><a href="<?= base_url('carrito.php') ?>" class="block py-3 text-gold">Carrito (<span x-data x-text="$store.cart.count"></span>)</a></li>
    </ul>
  </div>
</header>

<!-- Toast carrito -->
<div x-data x-show="$store.cart.open" x-cloak x-transition
     class="fixed bottom-24 right-5 z-50 glass px-5 py-3 rounded text-sm flex items-center gap-3">
  <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
  <span>Añadido al carrito · <a href="<?= base_url('carrito.php') ?>" class="text-gold link-under">ver</a></span>
</div>

<!-- WhatsApp flotante -->
<a href="<?= whatsapp_link() ?>" target="_blank" rel="noopener" data-magnetic
   class="fixed bottom-5 right-5 z-50 w-14 h-14 rounded-full flex items-center justify-center shadow-lg shadow-black/50"
   style="background:#1faa53" aria-label="Escríbenos por WhatsApp">
  <svg class="w-7 h-7" viewBox="0 0 24 24" fill="#fff"><path d="M.057 24l1.687-6.163a11.867 11.867 0 0 1-1.587-5.946C.16 5.335 5.495 0 12.05 0a11.817 11.817 0 0 1 8.413 3.488 11.824 11.824 0 0 1 3.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 0 1-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884a9.86 9.86 0 0 0 1.51 5.26l-.999 3.648 3.978-1.043zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
</a>

<main class="relative z-10">
