# La Cuponera SV

**Institución:** _(completar)_ · **Materia:** Lenguajes Interpretados en el Servidor (LIS941) · **Proyecto de cátedra, Fase 2 (entrega final)**
**Grupo 4** — Integrantes: _(completar nombres y carnets)_

Aplicación web en **Laravel 13 / PHP 8.3 + MySQL** para la venta de cupones de descuento.

## Funcionalidad

| Rol | Qué puede hacer |
| --- | --- |
| Visitante | Ver los cupones disponibles, registrarse como cliente o como empresa. |
| Cliente | Comprar de 1 a 5 cupones por oferta (pago simulado con tarjeta), ver su factura con un código único por cupón e historial de compras. Debe ser mayor de 18 años para registrarse. |
| Empresa | Registrarse (queda pendiente), y una vez aprobada, publicar y editar ofertas. |
| Administrador | Aprobar/rechazar empresas fijando su comisión, registrar otros administradores y ver reportes de cupones vendidos, ventas y ganancias por empresa. |

Seguridad: las rutas están protegidas por rol (`RoleMiddleware`); una empresa solo accede a sus propias ofertas, un cliente solo a sus propias facturas, y las empresas no aprobadas no pueden publicar. Los datos de la tarjeta nunca se guardan (solo los últimos 4 dígitos).

## Requisitos

PHP 8.3+ (con extensiones `pdo_mysql`, `mbstring`, `openssl`), Composer, MySQL/MariaDB (por ejemplo XAMPP).

## Instalación y ejecución

```bash
composer install
copy .env.example .env        # (cp en Linux/Mac)
php artisan key:generate
```

Crea la base de datos y cárgala con **una** de estas dos opciones:

1. **Con migraciones (recomendado):** crea una base vacía `cuponera_sv` y ejecuta
   ```bash
   php artisan migrate --seed
   ```
2. **Con el script SQL:** importa `cuponera_sv.sql` en MySQL (phpMyAdmin → Importar).
   Incluye el esquema y los usuarios de prueba. Las ofertas de ejemplo quedan vigentes desde el día de la
   importación (fechas relativas a `CURDATE()`).

Ajusta `DB_*` en `.env` si tu MySQL tiene otra contraseña. Luego:

```bash
php artisan serve
```

Abre <http://localhost:8000>.

## Usuarios de prueba (seeder)

| Rol | Usuario | Contraseña | Entrada |
| --- | --- | --- | --- |
| Administrador | `admin` | `Admin1234` | `/admin/login` |
| Cliente | `cliente_demo` | `Demo1234` | `/login` |
| Empresa aprobada | `pupuseria_marta` | `Demo1234` | `/empresa/login` |
| Empresa pendiente | `tecnoplaza` | `Demo1234` | `/empresa/login` |

Tarjeta de prueba: cualquier número de 16 dígitos, vencimiento futuro (p. ej. `12/30`) y CVV de 3 dígitos.

## Rutas principales

| Ruta | Rol |
| --- | --- |
| `/` | público (catálogo) |
| `/registro`, `/empresa/registro` | público |
| `/cupones/{id}/comprar`, `/mis-cupones`, `/facturas/{id}` | cliente |
| `/empresa/ofertas` (+ `/crear`, `/{id}/editar`) | empresa aprobada |
| `/admin/empresas`, `/admin/reportes`, `/admin/administradores` | administrador |

## Documentación

Modelo relacional y descripción de tablas: [`docs/DESCRIPCION_TABLAS.md`](docs/DESCRIPCION_TABLAS.md).
