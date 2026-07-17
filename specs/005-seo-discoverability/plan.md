# SPEC-005: SEO & Discoverability — Implementation Plan

## Context

After implementing SPEC-004 (Professional Knowledge Platform Foundation), the portfolio now has rich, structured content: projects with case studies, posts with seasons/episodes, categories, capabilities, and courses. However, **none of this content is discoverable by search engines** because:

- The `<title>` tag is hardcoded as "Portfolio" on every page
- There are zero meta descriptions, Open Graph tags, or Twitter Cards
- No sitemap.xml exists
- No structured data (JSON-LD) is present
- The SPA serves an empty `<div id="app">` to crawlers
- No hreflang tags for bilingual (es/en) support
- robots.txt doesn't reference a sitemap

This SPEC addresses **SPEC-008** from the SPEC-004 foundation note: "SEO y enlazado interno basado en conocimiento."

---

## Research Summary

### Current State
- **Blade template**: `resources/views/app.blade.php` — static, no dynamic data
- **Router**: `resources/js/router.js` — HTML5 history mode, no route meta fields
- **Head management**: None — no `vue-meta`, `@vueuse/head`, or similar
- **Sitemap**: Does not exist
- **robots.txt**: Permissive but no sitemap reference
- **Models**: All have unique slugs, `published_at` dates, translatable JSON fields

### Key Challenges
1. **SPA + SEO**: Crawlers see empty HTML. Without SSR/SSG, we need a hybrid approach.
2. **Bilingual**: Every meta tag must be locale-aware with hreflang.
3. **Dynamic content**: Projects, posts, courses change frequently — sitemap must be generated.

### Chosen Approach: Hybrid Client-Side SEO

**Why not SSR/SSG?**
- Would require migrating to Nuxt.js or Inertia.js — major rewrite
- Violates "Simplicity Over Abstraction" principle
- Current architecture works well for the admin SPA

**Hybrid approach:**
1. **Server-side meta injection**: Pass default SEO data from Laravel to Blade via `@json` directives
2. **Client-side head management**: Use `@vueuse/head` to dynamically update `<title>`, meta tags, OG tags per route
3. **API-based SEO endpoint**: Create `/api/seo/{type}/{slug}` that returns meta data for each page
4. **Static sitemap generation**: Artisan command `seo:generate-sitemap` creates `public/sitemap.xml`
5. **Structured data**: JSON-LD for Person, CreativeWork, BreadcrumbList

---

## Architecture Decisions

| Decision | Choice | Rationale |
|----------|--------|-----------|
| Head management library | `@vueuse/head` | Lightweight, Vue 3 native, maintained by VueUse team |
| Sitemap generation | Artisan command + Observer + Job | Regeneración automática al guardar contenido, no periódica |
| Sitemap trigger | Model Observer on Project/Post/Course | Inmediato al crear/actualizar/eliminar, con debounce de 60s |
| Structured data | JSON-LD in Blade + Vue | Server-rendered Person schema, client-rendered content schemas |
| Meta data source | API endpoint + route meta | Route-level defaults in Vue, detail pages fetch from API |
| SEO values | Auto-generated from content | No manual admin editing — title/description/image derived from existing fields |
| hreflang implementation | `<link>` tags via `@vueuse/head` | Dynamic per-page, locale-aware |

---

## Implementation Phases

### Phase 1: Head Management Foundation
**Goal**: Install and configure `@vueuse/head` for dynamic meta tags.

**Files to modify:**
- `package.json` — add `@vueuse/head` dependency
- `resources/js/app.js` — create head app, register plugin
- `resources/js/router.js` — add route meta fields (title, description defaults)
- `resources/js/App.vue` — use `useHead()` for base meta tags

**Route meta structure:**
```js
{
  path: '/projects/:slug',
  meta: {
    titleKey: 'project', // looks up translation or API
    description: '' // fetched dynamically
  }
}
```

### Phase 2: SEO API Endpoint
**Goal**: Create API endpoint that returns auto-generated meta data for any content entity.

**Files to create:**
- `app/Http/Controllers/Api/SeoController.php`

**Endpoints:**
```
GET /api/seo/project/{slug}?locale=es → { title, description, image, url, type }
GET /api/seo/post/{slug}?locale=es → { title, description, image, url, type }
GET /api/seo/course/{slug}?locale=es → { title, description, image, url, type }
GET /api/seo/home?locale=es → { title, description, image, url }
```

**Status filtering (critical):**
- All endpoints MUST verify `status = 'published'` before returning data
- If content is draft/scheduled, return 404 (not empty data)
- This prevents search engines from indexing unpublished content via SEO meta tags
- Works in conjunction with sitemap (which also excludes drafts)

