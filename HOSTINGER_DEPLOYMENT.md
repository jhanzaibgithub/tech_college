# Hostinger deployment

This is a Laravel application, not a static Vite site. Vite writes Laravel's
frontend manifest and compiled assets to `public/build`.

## Hostinger settings

- Build command: `npm run build`
- Build/output directory: `public/build` (not `dist`)
- Web/document root: the application's `public` directory
- PHP version: 8.1 or newer (8.2 is recommended)

If the Hostinger screen only accepts a static output directory and cannot run
PHP/Laravel, it is the wrong deployment workflow for this project. Use a
Hostinger PHP/Laravel hosting deployment instead. A static-site deployment of
only `public/build` will not run routes, Blade, the database, or the admin area.

## Server setup

1. Extract the project outside the public web root when Hostinger permits it,
   and point the domain's document root to the project's `public` directory.
2. Create the production `.env` on the server. Do not upload a local `.env`.
   Set `APP_ENV=production`, `APP_DEBUG=false`, the live `APP_URL`, and the
   Hostinger database credentials.
3. Run `composer install --no-dev --optimize-autoloader` if `vendor` was not
   uploaded, then run `php artisan optimize` and the appropriate migration
   command after backing up an existing production database.
4. Ensure `storage` and `bootstrap/cache` are writable by PHP.

The upload ZIP produced for this project contains the built `public/build`
assets and the existing Composer dependencies, but deliberately excludes
`.env`, Git data, Node dependencies, logs, caches, and tests.
