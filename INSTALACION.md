# La Cuponera SV: avance Fase 1 (Laravel)

Este paquete **no es un proyecto Laravel completo**: contiene solo los archivos propios del sistema (modelos, controladores, vistas, migraciones, rutas). Se copian encima de un proyecto Laravel recién creado.

**Requisitos:** PHP 8.2 o superior, Composer, MySQL (XAMPP sirve) y Laravel 11 o superior.

## Pasos

1. **Crea el proyecto Laravel** (en la carpeta donde guardas tus proyectos):
   ```
   composer create-project laravel/laravel cuponera-sv
   ```
2. **Copia el contenido de este paquete** dentro de `cuponera-sv`, aceptando reemplazar los archivos existentes (`routes/web.php`, `database/seeders/DatabaseSeeder.php`, `.env.example` y la migración `0001_01_01_000000_create_users_table.php`).
3. **Crea el archivo `.env`:**
   ```
   cd cuponera-sv
   copy .env.example .env        (en Mac/Linux: cp .env.example .env)
   php artisan key:generate
   ```
4. **Crea la base de datos** `cuponera_sv` en phpMyAdmin (cotejamiento `utf8mb4_unicode_ci`). Si tu MySQL tiene contraseña, ajústala en `DB_PASSWORD` dentro de `.env`.
5. **Crea las tablas y los datos de prueba:**
   ```
   php artisan migrate:fresh --seed
   ```
6. **Inicia el servidor:**
   ```
   php artisan serve
   ```
   Abre http://localhost:8000

## Cuentas de prueba

| Tipo | Dónde ingresar | Usuario | Contraseña |
|---|---|---|---|
| Administrador | /admin/login | `admin` | `Admin1234` |
| Cliente | /login | `cliente_demo` | `Demo1234` |
| Empresa aprobada | /empresa/login | `pupuseria_marta` | `Demo1234` |
| Empresa pendiente | /empresa/login | `tecnoplaza` | `Demo1234` |

## Qué cubre este avance

- Login separado para clientes, empresas y administradores
- Registro de clientes (valida DUI y mayoría de edad) y de empresas (valida NIT y teléfono)
- Registro de nuevos administradores desde el panel de administración
- Aprobación o rechazo de empresas, con porcentaje de comisión
- Recuperación de contraseña para los tres tipos de usuario
- Validaciones con mensajes en español
- Portada pública con las ofertas disponibles (las "no disponibles" no se muestran)
- Base de datos completa (incluye ofertas, compras y cupones para la fase final)

## Cómo probar la recuperación de contraseña

1. Ve a `/olvide-mi-contrasena` y escribe, por ejemplo, `cliente@demo.sv`.
2. En modo local la pantalla muestra el enlace directamente. También queda guardado el correo en `storage/logs/laravel.log`.
3. Abre el enlace, escribe la nueva contraseña y vuelve a iniciar sesión.

## Flujo para probar la aprobación de empresas

1. Registra una empresa en `/empresa/registro` (o usa `tecnoplaza`, que ya está pendiente).
2. Ingresa como `admin` → **Empresas** → pestaña **Pendientes**.
3. Escribe el porcentaje de comisión y pulsa **Aprobar** (o indica un motivo y pulsa **Rechazar**).
4. Ingresa como esa empresa y verás su estado actualizado.

> Las pantallas usan Bootstrap y la tipografía desde internet (CDN), así que necesitas conexión para verlas con su estilo completo.
