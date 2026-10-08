<?php
require_once __DIR__ . '/../config/db.php';

/** Escape para HTML */
function e(?string $s): string {
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

/** Formato de precio en COP: 1250000 -> $1.250.000 */
function precio_cop($n): string {
    return '$' . number_format((int)$n, 0, ',', '.');
}

/** Slug (transliteración manual; iconv es poco fiable en Windows) */
function slugify(string $t): string {
    $map = ['á'=>'a','à'=>'a','ä'=>'a','â'=>'a','é'=>'e','è'=>'e','ë'=>'e','ê'=>'e',
            'í'=>'i','ì'=>'i','ï'=>'i','î'=>'i','ó'=>'o','ò'=>'o','ö'=>'o','ô'=>'o',
            'ú'=>'u','ù'=>'u','ü'=>'u','û'=>'u','ñ'=>'n','ç'=>'c',
            'Á'=>'a','É'=>'e','Í'=>'i','Ó'=>'o','Ú'=>'u','Ñ'=>'n'];
    $t = strtr($t, $map);
    $t = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $t));
    return trim($t, '-');
}

/**
 * Devuelve la URL de imagen del producto. Si no hay archivo subido,
 * genera un placeholder SVG de marca (oro sobre negro) embebido como data-URI.
 */
function product_image(array $p, bool $absolute = false): string {
    if (!empty($p['imagen'])) {
        $file = UPLOAD_DIR . '/' . $p['imagen'];
        if (is_file($file)) {
            return base_url(UPLOAD_URL . '/' . $p['imagen']);
        }
    }
    return placeholder_svg($p['nombre'] ?? SITE_NAME, $p['categoria'] ?? '');
}

/** Placeholder SVG de marca como data-URI */
function placeholder_svg(string $nombre, string $categoria = ''): string {
    $glyphs = [
        'Anillos'  => 'M50 18a32 32 0 1 0 0.001 0Z',
        'Cadenas'  => '',
        'Relojes'  => '',
    ];
    $ini = strtoupper(mb_substr(trim($nombre), 0, 1));
    $cat = e($categoria);
    $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="800" height="800" viewBox="0 0 800 800">
  <defs>
    <radialGradient id="bg" cx="50%" cy="38%" r="75%">
      <stop offset="0%" stop-color="#241d12"/>
      <stop offset="55%" stop-color="#100c07"/>
      <stop offset="100%" stop-color="#000000"/>
    </radialGradient>
    <linearGradient id="gold" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#7a5c12"/>
      <stop offset="35%" stop-color="#f7e08a"/>
      <stop offset="55%" stop-color="#d4af37"/>
      <stop offset="100%" stop-color="#8a6a14"/>
    </linearGradient>
  </defs>
  <rect width="800" height="800" fill="url(#bg)"/>
  <g fill="none" stroke="url(#gold)" stroke-width="1.4" opacity="0.5">
    <circle cx="400" cy="360" r="210"/>
    <circle cx="400" cy="360" r="170"/>
  </g>
  <text x="400" y="410" font-family="Georgia, 'Cormorant', serif" font-size="240" font-weight="700"
        fill="url(#gold)" text-anchor="middle" dominant-baseline="middle">$ini</text>
  <text x="400" y="640" font-family="Montserrat, Arial, sans-serif" font-size="30" letter-spacing="8"
        fill="#d4af37" text-anchor="middle" opacity="0.85">VANEGAS ROYAL</text>
  <text x="400" y="690" font-family="Montserrat, Arial, sans-serif" font-size="22" letter-spacing="6"
        fill="#9a8347" text-anchor="middle" opacity="0.8">$cat</text>
</svg>
SVG;
    return 'data:image/svg+xml;charset=utf-8,' . rawurlencode($svg);
}

/** Precio efectivo (oferta si existe) */
function precio_final(array $p): int {
    return !empty($p['precio_oferta']) ? (int)$p['precio_oferta'] : (int)$p['precio'];
}

function tiene_oferta(array $p): bool {
    return !empty($p['precio_oferta']) && (int)$p['precio_oferta'] < (int)$p['precio'];
}

function descuento_pct(array $p): int {
    if (!tiene_oferta($p)) return 0;
    return (int)round((1 - $p['precio_oferta'] / $p['precio']) * 100);
}