**Auto-generation logic (no manual admin editing):**
- **Title**: `{entity.title} | Pablo Millaquen`
- **Description**: `{entity.summary}` or first 160 chars of `{entity.description}`
- **Image**: `{entity.cover_image_url}` or default OG image
- **URL**: `https://pablomillaquen.com/{type}/{slug}`
- **Type**: `article` for posts, `website` for projects/courses/home

**Response structure:**
```json
{
  "title": "Project Title | Pablo Millaquen",
  "description": "Project description for meta...",
  "image": "https://pablomillaquen.com/img/cover.jpg",
  "url": "https://pablomillaquen.com/projects/my-project",
  "type": "article",
  "locale": "es",
  "alternates": {
    "es": "https://pablomillaquen.com/projects/my-project?locale=es",
    "en": "https://pablomillaquen.com/projects/my-project?locale=en"
  }
}
```

### Phase 3: Dynamic Meta Tags per Page
**Goal**: Each page updates `<title>`, meta description, OG tags, and Twitter Cards when navigated to.

**Files to modify:**
- `resources/js/pages/HomePage.vue` — fetch SEO data on mount, useHead()
- `resources/js/pages/ProjectDetailPage.vue` — fetch SEO data on mount
- `resources/js/pages/PostDetailPage.vue` — fetch SEO data on mount
- `resources/js/pages/CourseDetailPage.vue` — fetch SEO data on mount
- `resources/js/pages/ProjectsPage.vue` — static meta via route meta
- `resources/js/pages/PostsPage.vue` — static meta via route meta
- `resources/js/pages/CoursesPage.vue` — static meta via route meta
- `resources/js/pages/ContactPage.vue` — static meta via route meta

**Pattern for detail pages:**
```js
import { useHead } from '@vueuse/head'

const seoData = ref({})

onMounted(async () => {
  const { data } = await api.get(`/api/seo/project/${slug.value}`, { params: { locale } })
  seoData.value = data
})

useHead(() => ({
  title: seoData.value.title || 'Portfolio',
  meta: [
    { name: 'description', content: seoData.value.description },
    { property: 'og:title', content: seoData.value.title },
    { property: 'og:description', content: seoData.value.description },
    { property: 'og:image', content: seoData.value.image },
    { property: 'og:url', content: seoData.value.url },
    { property: 'og:type', content: seoData.value.type || 'website' },
    { name: 'twitter:card', content: 'summary_large_image' },
    { name: 'twitter:title', content: seoData.value.title },
    { name: 'twitter:description', content: seoData.value.description },
    { name: 'twitter:image', content: seoData.value.image },
  ],
  link: [
    { rel: 'canonical', href: seoData.value.url },
    { rel: 'alternate', hreflang: 'es', href: seoData.value.alternates?.es },
    { rel: 'alternate', hreflang: 'en', href: seoData.value.alternates?.en },
  ]
}))
```

### Phase 4: Blade Template SEO Injection
**Goal**: Server-rendered default meta tags for crawlers that don't execute JavaScript.

**Files to modify:**
- `resources/views/app.blade.php` — add default meta tags with `@json` directives
- `app/Http/Controllers/Controller.php` or catch-all route — pass default SEO data

**Approach:**
- The catch-all route passes minimal SEO data (site name, default description)
- For crawlers, these defaults are better than nothing
- JavaScript then updates them dynamically for real users

**Default meta in Blade:**
```blade
<title>{{ $seo['title'] ?? 'Pablo Millaquen — Desarrollador & Investigador' }}</title>
<meta name="description" content="{{ $seo['description'] ?? 'Portfolio profesional...' }}">
<meta property="og:title" content="{{ $seo['title'] ?? 'Pablo Millaquen' }}">
<meta property="og:description" content="{{ $seo['description'] ?? '...' }}">
<meta property="og:image" content="{{ $seo['image'] ?? asset('img/og_image.png') }}">
<meta property="og:url" content="{{ $seo['url'] ?? url('/') }}">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<link rel="canonical" href="{{ $seo['url'] ?? url('/') }}">
```

### Phase 5: Sitemap Generation
**Goal**: Generate `public/sitemap.xml` with all published content.

**Files to create:**
- `app/Console/Commands/GenerateSitemap.php` — artisan command
- `app/Observers/ContentObserver.php` — dispatches job on model changes
- `app/Jobs/RegenerateSitemap.php` — queued job that runs artisan command
- `app/Providers/AppServiceProvider.php` — register observers

