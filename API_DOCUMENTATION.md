# API Documentation

## Overview

This API provides access to dashboard metrics and Dapodik (Data Pokok Pendidikan) data with Bearer token authentication using Laravel Sanctum.

## Authentication

All API endpoints (except login) require Bearer token authentication. The token must be included in the `Authorization` header.

### Getting Started

1. **Login** to get an access token
2. **Include the token** in all subsequent requests
3. **Refresh the token** when needed
4. **Logout** to revoke the token

### Authentication Endpoints

#### Login
```http
POST /api/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password123",
  "device_name": "Postman"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "user@example.com",
      "email_verified_at": "2024-01-01T00:00:00.000000Z"
    },
    "token": "1|abc123def456...",
    "token_type": "Bearer",
    "expires_at": null
  }
}
```

#### Get User Info
```http
GET /api/user
Authorization: Bearer 1|abc123def456...
```

#### Refresh Token
```http
POST /api/refresh
Authorization: Bearer 1|abc123def456...
```

#### Logout
```http
POST /api/logout
Authorization: Bearer 1|abc123def456...
```

## Dashboard API Endpoints

### Summary Statistics
```http
GET /api/dashboard/summary?period=month
Authorization: Bearer 1|abc123def456...
```

**Query Parameters:**
- `period` (optional): Time period - `today`, `week`, `month`, `year` (default: `month`)

**Response:**
```json
{
  "success": true,
  "data": {
    "summary": {
      "total_records": 1250,
      "total_unit_kerja": 45,
      "average_persentase_tl": 78.5,
      "total_rtl": 890,
      "total_rhp": 360
    },
    "period": "month",
    "generated_at": "2024-01-15T10:30:00Z"
  }
}
```

### Unit Kerja List
```http
GET /api/dashboard/unit-kerja?page=1&per_page=15&sort_by=persentase_tl&sort_direction=desc
Authorization: Bearer 1|abc123def456...
```

**Query Parameters:**
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 15, max: 100)
- `sort_by` (optional): Sort field (default: `persentase_tl`)
- `sort_direction` (optional): Sort direction - `asc`, `desc` (default: `desc`)
- `search` (optional): Search term
- `period` (optional): Time period filter

### Top Unit Kerja
```http
GET /api/dashboard/unit-kerja/top?limit=10&period=month
Authorization: Bearer 1|abc123def456...
```

**Query Parameters:**
- `limit` (optional): Number of top units (default: 10, max: 50)
- `period` (optional): Time period filter

### Last Seen Activity
```http
GET /api/dashboard/activity/last-seen?limit=10
Authorization: Bearer 1|abc123def456...
```

**Query Parameters:**
- `limit` (optional): Number of activities (default: 10, max: 50)

### Status Detail
```http
GET /api/dashboard/status-detail?period=month
Authorization: Bearer 1|abc123def456...
```

### RTL Duration Statistics
```http
GET /api/dashboard/durasi/rtl?period=month
Authorization: Bearer 1|abc123def456...
```

### RHP Duration Statistics
```http
GET /api/dashboard/durasi/rhp?period=month
Authorization: Bearer 1|abc123def456...
```

## Dapodik API Endpoints

### Dashboard Summary
```http
GET /api/dapodik/dashboard/summary
Authorization: Bearer 1|abc123def456...
```

### Dashboard Statistics
```http
GET /api/dapodik/dashboard/statistics
Authorization: Bearer 1|abc123def456...
```

### Schools List
```http
GET /api/dapodik/schools?page=1&per_page=15&kabupaten=Jakarta%20Pusat&bentuk_pendidikan=SD&sort_by=nama_satuan_pendidikan&sort_order=asc
Authorization: Bearer 1|abc123def456...
```

**Query Parameters:**
- `page` (optional): Page number
- `per_page` (optional): Items per page (max: 100)
- `kabupaten` (optional): Filter by kabupaten
- `bentuk_pendidikan` (optional): Filter by education type
- `sort_by` (optional): Sort field
- `sort_order` (optional): Sort order - `asc`, `desc`

### School Detail
```http
GET /api/dapodik/schools/{npsn}
Authorization: Bearer 1|abc123def456...
```

### Nearby Schools
```http
GET /api/dapodik/schools/nearby?lat=-6.2088&lng=106.8456&radius=5
Authorization: Bearer 1|abc123def456...
```

