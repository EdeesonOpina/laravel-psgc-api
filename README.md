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
php artisan psgc:import --regions=data/regions.csv --provinces=data/provinces.csv --city_municipalities=data/city_municipalities.csv --barangays=data/barangays.csv
```

**Package Documentation**: [PACKAGE_README.md](PACKAGE_README.md) | [Installation Guide](INSTALLATION_GUIDE.md)

---

## 🚀 Features

- **Official PSGC Data**: Uses the latest official PSGC data from the Philippine Statistics Authority (PSA) 2025 2Q
- **Complete Coverage**: All 18 regions, 115 provinces, 1,656 cities/municipalities, and 42,011 barangays
- **RESTful API**: Clean, intuitive REST endpoints with pagination
- **Laravel Framework**: Built with Laravel 11 for reliability and performance
- **Public Access**: Free API for public use and digital integration

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

### Regions
- **GET** `/regions` - List all regions
- **GET** `/regions/{id}` - Get specific region
- **POST** `/regions` - Create new region
- **PUT/PATCH** `/regions/{id}` - Update region
- **DELETE** `/regions/{id}` - Delete region

### Provinces
- **GET** `/provinces` - List all provinces
- **GET** `/provinces/{id}` - Get specific province
- **POST** `/provinces` - Create new province
- **PUT/PATCH** `/provinces/{id}` - Update province
- **DELETE** `/provinces/{id}` - Delete province

### Cities/Municipalities
- **GET** `/city-municipalities` - List all cities and municipalities
- **GET** `/city-municipalities/{id}` - Get specific city/municipality
- **POST** `/city-municipalities` - Create new city/municipality
- **PUT/PATCH** `/city-municipalities/{id}` - Update city/municipality
- **DELETE** `/city-municipalities/{id}` - Delete city/municipality

### Barangays
- **GET** `/barangays` - List all barangays
- **GET** `/barangays/{id}` - Get specific barangay
- **POST** `/barangays` - Create new barangay
- **PUT/PATCH** `/barangays/{id}` - Update barangay
- **DELETE** `/barangays/{id}` - Delete barangay

## 📝 Response Format

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
   php artisan psgc:import --regions=data/regions.csv --provinces=data/provinces.csv --city_municipalities=data/city_municipalities.csv --barangays=data/barangays.csv
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
php artisan psgc:import --regions=data/regions.csv --provinces=data/provinces.csv --city_municipalities=data/city_municipalities.csv --barangays=data/barangays.csv

# Dry run (validate only)
php artisan psgc:import --regions=data/regions.csv --dry-run

# Truncate and re-import
php artisan psgc:import --truncate --regions=data/regions.csv --provinces=data/provinces.csv --city_municipalities=data/city_municipalities.csv --barangays=data/barangays.csv
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
   php artisan psgc:import --truncate --regions=data/regions.csv --provinces=data/provinces.csv --city_municipalities=data/city_municipalities.csv --barangays=data/barangays.csv
   ```

## 🙏 Acknowledgments

This project would not be possible without the following contributors and resources:

### Data Source & Libraries
- **[@jobuntux/psgc](https://www.npmjs.com/package/@jobuntux/psgc)** - TypeScript-ready NPM package providing up-to-date PSGC data, updated quarterly in sync with PSA's official releases
- **Philippine Statistics Authority (PSA)** - Official source of PSGC data and classifications
- **Laravel Framework** - Robust PHP framework for building reliable APIs

### Development Team
- **Edeeson Opina** - Project Lead & Developer
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