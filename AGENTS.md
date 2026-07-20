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

### Cache serialization note
`App\Services\MenuService` caches Eloquent models (`Menu`/`MenuItem`). Laravel 13's `config/cache.php` `'serializable_classes'` therefore allow-lists `Menu`, `MenuItem`, and `Illuminate\Database\Eloquent\Collection` (default was `false`, which broke every `frontend.*` page). If you cache additional model types, add them to that list or they will deserialize to `__PHP_Incomplete_Class`.

### Auth redirect note
There is no standalone user area: the `dashboard` named route (`routes/web.php`) just forwards admin-capable users to `admin.dashboard` and everyone else to `profile.edit`. Laravel Breeze's auth controllers redirect to `route('dashboard')`, so that route must exist.

### Testing
- Tests: `php artisan test` (SQLite `:memory:`). 6 failures are pre-existing Laravel Breeze scaffolding tests that don't match this app (they reference the removed `dashboard` route and the default welcome page); the other 19 pass.
- Lint: `./vendor/bin/pint` (or `--test` to check without fixing). The repo currently has pre-existing style findings.
