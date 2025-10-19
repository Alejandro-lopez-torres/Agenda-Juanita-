# Agenda Juanita (PHP 8 + MySQL + MVC)

## Requisitos
- PHP 8.x
- MySQL 8 (o compatible) y Workbench
- Apache (con mod_rewrite habilitado) o similar

## Instalación
1. Crear la base de datos y tablas (DDL en `sql/schema.sql`).
2. Configurar credenciales en `config/config.php`.
3. Copiar el proyecto a tu servidor local, por ejemplo `htdocs/agenda-juanita`.
4. Asegúrate de que `.htaccess` esté activo (URL rewriting).
5. Navega a `http://localhost/agenda-juanita/public`.

## Características incluidas
- CRUD de **Clientes** y **Citas**.
- **CSRF** en formularios y eliminaciones.
- **Validaciones** de servidor (correo, DNI, etc.).
- **Paginación** visual.
- **Filtros avanzados** en Agenda (estado, hoy/semana/mes o fechas libre).
- **Autocompletado** de clientes al crear/editar cita.
- Prevención simple de **choque de citas** (misma fecha + hora + cliente).

## Estructura
- `/app/Core` → Database (PDO), Router, Helpers (CSRF, validates, etc.)
- `/app/Models` → Cliente, Cita
- `/app/Controllers` → ClienteController, CitaController
- `/views` → Layouts + vistas CRUD
- `/public` → index.php, assets, .htaccess
- `/config/config.php` → configuración

## Notas
- Para producción, añade manejo de errores/logging y endurece validaciones.
- Puedes cambiar el rango de validación de DNI en `Helpers::validateCliente`.
