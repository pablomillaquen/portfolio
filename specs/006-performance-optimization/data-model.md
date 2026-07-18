# Data Model: Performance Optimization

**Date**: 2026-07-17
**Feature**: SPEC-006

## Overview

This feature does not introduce new database entities. It defines configuration structures and asset delivery policies.

## Configuration Entities

### Cache Policy

Defines caching behavior for different asset types.

| Asset Type | Pattern | Cache-Control | Duration |
|------------|---------|---------------|----------|
| Hashed assets | `/build/assets/*` | `public, max-age=31536000, immutable` | 1 year |
| HTML document | `/` | `no-cache` | Revalidate always |
| OG image | `/img/og_image.*` | `public, max-age=86400` | 1 day |
| Fonts | External (Google Fonts) | Managed by Google CDN | N/A |

### Route Chunk Map

Maps routes to their chunk loading strategy.

| Route | Component | Loading Strategy | Rationale |
|-------|-----------|-----------------|-----------|
| `/` | HomePage | Eager (static import) | First paint — must be instant |
| `/projects` | ProjectsPage | Lazy (dynamic import) | Below fold, load on demand |
| `/projects/{slug}` | ProjectDetailPage | Lazy | Detail page, load on demand |
| `/posts` | PostsPage | Lazy | Below fold, load on demand |
| `/posts/{slug}` | PostDetailPage | Lazy | Detail page, load on demand |
| `/courses` | CoursesPage | Lazy | Below fold, load on demand |
| `/courses/{slug}` | CourseDetailPage | Lazy | Detail page, load on demand |
| `/contact` | ContactPage | Lazy | Low-traffic page, load on demand |
| `/admin/*` | AdminPage | Lazy (isolated chunk) | Public visitors never download |

### Asset Optimization

| Asset | Current | Target | Method |
|-------|---------|--------|--------|
| `og_image.png` | 1.3MB PNG | <200KB WebP | `cwebp -q 80` |
| JS bundle (total) | 209KB single chunk | <120KB first load (gzipped) | Route splitting |
| Route chunks | N/A (all in one) | <50KB each (gzipped) | Dynamic imports |
| Vendor chunk | Mixed in app bundle | Separate, cacheable | `splitVendorChunkPlugin` |

## State Transitions

### Asset Loading Sequence

```
1. Browser requests HTML (/)
   → Server returns no-cache HTML with preload hints
2. Browser parses HTML, discovers <script> and <link> tags
   → Downloads vendor chunk (cached on repeat visits)
   → Downloads homepage chunk (cached on repeat visits)
   → Downloads critical CSS
3. Homepage renders (Time to Interactive)
4. User navigates to /projects
   → Router intercepts navigation
   → Downloads ProjectsPage chunk on demand
   → Renders new page
5. User navigates to /admin (if authenticated)
   → Downloads AdminPage chunk (isolated from public bundle)
```

### Cache Lifecycle

```
First visit:
  → All assets downloaded from network
  → Stored in browser cache with 1-year max-age
  → HTML marked as no-cache (revalidate each visit)

Repeat visit:
  → HTML fetched from server (may have changed)
  → CSS/JS served from cache (immutable, no network request)
  → If new deployment: new hash = new URL = fresh download
```
