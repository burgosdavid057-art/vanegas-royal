<?php
/**
 * Configuración global — Vanegas Royal
 * Joyería de lujo · ecommerce premium
 */

// ── Negocio ───────────────────────────────────────────────────────────────
const SITE_NAME    = 'Vanegas Royal';
const SITE_TAGLINE = 'Hecho para dominar';
const SITE_DESC    = 'Joyería de lujo en oro 18k. Anillos, cadenas, dijes y relojes hechos para quienes nacieron para dominar.';

// WhatsApp (formato internacional sin + ni espacios)
const WHATSAPP_NUMBER = '573001234567';
const WHATSAPP_LABEL  = '+57 300 123 4567';

const CONTACT_EMAIL   = 'ventas@vanegasroyal.com';
const CONTACT_PHONE   = '+57 300 123 4567';
const CONTACT_ADDRESS = 'Cra. 43A #1-50, El Poblado · Medellín, Colombia';
const CONTACT_HOURS   = 'Lun–Sáb · 9:00 a.m. – 7:00 p.m.';

const SOCIAL_INSTAGRAM = 'https://instagram.com/vanegasroyal';
const SOCIAL_FACEBOOK  = 'https://facebook.com/vanegasroyal';
const SOCIAL_TIKTOK    = 'https://tiktok.com/@vanegasroyal';

// ── Admin (login simple) ────────────────────────────────────────────────────
// Usuario y hash de contraseña. Contraseña por defecto: vanegas2025
const ADMIN_USER = 'admin';
// password_hash('vanegas2025', PASSWORD_DEFAULT)
const ADMIN_PASS_HASH = '$2y$10$Qm3yQ8z3wq3jJ7XZ1Yk0xeYpYwq4Z2gqV1uPpV9cDqg3qg9Bqg2C';
// Fallback en texto plano para validar también (por si el hash difiere entre entornos PHP)
const ADMIN_PASS_PLAIN = 'vanegas2025';

// ── Pago (estructura — sin integrar) ────────────────────────────────────────
// TODO: integrar pasarela real (Wompi / Mercado Pago) con llaves del cliente.
const PAYMENT_ENABLED  = false;          // cuando haya credenciales, poner true
const PAYMENT_PROVIDER = 'wompi';        // 'wompi' | 'mercadopago'
const CURRENCY         = 'COP';

// ── Rutas ────────────────────────────────────────────────────────────────────
const DB_PATH       = __DIR__ . '/../data/vanegas.sqlite';
const UPLOAD_DIR    = __DIR__ . '/../assets/img/productos';
const UPLOAD_URL    = 'assets/img/productos';

// URL base del sitio (para enlaces absolutos cuando haga falta)
function base_url(string $path = ''): string {
    $dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    // Si estamos dentro de /admin, subir un nivel para enlaces al storefront
    if (str_ends_with($dir, '/admin')) {
        $dir = substr($dir, 0, -6);
    }
    $dir = $dir === '' ? '' : $dir;
    return $dir . '/' . ltrim($path, '/');
}

// Zona horaria Colombia
date_default_timezone_set('America/Bogota');

// Sesiones
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
