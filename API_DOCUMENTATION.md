# PSGC API Documentation

## Quick Start Examples

### Get All Regions
```bash
curl -X GET "http://localhost:8000/api/v1/regions"
```

### Get All Provinces
```bash
curl -X GET "http://localhost:8000/api/v1/provinces"
```

### Get All Cities/Municipalities
```bash
curl -X GET "http://localhost:8000/api/v1/city-municipalities"
```

### Get All Barangays
```bash
curl -X GET "http://localhost:8000/api/v1/barangays"
```

## Pagination

All endpoints support pagination with the following parameters:
- `page` - Page number (default: 1)
- `per_page` - Items per page (default: 50, max: 100)

### Examples

```bash
# Get second page of provinces
curl -X GET "http://localhost:8000/api/v1/provinces?page=2"

# Get 25 items per page
curl -X GET "http://localhost:8000/api/v1/provinces?per_page=25"

# Get specific page with custom per_page
curl -X GET "http://localhost:8000/api/v1/barangays?page=5&per_page=100"
```

## Filtering

### Get Specific Record by ID
```bash
# Get region with ID 1
curl -X GET "http://localhost:8000/api/v1/regions/1"

# Get province with ID 5
curl -X GET "http://localhost:8000/api/v1/provinces/5"
```

## Sample Responses

### Region Response
```json
{
  "id": 19,
  "code": "1300000000",
  "name": "National Capital Region (NCR)",
  "short_name": "13",
  "island_group": null,
  "status": "active",
  "deleted_at": null,
  "created_at": "2025-10-06T05:14:41.000000Z",
  "updated_at": "2025-10-06T05:14:41.000000Z"
}
```

### Province Response
```json
{
  "id": 1,
  "code": "1400100000",
  "name": "Abra",
  "region_id": 20,
  "old_name": null,
  "status": "active",
  "deleted_at": null,
  "created_at": "2025-10-06T05:14:41.000000Z",
  "updated_at": "2025-10-06T05:14:41.000000Z"
}
```

### City/Municipality Response
```json
{
  "id": 1,
  "code": "1705301000",
  "name": "Aborlan",
  "province_id": 91,
  "region_id": 25,
  "type": "Municipality",
  "income_class": null,
  "urban_rural": null,
  "old_name": null,
  "status": "active",
  "deleted_at": null,
  "created_at": "2025-10-06T05:14:41.000000Z",
  "updated_at": "2025-10-06T05:14:41.000000Z"
}
```

### Barangay Response
```json
{
  "id": 1,
  "code": "1004210051",
  "name": "50th District",
  "city_municipality_id": 435,
  "province_id": 81,
  "region_id": 32,
  "old_name": null,
  "status": "active",
  "deleted_at": null,
  "created_at": "2025-10-06T05:14:47.000000Z",
  "updated_at": "2025-10-06T05:14:47.000000Z"
}
```

## Error Responses

### 404 Not Found
```json
{
  "message": "No query results for model [App\\Models\\Region] 999"
}
```

### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "name": ["The name field is required."]
  }
}
```

## HTTP Status Codes

- `200` - Success
- `201` - Created
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## Rate Limiting

Currently, there are no rate limits implemented. However, please use the API responsibly.

## Data Relationships

The API maintains hierarchical relationships:
- **Regions** contain **Provinces**
- **Provinces** contain **Cities/Municipalities**
- **Cities/Municipalities** contain **Barangays**

You can use the foreign key IDs to establish these relationships in your applications.

## PSGC Code Format

All PSGC codes follow the official format:
- **Regions**: 10 digits (e.g., "1300000000")
- **Provinces**: 10 digits (e.g., "1400100000")
- **Cities/Municipalities**: 10 digits (e.g., "1705301000")
- **Barangays**: 10 digits (e.g., "1004210051")

The codes are structured hierarchically, where each level represents a different administrative division.

## 📤 Data Export

You can export the PSGC data from the database using the export command:

```bash
# Export all data to CSV
php artisan psgc:export

# Export to JSON format
php artisan psgc:export --format=json

# Export specific data types
php artisan psgc:export --regions --provinces

# Export to custom directory
php artisan psgc:export --output=my-backup
```

## 🙏 Acknowledgments

This API is powered by:
- **[@jobuntux/psgc](https://www.npmjs.com/package/@jobuntux/psgc)** - Official PSGC data package
- **Philippine Statistics Authority (PSA)** - Official data source
- **Edeeson Opina** - API Developer ([https://edeesonopina.vercel.app/](https://edeesonopina.vercel.app/))

---

**Developed by**: [Edeeson Opina](https://edeesonopina.vercel.app/) - Creating reliable APIs for Philippine digital integration.
