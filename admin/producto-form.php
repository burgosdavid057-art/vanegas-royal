<?php
require_once __DIR__ . '/auth.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
$editar = $id > 0;
$p = $editar ? (db()->query("SELECT * FROM productos WHERE id=$id")->fetch() ?: null) : null;
if ($editar && !$p) { header('Location: ' . base_url('admin/productos.php')); exit; }

$cats = ['Anillos','Cadenas','Dijes','Relojes','Aretes','Esclavas'];
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d = [
        'nombre'      => trim($_POST['nombre'] ?? ''),
        'categoria'   => trim($_POST['categoria'] ?? ''),
        'descripcion' => trim($_POST['descripcion'] ?? ''),
        'precio'      => (int)preg_replace('/\D/', '', $_POST['precio'] ?? '0'),
        'precio_oferta' => $_POST['precio_oferta'] !== '' ? (int)preg_replace('/\D/', '', $_POST['precio_oferta']) : null,
        'material'    => trim($_POST['material'] ?? 'Oro 18k'),
        'quilataje'   => trim($_POST['quilataje'] ?? '18k'),
        'peso_g'      => $_POST['peso_g'] !== '' ? (float)$_POST['peso_g'] : null,
        'stock'       => (int)($_POST['stock'] ?? 0),
        'destacado'   => isset($_POST['destacado']) ? 1 : 0,
        'activo'      => isset($_POST['activo']) ? 1 : 0,
    ];

    if ($d['nombre'] === '')    $errores[] = 'El nombre es obligatorio.';
    if ($d['categoria'] === '') $errores[] = 'Selecciona una categoría.';
    if ($d['precio'] <= 0)      $errores[] = 'El precio debe ser mayor a 0.';
    if ($d['precio_oferta'] !== null && $d['precio_oferta'] >= $d['precio']) $errores[] = 'El precio de oferta debe ser menor al precio normal.';

    // Imagen
    $imagen = $p['imagen'] ?? null;
    if (!empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg','jpeg','png','webp','avif','gif'];
        if (!in_array($ext, $permitidas, true)) {
            $errores[] = 'Formato de imagen no permitido (usa JPG, PNG, WEBP).';
        } elseif ($_FILES['imagen']['size'] > 6 * 1024 * 1024) {
            $errores[] = 'La imagen supera 6 MB.';
        } else {
            if (!is_dir(UPLOAD_DIR)) @mkdir(UPLOAD_DIR, 0775, true);
            $nuevo = slugify($d['nombre']) . '-' . substr(md5(uniqid('', true)), 0, 6) . '.' . $ext;
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], UPLOAD_DIR . '/' . $nuevo)) {
                if (!empty($p['imagen']) && is_file(UPLOAD_DIR . '/' . $p['imagen'])) @unlink(UPLOAD_DIR . '/' . $p['imagen']);
                $imagen = $nuevo;
            } else {
                $errores[] = 'No se pudo guardar la imagen.';
            }
        }
    }

    if (!$errores) {
        $slug = slugify($d['nombre']);
        // slug único
        $chk = db()->prepare('SELECT id FROM productos WHERE slug=? AND id<>?');
        $chk->execute([$slug, $id]);
        if ($chk->fetch()) $slug .= '-' . substr(md5(uniqid()), 0, 4);

        if ($editar) {
            db()->prepare('UPDATE productos SET nombre=?,slug=?,descripcion=?,categoria=?,precio=?,precio_oferta=?,material=?,quilataje=?,peso_g=?,stock=?,imagen=?,destacado=?,activo=? WHERE id=?')
                ->execute([$d['nombre'],$slug,$d['descripcion'],$d['categoria'],$d['precio'],$d['precio_oferta'],$d['material'],$d['quilataje'],$d['peso_g'],$d['stock'],$imagen,$d['destacado'],$d['activo'],$id]);
        } else {
            db()->prepare('INSERT INTO productos (nombre,slug,descripcion,categoria,precio,precio_oferta,material,quilataje,peso_g,stock,imagen,destacado,activo) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)')
                ->execute([$d['nombre'],$slug,$d['descripcion'],$d['categoria'],$d['precio'],$d['precio_oferta'],$d['material'],$d['quilataje'],$d['peso_g'],$d['stock'],$imagen,$d['destacado'],$d['activo']]);
        }
        header('Location: ' . base_url('admin/productos.php'));
        exit;
    }
    // conservar valores ingresados
    $p = array_merge($p ?? [], $d, ['imagen' => $imagen]);
}

