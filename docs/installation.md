# Installation Guide

## Prerequisites

Before installing Laravel H5P, ensure you have:

- PHP 8.2 or higher
- Laravel 11 or 12
- PostgreSQL or MySQL database
- Composer

## Step-by-Step Installation

### 1. Add Package to Composer

```bash
composer require djoudi/laravel-h5p
```

### 2. Run Installation Command

```bash
php artisan h5p:install
```

**Options:**
- `--force` - Overwrite existing files
- `--skip-migrations` - Skip database migrations

### 3. Verify Installation

```bash
php artisan h5p:status
```

This shows:
- Database tables status
- Statistics (libraries, content, results)
- Directory permissions
- LRS integration status

## Queue Configuration (Required for xAPI)

### 1. Choose Queue Driver

In `.env`:
```env
QUEUE_CONNECTION=database
```

### 2. Create Queue Tables

```bash
php artisan queue:table
php artisan migrate
```

### 3. Run Queue Worker

```bash
php artisan queue:work
```

For production, use Supervisor to keep the worker running.

## Troubleshooting

### Assets Not Loading

```bash
php artisan h5p:publish --assets --force
```

### Permission Issues

```bash
chmod -R 775 storage/app/public/h5p
chown -R www-data:www-data storage/app/public/h5p
```

### Clear Caches

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```
