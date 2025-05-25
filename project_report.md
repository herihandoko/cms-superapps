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
    *   [`app/Models/`](app/Models/): (Implicitly, Laravel 8+ style, models might be directly in `app/` or a dedicated `app/Models` directory if created by the developer).
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