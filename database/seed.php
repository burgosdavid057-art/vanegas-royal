<?php
/**
 * Seed — crea el esquema SQLite y carga datos demo de joyería.
 * Uso:  C:\xampp\php\php.exe database/seed.php
 */
require_once __DIR__ . '/../includes/functions.php';

$pdo = db();

echo "Creando esquema...\n";

$pdo->exec('DROP TABLE IF EXISTS pedidos');
$pdo->exec('DROP TABLE IF EXISTS ofertas');
$pdo->exec('DROP TABLE IF EXISTS productos');

$pdo->exec(<<<SQL
CREATE TABLE productos (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre        TEXT    NOT NULL,
    slug          TEXT    NOT NULL UNIQUE,
    descripcion   TEXT    NOT NULL DEFAULT '',
    categoria     TEXT    NOT NULL,
    precio        INTEGER NOT NULL,            -- COP, entero
    precio_oferta INTEGER,                     -- NULL si no está en oferta
    material      TEXT    NOT NULL DEFAULT 'Oro 18k',
    quilataje     TEXT    NOT NULL DEFAULT '18k',
    peso_g        REAL,
    stock         INTEGER NOT NULL DEFAULT 1,
    imagen        TEXT,                         -- archivo en assets/img/productos o NULL
    destacado     INTEGER NOT NULL DEFAULT 0,
    activo        INTEGER NOT NULL DEFAULT 1,
    created_at    TEXT    NOT NULL DEFAULT (datetime('now'))
)
SQL);

$pdo->exec(<<<SQL
CREATE TABLE ofertas (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    titulo       TEXT    NOT NULL,
    descripcion  TEXT    NOT NULL DEFAULT '',
    producto_id  INTEGER,
    descuento_pct INTEGER NOT NULL DEFAULT 0,
    fecha_inicio TEXT,
    fecha_fin    TEXT,
    activo       INTEGER NOT NULL DEFAULT 1,
    created_at   TEXT    NOT NULL DEFAULT (datetime('now')),
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL
)
SQL);

$pdo->exec(<<<SQL
CREATE TABLE pedidos (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    cliente_nombre TEXT   NOT NULL,
    email         TEXT,
    telefono      TEXT,
    direccion     TEXT,
    ciudad        TEXT,
    items         TEXT    NOT NULL,            -- JSON
    total         INTEGER NOT NULL,
    estado        TEXT    NOT NULL DEFAULT 'pendiente',
    metodo_pago   TEXT    NOT NULL DEFAULT 'whatsapp',
    notas         TEXT,
    created_at    TEXT    NOT NULL DEFAULT (datetime('now'))
)
SQL);

echo "Insertando productos demo...\n";

