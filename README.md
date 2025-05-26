# Project Report: cms-superapp

**1. Project Overview**

The `cms-superapp` (originally "Filament Demo App") is a demonstration application designed to illustrate the capabilities and workings of the Filament Admin panel framework, which is built on top of Laravel. It serves as a practical example for developers looking to understand how to implement various features using Filament, including data management, relationships, and UI components. The project includes a pre-configured setup with a default admin user for quick exploration.

**2. Technology Stack**

*   **Backend Framework**: Laravel ([`v11.0`](composer.json:19)) - A popular PHP web application framework known for its elegant syntax and robust features.
*   **Admin Panel**: Filament ([`^3.0`](composer.json:12)) - A collection of tools for rapidly building beautiful TALL stack admin panels, forms, and tables.
*   **PHP Version**: [`^8.2`](composer.json:11)
*   **Frontend Build Tool**: Vite ([`^3.0.0`](package.json:20)) - A modern frontend build tool that provides an extremely fast development environment and bundles code for production.
*   **CSS Framework**: Tailwind CSS ([`^3.3.3`](package.json:18)) - A utility-first CSS framework for rapidly building custom user interfaces.
*   **JavaScript Framework**: Alpine.js ([`^3.10.3`](package.json:13)) - A rugged, minimal framework for composing JavaScript behavior in your markup.
*   **Database**: Default setup uses SQLite (as per [`README.md`](README.md:38)). The project is configured to be flexible with other database systems like MySQL or PostgreSQL through Laravel's database configuration.
*   **Key PHP Libraries**:
    *   [`filament/spatie-laravel-media-library-plugin`](composer.json:13): Integration for Spatie's media library.
    *   [`filament/spatie-laravel-settings-plugin`](composer.json:14): Integration for Spatie's settings plugin.
    *   [`filament/spatie-laravel-tags-plugin`](composer.json:15): Integration for Spatie's tags plugin.
    *   [`laravel/sanctum`](composer.json:21): For API authentication.
    *   [`spatie/laravel-permission`](composer.json:23): For managing roles and permissions.
*   **Key Frontend Libraries**:
    *   [`@tailwindcss/forms`](package.json:11): A Tailwind plugin for better default form styling.
    *   [`@tailwindcss/typography`](package.json:12): A Tailwind plugin for beautiful typographic defaults.

**3. Key Features (Highlighted by Filament Demo)**

The application showcases several of Filament's capabilities, particularly in handling database relationships:

*   **Resource Management**: Demonstrates creating Filament resources for managing database models (e.g., Products, Orders, Posts, Customers, Brands).
*   **Database Relationships**:
    *   `BelongsTo`: e.g., [`ProductResource`](app/Filament/Resources/KomoditasResource.php), [`OrderResource`](app/Filament/Resources/HargaKomoditasHarianKabkotaResource.php), [`PostResource`](app/Filament/Resources/Blog/PostFactory.php)
    *   `BelongsToMany`: e.g., [`CategoryResource\RelationManagers\ProductsRelationManager`](app/Filament/Imports/Shop/CategoryImporter.php)
    *   `HasMany`: e.g., [`OrderResource\RelationManagers\PaymentsRelationManager`](app/Filament/Resources/HargaKomoditasHarianKabkotaResource.php)
    *   `HasManyThrough`: e.g., [`CustomerResource\RelationManagers\PaymentsRelationManager`](app/Filament/Resources/Shop/CustomerFactory.php)
    *   `MorphOne`: e.g., [`OrderResource`](app/Filament/Resources/HargaKomoditasHarianKabkotaResource.php) -> Address
    *   `MorphMany`: e.g., [`ProductResource\RelationManagers\CommentsRelationManager`](app/Filament/Resources/KomoditasResource.php), [`PostResource\RelationManagers\CommentsRelationManager`](app/Filament/Resources/Blog/PostFactory.php)
    *   `MorphToMany`: e.g., [`BrandResource\RelationManagers\AddressRelationManager`](app/Filament/Resources/Shop/BrandFactory.php), [`CustomerResource\RelationManagers\AddressRelationManager`](app/Filament/Resources/Shop/CustomerFactory.php)
