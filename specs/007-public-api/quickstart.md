# Quickstart: Public API

**Feature**: SPEC-007
**Purpose**: Validate that the public API works correctly end-to-end

## Prerequisites

- PHP 8.3+ with Laravel 12
- Sanctum installed (`composer require laravel/sanctum`)
- Scramble installed (`composer require dedoc/scramble`)
- Database migrated (`php artisan migrate`)
- Local dev server running (`php artisan serve`)

## Validation Scenarios

### 1. API Documentation Page

**Verify**: Documentation page loads and shows all endpoints.

```bash
# Start dev server
php artisan serve

# Check docs page loads
curl -s http://localhost:8000/docs/api | head -5
# Expected: HTML page with Stoplight Elements UI

# Check OpenAPI JSON
curl -s http://localhost:8000/docs/api.json | python3 -m json.tool | head -10
# Expected: Valid OpenAPI 3.1 JSON
```

### 2. Unauthenticated Content Access

**Verify**: Public endpoints work without API key.

```bash
# List projects
curl -s http://localhost:8000/api/v1/projects | python3 -m json.tool | head -10
# Expected: Paginated list of published projects

# Get single project
curl -s http://localhost:8000/api/v1/projects/{slug} | python3 -m json.tool | head -10
# Expected: Full project detail

# List posts
curl -s http://localhost:8000/api/v1/posts | python3 -m json.tool | head -10
# Expected: Paginated list of published posts
```

### 3. Rate Limiting

**Verify**: Rate limits are enforced.

```bash
# Check rate limit headers
curl -sI http://localhost:8000/api/v1/projects | grep -i "x-ratelimit"
# Expected: X-RateLimit-Limit, X-RateLimit-Remaining, X-RateLimit-Reset headers
```

### 4. API Key Authentication

**Verify**: API keys are recognized.

```bash
# Create a test token (requires tinker or admin route)
php artisan tinker --execute="User::first()->createToken('test', ['*'])->plainTextToken"
# Note the returned token

# Test with valid key
curl -s -H "X-API-Key: {token}" http://localhost:8000/api/v1/projects | head -5
# Expected: 200 OK with projects

# Test with invalid key
curl -s -H "X-API-Key: invalid-key" http://localhost:8000/api/v1/projects | head -5
# Expected: 401 Unauthorized
```

### 5. Pagination

**Verify**: Pagination works correctly.

```bash
# Request page 2
curl -s "http://localhost:8000/api/v1/projects?page=2&per_page=5" | python3 -m json.tool | grep -A5 "meta"
# Expected: current_page=2, per_page=5

# Request with invalid page
curl -s "http://localhost:8000/api/v1/projects?page=999" | python3 -m json.tool | head -5
# Expected: Empty data array, valid meta
```

### 6. Error Responses

**Verify**: Errors return consistent JSON format.

```bash
# Request non-existent resource
curl -s http://localhost:8000/api/v1/projects/non-existent-slug | python3 -m json.tool
# Expected: {"message": "Resource not found"} with 404 status

# Request with invalid parameters
curl -s "http://localhost:8000/api/v1/projects?per_page=999" | python3 -m json.tool | head -5
# Expected: Validation error or capped at max
```

### 7. CORS Headers

**Verify**: Cross-origin requests are allowed.

```bash
# Check CORS headers
curl -sI -H "Origin: https://example.com" http://localhost:8000/api/v1/projects | grep -i "access-control"
# Expected: Access-Control-Allow-Origin header present
```

## Success Criteria Checklist

| Criterion | Target | How to Verify |
|-----------|--------|---------------|
| Docs page loads | 200 OK | `curl /docs/api` |
| OpenAPI JSON valid | Valid JSON | `curl /docs/api.json | python3 -m json.tool` |
| Public endpoints work | 200 OK | `curl /api/v1/projects` |
| Pagination works | Meta present | `curl ?page=2` |
| Rate limit headers | Present | `curl -sI \| grep ratelimit` |
| Error format consistent | JSON envelope | `curl /api/v1/projects/nope` |
| CORS headers | Present | `curl -sI -H "Origin: ..." \| grep access-control` |

## Troubleshooting

| Issue | Solution |
|-------|----------|
| 404 on /api/v1/* | Check routes/api.php is loaded in bootstrap/app.php |
| 401 on all requests | Check Sanctum migration ran, token is valid |
| Rate limits not working | Check rate limiter defined in AppServiceProvider |
| CORS errors | Check config/cors.php has correct origins |
| Docs page blank | Check Scramble config in AppServiceProvider |