/** [nombre, categoria, precio, precio_oferta|null, descripcion, peso_g, destacado, stock] */
$productos = [
    ['Anillo Sello Dragón Imperial', 'Anillos', 2890000, 2390000, 'Anillo sello macizo en oro 18k con relieve de dragón tallado a mano. Una pieza de poder, hecha para imponer presencia.', 14.5, 1, 4],
    ['Anillo Solitario Corona Real', 'Anillos', 3450000, null, 'Solitario en oro amarillo 18k con engaste de circón blanco de alta pureza. Elegancia que no pide permiso.', 6.2, 1, 3],
    ['Anillo Firma VR Negro Oro', 'Anillos', 1690000, 1390000, 'Anillo de firma con acabado negro mate y filo en oro pulido. Contraste audaz para uso diario.', 9.0, 0, 8],
    ['Cadena Cubana 10mm Maciza', 'Cadenas', 7850000, null, 'Cadena cubana maciza 10mm en oro 18k, broche de seguridad reforzado. El clásico que define estatus.', 78.0, 1, 2],
    ['Cadena Franco 4mm', 'Cadenas', 2950000, 2490000, 'Cadena tejido franco 4mm, flexible y resistente. Brillo espejo en oro 18k.', 22.0, 0, 6],
    ['Cadena Barbada Diamantada', 'Cadenas', 4200000, null, 'Cadena barbada con corte diamantado que multiplica el reflejo de la luz. Lujo en movimiento.', 35.0, 0, 4],
    ['Dije Dragón Real 3D', 'Dijes', 1980000, 1580000, 'Dije escultórico de dragón en oro 18k con volumen 3D y ojos en pedrería roja. La firma de la casa.', 16.0, 1, 5],
    ['Dije Corona Imperial', 'Dijes', 1250000, null, 'Dije corona en oro pulido con micro-engaste. Para coronar cualquier cadena.', 8.5, 0, 7],
    ['Dije Cruz Gótica Oro', 'Dijes', 1490000, 1190000, 'Cruz de estilo gótico con textura tallada y filos definidos. Devoción con carácter.', 11.0, 0, 6],
    ['Reloj Royal Gold Automático', 'Relojes', 12900000, null, 'Reloj automático con caja chapada en oro 18k, esfera negra y detalles dorados. Tiempo de reyes.', 145.0, 1, 2],
    ['Reloj Skeleton Black & Gold', 'Relojes', 8900000, 7490000, 'Movimiento esqueletizado a la vista, bisel dorado sobre cuerpo negro. Mecánica como arte.', 130.0, 0, 3],
    ['Aretes Argolla Oro 18k', 'Aretes', 980000, null, 'Argollas clásicas en oro 18k, acabado brillante, cierre seguro. Imprescindibles.', 4.0, 0, 10],
    ['Aretes Solitario Brillante', 'Aretes', 1350000, 1090000, 'Topos solitario con circón talla brillante engastado en oro 18k. Destello discreto y elegante.', 2.5, 0, 8],
    ['Esclava Cubana Grabada', 'Esclavas', 5600000, null, 'Esclava cubana maciza con placa grabable. Presencia en la muñeca, hecha para durar generaciones.', 62.0, 1, 2],
    ['Pulsera Tenis Circones', 'Esclavas', 3900000, 3290000, 'Pulsera tenis con línea continua de circones engastados en oro 18k. Brillo ininterrumpido.', 18.0, 0, 4],
    ['Gargantilla Dije Dragón Set', 'Cadenas', 2790000, 2290000, 'Set de cadena franco con dije dragón a juego. La combinación insignia de Vanegas Royal.', 30.0, 1, 3],
];

$stmt = $pdo->prepare(
    'INSERT INTO productos (nombre, slug, descripcion, categoria, precio, precio_oferta, material, quilataje, peso_g, stock, imagen, destacado, activo)
     VALUES (:nombre, :slug, :descripcion, :categoria, :precio, :precio_oferta, :material, :quilataje, :peso_g, :stock, :imagen, :destacado, 1)'
);

foreach ($productos as $i => $p) {
    [$nombre, $cat, $precio, $oferta, $desc, $peso, $dest, $stock] = $p;
    // imagen demo pN.jpg si existe el archivo, si no NULL (usa placeholder de marca)
    $imgFile = 'p' . ($i + 1) . '.jpg';
    $imagen = is_file(UPLOAD_DIR . '/' . $imgFile) ? $imgFile : null;
    $stmt->execute([
        ':nombre' => $nombre,
        ':slug' => slugify($nombre),
        ':descripcion' => $desc,
        ':categoria' => $cat,
        ':precio' => $precio,
        ':precio_oferta' => $oferta,
        ':material' => 'Oro 18k',
        ':quilataje' => '18k',
        ':peso_g' => $peso,
        ':stock' => $stock,
        ':imagen' => $imagen,
        ':destacado' => $dest,
    ]);
}

echo "Insertando ofertas...\n";

$pdo->exec("INSERT INTO ofertas (titulo, descripcion, producto_id, descuento_pct, activo) VALUES
    ('Colección Dragón · hasta 20% OFF', 'Las piezas insignia de la casa, por tiempo limitado.', NULL, 20, 1),
    ('Solo esta semana · Cadenas seleccionadas', 'Descuentos exclusivos en cadenas de tejido fino.', NULL, 15, 1)");

$total = (int)$pdo->query('SELECT COUNT(*) FROM productos')->fetchColumn();
echo "Listo. $total productos cargados en " . DB_PATH . "\n";
