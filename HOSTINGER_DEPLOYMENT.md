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

## Pictures missing or flickering on the live site

- Set `APP_URL=https://techcollege.com.pk` in the live `.env`. The site then always builds https:// links, so
  browsers do not block images as "mixed content" when the host terminates SSL in front of PHP.
- After changing `.env` or uploading new code, use **Admin -> Maintenance -> Clear all caches** (no SSH needed).
- If files in `storage/app/public` are not reachable, use **Admin -> Maintenance -> Connect storage link**.
- If a picture still fails, open the browser's Network tab, click the failing image and note its status
  (404 = file missing, 403 = permissions, 5xx/508 = hosting resource limit). Images are retried once automatically.