**Query Parameters:**
- `lat` (required): Latitude
- `lng` (required): Longitude
- `radius` (optional): Radius in km (default: 5)

### Geographic Data
```http
GET /api/dapodik/locations/kabupaten
Authorization: Bearer 1|abc123def456...
```

```http
GET /api/dapodik/locations/kecamatan?kabupaten=Jakarta%20Pusat
Authorization: Bearer 1|abc123def456...
```

```http
GET /api/dapodik/locations/desa?kecamatan=Menteng
Authorization: Bearer 1|abc123def456...
```

### Analytics
```http
GET /api/dapodik/analytics/students?kabupaten=Jakarta%20Pusat
Authorization: Bearer 1|abc123def456...
```

```http
GET /api/dapodik/analytics/teachers?kabupaten=Jakarta%20Pusat
Authorization: Bearer 1|abc123def456...
```

```http
GET /api/dapodik/analytics/classrooms
Authorization: Bearer 1|abc123def456...
```

### Accreditation
```http
GET /api/dapodik/accreditation/summary
Authorization: Bearer 1|abc123def456...
```

```http
GET /api/dapodik/accreditation/expiring
Authorization: Bearer 1|abc123def456...
```

### Comparison & Benchmark
```http
GET /api/dapodik/compare/kabupaten
Authorization: Bearer 1|abc123def456...
```

```http
GET /api/dapodik/compare/bentuk-pendidikan
Authorization: Bearer 1|abc123def456...
```

### Search & Discovery
```http
GET /api/dapodik/search?q=SDN%2001&limit=10
Authorization: Bearer 1|abc123def456...
```

**Query Parameters:**
- `q` (required): Search query (min 2 characters)
- `limit` (optional): Result limit (default: 10, max: 50)

```http
GET /api/dapodik/map-data?kabupaten=Jakarta%20Pusat
Authorization: Bearer 1|abc123def456...
```

### Trends & Forecasting
```http
GET /api/dapodik/trends/enrollment
Authorization: Bearer 1|abc123def456...
```

```http
GET /api/dapodik/trends/growth
Authorization: Bearer 1|abc123def456...
```

### Export
```http
GET /api/dapodik/export/excel?kabupaten=Jakarta%20Pusat&bentuk_pendidikan=SD
Authorization: Bearer 1|abc123def456...
```

### Health Check
```http
GET /api/dapodik/health
Authorization: Bearer 1|abc123def456...
```

## Error Responses

### Authentication Errors

**401 Unauthorized**
```json
{
  "message": "Unauthenticated"
}
```

**401 Invalid Credentials**
```json
{
  "success": false,
  "message": "Invalid credentials"
}
```

### Validation Errors

**422 Validation Error**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password field is required."]
  }
}
```

### General Errors

**500 Internal Server Error**
```json
{
  "success": false,
  "message": "Internal server error",
  "error": "Error details"
}
```

## Rate Limiting

API requests are limited to 60 requests per minute per user/IP address.

## cURL Examples

### Login
```bash
curl -X POST "{{base_url}}/api/login" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password123",
    "device_name": "cURL"
  }'
```

### Get Dashboard Summary
```bash
curl -X GET "{{base_url}}/api/dashboard/summary?period=month" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|abc123def456..."
```

### Get Schools List
```bash
curl -X GET "{{base_url}}/api/dapodik/schools?page=1&per_page=15&kabupaten=Jakarta%20Pusat" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|abc123def456..."
```

### Logout
```bash
curl -X POST "{{base_url}}/api/logout" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|abc123def456..."
```

## Postman Collection

A complete Postman collection is available at `postman_collection.json` with all endpoints pre-configured with authentication headers.

### Environment Variables

Set up these environment variables in Postman:

- `base_url`: Your API base URL (e.g., `http://localhost:8000`)
- `token`: The Bearer token received from login

### Usage in Postman

1. Import the `postman_collection.json` file
2. Set up environment variables
3. Run the "Login" request to get a token
4. The token will be automatically used in subsequent requests

## Security Notes

- Tokens are stored securely using Laravel Sanctum
- Tokens don't expire by default but can be configured
- Each device gets a unique token
- Tokens are automatically revoked on logout
- Use HTTPS in production
- Keep tokens secure and don't share them
- Implement proper error handling for authentication failures 