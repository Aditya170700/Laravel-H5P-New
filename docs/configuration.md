# Configuration Guide

## Main Configuration

After installation, configuration is available at `config/laravel-h5p.php`.

### Key Options

```php
return [
    // Storage path for H5P files
    'storage_path' => 'h5p',
    
    // Default language
    'language' => 'en',
    
    // Enable/disable H5P hub
    'h5p_hub_is_enabled' => true,
    
    // Development mode
    'h5p_dev_mode' => env('H5P_DEV_MODE', false),
];
```

## LRS Configuration

For xAPI/LRS integration, create `config/lrs.php` or add to `.env`:

### Environment Variables

```env
LRS_ENABLED=true
LRS_ENDPOINT=https://your-lrs.com/data/xAPI
LRS_USERNAME=your-api-key
LRS_PASSWORD=your-api-secret
```

### Supported LRS Systems

- Learning Locker
- SCORM Cloud
- Watershed
- Any xAPI 1.0.3 compliant LRS

## Route Customization

### Publish Routes

```bash
php artisan vendor:publish --tag=laravel-h5p-routes
```

### Available Route Groups

| Prefix | Description |
|--------|-------------|
| `/h5p` | Library management |
| `/admin/h5p` | Content management |
| `/lesson` | Student content view |
| `/admin/reports` | Reports and analytics |

## View Customization

### Publish Views

```bash
php artisan h5p:publish --views
```

Views will be published to:
```
resources/views/vendor/laravel-h5p/
```

## Middleware

Add middleware in your routes if needed:

```php
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin routes
});
```
