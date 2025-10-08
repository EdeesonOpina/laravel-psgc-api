# PSGC API Philippines

A comprehensive REST API for the Philippine Standard Geographic Code (PSGC) data, providing access to official administrative divisions of the Philippines including regions, provinces, cities/municipalities, and barangays.

## 📦 Laravel Package (Recommended)

**For existing Laravel projects** - Add PSGC functionality to your project:

```bash
composer require edeesonopina/laravel-psgc-api
```

### Quick Setup
```bash
# Install the package
composer require edeesonopina/laravel-psgc-api

# Run setup script (optional - handles everything automatically)
node vendor/edeesonopina/laravel-psgc-api/scripts/install-package.js

# Start using the API
php artisan serve
# API available at: http://localhost:8000/api/v1/regions
```

### Manual Setup
```bash
# Run migrations
php artisan migrate

# Import PSGC data
php artisan psgc:import --regions=database/psgc/data/regions.csv --provinces=database/psgc/data/provinces.csv --city_municipalities=database/psgc/data/city_municipalities.csv --barangays=database/psgc/data/barangays.csv
```

### Customization Options

**Publish Controllers** (to customize API logic):
```bash
php artisan vendor:publish --provider="EdeesonOpina\PsgcApi\Providers\PsgcApiServiceProvider" --tag="psgc-controllers"
```

**Publish Routes** (to customize API endpoints):
```bash
php artisan vendor:publish --provider="EdeesonOpina\PsgcApi\Providers\PsgcApiServiceProvider" --tag="psgc-routes"
```

**Publish Models** (to add custom relationships):
```bash
php artisan vendor:publish --provider="EdeesonOpina\PsgcApi\Providers\PsgcApiServiceProvider" --tag="psgc-models"
```

**Package Documentation**: [PACKAGE_README.md](PACKAGE_README.md) | [Installation Guide](INSTALLATION_GUIDE.md)

---

## 🚀 Features

- **Official PSGC Data**: Uses the latest official PSGC data from the Philippine Statistics Authority (PSA) 2025 2Q
- **Complete Coverage**: All 18 regions, 115 provinces, 1,656 cities/municipalities, and 42,011 barangays
- **RESTful API**: Clean, intuitive REST endpoints with pagination
- **Laravel Framework**: Built with Laravel 11 for reliability and performance
- **Public Access**: Free API for public use and digital integration

## 📋 Requirements

### System Requirements
- **PHP**: 8.1 or higher
- **Laravel**: 9.x, 10.x, 11.x, or 12.x
- **Database**: MySQL, PostgreSQL, SQLite, or SQL Server
- **Node.js**: 16.x or higher (for installation script)

### PHP Extensions
- `ext-pdo` - Database connectivity
- `ext-mbstring` - String handling
- `ext-openssl` - Security features
- `ext-tokenizer` - Code parsing
- `ext-xml` - XML processing
- `ext-ctype` - Character type checking
- `ext-json` - JSON processing
- `ext-bcmath` - Arbitrary precision mathematics

### Laravel Package Requirements
- `illuminate/support`: ^9.0|^10.0|^11.0|^12.0
- `illuminate/database`: ^9.0|^10.0|^11.0|^12.0
- `illuminate/console`: ^9.0|^10.0|^11.0|^12.0

## 📊 Data Statistics

- **Regions**: 18
- **Provinces**: 115  
- **Cities/Municipalities**: 1,656
- **Barangays**: 42,011
- **Total Records**: 43,800

## 🔗 API Endpoints

### Base URL
```
http://localhost:8000/api/v1
```

**Important**: All endpoint parameters use **database IDs** (not PSGC codes). This makes the API more developer-friendly and consistent with standard REST practices.

### Regions
- **GET** `/regions` - List all regions
- **GET** `/regions/{id}` - Get specific region by database ID

**Query Parameters:**
- `q` - Search by name or code (e.g., `?q=manila`)

### Provinces
- **GET** `/provinces` - List all provinces
- **GET** `/provinces/{id}` - Get specific province by database ID

**Query Parameters:**
- `q` - Search by name or code (e.g., `?q=manila`)
- `region_id` - Filter by region ID (e.g., `?region_id=19`)

### Cities/Municipalities
- **GET** `/city-municipalities` - List all cities and municipalities
- **GET** `/city-municipalities/{id}` - Get specific city/municipality by database ID

**Query Parameters:**
- `q` - Search by name or code (e.g., `?q=manila`)
- `province_id` - Filter by province ID (e.g., `?province_id=86`)
- `region_id` - Filter by region ID (e.g., `?region_id=19`)
- `type` - Filter by type: `City` or `Municipality` (e.g., `?type=City`)
- `limit` - Limit results (max 1000, e.g., `?limit=50`)

### Barangays
- **GET** `/barangays` - List all barangays
- **GET** `/barangays/{id}` - Get specific barangay by database ID