*   **Admin Interface**: Provides a clean, responsive, and feature-rich admin panel for managing application data.
*   **Authentication**: Standard Laravel authentication with a default admin user (`admin@filamentphp.com` / `password`).
*   **Database Seeding**: Includes seeders to populate the database with initial sample data for demonstration.

**4. Directory Structure Overview**

The project follows a standard Laravel application structure:

*   [`app/`](app/): Contains the core code of the application, including Models, Controllers, Filament Resources, Http Kernel, Console Kernel.
    *   [`app/Filament/`](app/Filament/): Houses Filament-specific code like Resources, Pages, Widgets, and Clusters.
    *   [`app/Http/`](app/Http/): Contains controllers, middleware, and form requests.
    *   [`app/Models/`](app/Models/): Contains Eloquent models.
*   [`bootstrap/`](bootstrap/): Contains files that bootstrap the framework and configure autoloading.
*   [`config/`](config/): Contains all of the application's configuration files.
*   [`database/`](database/): Contains database migrations, factories, and seeders.
*   [`public/`](public/): The web server's document root. Contains the `index.php` entry point and publicly accessible assets (CSS, JS, images).
*   [`resources/`](resources/): Contains views (Blade templates), raw assets (CSS, JS via Vite), and language files.
*   [`routes/`](routes/): Contains all route definitions for the application (web, API, console, channels).
*   [`storage/`](storage/): Contains compiled Blade templates, file-based sessions, file caches, and other files generated by the framework.
*   [`tests/`](tests/): Contains automated tests (unit, feature).

**5. Installation and Setup**

The [`README.md`](README.md:1) provides the following setup instructions:

1.  Clone the repository: `git clone https://github.com/laravel-filament/demo.git filament-demo && cd filament-demo`
2.  Install PHP dependencies: `composer install`
3.  Copy environment file: `cp .env.example .env`
4.  Generate application key: `php artisan key:generate`
5.  Create database (e.g., SQLite): `touch database/database.sqlite` (and update `.env` if using a different DB)
6.  Run database migrations: `php artisan migrate`
7.  Run database seeder: `php artisan db:seed`
8.  Create storage symlink: `php artisan storage:link`
9.  Run development server: `php artisan serve`

**6. Key Dependencies**

**PHP Dependencies (from [`composer.json`](composer.json:1)):**

*   `php: ^8.2`
*   `filament/filament: ^3.0` (Core Filament package)
*   `filament/spatie-laravel-media-library-plugin: ^3.0`
*   `filament/spatie-laravel-settings-plugin: ^3.0`
*   `filament/spatie-laravel-tags-plugin: ^3.0`
*   `filament/spatie-laravel-translatable-plugin: ^3.0`
*   `laravel/framework: ^11.0` (Core Laravel framework)
*   `laravel/horizon: ^5.21` (Queue monitoring)
*   `laravel/sanctum: ^4.0` (API authentication)
*   `spatie/laravel-permission: ^6.18` (Role and permission management)

**Development PHP Dependencies:**

*   `barryvdh/laravel-debugbar: ^3.6`
*   `fakerphp/faker: ^1.9.1`
*   `laravel/pint: ^1.0` (Code styling)
*   `larastan/larastan: ^2.1` (Static analysis)
*   `phpunit/phpunit: ^10.1` (Testing framework)

**Node.js Dependencies (from [`package.json`](package.json:1)):**

*   `vite: ^3.0.0` (Build tool)
*   `alpinejs: ^3.10.3` (JavaScript framework)
*   `tailwindcss: ^3.3.3` (CSS framework)
*   `@tailwindcss/forms: ^0.5.3`
*   `@tailwindcss/typography: ^0.5.9`
*   `laravel-vite-plugin: ^0.5.0` (Laravel integration for Vite)
*   `autoprefixer: ^10.4.14`
*   `postcss: ^8.4.28`

**7. View Script Examples**

This section provides a glimpse into some of the Blade templates used in the project.

**a. Main Application Layout (`resources/views/components/layouts/app.blade.php`)**

