# Data Model: Public API

**Date**: 2026-07-17
**Feature**: SPEC-007

## Overview

This feature introduces API key management via Laravel Sanctum tokens and documents the existing content entities for public consumption.

## New Entities

### ApiKey (Sanctum Personal Access Token)

Represents an external consumer's API credentials.

| Field | Type | Description |
|-------|------|-------------|
| id | integer | Primary key |
| tokenable_id | integer | Owner user ID (admin) |
| tokenable_type | string | Model class (User) |
| name | string | Consumer label (e.g., "My App Integration") |
| token | string | Hashed token (plaintext shown once on creation) |
| abilities | json | Token permissions (e.g., `["*"]` for full access) |
| last_used_at | timestamp | Last request using this token |
| expires_at | timestamp | Optional expiration (null = never) |
| created_at | timestamp | Creation timestamp |

### Rate Limit State

Not a database entity — tracked in memory/cache by Laravel's throttle middleware.

| Key | Scope | Limit | Window |
|-----|-------|-------|--------|
| anonymous | By IP address | 60 requests | 1 minute |
| authenticated | By token ID | 120 requests | 1 minute |

## Existing Entities (API Response Format)

All content entities are exposed through versioned API endpoints. Response formats are defined by API Resources.

### Project (List Response)

| Field | Type | Description |
|-------|------|-------------|
| id | integer | Project ID |
| slug | string | URL-safe identifier |
| title | string | Translated title |
| summary | string | Translated summary |
| cover_image_url | string | Cover image URL |
| featured | boolean | Featured flag |
| categories | array | Associated category objects |
| published_at | datetime | Publication date |

### Project (Detail Response)

All list fields plus:

| Field | Type | Description |
|-------|------|-------------|
| description | string | Translated full description (HTML) |
| problem | string | Translated problem statement |
| approach | string | Translated approach description |
| contribution | string | Translated contribution description |
| what_it_demonstrates | string | Translated demonstration |
| stack | string | Technology stack |
| demo_url | string | Live demo URL |
| repository_url | string | Source code URL |
| media | array | Associated media objects |
| capabilities | array | Associated capability objects |
| related_posts | array | Associated post summaries |

### Post (List Response)

| Field | Type | Description |
|-------|------|-------------|
| id | integer | Post ID |
| slug | string | URL-safe identifier |
| title | string | Translated title |
| excerpt | string | Translated excerpt |
| type | string | "internal" or "external" |
| cover_image_url | string | Cover image URL |
| season | object | Associated season (name, slug) |
| episode_number | integer | Episode number within season |
| published_at | datetime | Publication date |

### Course (List Response)

| Field | Type | Description |
|-------|------|-------------|
| id | integer | Course ID |
| slug | string | URL-safe identifier |
| name | string | Translated course name |
| issuer | string | Issuing organization |
| credential_id | string | Certificate ID |
| url | string | Course URL |
| issued_at | date | Issue date |

### Season, Category, Capability (List Response)

| Field | Type | Description |
|-------|------|-------------|
| id | integer | Entity ID |
| slug | string | URL-safe identifier |
| name | string | Translated name |
| description | string | Translated description |

## Pagination Format

All list endpoints return paginated responses:

```json
{
    "data": [...],
    "links": {
        "first": "https://api.example.com/v1/projects?page=1",
        "last": "https://api.example.com/v1/projects?page=5",
        "prev": null,
        "next": "https://api.example.com/v1/projects?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 5,
        "path": "https://api.example.com/v1/projects",
        "per_page": 15,
        "to": 15,
        "total": 75
    }
}
```

## Error Response Format

```json
{
    "message": "Error description",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

Status codes: 400 (bad request), 401 (unauthorized), 403 (forbidden), 404 (not found), 422 (validation), 429 (rate limited), 500 (server error).