**Query Parameters:**
- `q` - Search by name or code (e.g., `?q=malate`)
- `city_municipality_id` - Filter by city/municipality ID (e.g., `?city_municipality_id=44`)
- `province_id` - Filter by province ID (e.g., `?province_id=86`)
- `region_id` - Filter by region ID (e.g., `?region_id=19`)
- `limit` - Limit results (max 1000, e.g., `?limit=100`)

## 📝 Response Format

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

## 🚀 API Examples

### Get All Regions
```bash
curl http://localhost:8000/api/v1/regions
```

### Get Specific Region
```bash
curl http://localhost:8000/api/v1/regions/19
```

### Get Provinces in NCR
```bash
curl http://localhost:8000/api/v1/provinces?region_id=19
```

### Get Cities in a Province
```bash
curl http://localhost:8000/api/v1/city-municipalities?province_id=86&limit=10
```

### Get Barangays in Manila
```bash
curl http://localhost:8000/api/v1/barangays?city_municipality_id=44&limit=20
```

### Search for Barangays
```bash
curl http://localhost:8000/api/v1/barangays?q=malate&limit=5
```

### Get All Cities (Limited)
```bash
curl http://localhost:8000/api/v1/city-municipalities?type=City&limit=50
```

## 🔄 Migration Guide (v1.0.0 → v1.1.0)

### Breaking Changes

**⚠️ Important**: v1.1.0 contains breaking changes. Existing applications using v1.0.0 will need updates.

#### 1. Endpoint Parameters Changed
```bash
# v1.0.0 (Old)
GET /api/v1/regions/{code}          # Used PSGC codes
GET /api/v1/provinces?region_code=1300000000

# v1.1.0 (New)
GET /api/v1/regions/{id}            # Uses database IDs
GET /api/v1/provinces?region_id=19
```

#### 2. Response Format Changed
```json
// v1.0.0 (Old)
{
  "table": "regions",
  "rows": [...],
  "pagination": {...}
}

// v1.1.0 (New)
[...]  // Direct array, no wrapper
```

#### 3. New Features Added
- **Limit Parameter**: `?limit=50` for cities and barangays
- **Publishable Components**: Controllers, routes, and models can be customized
- **Improved Performance**: No pagination overhead for small datasets

### Migration Steps

1. **Update Package Version**:
   ```bash
   composer require edeesonopina/laravel-psgc-api:^1.1.0
   ```

2. **Update API Calls**:
   - Replace `{code}` with `{id}` in URLs
   - Replace `region_code` with `region_id` in parameters
   - Update response parsing (remove pagination wrapper)

3. **Test Your Application**:
   - Verify all API endpoints work with new format
   - Update frontend code to handle direct JSON arrays

### Staying on v1.0.0

If you prefer to stay on the stable v1.0.0:
```bash
composer require edeesonopina/laravel-psgc-api:1.0.0
```

## 🛠️ Standalone Project Setup

**For new Laravel projects** - Clone and set up from scratch:

### Prerequisites
- PHP 8.2+
- Composer
- MySQL/SQLite
- Node.js (for data conversion)

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/EdeesonOpina/laravel-psgc-api.git
   cd laravel-psgc-api
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   ```

6. **Import PSGC data**
   ```bash
   # Generate CSV files from official PSGC data
   node scripts/convert_psgc_to_csv.js
   
   # Import the data
   php artisan psgc:import --regions=database/psgc/data/regions.csv --provinces=database/psgc/data/provinces.csv --city_municipalities=database/psgc/data/city_municipalities.csv --barangays=database/psgc/data/barangays.csv
   ```

7. **Start the server**
   ```bash
   php artisan serve --port=8000
   ```

## 📋 Data Import & Export Commands

### Import Command

The `psgc:import` command provides several options:

```bash
php artisan psgc:import [options]

Options:
  --regions=FILE              Path to regions CSV file
  --provinces=FILE            Path to provinces CSV file
  --city_municipalities=FILE  Path to city/municipalities CSV file
  --barangays=FILE            Path to barangays CSV file
  --truncate                  Truncate all PSGC tables before import
  --dry-run                   Validate only (no DB commit)
```

### Examples

```bash
# Import all data
php artisan psgc:import --regions=database/psgc/data/regions.csv --provinces=database/psgc/data/provinces.csv --city_municipalities=database/psgc/data/city_municipalities.csv --barangays=database/psgc/data/barangays.csv

# Dry run (validate only)
php artisan psgc:import --regions=database/psgc/data/regions.csv --dry-run

# Truncate and re-import
php artisan psgc:import --truncate --regions=database/psgc/data/regions.csv --provinces=database/psgc/data/provinces.csv --city_municipalities=database/psgc/data/city_municipalities.csv --barangays=database/psgc/data/barangays.csv
```

### Export Command

The `psgc:export` command allows you to export data from the database back to CSV or JSON files:

```bash
php artisan psgc:export [options]

