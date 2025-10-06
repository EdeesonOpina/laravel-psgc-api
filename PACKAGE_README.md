# PSGC API Package for Laravel

[![Latest Version](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://packagist.org/packages/edeeson/psgc-api)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

A comprehensive Laravel package for the Philippine Standard Geographic Code (PSGC) API, providing official PSA data with import/export functionality.

## 🚀 Quick Installation

```bash
composer require edeesonopina/laravel-psgc-api
```

That's it! The package will automatically:
- ✅ Publish migrations
- ✅ Publish configuration
- ✅ Register commands
- ✅ Set up API routes

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
php artisan psgc:import --regions=data/regions.csv --provinces=data/provinces.csv --city_municipalities=data/city_municipalities.csv --barangays=data/barangays.csv
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
php artisan psgc:import --regions=data/regions.csv --provinces=data/provinces.csv --city_municipalities=data/city_municipalities.csv --barangays=data/barangays.csv

# Dry run (validate only)
php artisan psgc:import --regions=data/regions.csv --dry-run

# Truncate and re-import
php artisan psgc:import --truncate --regions=data/regions.csv --provinces=data/provinces.csv --city_municipalities=data/city_municipalities.csv --barangays=data/barangays.csv
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

## 📚 API Endpoints

### Regions
- `GET /api/v1/regions` - List all regions
- `GET /api/v1/regions/{id}` - Get specific region
- `POST /api/v1/regions` - Create new region
- `PUT/PATCH /api/v1/regions/{id}` - Update region
- `DELETE /api/v1/regions/{id}` - Delete region

### Provinces
- `GET /api/v1/provinces` - List all provinces
- `GET /api/v1/provinces/{id}` - Get specific province
- `POST /api/v1/provinces` - Create new province
- `PUT/PATCH /api/v1/provinces/{id}` - Update province
- `DELETE /api/v1/provinces/{id}` - Delete province

### Cities/Municipalities
- `GET /api/v1/city-municipalities` - List all cities and municipalities
- `GET /api/v1/city-municipalities/{id}` - Get specific city/municipality
- `POST /api/v1/city-municipalities` - Create new city/municipality
- `PUT/PATCH /api/v1/city-municipalities/{id}` - Update city/municipality
- `DELETE /api/v1/city-municipalities/{id}` - Delete city/municipality

### Barangays
- `GET /api/v1/barangays` - List all barangays
- `GET /api/v1/barangays/{id}` - Get specific barangay
- `POST /api/v1/barangays` - Create new barangay
- `PUT/PATCH /api/v1/barangays/{id}` - Update barangay
- `DELETE /api/v1/barangays/{id}` - Delete barangay

## 📄 Response Format

All endpoints return JSON responses with pagination:

```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "code": "1300000000",
      "name": "National Capital Region (NCR)",
      "short_name": "13",
      "island_group": null,
      "status": "active",
      "deleted_at": null,
      "created_at": "2025-10-06T05:14:41.000000Z",
      "updated_at": "2025-10-06T05:14:41.000000Z"
    }
  ],
  "first_page_url": "http://localhost:8000/api/v1/regions?page=1",
  "from": 1,
  "last_page": 1,
  "last_page_url": "http://localhost:8000/api/v1/regions?page=1",
  "links": [...],
  "next_page_url": null,
  "path": "http://localhost:8000/api/v1/regions",
  "per_page": 50,
  "prev_page_url": null,
  "to": 18,
  "total": 18
}
```

## 🔄 Data Updates

The PSGC data is updated quarterly by the Philippine Statistics Authority. To update your data:

```bash
# Update the data package
npm install @jobuntux/psgc@latest

# Regenerate CSV files
node vendor/edeesonopina/laravel-psgc-api/scripts/convert_psgc_to_csv.js

# Import updated data
php artisan psgc:import --truncate --regions=data/regions.csv --provinces=data/provinces.csv --city_municipalities=data/city_municipalities.csv --barangays=data/barangays.csv
```

## 🙏 Acknowledgments

This package is powered by:
- **[@jobuntux/psgc](https://www.npmjs.com/package/@jobuntux/psgc)** - Official PSGC data package
- **Philippine Statistics Authority (PSA)** - Official data source
- **Edeeson Opina** - Package Developer ([https://edeesonopina.vercel.app/](https://edeesonopina.vercel.app/))

## 📄 License

This package is open source and available under the [MIT License](LICENSE).

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📞 Support

For questions or support, please open an issue in the repository or contact the developer.

---

**Developed by**: [Edeeson Opina](https://edeesonopina.vercel.app/) - Creating reliable APIs for Philippine digital integration.