// ── Consultas ────────────────────────────────────────────────────────────────
function get_productos(array $opts = []): array {
    $sql = 'SELECT * FROM productos WHERE activo = 1';
    $args = [];
    if (!empty($opts['categoria'])) {
        $sql .= ' AND categoria = ?';
        $args[] = $opts['categoria'];
    }
    if (!empty($opts['buscar'])) {
        $sql .= ' AND (nombre LIKE ? OR descripcion LIKE ?)';
        $args[] = '%' . $opts['buscar'] . '%';
        $args[] = '%' . $opts['buscar'] . '%';
    }
    if (!empty($opts['destacado'])) {
        $sql .= ' AND destacado = 1';
    }
    if (!empty($opts['oferta'])) {
        $sql .= ' AND precio_oferta IS NOT NULL';
    }
    $orden = $opts['orden'] ?? 'recientes';
    $sql .= match ($orden) {
        'precio_asc'  => ' ORDER BY COALESCE(precio_oferta, precio) ASC',
        'precio_desc' => ' ORDER BY COALESCE(precio_oferta, precio) DESC',
        'nombre'      => ' ORDER BY nombre ASC',
        default       => ' ORDER BY destacado DESC, id DESC',
    };
    if (!empty($opts['limit'])) {
        $sql .= ' LIMIT ' . (int)$opts['limit'];
    }
    $st = db()->prepare($sql);
    $st->execute($args);
    return $st->fetchAll();
}

function get_producto(int $id): ?array {
    $st = db()->prepare('SELECT * FROM productos WHERE id = ? AND activo = 1');
    $st->execute([$id]);
    return $st->fetch() ?: null;
}

function get_producto_slug(string $slug): ?array {
    $st = db()->prepare('SELECT * FROM productos WHERE slug = ? AND activo = 1');
    $st->execute([$slug]);
    return $st->fetch() ?: null;
}

function get_categorias(): array {
    return db()->query('SELECT categoria, COUNT(*) c FROM productos WHERE activo = 1 GROUP BY categoria ORDER BY categoria')
               ->fetchAll();
}

function get_ofertas(): array {
    return db()->query('SELECT * FROM ofertas WHERE activo = 1 ORDER BY id DESC')->fetchAll();
}

/** Render de una tarjeta de producto (storefront) */
function product_card(array $p): void {
    $img   = product_image($p);
    $url   = base_url('producto.php?slug=' . urlencode($p['slug']));
    $final = precio_final($p);
    $oferta = tiene_oferta($p);
    // datos para el store de Alpine (precio = precio final)
    $js = htmlspecialchars(json_encode([
        'id' => (int)$p['id'], 'nombre' => $p['nombre'], 'precio' => $final, 'imagen' => $img,
    ], JSON_UNESCAPED_UNICODE), ENT_QUOTES);
    ?>
    <article class="card-prod reveal group">
      <?php if ($oferta): ?><span class="badge-oferta">-<?= descuento_pct($p) ?>%</span><?php endif; ?>
      <a href="<?= $url ?>" class="block card-prod__img media" aria-label="<?= e($p['nombre']) ?>">
        <img src="<?= $img ?>" alt="<?= e($p['nombre']) ?>" loading="lazy" width="800" height="800">
      </a>
      <div class="p-5">
        <p class="eyebrow text-mute mb-1"><?= e($p['categoria']) ?></p>
        <h3 class="font-display text-xl leading-tight mb-2">
          <a href="<?= $url ?>" class="hover:text-gold transition-colors"><?= e($p['nombre']) ?></a>
        </h3>
        <div class="flex items-end justify-between gap-3 mt-3">
          <div>
            <?php if ($oferta): ?>
              <span class="block text-xs text-mute line-through"><?= precio_cop($p['precio']) ?></span>
            <?php endif; ?>
            <span class="text-gold font-semibold text-lg tabular-nums"><?= precio_cop($final) ?></span>
          </div>
          <button type="button" x-data
                  @click='$store.cart.add(<?= $js ?>)'
                  class="shrink-0 w-10 h-10 grid place-items-center glass rounded hover:border-gold hover:text-gold transition-all"
                  aria-label="Añadir <?= e($p['nombre']) ?> al carrito" data-cursor>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
          </button>
        </div>
      </div>
    </article>
    <?php
}

/** Link a WhatsApp con mensaje precargado */
function whatsapp_link(string $msg = ''): string {
    $msg = $msg !== '' ? $msg : 'Hola Vanegas Royal, quiero información sobre sus joyas.';
    return 'https://wa.me/' . WHATSAPP_NUMBER . '?text=' . rawurlencode($msg);
}
