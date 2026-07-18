# Research: Performance Optimization

**Date**: 2026-07-17
**Feature**: SPEC-006

## Decision 1: Route-Level Code Splitting

**Decision**: Use dynamic `import()` in Vue Router route definitions. Vite/Rollup automatically creates separate chunks per route.

**Rationale**: Zero additional dependencies — Vite's built-in Rollup config handles splitting. Each route becomes its own chunk loaded on demand. The homepage can remain eagerly imported for fastest first paint.

**Alternatives considered**:
- `defineAsyncComponent` for routes: More verbose, same result — rejected for simplicity
- Nuxt-style file-based routing: Would require framework migration — rejected per constitution
- Webpack dynamic imports: Not applicable — project uses Vite

**Pattern**:
```js
// Static import for homepage (fastest first paint)
import HomePage from './pages/HomePage.vue'

// Dynamic import for all other routes
const ProjectsPage = () => import('./pages/ProjectsPage.vue')
const PostsPage = () => import('./pages/PostsPage.vue')
// etc.
```

## Decision 2: Vendor Chunk Separation

**Decision**: Use Vite's `splitVendorChunkPlugin()` for automatic vendor splitting, with manual override for Vue ecosystem packages.

**Rationale**: The built-in plugin handles most cases automatically. Manual override ensures Vue + Vue Router + VueUse Head are grouped together (they change infrequently and cache well together).

**Alternatives considered**:
- Fully manual `manualChunks`: More control but more maintenance — rejected for simplicity
- No vendor splitting: Keeps single chunk but wastes bandwidth on repeat visits — rejected
- Single vendor chunk for everything: Simpler but less optimal caching — rejected

**Configuration**:
```js
// vite.config.js
import { splitVendorChunkPlugin } from 'vite'

plugins: [vue(), splitVendorChunkPlugin(), laravel({...}), tailwindcss()]
```

## Decision 3: Cache Headers Strategy

**Decision**: Add Laravel middleware to set `Cache-Control: public, max-age=31536000, immutable` on `/build/assets/*` routes. HTML document gets `no-cache`.

**Rationale**: Vite content-hashes all asset filenames, so new versions get new URLs — cache busting is automatic. The `immutable` directive eliminates 304 revalidation roundtrips. HTML must always be revalidated to check for new content.

**Alternatives considered**:
- Web server config (Nginx/Apache): More performant but user deploys via cPanel — may not have server config access
- Laravel `cache.headers` middleware: Doesn't support `immutable` directly — custom middleware needed
- No caching: Wastes bandwidth on every visit — rejected

**Implementation**: Custom `CacheHeaders` middleware applied to `build/assets` prefix in `AppServiceProvider`.

## Decision 4: Image Optimization

**Decision**: Convert `og_image.png` (1.3MB) to WebP using `cwebp -q 80`. Keep PNG as fallback for older platforms.

**Rationale**: WebP is supported by all modern browsers and social crawlers (Facebook, Twitter, LinkedIn). The `-q 80` setting provides ~85% size reduction with imperceptible quality loss. Keeping PNG as fallback ensures compatibility.

**Alternatives considered**:
- AVIF only: Better compression but lower support on older platforms — rejected for broad compatibility
- Convert and remove PNG: Risky if a crawler doesn't support WebP — keeping PNG as fallback
- External image CDN: Overkill for a portfolio site — rejected per Simplicity principle
- `sips` (macOS native): No quality control — rejected for `cwebp`

**Expected result**: ~150-200KB WebP (from 1.3MB PNG)

## Decision 5: Font Loading Optimization

**Decision**: Add `font-display: swap` via Google Fonts URL parameter `&display=swap`. Add `<link rel="preload">` for critical fonts in Blade template.

**Rationale**: `display=swap` is already in the Google Fonts URL (verified in `app.blade.php`). Preloading ensures fonts start downloading immediately rather than waiting for CSS parse.

**Alternatives considered**- Self-hosting fonts: More control but adds maintenance burden — rejected per Simplicity
- Remove web fonts: Would degrade visual quality — rejected
- Only preload on homepage: Complex per-page logic — rejected, preload globally

## Decision 6: Noscript Fallback

**Decision**: Add a `<noscript>` tag in `app.blade.php` with a message directing users to enable JavaScript.

**Rationale**: The SPA requires JavaScript to function. A noscript message provides a usable fallback instead of a blank page. Minimal effort, standard practice.

**Alternatives considered**- Server-side rendered fallback: Would require significant architecture change — rejected
- Redirect to static page: Overkill for a portfolio — rejected
- Do nothing: Poor UX for JS-disabled users — rejected
