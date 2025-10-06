# PSGC API Package Installation Guide

## 🚀 Quick Start (Recommended)

### 1. Install the Package
```bash
composer require edeesonopina/laravel-psgc-api
```

### 2. Run the Setup Script
```bash
node vendor/edeesonopina/laravel-psgc-api/scripts/install-package.js
```

That's it! Your PSGC API is now ready to use.

## 📋 Manual Installation

If you prefer to set up manually or the automatic script fails:

### 1. Install Package
```bash
composer require edeesonopina/laravel-psgc-api
```

### 2. Publish Assets
```bash
# Publish migrations
php artisan vendor:publish --provider="EdeesonOpina\PsgcApi\PsgcApiServiceProvider" --tag="psgc-migrations"

# Publish configuration (optional)
php artisan vendor:publish --provider="EdeesonOpina\PsgcApi\PsgcApiServiceProvider" --tag="psgc-config"
```

### 3. Install Data Package
```bash
npm install @jobuntux/psgc
```

### 4. Convert Data to CSV
```bash
# Copy the conversion script
cp vendor/edeesonopina/laravel-psgc-api/scripts/convert_psgc_to_csv.js data/

# Run the conversion
node data/convert_psgc_to_csv.js
```

### 5. Run Migrations
```bash
php artisan migrate
```

### 6. Import PSGC Data
```bash
php artisan psgc:import --regions=data/regions.csv --provinces=data/provinces.csv --city_municipalities=data/city_municipalities.csv --barangays=data/barangays.csv
```

### 7. Start the Server
```bash
php artisan serve
```

## 🧪 Test Your Installation

### Test API Endpoints
```bash
# Test regions endpoint
curl http://localhost:8000/api/v1/regions

# Test provinces endpoint
curl http://localhost:8000/api/v1/provinces

# Test cities/municipalities endpoint
curl http://localhost:8000/api/v1/city-municipalities

# Test barangays endpoint
curl http://localhost:8000/api/v1/barangays
```

### Test Commands
```bash
# Test import command
php artisan psgc:import --help

# Test export command
php artisan psgc:export --help

# Test export functionality
php artisan psgc:export --regions --format=json
```

## 🔧 Configuration

### Environment Variables
Add these to your `.env` file:

```env
# API Settings
PSGC_API_PREFIX=api/v1
PSGC_MIDDLEWARE=api

# Database Settings (optional)
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

### Custom Configuration
Publish the configuration file to customize settings:

```bash
php artisan vendor:publish --provider="EdeesonOpina\PsgcApi\PsgcApiServiceProvider" --tag="psgc-config"
```

This will create `config/psgc.php` where you can modify all package settings.

## 🗄️ Database Schema

The package creates the following tables:
- `regions` - Philippine regions
- `provinces` - Philippine provinces
- `city_municipalities` - Cities and municipalities
- `barangays` - Barangays

All tables include:
- Soft deletes
- Timestamps
- Proper foreign key relationships
- Indexes for performance

## 📊 Data Statistics

After installation, you should have:
- **18 Regions**
- **115 Provinces**
- **1,656 Cities/Municipalities**
- **42,011 Barangays**
- **Total: 43,800 records**

## 🔄 Updating Data

PSGC data is updated quarterly by the Philippine Statistics Authority. To update:

```bash
# Update the data package
npm install @jobuntux/psgc@latest

# Regenerate CSV files
node data/convert_psgc_to_csv.js

# Import updated data (truncate existing)
php artisan psgc:import --truncate --regions=data/regions.csv --provinces=data/provinces.csv --city_municipalities=data/city_municipalities.csv --barangays=data/barangays.csv
```

## 🚨 Troubleshooting

### Common Issues

#### 1. Migration Errors
```bash
# If migrations fail, try:
php artisan migrate:reset
php artisan migrate
```

#### 2. Import Errors
```bash
# If import fails, check CSV files exist:
ls -la data/

# Regenerate CSV files:
node data/convert_psgc_to_csv.js
```

#### 3. API Not Working
```bash
# Check routes are registered:
php artisan route:list | grep psgc

# Clear cache:
php artisan config:clear
php artisan route:clear
```

#### 4. Node.js Issues
```bash
# If Node.js scripts fail:
npm install @jobuntux/psgc
node --version  # Should be 14+
```

### Getting Help

1. Check the [Package README](PACKAGE_README.md)
2. Review the [API Documentation](API_DOCUMENTATION.md)
3. Open an issue on GitHub
4. Contact the developer: [Edeeson Opina](https://edeesonopina.vercel.app/)

## 🎉 Success!

If everything worked correctly, you should now have:

✅ PSGC API running on `http://localhost:8000/api/v1/`  
✅ All 43,800+ PSGC records imported  
✅ Import/Export commands available  
✅ Full REST API with pagination  
✅ Official PSA data (2025 2Q)  

## 🙏 Acknowledgments

This package is made possible by:
- **[@jobuntux/psgc](https://www.npmjs.com/package/@jobuntux/psgc)** - Data source
- **Philippine Statistics Authority (PSA)** - Official data
- **Edeeson Opina** - Package developer ([https://edeesonopina.vercel.app/](https://edeesonopina.vercel.app/))

---

**Happy coding!** 🚀