Options:
  --output=DIR              Output directory (default: exports)
  --regions                 Export regions only
  --provinces               Export provinces only
  --city_municipalities     Export cities/municipalities only
  --barangays               Export barangays only
  --all                     Export all data (default)
  --format=FORMAT           Export format: csv or json (default: csv)
  --status=STATUS           Filter by status: active, inactive, or all (default: active)
```

#### Export Examples

```bash
# Export all data to CSV
php artisan psgc:export

# Export all data to JSON
php artisan psgc:export --format=json

# Export only regions to custom directory
php artisan psgc:export --regions --output=backup

# Export provinces and cities to JSON
php artisan psgc:export --provinces --city_municipalities --format=json

# Export all data including inactive records
php artisan psgc:export --status=all

# Export to specific directory
php artisan psgc:export --output=my-exports --format=csv
```

## 🗄️ Database Schema

### Regions Table
- `id` - Primary key
- `code` - PSGC code (10 characters)
- `name` - Region name
- `short_name` - Short region code
- `island_group` - Island group (Luzon/Visayas/Mindanao)
- `status` - Status (active/inactive)
- `deleted_at` - Soft delete timestamp
- `created_at` - Creation timestamp
- `updated_at` - Update timestamp

### Provinces Table
- `id` - Primary key
- `code` - PSGC code (10 characters)
- `name` - Province name
- `region_id` - Foreign key to regions table
- `old_name` - Previous name (if any)
- `status` - Status (active/inactive)
- `deleted_at` - Soft delete timestamp
- `created_at` - Creation timestamp
- `updated_at` - Update timestamp

### City/Municipalities Table
- `id` - Primary key
- `code` - PSGC code (10 characters)
- `name` - City/Municipality name
- `province_id` - Foreign key to provinces table
- `region_id` - Foreign key to regions table
- `type` - Type (City/Municipality)
- `income_class` - Income classification
- `urban_rural` - Urban/Rural classification
- `old_name` - Previous name (if any)
- `status` - Status (active/inactive)
- `deleted_at` - Soft delete timestamp
- `created_at` - Creation timestamp
- `updated_at` - Update timestamp

### Barangays Table
- `id` - Primary key
- `code` - PSGC code (10 characters)
- `name` - Barangay name
- `city_municipality_id` - Foreign key to city_municipalities table
- `province_id` - Foreign key to provinces table
- `region_id` - Foreign key to regions table
- `old_name` - Previous name (if any)
- `status` - Status (active/inactive)
- `deleted_at` - Soft delete timestamp
- `created_at` - Creation timestamp
- `updated_at` - Update timestamp

## 🔄 Data Updates

The PSGC data is updated quarterly by the Philippine Statistics Authority. To update your local data:

1. **Download latest data**
   ```bash
   npm install @jobuntux/psgc@latest
   ```

2. **Regenerate CSV files**
   ```bash
   node scripts/convert_psgc_to_csv.js
   ```

3. **Import updated data**
   ```bash
   php artisan psgc:import --truncate --regions=database/psgc/data/regions.csv --provinces=database/psgc/data/provinces.csv --city_municipalities=database/psgc/data/city_municipalities.csv --barangays=database/psgc/data/barangays.csv
   ```

## 🙏 Acknowledgments

This project would not be possible without the following contributors and resources:

### Data Source & Libraries
- **[@jobuntux/psgc](https://www.npmjs.com/package/@jobuntux/psgc)** - TypeScript-ready NPM package providing up-to-date PSGC data, updated quarterly in sync with PSA's official releases
- **Philippine Statistics Authority (PSA)** - Official source of PSGC data and classifications
- **Laravel Framework** - Robust PHP framework for building reliable APIs

### Development Team
- **Edeeson Opina** - Full Stack Web Developer & Project Lead
  - Website: [https://edeesonopina.vercel.app/](https://edeesonopina.vercel.app/)
  - Responsible for API architecture, data integration, and system implementation

### Special Thanks
- The Philippine Statistics Authority for maintaining and providing official PSGC data
- The open-source community for tools and libraries that made this project possible
- All contributors who help maintain the PSGC data accuracy and accessibility

## 🤝 Contributing

This API is designed for public use and digital integration. Contributions are welcome!

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 📞 Support

For questions or support, please open an issue in the repository or contact the development team.

## 🏛️ Data Source

This API uses official PSGC data from the **Philippine Statistics Authority (PSA)**:
- **Source**: PSA PSGC 2025 2Q
- **Update Frequency**: Quarterly
- **Official Website**: [psa.gov.ph/classification/psgc](https://psa.gov.ph/classification/psgc/Philippine)
- **Data Package**: Powered by [@jobuntux/psgc](https://www.npmjs.com/package/@jobuntux/psgc)

---

**Note**: This API provides the latest official PSGC data for digital integration and public use. The data is maintained according to PSA standards and updated regularly to reflect administrative changes in the Philippines.