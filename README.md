# Vanegas Royal — Joyería E-commerce

Tienda online premium (dark + oro) para joyería de lujo. PHP puro + SQLite + Tailwind + Alpine.js + GSAP.

## Arrancar en local (XAMPP)

```bash
# 1. Crear la base de datos con datos demo (solo la primera vez o para resetear)
C:\xampp\php\php.exe database/seed.php

# 2. Levantar el servidor
C:\xampp\php\php.exe -S localhost:8000 -t "C:\Users\USER\OneDrive\Documentos\Desarrollo\Vanegas"
```

Abrir http://localhost:8000

## Logo

Coloca el logo del dragón en **`assets/img/logo.png`**. Aparece en el nav, en el hero (con efecto parallax)
y en el login del admin. Si el archivo no existe, el sitio usa un texto de respaldo automáticamente.

## Panel de administración

- URL: http://localhost:8000/admin/login.php
- Usuario: **admin** · Contraseña: **vanegas2025**
- (cambiar en `config/config.php`)

CRUD completo de productos (con subida de imagen), gestión de ofertas y pedidos.

## Estructura

- `index.php` `tienda.php` `producto.php` `ofertas.php` — storefront
- `carrito.php` `checkout.php` — carrito (localStorage) y pedido (validado en servidor)
- `faq.php` `contacto.php` — soporte
- `admin/` — panel CRUD
- `config/` `includes/` — configuración, BD y helpers
- `database/seed.php` — esquema + datos demo
- `data/vanegas.sqlite` — base de datos (generada, ignorada por git)

## Pendiente / configurar antes de producción

- **WhatsApp y datos de contacto** en `config/config.php` (número real, correo, dirección, redes).
- **Pasarela de pago**: el checkout queda estructurado; falta integrar Wompi/Mercado Pago con las
  llaves reales del cliente (`PAYMENT_ENABLED`, `PAYMENT_PROVIDER` en `config/config.php`).
- **Fotos reales** de los productos desde el admin (reemplazan los placeholders de marca).
- Tailwind se carga por CDN (igual que los demás sitios). Para producción se puede compilar con la CLI.
