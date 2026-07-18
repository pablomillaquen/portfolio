# Implementation Plan: Performance Optimization

**Branch**: `006-performance-optimization` | **Date**: 2026-07-17 | **Spec**: [spec.md](spec.md)

**Input**: Feature specification from `/specs/006-performance-optimization/spec.md`

## Summary

Optimize portfolio load performance through route-level code splitting, lazy image loading, browser caching headers, admin bundle isolation, and OG image optimization. Currently all 9 page components load eagerly in a single 209KB JS bundle — this spec splits them into on-demand chunks, adds cache headers for static assets, and optimizes the 1.3MB OG image.

## Technical Context

**Language/Version**: PHP 8.3, JavaScript (ES2022+)

**Primary Dependencies**: Laravel 12, Vue 3, Vite 7, Tailwind CSS v4

**Storage**: N/A (static asset optimization, no new data)

**Testing**: PHPUnit 11.5 (backend), manual browser testing (frontend performance)

**Target Platform**: Modern browsers (Chrome 90+, Firefox 90+, Safari 15+, Edge 90+)

**Project Type**: Web application (SPA frontend + Laravel API backend)

**Performance Goals**: Initial content render <1.5s on 3G, total JS <120KB gzipped on first load, repeat visits <500ms

**Constraints**: Must maintain constitution compliance (API-First, Component-Based, Simplicity). No SSR migration. No new heavy dependencies.

**Scale/Scope**: Portfolio site with ~10 pages, low traffic, single admin user

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

| Principle | Status | Notes |
|-----------|--------|-------|
| I. API-First | ✅ PASS | Performance changes are client-side only. API endpoints unchanged. |
| II. Bilingual | ✅ PASS | No content changes. Cache headers apply to all locales equally. |
| III. Admin CRUD | ✅ PASS | Admin panel isolation is additive — admin functionality unchanged. |
| IV. Component-Based | ✅ PASS | Lazy loading uses Vue's `defineAsyncComponent` and dynamic imports — standard Vue patterns. |
| V. Simplicity | ✅ PASS | Uses Vite's built-in code splitting and Laravel's built-in middleware — no new abstractions. |

## Project Structure

### Documentation (this feature)

```text
specs/006-performance-optimization/
├── spec.md              # Feature specification
├── plan.md              # This file
├── research.md          # Phase 0 output
├── data-model.md        # Phase 1 output
├── quickstart.md        # Phase 1 output
├── contracts/           # Phase 1 output
└── tasks.md             # Phase 2 output (/speckit.tasks)
```

### Source Code (repository root)

```text
resources/js/
├── app.js               # Vue mounting (add noscript handling)
├── router.js            # Route definitions (convert to lazy imports)
├── pages/               # Page components (unchanged, loaded lazily)
├── components/          # Shared components (unchanged)
└── composables/         # New: useLazyImage.js composable

app/
├── Http/
│   └── Middleware/
│       └── CacheHeaders.php  # New: middleware for static asset caching

resources/views/
└── app.blade.php        # Add noscript tag, preload hints

vite.config.js           # Add manualChunks configuration

public/
└── img/
    └── og_image.webp    # New: optimized OG image (from PNG conversion)
```

**Structure Decision**: Single-project web application. Changes span Laravel middleware (backend), Vite config (build), and Vue router/components (frontend). No new directories needed — files added to existing locations.

## Complexity Tracking

No constitution violations. All changes use built-in framework features (Vite code splitting, Laravel middleware, Vue dynamic imports). No new abstractions or patterns introduced.
