# API Documentation

## Overview
This document provides comprehensive documentation for all API endpoints in the CMS Superapp, including Dashboard API and Dapodik (Data Pokok Pendidikan) API.

## Base URL
```
http://your-domain/api
```

## Authentication
All endpoints require authentication. Include your authentication token in the request header:
```
Authorization: Bearer your-token-here
```

## Common Response Format
All endpoints follow a standard response format:

```json
{
    "success": true,
    "data": {
        // Endpoint specific data
    }
}
```

Error responses follow this format:
```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        // Validation errors if any
    }
}
```

---

# Dashboard API

## Base URL: `/api/dashboard`

### Endpoints:

1. **GET /summary** - Get overall summary statistics
2. **GET /unit-kerja** - Get unit kerja data with pagination
3. **GET /unit-kerja/top** - Get top performing units
4. **GET /activity/last-seen** - Get recent activity updates
5. **GET /status-detail** - Get detailed status breakdown
6. **GET /durasi/rtl** - Get RTL duration statistics
7. **GET /durasi/rhp** - Get RHP duration statistics

### Example Usage:
```bash
# Get dashboard summary
curl -X GET "http://your-domain/api/dashboard/summary" \
-H "Accept: application/json"

# Get unit kerja data
curl -X GET "http://your-domain/api/dashboard/unit-kerja?page=1&per_page=15" \
-H "Accept: application/json"
```

---

# Dapodik API

## Base URL: `/api/dapodik`

### Dashboard & Analytics:
1. **GET /dashboard/summary** - Get comprehensive dashboard summary
2. **GET /dashboard/statistics** - Get detailed statistics breakdown

### Schools:
3. **GET /schools** - List schools with pagination and filtering
4. **GET /schools/{npsn}** - Get school detail by NPSN
5. **GET /schools/nearby** - Find nearby schools by coordinates

### Geographic & Location:
6. **GET /locations/kabupaten** - Get kabupaten list with school counts
7. **GET /locations/kecamatan** - Get kecamatan list (filter by kabupaten)
8. **GET /locations/desa** - Get desa list (filter by kecamatan)

### Analytics:
9. **GET /analytics/students** - Get student analytics by grade/gender
10. **GET /analytics/teachers** - Get teacher and staff analytics
11. **GET /analytics/classrooms** - Get classroom and rombongan belajar analytics

### Accreditation:
12. **GET /accreditation/summary** - Get accreditation statistics
13. **GET /accreditation/expiring** - Get schools with expiring accreditation

### Comparison & Benchmark:
14. **GET /compare/kabupaten** - Compare educational metrics across kabupaten
15. **GET /compare/bentuk-pendidikan** - Compare metrics across education types

### Search & Discovery:
16. **GET /search** - Search schools by name or NPSN
17. **GET /map-data** - Get school data for mapping applications

### Trends & Forecasting:
18. **GET /trends/enrollment** - Get enrollment trends by education type
19. **GET /trends/growth** - Get growth trends by kabupaten

### Export & Health:
20. **GET /export/excel** - Export data to Excel format
21. **GET /health** - Check API health and database connection

### Example Usage:

```bash
# Get Dapodik dashboard summary
curl -X GET "http://your-domain/api/dapodik/dashboard/summary" \
-H "Accept: application/json"

# List schools with filters
curl -X GET "http://your-domain/api/dapodik/schools?kabupaten=Jakarta%20Pusat&bentuk_pendidikan=SD&page=1&per_page=15" \
-H "Accept: application/json"

# Get school detail by NPSN
curl -X GET "http://your-domain/api/dapodik/schools/20100101" \
-H "Accept: application/json"

# Find nearby schools
curl -X GET "http://your-domain/api/dapodik/schools/nearby?lat=-6.2088&lng=106.8456&radius=5" \
-H "Accept: application/json"

# Search schools
curl -X GET "http://your-domain/api/dapodik/search?q=SDN%2001&limit=10" \
-H "Accept: application/json"

# Get student analytics
curl -X GET "http://your-domain/api/dapodik/analytics/students?kabupaten=Jakarta%20Pusat" \
-H "Accept: application/json"

# Get teacher analytics
curl -X GET "http://your-domain/api/dapodik/analytics/teachers" \
-H "Accept: application/json"

# Get accreditation summary
curl -X GET "http://your-domain/api/dapodik/accreditation/summary" \
-H "Accept: application/json"

# Compare kabupaten
curl -X GET "http://your-domain/api/dapodik/compare/kabupaten" \
-H "Accept: application/json"

# Get map data
curl -X GET "http://your-domain/api/dapodik/map-data?kabupaten=Jakarta%20Pusat" \
-H "Accept: application/json"

# Check API health
curl -X GET "http://your-domain/api/dapodik/health" \
-H "Accept: application/json"
```

