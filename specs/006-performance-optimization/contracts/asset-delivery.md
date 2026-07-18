# Asset Delivery Contract

**Feature**: SPEC-006 — Performance Optimization
**Type**: HTTP behavior contract

## Purpose

Define the expected HTTP behavior for static asset delivery. This contract ensures consistent caching and loading behavior across the portfolio.

## Contract: Hashed Asset Delivery

**Endpoint**: `GET /build/assets/{name}-{hash}.{ext}`
**Trigger**: Any request for Vite-compiled assets

### Request

```
GET /build/assets/app-D73ToWol.js HTTP/1.1
Host: pablomillaquen.com
```

### Expected Response

```
HTTP/1.1 200 OK
Content-Type: application/javascript
Cache-Control: public, max-age=31536000, immutable
Content-Length: {size}

{minified JavaScript}
```

### Headers Required

| Header | Value | Purpose |
|--------|-------|---------|
| `Cache-Control` | `public, max-age=31536000, immutable` | Browser caches for 1 year, no revalidation |
| `Content-Type` | `application/javascript` or `text/css` | Correct MIME type |

### Behavior

- Browser caches asset indefinitely (until new HTML is fetched)
- No 304 revalidation — the `immutable` directive prevents it
- New deployments create new hash → new URL → automatic cache bust

## Contract: HTML Document Delivery

**Endpoint**: `GET /`
**Trigger**: Any page navigation

### Expected Response

```
HTTP/1.1 200 OK
Content-Type: text/html
Cache-Control: no-cache

<!DOCTYPE html>...
```

### Headers Required

| Header | Value | Purpose |
|--------|-------|---------|
| `Cache-Control` | `no-cache` | Always revalidate — ensures users get latest content |

## Contract: Route Chunk Loading

**Endpoint**: `GET /build/assets/{chunk-name}-{hash}.js`
**Trigger**: Vue Router navigation to a lazy route

### Expected Behavior

1. User navigates to `/projects`
2. Router intercepts navigation
3. Browser downloads `ProjectsPage-{hash}.js` (if not cached)
4. Page renders after chunk loads
5. Subsequent visits to `/projects` serve chunk from cache

### Chunk Size Constraints

| Chunk Type | Max Size (gzipped) |
|------------|-------------------|
| Vendor chunk | <60KB |
| Route page chunk | <50KB |
| Total first load | <120KB |

## Error Scenarios

| Scenario | Expected Behavior |
|----------|-------------------|
| Chunk fails to download | Vue Router error handler shows retry message |
| HTML fetch fails | Browser shows offline error page |
| Cache corrupted | Browser re-fetches from network |
