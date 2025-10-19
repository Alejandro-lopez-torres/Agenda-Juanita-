# Agenda Juanita (PHP8/MySQL/MVC)

## Reqs
- PHP8+
- MySQL8+ & Workbench
- Apache c/ mod_rewrite

## Instalación
1. Ejecuta `sql/schema.sql`.
2. Edita `config/config.php`.
3. Copia al servidor.
4. Activa `.htaccess`.
5. Accede `localhost/agenda-juanita/public`.

## Funciones
- CRUD: Clientes/Citas.
- CSRF.
- Validaciones: email/DNI.
- Paginación/Filtros.
- Autocompletado.
- Sin choques.

## Estructura
- `/app/Core`: BD/Router/Helpers.
- `/app/Models`: Cliente/Cita.
- `/app/Controllers`: Cliente/Cita.
- `/views`: Plantillas.
- `/public`: Entrada/assets.
- `/config`: Config.

## Notas
- Prod: Logs/validaciones estrictas.
- Ajusta DNI en Helpers.

