# PSGC API Package for Laravel

[![Latest Version](https://img.shields.io/badge/version-1.1.0-blue.svg)](https://packagist.org/packages/edeesonopina/laravel-psgc-api)
[![Laravel](https://img.shields.io/badge/Laravel-9.x%20%7C%2010.x%20%7C%2011.x%20%7C%2012.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

A comprehensive Laravel package for the Philippine Standard Geographic Code (PSGC) API, providing official PSA data with import/export functionality and customizable components.

## 🚀 Quick Installation

```bash
composer require edeesonopina/laravel-psgc-api
```

That's it! The package will automatically:
- ✅ Publish migrations
- ✅ Publish configuration
- ✅ Register commands
- ✅ Set up API routes

## 🆕 What's New in v1.1.0

- **🎯 Developer-Friendly**: Uses database IDs instead of PSGC codes
- **⚡ Better Performance**: Direct JSON responses, no pagination overhead
- **🔧 Customizable**: Publish controllers, routes, and models for customization
- **📊 Smart Limiting**: `limit` parameter for large datasets (cities/barangays)
- **🔄 Migration Guide**: Clear upgrade path from v1.0.0

## 📊 What You Get

- **18 Regions** - All Philippine regions
- **115 Provinces** - Complete province data
- **1,656 Cities/Municipalities** - All cities and municipalities
- **42,011 Barangays** - Complete barangay data
- **REST API** - Ready-to-use endpoints
- **Import/Export** - Data management commands
- **Official Data** - Latest PSA PSGC 2025 2Q

## 🛠️ Setup & Usage

### 1. Install Package
```bash
composer require edeesonopina/laravel-psgc-api
```

### 2. Run Migrations
```bash
php artisan migrate
```

### 3. Import PSGC Data
```bash
# The package includes a data conversion script
npm install @jobuntux/psgc
node vendor/edeesonopina/laravel-psgc-api/scripts/convert_psgc_to_csv.js

# Import the data
php artisan psgc:import --regions=database/psgc/data/regions.csv --provinces=database/psgc/data/provinces.csv --city_municipalities=database/psgc/data/city_municipalities.csv --barangays=database/psgc/data/barangays.csv
```

### 4. Start Using the API
```bash
php artisan serve
```

Your API is now available at:
- `GET /api/v1/regions`
- `GET /api/v1/provinces`
- `GET /api/v1/city-municipalities`
- `GET /api/v1/barangays`

## 📋 Available Commands

### Import Data
```bash
# Import all data
php artisan psgc:import --regions=database/psgc/data/regions.csv --provinces=database/psgc/data/provinces.csv --city_municipalities=database/psgc/data/city_municipalities.csv --barangays=database/psgc/data/barangays.csv

# Dry run (validate only)
php artisan psgc:import --regions=database/psgc/data/regions.csv --dry-run

# Truncate and re-import
php artisan psgc:import --truncate --regions=database/psgc/data/regions.csv --provinces=database/psgc/data/provinces.csv --city_municipalities=database/psgc/data/city_municipalities.csv --barangays=database/psgc/data/barangays.csv
```

### Export Data
```bash
# Export all data to CSV
php artisan psgc:export

# Export to JSON
php artisan psgc:export --format=json

# Export specific data types
php artisan psgc:export --regions --provinces

# Export to custom directory
php artisan psgc:export --output=my-backup
```

## 🔧 Configuration

Publish the configuration file:
```bash
php artisan vendor:publish --provider="EdeesonOpina\PsgcApi\PsgcApiServiceProvider" --tag="psgc-config"
```

### Environment Variables
```env
# API Settings
PSGC_API_PREFIX=api/v1
PSGC_MIDDLEWARE=api

# Database Settings
PSGC_TABLE_PREFIX=
PSGC_REGIONS_TABLE=regions
PSGC_PROVINCES_TABLE=provinces
PSGC_CITY_MUNICIPALITIES_TABLE=city_municipalities
PSGC_BARANGAYS_TABLE=barangays

# Cache Settings
PSGC_CACHE_ENABLED=true
PSGC_CACHE_TTL=3600
PSGC_CACHE_PREFIX=psgc

# Rate Limiting
PSGC_RATE_LIMIT_ENABLED=false
PSGC_RATE_LIMIT_MAX_ATTEMPTS=60
PSGC_RATE_LIMIT_DECAY_MINUTES=1
```

## 🎨 Customization Options

### Publish Controllers (Customize API Logic)
```bash
php artisan vendor:publish --provider="EdeesonOpina\PsgcApi\Providers\PsgcApiServiceProvider" --tag="psgc-controllers"
```
This publishes controllers to `app/Http/Controllers/Psgc/` where you can customize:
- Response formats
- Additional validation
- Custom business logic
- Authentication/authorization

### Publish Routes (Customize API Endpoints)
```bash
php artisan vendor:publish --provider="EdeesonOpina\PsgcApi\Providers\PsgcApiServiceProvider" --tag="psgc-routes"
```
This publishes routes to `routes/psgc-api.php` where you can:
- Change endpoint URLs
- Add middleware
- Create custom routes
- Modify route groups

### Publish Models (Add Custom Relationships)
```bash
php artisan vendor:publish --provider="EdeesonOpina\PsgcApi\Providers\PsgcApiServiceProvider" --tag="psgc-models"
```
This publishes models to `app/Models/` where you can:
- Add custom relationships
- Add accessors/mutators
- Add scopes
- Extend functionality

### Example: Custom Controller
```php
// app/Http/Controllers/Psgc/RegionController.php
<?php

namespace App\Http\Controllers\Psgc;

use EdeesonOpina\PsgcApi\Http\Controllers\Psgc\Controller;
use Illuminate\Http\Request;
use EdeesonOpina\PsgcApi\Models\Region;

class RegionController extends Controller
{
    public function index(Request $request)
    {
        // Your custom logic here
        $regions = Region::with('provinces')->get();
        
        return response()->json([
            'success' => true,
            'data' => $regions,
            'meta' => [
                'total' => $regions->count(),
                'timestamp' => now()
            ]
        ]);
    }
}
```

## 📚 API Endpoints

### Regions
- `GET /api/v1/regions` - List all regions
- `GET /api/v1/regions/{id}` - Get specific region by database ID

**Query Parameters:**
- `q` - Search by name or code (e.g., `?q=manila`)

### Provinces
- `GET /api/v1/provinces` - List all provinces
- `GET /api/v1/provinces/{id}` - Get specific province by database ID

**Query Parameters:**
- `q` - Search by name or code (e.g., `?q=manila`)
- `region_id` - Filter by region ID (e.g., `?region_id=19`)

### Cities/Municipalities
- `GET /api/v1/city-municipalities` - List all cities and municipalities
- `GET /api/v1/city-municipalities/{id}` - Get specific city/municipality by database ID

**Query Parameters:**
- `q` - Search by name or code (e.g., `?q=manila`)
- `province_id` - Filter by province ID (e.g., `?province_id=86`)
- `region_id` - Filter by region ID (e.g., `?region_id=19`)
- `type` - Filter by type: `City` or `Municipality` (e.g., `?type=City`)
- `limit` - Limit results (max 1000, e.g., `?limit=50`)

### Barangays
- `GET /api/v1/barangays` - List all barangays
- `GET /api/v1/barangays/{id}` - Get specific barangay by database ID

**Query Parameters:**
- `q` - Search by name or code (e.g., `?q=malate`)
- `city_municipality_id` - Filter by city/municipality ID (e.g., `?city_municipality_id=44`)
- `province_id` - Filter by province ID (e.g., `?province_id=86`)
- `region_id` - Filter by region ID (e.g., `?region_id=19`)
- `limit` - Limit results (max 1000, e.g., `?limit=100`)

## 📄 Response Format

All endpoints return JSON responses directly (no pagination wrapper):

```json
[
  {
    "id": 19,
    "code": "1300000000",
    "name": "National Capital Region (NCR)",
    "short_name": "13",
    "island_group": "",
    "status": "active",
    "deleted_at": null,
    "created_at": "2025-10-06T05:14:41.000000Z",
    "updated_at": "2025-10-06T05:14:41.000000Z"
  }
]
```

## 🔄 Data Updates

The PSGC data is updated quarterly by the Philippine Statistics Authority. To update your data:

```bash
# Update the data package
npm install @jobuntux/psgc@latest

# Regenerate CSV files
node vendor/edeesonopina/laravel-psgc-api/scripts/convert_psgc_to_csv.js

# Import updated data
php artisan psgc:import --truncate --regions=database/psgc/data/regions.csv --provinces=database/psgc/data/provinces.csv --city_municipalities=database/psgc/data/city_municipalities.csv --barangays=database/psgc/data/barangays.csv
```

## 🙏 Acknowledgments

This package is powered by:
- **[@jobuntux/psgc](https://www.npmjs.com/package/@jobuntux/psgc)** - Official PSGC data package
- **Philippine Statistics Authority (PSA)** - Official data source
- **Edeeson Opina** - Full Stack Web Developer ([https://edeesonopina.vercel.app/](https://edeesonopina.vercel.app/))

## 📄 License

This package is open source and available under the [MIT License](LICENSE).

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📞 Support

For questions or support, please open an issue in the repository or contact the developer.

---

**Developed by**: [Edeeson Opina](https://edeesonopina.vercel.app/) - Creating reliable APIs for Philippine digital integration.
