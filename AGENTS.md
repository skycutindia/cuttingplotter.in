# AGENTS.md

## Cursor Cloud specific instructions

Laravel 13 CMS ("Cutting Plotter India"). PHP 8.3 + Composer, Node 22 (npm), SQLite. See `README.md` for the product overview and the full command list.

### Services & how to run (development mode)
- Backend: `php artisan serve --host=0.0.0.0 --port=8000`.
- Frontend assets: `npm run dev` (Vite dev server on port 5173, writes `public/hot`). Assets are also pre-built via `npm run build` (`public/build/manifest.json`); without either, any page that renders a Blade layout throws "Vite manifest not found".
- `composer dev` runs server + queue + logs (pail) + Vite together via `concurrently`.

### Environment
- System deps (PHP 8.3 CLI + extensions: mbstring, xml, curl, sqlite3, gd, zip, bcmath, intl, gmp; and Composer) are pre-installed in the VM snapshot. The startup update script only refreshes `composer`/`npm` dependencies.
- `.env`, `database/database.sqlite`, `vendor/`, `node_modules/`, and `public/build/` are all gitignored and persist in the VM snapshot. First-time setup only: `cp .env.example .env`, `php artisan key:generate`, `touch database/database.sqlite`, `php artisan migrate --seed`, `php artisan storage:link`.
- Default cache/session/queue drivers are `database` (SQLite). If migrations or cached objects behave oddly, run `php artisan optimize:clear`.

### Admin CMS (the core product)
- Log in at `/login` with `admin@cuttingplotter.in` / `password`, then use `/admin` (dashboard, products, brands, categories, pages, menus, leads, settings).

### Known pre-existing bugs (NOT environment issues — do not "fix" as part of setup)
- Post-login redirect throws `Route [dashboard] not defined` (500). Authentication still succeeds; navigate directly to `/admin` afterward. Caused by the Breeze default redirect target being removed in favor of `/admin`.
- Frontend pages (`/`, `/products`, `/brands`, `/page/{slug}`, etc.) return 500 with `App\Models\Menu ... incomplete object`. `App\Services\MenuService` caches an Eloquent model, which is incompatible with Laravel 13's `config/cache.php` default `'serializable_classes' => false`. The admin panel (`admin.*` views) is unaffected. For local frontend work you can set `CACHE_STORE=array` in `.env` (never commit that), but the real fix is application-level (cache primitives instead of the model, or allowlist the classes).

### Testing
- Tests: `php artisan test` (SQLite `:memory:`). 6 failures are pre-existing Laravel Breeze scaffolding tests that don't match this app (they reference the removed `dashboard` route and the default welcome page); the other 19 pass.
- Lint: `./vendor/bin/pint` (or `--test` to check without fixing). The repo currently has pre-existing style findings.