// valores por defecto
$v = fn($k, $def = '') => e((string)($p[$k] ?? $def));
admin_header($editar ? 'Editar producto' : 'Nuevo producto', 'productos.php');
?>
<a href="<?= base_url('admin/productos.php') ?>" class="text-sm text-mute hover:text-gold">← Volver a productos</a>
<h1 class="font-display text-4xl mt-3 mb-7"><?= $editar ? 'Editar' : 'Nuevo' ?> <span class="text-gold">producto</span></h1>

<?php if ($errores): ?>
  <div class="rounded p-4 mb-6 border" style="border-color:rgba(220,38,38,.4);background:rgba(220,38,38,.06)">
    <ul class="text-sm text-red-300 space-y-1"><?php foreach ($errores as $e): ?><li>• <?= e($e) ?></li><?php endforeach; ?></ul>
  </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="grid lg:grid-cols-[1fr_320px] gap-6">
  <div class="space-y-6">
    <div class="panel rounded-lg p-6 border border-gold/15 space-y-4">
      <div><label class="lbl">Nombre *</label><input name="nombre" required class="field" value="<?= $v('nombre') ?>"></div>
      <div><label class="lbl">Descripción</label><textarea name="descripcion" rows="4" class="field"><?= $v('descripcion') ?></textarea></div>
      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label class="lbl">Categoría *</label>
          <select name="categoria" required class="field">
            <option value="">Selecciona...</option>
            <?php foreach ($cats as $c): ?><option <?= ($p['categoria'] ?? '')===$c?'selected':'' ?>><?= $c ?></option><?php endforeach; ?>
          </select>
        </div>
        <div><label class="lbl">Stock</label><input name="stock" type="number" min="0" class="field" value="<?= $v('stock','1') ?>"></div>
      </div>
    </div>

    <div class="panel rounded-lg p-6 border border-gold/15 space-y-4">
      <h3 class="font-display text-xl">Precio</h3>
      <div class="grid sm:grid-cols-2 gap-4">
        <div><label class="lbl">Precio normal (COP) *</label><input name="precio" inputmode="numeric" required class="field" value="<?= $v('precio') ?>" placeholder="2890000"></div>
        <div><label class="lbl">Precio oferta (opcional)</label><input name="precio_oferta" inputmode="numeric" class="field" value="<?= e((string)($p['precio_oferta'] ?? '')) ?>" placeholder="2390000"></div>
      </div>
    </div>

    <div class="panel rounded-lg p-6 border border-gold/15 grid sm:grid-cols-3 gap-4">
      <div><label class="lbl">Material</label><input name="material" class="field" value="<?= $v('material','Oro 18k') ?>"></div>
      <div><label class="lbl">Quilataje</label><input name="quilataje" class="field" value="<?= $v('quilataje','18k') ?>"></div>
      <div><label class="lbl">Peso (g)</label><input name="peso_g" inputmode="decimal" class="field" value="<?= e((string)($p['peso_g'] ?? '')) ?>"></div>
    </div>
  </div>

  <!-- Lateral -->
  <aside class="space-y-6">
    <div class="panel rounded-lg p-6 border border-gold/15">
      <h3 class="font-display text-xl mb-4">Imagen</h3>
      <div class="aspect-square rounded overflow-hidden mb-4 bg-bg border border-gold/10">
        <img src="<?= product_image($p ?: ['nombre'=>'Nuevo','categoria'=>'']) ?>" id="preview" class="w-full h-full object-cover">
      </div>
      <input type="file" name="imagen" accept="image/*" class="field text-sm"
             onchange="if(this.files[0]){document.getElementById('preview').src=URL.createObjectURL(this.files[0])}">
      <p class="text-xs text-mute mt-2">JPG, PNG o WEBP · máx 6 MB. Si no subes imagen, se usa un placeholder de marca.</p>
    </div>

    <div class="panel rounded-lg p-6 border border-gold/15 space-y-3">
      <label class="flex items-center gap-3 cursor-pointer">
        <input type="checkbox" name="destacado" class="accent-gold w-4 h-4" <?= ($p['destacado'] ?? 0)?'checked':'' ?>>
        <span>Producto destacado (portada)</span>
      </label>
      <label class="flex items-center gap-3 cursor-pointer">
        <input type="checkbox" name="activo" class="accent-gold w-4 h-4" <?= ($p['activo'] ?? 1)?'checked':'' ?>>
        <span>Visible en la tienda</span>
      </label>
    </div>

    <button class="btn btn-gold w-full justify-center"><?= $editar ? 'Guardar cambios' : 'Crear producto' ?></button>
  </aside>
</form>

<?php admin_footer(); ?>