**Sitemap structure:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>https://pablomillaquen.com/</loc>
    <changefreq>weekly</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc>https://pablomillaquen.com/projects</loc>
    <changefreq>weekly</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc>https://pablomillaquen.com/projects/my-project</loc>
    <lastmod>2026-07-17</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>
  <!-- ... more URLs ... -->
</urlset>
```

**Logic:**
- Static pages: `/`, `/projects`, `/posts`, `/courses`, `/contact`
- Dynamic pages: each published project, post, course with their slugs
- Use `published_at` for `<lastmod>`
- Exclude admin routes, API routes, draft content

**Automation flow:**
```
Admin saves content (Project/Post/Course)
  → ContentObserver fires (created/updated/deleted)
    → Dispatch RegenerateSitemap job (queued, 60s debounce lock)
      → Job runs `php artisan seo:generate-sitemap`
        → public/sitemap.xml updated
```

**Debounce logic:** Job acquires cache lock `sitemap_regenerating` for 60 seconds. If lock exists, job is skipped (previous job will handle it). This prevents 10 regenerations for 10 rapid saves.

**Queue note:** User will configure queue worker via cPanel cron on production server (`php artisan queue:work`). Job uses `database` queue driver.

**OG image:** Default fallback image at `public/img/og_image.png`.

### Phase 6: Structured Data (JSON-LD)
**Goal**: Add schema.org structured data for better search engine understanding.

**Files to modify:**
- `resources/views/app.blade.php` — Person schema (server-rendered)
- `resources/js/pages/ProjectDetailPage.vue` — CreativeWork schema
- `resources/js/pages/PostDetailPage.vue` — Article schema
- `resources/js/pages/CourseDetailPage.vue` — EducationalOccupationalCredential schema

**Schemas:**

**Person (home page):**
```json
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Pablo Millaquen",
  "jobTitle": "Desarrollador & Investigador",
  "url": "https://pablomillaquen.com",
  "sameAs": ["https://github.com/...", "https://linkedin.com/..."]
}
```

**CreativeWork (projects):**
```json
{
  "@context": "https://schema.org",
  "@type": "CreativeWork",
  "name": "Project Title",
  "description": "Project description",
  "author": { "@type": "Person", "name": "Pablo Millaquen" },
  "url": "https://pablomillaquen.com/projects/slug",
  "image": "https://pablomillaquen.com/img/cover.jpg"
}
```

**Article (posts):**
```json
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "Post title",
  "description": "Post excerpt",
  "author": { "@type": "Person", "name": "Pablo Millaquen" },
  "datePublished": "2026-07-17",
  "url": "https://pablomillaquen.com/posts/slug",
  "image": "https://pablomillaquen.com/img/cover.jpg"
}
```

### Phase 7: robots.txt & Final Touches
**Goal**: Update robots.txt, add hreflang to Blade, ensure all pieces work together.

**Files to modify:**
- `resources/views/app.blade.php` — add hreflang link tags, dynamic `<html lang>`
- `public/robots.txt` — add sitemap reference

**robots.txt:**
```
User-agent: *
Disallow: /admin
Disallow: /api/

Sitemap: https://pablomillaquen.com/sitemap.xml
```

---

## Verification Plan

1. **Manual testing**: Navigate to each page type, verify `<title>`, meta tags, OG tags update correctly
2. **Google Rich Results Test**: Validate structured data on project and post detail pages
3. **Facebook Sharing Debugger**: Verify OG tags render correctly for social sharing
4. **Sitemap validation**: Use online sitemap validator to check XML structure
5. **curl test**: `curl -s http://localhost:8000/ | grep -i "meta\|title\|og:"` to verify server-rendered defaults
6. **Build check**: `npm run build` succeeds
7. **Lighthouse audit**: Run Lighthouse SEO audit, target score >90

---

## Future SPECs (Noted)

| SPEC | Focus |
|------|-------|
| SPEC-006 | Performance & Core Web Vitals |
| SPEC-007 | Public API / Developer Portal |
| SPEC-008 | Accessibility WCAG 2.1 |
| SPEC-009 | Testing & Quality |
| SPEC-010 | CI/CD Pipeline |

---

## Confidence

| Aspect | Level | Notes |
|--------|-------|-------|
| Technical approach | HIGH | `@vueuse/head` is well-established, hybrid SPA SEO is proven |
| Effort estimate | MEDIUM | ~7 phases, each independently testable |
| Risk | LOW | No architecture changes, additive only |
| Impact | HIGH | Directly affects discoverability and professional presentation |
