# Quickstart: Performance Optimization

**Feature**: SPEC-006
**Purpose**: Validate that all performance optimizations work correctly

## Prerequisites

- PHP 8.3+ with Laravel 12
- Node.js 18+ with npm
- `cwebp` installed (`brew install webp`)
- Local dev server running (`php artisan serve`)

## Validation Scenarios

### 1. Route Code Splitting

**Verify**: Each route loads only its own chunk.

```bash
# Build production assets
npm run build

# Check that multiple JS chunks exist
ls -la public/build/assets/*.js | wc -l
# Expected: >2 files (vendor + homepage + route chunks)
```

**Manual test**:
1. Open browser DevTools → Network tab
2. Load homepage — verify 1 JS file (vendor) + 1 JS file (homepage chunk)
3. Navigate to `/projects` — verify a new JS file loads
4. Navigate to `/admin` — verify a separate admin chunk loads
5. Navigate back to `/` — verify NO new JS loads (cached)

### 2. Browser Caching

**Verify**: Hashed assets return immutable cache headers.

```bash
# Start dev server
php artisan serve

# Check cache headers on hashed asset
curl -sI http://localhost:8000/build/assets/app-*.js | grep -i cache-control
# Expected: Cache-Control: public, max-age=31536000, immutable
```

**Manual test**:
1. Load homepage, note the JS/CSS filenames from Network tab
2. Reload page — verify those files show "(from disk cache)" in Network tab
3. Check HTML document always shows "(from server)" or "(304)"

### 3. OG Image Optimization

**Verify**: WebP version exists and is significantly smaller.

```bash
# Convert PNG to WebP
cwebp -q 80 public/img/og_image.png -o public/img/og_image.webp

# Compare sizes
ls -lh public/img/og_image.png public/img/og_image.webp
# Expected: WebP <200KB, PNG ~1.3MB
```

### 4. Admin Bundle Isolation

**Verify**: Public visitors never download admin code.

```bash
# Build and analyze chunks
npm run build

# Check for admin in public chunks
grep -r "admin" public/build/assets/*.js | head -5
# Expected: admin code only in the admin-specific chunk
```

**Manual test**:
1. Load public site (not logged in)
2. DevTools → Network → filter by JS
3. Verify no chunk contains admin-related code (search for "admin" in response)

### 5. Noscript Fallback

**Verify**: JavaScript-disabled browsers see a message.

```bash
# Check blade template has noscript tag
grep -A3 "noscript" resources/views/app.blade.php
# Expected: <noscript> tag with fallback message
```

### 6. Font Loading

**Verify**: Fonts load with display=swap and preload hints exist.

```bash
# Check preload hints in HTML
curl -s http://localhost:8000 | grep 'rel="preload"'
# Expected: preload link for font files
```

## Success Criteria Checklist

| Criterion | Target | How to Verify |
|-----------|--------|---------------|
| First load JS <120KB gzipped | <120KB | `curl` + `gzip -c` or DevTools |
| Route chunks <50KB each | <50KB | DevTools Network tab |
| Hashed assets immutable | 1 year cache | `curl -sI` headers |
| HTML no-cache | Revalidate | `curl -sI` headers |
| OG image <200KB | <200KB | `ls -lh` |
| Admin isolated | 0 bytes for public | DevTools Network tab |
| Repeat visit <500ms | <500ms | DevTools Performance tab |

## Troubleshooting

| Issue | Solution |
|-------|----------|
| Chunks not splitting | Verify dynamic `import()` syntax in router.js |
| Cache headers missing | Check CacheHeaders middleware is registered |
| WebP too large | Lower `-q` value (try `-q 70`) |
| Admin code in public | Check route guard in router.js |