### Query Parameters for Schools Endpoint:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| page | integer | No | Page number (default: 1) |
| per_page | integer | No | Items per page (default: 15) |
| search | string | No | Search by school name or NPSN |
| kabupaten | string | No | Filter by kabupaten |
| kecamatan | string | No | Filter by kecamatan |
| bentuk_pendidikan | string | No | Filter by education type |
| status_sekolah | string | No | Filter by school status |
| sort_by | string | No | Sort field (default: nama_satuan_pendidikan) |
| sort_order | string | No | Sort order (asc/desc) |

### Sample Response Format:

```json
{
    "success": true,
    "data": [
        {
            "nama_satuan_pendidikan": "SDN 01 Jakarta Pusat",
            "npsn": "20100101",
            "bentuk_pendidikan": "SD",
            "status_sekolah": "Negeri",
            "alamat": "Jl. Sudirman No. 1",
            "kabupaten_kota": "Jakarta Pusat",
            "kecamatan": "Menteng",
            "guru": 25,
            "tendik": 5,
            "jumlah_ruang_kelas": 12,
            "akreditasi": "A",
            "lintang": -6.2088,
            "bujur": 106.8456
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 10,
        "per_page": 15,
        "total": 150,
        "from": 1,
        "to": 15
    }
}
```

### Dashboard Summary Response:

```json
{
    "success": true,
    "data": {
        "total_schools": 1250,
        "total_teachers": 8500,
        "total_students": 125000,
        "total_classrooms": 4500,
        "schools_by_status": {
            "Negeri": 800,
            "Swasta": 450
        },
        "schools_by_type": {
            "SD": 500,
            "SMP": 300,
            "SMA": 250,
            "SMK": 200
        },
        "top_kabupaten": [
            {
                "kabupaten_kota": "Jakarta Pusat",
                "school_count": 150
            }
        ]
    }
}
```

### Student Analytics Response:

```json
{
    "success": true,
    "data": {
        "tka_total": 15000,
        "tka_l": 7500,
        "tka_p": 7500,
        "t1_total": 18000,
        "t1_l": 9000,
        "t1_p": 9000,
        "t2_total": 17500,
        "t2_l": 8750,
        "t2_p": 8750
    }
}
```

---

## Error Handling

### HTTP Status Codes:
- **200 OK**: Request successful
- **400 Bad Request**: Invalid request parameters
- **401 Unauthorized**: Authentication required
- **404 Not Found**: Resource not found
- **422 Unprocessable Entity**: Validation errors
- **500 Internal Server Error**: Server error

### Error Response:
```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        "field_name": ["Validation error message"]
    }
}
```

## Features

### Caching
- Dashboard endpoints: 1 hour cache
- Dapodik endpoints: 1 hour cache
- Search endpoints: 30 minutes cache

### Pagination
- Default: 15 items per page
- Maximum: 100 items per page
- Pagination metadata included in response

### Filtering & Sorting
- Multiple filter parameters supported
- Case-insensitive filtering
- Configurable sorting (asc/desc)

### Search
- Partial matching
- Case-insensitive search
- Minimum 2 characters required
- Search by school name or NPSN

### Geographic Features
- Nearby schools search by coordinates
- Distance calculation in kilometers
- Map data export for visualization

### Data Export
- Excel export functionality
- Filtered data export
- Bulk data retrieval

### Health Monitoring
- API health check endpoint
- Database connection status
- Data integrity validation
