<?php
require_once __DIR__ . '/../includes/functions.php';

function is_admin(): bool {
    return !empty($_SESSION['admin']);
}

function require_admin(): void {
    if (!is_admin()) {
        header('Location: ' . base_url('admin/login.php'));
        exit;
    }
}

function check_admin_login(string $user, string $pass): bool {
    if (!hash_equals(ADMIN_USER, $user)) return false;
    // acepta hash bcrypt o, como respaldo, contraseña en texto de config
    if (password_verify($pass, ADMIN_PASS_HASH)) return true;
    return hash_equals(ADMIN_PASS_PLAIN, $pass);
}

/** Layout del panel */
function admin_header(string $title, string $active = ''): void {
    $nav = [
        'index.php'     => ['Dashboard', 'M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6Z'],
        'productos.php' => ['Productos', 'M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z'],
        'ofertas.php'   => ['Ofertas', 'M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z'],
        'pedidos.php'   => ['Pedidos', 'M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272'],
    ];
    ?>
<!DOCTYPE html>
<html lang="es" class="no-js">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?> · Admin Vanegas Royal</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant:wght@500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config={theme:{extend:{colors:{bg:'#060503',panel:'#110d08',gold:{DEFAULT:'#d4af37',light:'#f7e08a',dark:'#8a6a14'},crema:'#f5efe0',mute:'#9c9486'},fontFamily:{display:['Cormorant','serif'],sans:['Montserrat','sans-serif']}}}}
</script>
<link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
<script>document.documentElement.classList.remove('no-js');</script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans bg-bg text-crema min-h-screen" x-data="{ menu:false }">
<div class="flex min-h-screen">
  <!-- Sidebar -->
  <aside class="hidden lg:flex flex-col w-64 shrink-0 border-r border-gold/15 bg-panel/40 p-6 sticky top-0 h-screen">
    <a href="<?= base_url('admin/index.php') ?>" class="font-display text-2xl mb-1">VANEGAS <span class="text-gold">ROYAL</span></a>
    <p class="text-[.6rem] tracking-[.3em] text-mute uppercase mb-8">Panel de control</p>
    <nav class="space-y-1 flex-1">
      <?php foreach ($nav as $file => $n): ?>
        <a href="<?= base_url('admin/' . $file) ?>" class="flex items-center gap-3 px-4 py-3 rounded text-sm <?= $active === $file ? 'bg-gold/10 text-gold border border-gold/30' : 'text-crema/70 hover:text-gold hover:bg-white/5' ?>">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="<?= $n[1] ?>"/></svg>
          <?= e($n[0]) ?>
        </a>
      <?php endforeach; ?>
    </nav>
    <div class="border-t border-gold/10 pt-4 space-y-1">
      <a href="<?= base_url('') ?>" target="_blank" class="block px-4 py-2 text-sm text-mute hover:text-gold">Ver tienda ↗</a>
      <a href="<?= base_url('admin/logout.php') ?>" class="block px-4 py-2 text-sm text-mute hover:text-gold">Cerrar sesión</a>
    </div>
  </aside>

  <div class="flex-1 min-w-0">
    <!-- Topbar móvil -->
    <header class="lg:hidden flex items-center justify-between border-b border-gold/15 p-4 bg-panel/40">
      <span class="font-display text-xl">VANEGAS <span class="text-gold">ROYAL</span></span>
      <a href="<?= base_url('admin/logout.php') ?>" class="text-sm text-mute">Salir</a>
    </header>
    <!-- nav móvil -->
    <nav class="lg:hidden flex gap-1 overflow-x-auto p-3 border-b border-gold/10 text-xs">
      <?php foreach ($nav as $file => $n): ?>
        <a href="<?= base_url('admin/' . $file) ?>" class="px-3 py-2 rounded whitespace-nowrap <?= $active === $file ? 'bg-gold/10 text-gold' : 'text-crema/70' ?>"><?= e($n[0]) ?></a>
      <?php endforeach; ?>
    </nav>

    <main class="p-5 sm:p-8 max-w-7xl">
    <?php
}

function admin_footer(): void {
    ?>
    </main>
  </div>
</div>
</body>
</html>
    <?php
}