This file defines the main HTML structure for the application pages. It includes common head elements, styles, and scripts.

```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />

        <meta name="application-name" content="{{ config('app.name') }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>{{ config('app.name') }}</title>

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        @filamentStyles
        @vite('resources/css/app.css')
    </head>

    <body class="antialiased">
        {{ $slot }}

        @filamentScripts
        @vite('resources/js/app.js')
    </body>
</html>
```

**b. Livewire Form Component (`resources/views/livewire/form.blade.php`)**

This is an example of a Livewire component's Blade view, typically used for dynamic forms. It renders a form managed by a corresponding Livewire PHP class.

```html
<form wire:submit="submit" class="max-w-3xl mx-auto w-full p-8 space-y-6">
    {{ $this->form }}

    {{ json_encode($this->data) }}

    <x-filament::button type="submit">
        Submit
    </x-filament::button>
</form>
```

**c. Included Assets**

The main layout includes CSS and JavaScript files processed by Vite.

**i. `resources/css/app.css`**

This file is the main entry point for CSS and primarily includes Tailwind CSS directives.

```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

**ii. `resources/js/app.js`**

This file is the main entry point for JavaScript. In the current state of the project, this file is empty.
```javascript
// This file is currently empty.
// Custom JavaScript for the application would be added here.
```

**8. Detailed Feature Structure**

This section outlines the project structure for key features, focusing on Models, Filament Resources (controllers/managers), and Filament Pages (views).

**a. Menu Structure (Navigation)**

Filament's navigation menu is primarily configured through:
*   **Navigation Groups**: Defined in [`app/Providers/AppServiceProvider.php`](app/Providers/AppServiceProvider.php:36) (e.g., 'Span Lapor', 'Harga Komoditas', 'Master', 'User Management').
*   **Filament Resources**: Individual resources define their navigation label, sort order, slug, and assign themselves to a navigation group.
    *   Example: [`app/Filament/Resources/SpanSumResource.php`](app/Filament/Resources/SpanSumResource.php:31) is in 'Span Lapor'.
    *   Example: [`app/Filament/Resources/HargaKomoditasHarianKabkotaResource.php`](app/Filament/Resources/HargaKomoditasHarianKabkotaResource.php:29) is in 'Harga Komoditas'.

**b. SPAN Lapor**
*   **Navigation Group**: 'Span Lapor'
*   **Filament Resource**: [`app/Filament/Resources/SpanSumResource.php`](app/Filament/Resources/SpanSumResource.php)
*   **Model**: [`app/Models/SpanSum.php`](app/Models/SpanSum.php) (Table: `superapps.span_sum`)
    *   *Related Model*: [`app/Models/MasterOpd.php`](app/Models/MasterOpd.php)
*   **Filament Pages (Views)**:
    *   List: [`app/Filament/Resources/SpanSumResource/Pages/ListSpanSums.php`](app/Filament/Resources/SpanSumResource/Pages/ListSpanSums.php)
    *   Create: [`app/Filament/Resources/SpanSumResource/Pages/CreateSpanSum.php`](app/Filament/Resources/SpanSumResource/Pages/CreateSpanSum.php)
    *   Edit: [`app/Filament/Resources/SpanSumResource/Pages/EditSpanSum.php`](app/Filament/Resources/SpanSumResource/Pages/EditSpanSum.php)
*   **Permissions**: `viewSpanSummary`, `addSpanSummary`, `editSpanSummary`, `deleteSpanSummary` (defined in Resource)

**c. Harga Pangan (Harga Komoditas)**
*   **Navigation Group**: 'Harga Komoditas'
*   **Sub-Feature: Harga Harian Kab/Kota**
    *   **Filament Resource**: [`app/Filament/Resources/HargaKomoditasHarianKabkotaResource.php`](app/Filament/Resources/HargaKomoditasHarianKabkotaResource.php)
        *   Navigation Label: 'Harga Harian Kab/Kota'
        *   Slug: `harga-komoditas/harian-kab-kota`
    *   **Model**: [`app/Models/HargaKomoditasHarianKabkota.php`](app/Models/HargaKomoditasHarianKabkota.php) (Table: `harga_komoditas_harian_kabkota`)
        *   *Related Models*: [`app/Models/Komoditas.php`](app/Models/Komoditas.php), [`app/Models/Administrasi.php`](app/Models/Administrasi.php)
    *   **Filament Pages (Views)**:
        *   List: [`app/Filament/Resources/HargaKomoditasHarianKabkotaResource/Pages/ListHargaKomoditasHarianKabkotas.php`](app/Filament/Resources/HargaKomoditasHarianKabkotaResource/Pages/ListHargaKomoditasHarianKabkotas.php)
        *   Create: [`app/Filament/Resources/HargaKomoditasHarianKabkotaResource/Pages/CreateHargaKomoditasHarianKabkota.php`](app/Filament/Resources/HargaKomoditasHarianKabkotaResource/Pages/CreateHargaKomoditasHarianKabkota.php)
        *   Edit: [`app/Filament/Resources/HargaKomoditasHarianKabkotaResource/Pages/EditHargaKomoditasHarianKabkota.php`](app/Filament/Resources/HargaKomoditasHarianKabkotaResource/Pages/EditHargaKomoditasHarianKabkota.php)
    *   **Permissions**: `viewHargaKomoditas`, `addHargaKomoditas`, `editHargaKomoditas`, `deleteHargaKomoditas`
*   **Sub-Feature: Ketersediaan Pangan**
    *   **Filament Resource**: [`app/Filament/Resources/KetersediaanPanganResource.php`](app/Filament/Resources/KetersediaanPanganResource.php)
        *   Navigation Label: 'Ketersediaan Pangan'
    *   **Model**: [`app/Models/KetersediaanPangan.php`](app/Models/KetersediaanPangan.php) (Table: `ketersediaan_pangan`)
    *   **Filament Pages (Views)**:
        *   List: [`app/Filament/Resources/KetersediaanPanganResource/Pages/ListKetersediaanPangans.php`](app/Filament/Resources/KetersediaanPanganResource/Pages/ListKetersediaanPangans.php)
        *   Create: [`app/Filament/Resources/KetersediaanPanganResource/Pages/CreateKetersediaanPangan.php`](app/Filament/Resources/KetersediaanPanganResource/Pages/CreateKetersediaanPangan.php)
        *   Edit: [`app/Filament/Resources/KetersediaanPanganResource/Pages/EditKetersediaanPangan.php`](app/Filament/Resources/KetersediaanPanganResource/Pages/EditKetersediaanPangan.php)
    *   **Permissions**: `viewKetersediaanPangan`, `addKetersediaanPangan`, `editKetersediaanPangan`, `deleteKetersediaanPangan`

**d. Master Data**
*   **Navigation Group**: 'Master'
*   **Sub-Feature: Master OPD**
    *   **Filament Resource**: [`app/Filament/Resources/MasterOpdResource.php`](app/Filament/Resources/MasterOpdResource.php)
    *   **Model**: [`app/Models/MasterOpd.php`](app/Models/MasterOpd.php) (Table: `master_opd`)
    *   **Filament Pages (Views)**:
        *   List: [`app/Filament/Resources/MasterOpdResource/Pages/ListMasterOpds.php`](app/Filament/Resources/MasterOpdResource/Pages/ListMasterOpds.php)
        *   Create: [`app/Filament/Resources/MasterOpdResource/Pages/CreateMasterOpd.php`](app/Filament/Resources/MasterOpdResource/Pages/CreateMasterOpd.php)
        *   Edit: [`app/Filament/Resources/MasterOpdResource/Pages/EditMasterOpd.php`](app/Filament/Resources/MasterOpdResource/Pages/EditMasterOpd.php)
        *   View: [`app/Filament/Resources/MasterOpdResource/Pages/ViewMasterOpd.php`](app/Filament/Resources/MasterOpdResource/Pages/ViewMasterOpd.php)
    *   **Permissions**: `viewMasterOpd`, `addMasterOpd`, `editMasterOpd`, `deleteMasterOpd` (via Laravel Gates)
*   **Sub-Feature: Komoditas** (Also relevant to Harga Pangan)
    *   **Filament Resource**: [`app/Filament/Resources/KomoditasResource.php`](app/Filament/Resources/KomoditasResource.php)
        *   Navigation Label: 'Komoditas'
        *   Slug: `master/komoditas`
    *   **Model**: [`app/Models/Komoditas.php`](app/Models/Komoditas.php) (Table: `master_komoditas`)
    *   **Filament Pages (Views)**:
        *   List: [`app/Filament/Resources/KomoditasResource/Pages/ListKomoditas.php`](app/Filament/Resources/KomoditasResource/Pages/ListKomoditas.php)
        *   Create: [`app/Filament/Resources/KomoditasResource/Pages/CreateKomoditas.php`](app/Filament/Resources/KomoditasResource/Pages/CreateKomoditas.php)
        *   Edit: [`app/Filament/Resources/KomoditasResource/Pages/EditKomoditas.php`](app/Filament/Resources/KomoditasResource/Pages/EditKomoditas.php)
    *   **Permissions**: `viewKomoditas`, `addKomoditas`, `editKomoditas`, `deleteKomoditas`
*   **Sub-Feature: Administrasi**
    *   **Filament Resource**: [`app/Filament/Resources/AdministrasiResource.php`](app/Filament/Resources/AdministrasiResource.php)
        *   Slug: `master/administrasi`
    *   **Model**: [`app/Models/Administrasi.php`](app/Models/Administrasi.php) (Table: `master_administrasi`)
    *   **Filament Pages (Views)**: Standard List, Create, Edit pages are typically generated.

**e. User Management**
*   **Navigation Group**: 'User Management'
*   **Sub-Feature: Users**
    *   **Filament Resource**: [`app/Filament/Resources/UserResource.php`](app/Filament/Resources/UserResource.php)
    *   **Model**: [`app/Models/User.php`](app/Models/User.php) (Uses `Spatie\Permission\Traits\HasRoles`)
    *   **Filament Pages (Views)**:
        *   List: [`app/Filament/Resources/UserResource/Pages/ListUsers.php`](app/Filament/Resources/UserResource/Pages/ListUsers.php)
        *   Create: [`app/Filament/Resources/UserResource/Pages/CreateUser.php`](app/Filament/Resources/UserResource/Pages/CreateUser.php)
        *   Edit: [`app/Filament/Resources/UserResource/Pages/EditUser.php`](app/Filament/Resources/UserResource/Pages/EditUser.php)
*   **Sub-Feature: Roles**
    *   **Filament Resource**: [`app/Filament/Resources/RoleResource.php`](app/Filament/Resources/RoleResource.php)
    *   **Model**: `Spatie\Permission\Models\Role` (from Spatie Laravel Permission package)
    *   **Filament Pages (Views)**:
        *   List: [`app/Filament/Resources/RoleResource/Pages/ListRoles.php`](app/Filament/Resources/RoleResource/Pages/ListRoles.php)
        *   Create: [`app/Filament/Resources/RoleResource/Pages/CreateRole.php`](app/Filament/Resources/RoleResource/Pages/CreateRole.php)
        *   Edit: [`app/Filament/Resources/RoleResource/Pages/EditRole.php`](app/Filament/Resources/RoleResource/Pages/EditRole.php)
*   **Sub-Feature: Permissions**
    *   **Filament Resource**: [`app/Filament/Resources/PermissionResource.php`](app/Filament/Resources/PermissionResource.php)
    *   **Model**: `Spatie\Permission\Models\Permission` (from Spatie Laravel Permission package)
    *   **Filament Pages (Views)**:
        *   List: [`app/Filament/Resources/PermissionResource/Pages/ListPermissions.php`](app/Filament/Resources/PermissionResource/Pages/ListPermissions.php)
        *   Create: [`app/Filament/Resources/PermissionResource/Pages/CreatePermission.php`](app/Filament/Resources/PermissionResource/Pages/CreatePermission.php)
        *   Edit: [`app/Filament/Resources/PermissionResource/Pages/EditPermission.php`](app/Filament/Resources/PermissionResource/Pages/EditPermission.php)