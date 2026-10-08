<?php
require_once __DIR__ . '/auth.php';
if (is_admin()) { header('Location: ' . base_url('admin/index.php')); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['usuario'] ?? '');
    $p = $_POST['password'] ?? '';
    if (check_admin_login($u, $p)) {
        session_regenerate_id(true);
        $_SESSION['admin'] = $u;
        header('Location: ' . base_url('admin/index.php'));
        exit;
    }
    $error = 'Usuario o contraseña incorrectos.';
}
?>
<!DOCTYPE html>
<html lang="es" class="no-js">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Acceso · Admin Vanegas Royal</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant:wght@500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={theme:{extend:{colors:{bg:'#060503',gold:{DEFAULT:'#d4af37',light:'#f7e08a',dark:'#8a6a14'},crema:'#f5efe0',mute:'#9c9486'},fontFamily:{display:['Cormorant','serif'],sans:['Montserrat','sans-serif']}}}}</script>
<link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
<script>document.documentElement.classList.remove('no-js');</script>
</head>
<body class="font-sans bg-bg text-crema min-h-screen grid place-items-center p-5">
<div class="aura" style="background:#d4af37;width:420px;height:420px;top:-120px;left:50%;transform:translateX(-50%)"></div>
<div class="relative w-full max-w-sm">
  <div class="text-center mb-8">
    <div class="font-display text-3xl">VANEGAS <span class="text-gold-shine">ROYAL</span></div>
    <p class="text-[.6rem] tracking-[.35em] text-mute uppercase mt-1">Panel de administración</p>
  </div>
  <form method="post" class="panel rounded-lg p-8 space-y-5 border border-gold/15">
    <?php if ($error): ?>
      <p class="text-sm text-red-300 bg-red-500/10 border border-red-500/30 rounded p-3"><?= e($error) ?></p>
    <?php endif; ?>
    <div><label class="lbl">Usuario</label><input name="usuario" autofocus required class="field" placeholder="admin"></div>
    <div><label class="lbl">Contraseña</label><input name="password" type="password" required class="field" placeholder="••••••••"></div>
    <button class="btn btn-gold w-full justify-center">Ingresar</button>
  </form>
  <a href="<?= base_url('') ?>" class="block text-center text-sm text-mute hover:text-gold mt-6">← Volver a la tienda</a>
</div>
</body>
</html>
