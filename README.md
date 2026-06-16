# TailAdmin Laravel Dashboard

This project is a trimmed Laravel 12 dashboard application based on TailAdmin. The current app keeps only the core dashboard experience plus authentication screens:

- Dashboard: `/`
- Login: `/login`
- Signup: `/signup`

All extra demo pages, routes, controllers, and unused Blade components have been removed so the structure is easier to understand and extend.

## Stack

- PHP 8.2+
- Laravel 12
- Blade templates
- Tailwind CSS 4
- Alpine.js
- Vite
- Pest for tests

## Main Routes

Routes are defined in `routes/web.php`.

```php
GET /        -> pages.dashboard.ecommerce
GET /login   -> pages.auth.login
GET /signup  -> pages.auth.signup
```

Laravel also registers framework routes such as `/up` and the local storage route when enabled.

## Current View Structure

```text
resources/views/
+-- components/
|   +-- common/
|   |   +-- common-grid-shape.blade.php
|   |   +-- dropdown-menu.blade.php
|   |   +-- preloader.blade.php
|   +-- ecommerce/
|   |   +-- customer-demographic.blade.php
|   |   +-- ecommerce-metrics.blade.php
|   |   +-- monthly-sale.blade.php
|   |   +-- monthly-target.blade.php
|   |   +-- recent-orders.blade.php
|   |   +-- statistics-chart.blade.php
|   +-- header/
|       +-- notification-dropdown.blade.php
|       +-- user-dropdown.blade.php
+-- layouts/
|   +-- app.blade.php
|   +-- app-header.blade.php
|   +-- backdrop.blade.php
|   +-- fullscreen-layout.blade.php
|   +-- sidebar.blade.php
|   +-- sidebar-widget.blade.php
+-- pages/
    +-- auth/
    |   +-- login.blade.php
    |   +-- signup.blade.php
    +-- dashboard/
        +-- ecommerce.blade.php
```

## Application Structure

```text
app/
+-- Helpers/
|   +-- MenuHelper.php
+-- Http/
|   +-- Controllers/
|       +-- Controller.php
+-- Models/
|   +-- User.php
+-- Providers/
|   +-- AppServiceProvider.php
+-- View/
    +-- Components/
        +-- common/
        +-- ecommerce/
        +-- header/
```

`MenuHelper.php` currently returns a single sidebar menu item for the dashboard.

## Important Files

- `routes/web.php`: Web route definitions.
- `resources/views/layouts/app.blade.php`: Main authenticated dashboard layout with sidebar and header.
- `resources/views/layouts/fullscreen-layout.blade.php`: Fullscreen layout used by login and signup.
- `resources/views/pages/dashboard/ecommerce.blade.php`: Dashboard page composition.
- `resources/views/pages/auth/login.blade.php`: Login screen.
- `resources/views/pages/auth/signup.blade.php`: Signup screen.
- `resources/css/app.css`: Main CSS entry point.
- `resources/js/app.js`: Main JavaScript entry point.
- `vite.config.js`: Vite configuration.
- `composer.json`: PHP dependencies and Laravel scripts.
- `package.json`: frontend dependencies and Vite scripts.

## Installation

Install PHP dependencies:

```bash
composer install
```

Install frontend dependencies:

```bash
npm install
```

Create the environment file:

```bash
copy .env.example .env
```

Generate the Laravel application key:

```bash
php artisan key:generate
```

Run migrations:

```bash
php artisan migrate
```

Seed the database if needed:

```bash
php artisan db:seed
```

## Development

Run Laravel and Vite together:

```bash
composer run dev
```

Or run them separately:

```bash
php artisan serve
npm run dev
```

The Laravel app usually runs at:

```text
http://127.0.0.1:8000
```

## Build

Build frontend assets for production:

```bash
npm run build
```

Cache views:

```bash
php artisan view:cache
```

Clear Laravel caches during development:

```bash
php artisan optimize:clear
```

## Testing

Run the Laravel test suite:

```bash
composer run test
```

Or:

```bash
php artisan test
```

## Notes

- This app currently uses static Blade screens for login and signup. Form submission/authentication logic can be added later.
- The dashboard still uses chart and UI JavaScript dependencies such as ApexCharts, Alpine.js, Flatpickr, and jsvectormap.
- Removed TailAdmin demo sections include calendar, profile, forms, tables, chart demo pages, UI element pages, blank page, and error demo page.
