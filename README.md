# Cutting Plotter India — Laravel CMS

Enterprise-level, SEO-friendly, multi-brand website for plotters, printers, printheads, inks, spare parts, and industrial printing equipment. Built with Laravel 13, Bootstrap 5, and a custom CMS admin panel.

## Tech Stack

- **Backend:** Laravel 13, PHP 8.3+, MySQL/SQLite
- **Frontend:** Bootstrap 5, Blade, jQuery, Alpine.js (via Breeze)
- **Auth:** Laravel Breeze + Spatie Permission (RBAC)
- **Architecture:** MVC + Repository Pattern + Service Layer

## Features

### Admin CMS Panel (`/admin`)
- Dashboard with statistics (products, leads, low stock alerts)
- Product management (CRUD, clone, images, specs, SEO)
- Brand & category management (unlimited, nested categories)
- Lead/CRM management (status tracking, assignment, notes)
- Website settings (company info, social media, SEO defaults)
- Role-based access control (8 roles, 12 permissions)

### Frontend
- Responsive homepage with hero, categories, featured products, brands, testimonials
- Product catalog with search, filters, sorting, pagination
- Product detail pages with specs, features, quote request
- Brand pages, blog system, contact form
- WhatsApp & call floating buttons
- SEO meta tags, breadcrumbs, clean URLs

### Database
30+ tables including products, brands, categories, pages, blogs, menus, banners, leads, dealers, settings, SEO metadata, activity logs, and more.

## Quick Start

```bash
# Install dependencies
composer install
npm install && npm run build

# Configure environment
cp .env.example .env
php artisan key:generate

# Database (SQLite default, or configure MySQL in .env)
touch database/database.sqlite
php artisan migrate --seed

# Storage link
php artisan storage:link

# Start server
php artisan serve
```

## Default Admin Login

| Field | Value |
|-------|-------|
| URL | `/login` then `/admin` |
| Email | `admin@cuttingplotter.in` |
| Password | `password` |

## MySQL Configuration

Update `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cuttingplotter
DB_USERNAME=root
DB_PASSWORD=your_password
```

## Project Structure

```
app/
├── Http/Controllers/
│   ├── Admin/          # CMS admin controllers
│   └── ...             # Frontend controllers
├── Models/             # Eloquent models (30+)
├── Repositories/       # Repository pattern
├── Services/           # Business logic layer
database/
├── migrations/         # Database schema
└── seeders/            # Sample data
resources/views/
├── admin/              # Admin panel views
└── frontend/           # Public website views
routes/
├── web.php             # Frontend routes
└── admin.php           # Admin routes
```

## Routes

| Route | Description |
|-------|-------------|
| `/` | Homepage |
| `/products` | Product catalog |
| `/products/{slug}` | Product detail |
| `/brands` | Brand listing |
| `/blog` | Blog listing |
| `/contact` | Contact form |
| `/admin` | Admin dashboard |
| `/admin/products` | Product management |
| `/admin/brands` | Brand management |
| `/admin/categories` | Category management |
| `/admin/leads` | Lead management |
| `/admin/settings` | Website settings |

## Roles

- Super Admin, Admin, Content Manager, SEO Executive
- Sales Team, Support Team, Dealer, Customer

## License

Proprietary — Skycut India / cuttingplotter.in
