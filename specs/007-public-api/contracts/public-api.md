# Public API Contracts

**Feature**: SPEC-007 — Public API
**Type**: REST API endpoint contracts

## Authentication Contract

### Request with API Key

```
GET /api/v1/projects HTTP/1.1
Host: pablomillaquen.com
X-API-Key: {plain-text-token}
Accept: application/json
```

### Request without API Key

```
GET /api/v1/projects HTTP/1.1
Host: pablomillaquen.com
Accept: application/json
```

### Response Headers

| Header | Value | Description |
|--------|-------|-------------|
| `X-RateLimit-Limit` | `60` or `120` | Max requests per window |
| `X-RateLimit-Remaining` | `59` | Requests remaining |
| `X-RateLimit-Reset` | `1672531200` | Unix timestamp when window resets |

### Rate Limit Exceeded Response

```
HTTP/1.1 429 Too Many Requests
Retry-After: 45

{
    "message": "Too many requests. Please try again later.",
    "retry_after": 45
}
```

## Endpoint Contracts

### GET /api/v1/projects

List published projects with pagination.

**Query Parameters**:
- `page` (integer, optional, default: 1)
- `per_page` (integer, optional, default: 15, max: 100)
- `category` (string, optional) — filter by category slug(s), comma-separated
- `featured` (boolean, optional) — filter by featured status

**Response 200**:
```json
{
    "data": [
        {
            "id": 1,
            "slug": "logistics-optimizer",
            "title": "Logistics Route Optimizer",
            "summary": "AI-powered route optimization...",
            "cover_image_url": "https://...",
            "featured": true,
            "categories": [{"id": 1, "slug": "ai", "name": "AI"}],
            "published_at": "2026-01-15T10:00:00Z"
        }
    ],
    "links": { "first": "...", "last": "...", "prev": null, "next": "..." },
    "meta": { "current_page": 1, "last_page": 5, "per_page": 15, "total": 75 }
}
```

### GET /api/v1/projects/{slug}

Get full project detail.

**Response 200**:
```json
{
    "data": {
        "id": 1,
        "slug": "logistics-optimizer",
        "title": "Logistics Route Optimizer",
        "summary": "...",
        "description": "<p>Full HTML description...</p>",
        "problem": "...",
        "approach": "...",
        "contribution": "...",
        "what_it_demonstrates": "...",
        "stack": "Python, FastAPI, PostgreSQL",
        "demo_url": "https://demo.example.com",
        "repository_url": "https://github.com/...",
        "cover_image_url": "...",
        "featured": true,
        "categories": [...],
        "capabilities": [...],
        "media": [...],
        "related_posts": [...],
        "published_at": "2026-01-15T10:00:00Z"
    }
}
```

**Response 404**:
```json
{
    "message": "Project not found"
}
```

### GET /api/v1/posts

List published posts with pagination.

**Query Parameters**: `page`, `per_page`, `season` (slug filter)

**Response 200**: Same pagination format as projects, with post-specific fields.

### GET /api/v1/posts/{slug}

Get full post detail with prev/next navigation.

### GET /api/v1/courses

List published courses with pagination.

### GET /api/v1/courses/{slug}

Get full course detail.

### GET /api/v1/seasons

List all seasons.

**Query Parameters**: `status` (active, completed, upcoming, draft)

### GET /api/v1/categories

List all categories.

**Query Parameters**: `dimension` (domain, capability, technology, methodology)

### GET /api/v1/capabilities

List all capabilities.

## Documentation Contract

### GET /docs/api

Interactive API documentation page (Stoplight Elements UI).

### GET /docs/api.json

OpenAPI 3.1 JSON specification.

## Error Contracts

### 400 Bad Request

```json
{
    "message": "Bad request"
}
```

### 401 Unauthorized

```json
{
    "message": "Invalid or missing API key"
}
```

### 404 Not Found

```json
{
    "message": "Resource not found"
}
```

### 422 Validation Error

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

### 429 Rate Limited

```json
{
    "message": "Too many requests. Please try again later.",
    "retry_after": 45
}
```
