# SPEC-005: SEO & Discoverability

## Overview

Make the portfolio's rich content discoverable by search engines, social platforms, and AI assistants through dynamic meta tags, structured data, sitemap generation, and bilingual SEO best practices.

## Problem Statement

After implementing SPEC-004, the portfolio has rich structured content (projects with case studies, posts with seasons, categories, capabilities) but **none of it is indexable**:

- `<title>` is hardcoded as "Portfolio" on every page
- Zero meta descriptions, Open Graph tags, or Twitter Cards
- No sitemap.xml for crawlers
- No structured data (JSON-LD) for search engines
- SPA serves empty HTML to crawlers
- No hreflang for bilingual (es/en) support
- robots.txt has no sitemap reference

## User Stories

### US1: Search Engine Crawling
**As a** search engine crawler,
**I want** to see meaningful meta tags, structured data, and a sitemap,
**So that** I can index the portfolio's content accurately.

**Acceptance Scenarios:**
1. Given I visit any page, when I inspect the HTML, then I see a descriptive `<title>` unique to that page.
2. Given I visit any page, when I inspect the HTML, then I see `<meta name="description">` with relevant content.
3. Given I request `/sitemap.xml`, when I parse it, then I find all published projects, posts, and courses with their slugs and lastmod dates.
4. Given I visit a project detail page, when I extract structured data, then I find valid JSON-LD for CreativeWork.

### US2: Social Sharing
**As a** user sharing a portfolio link on social media,
**I want** the link preview to show the correct title, description, and image,
**So that** the shared content is compelling and accurate.

**Acceptance Scenarios:**
1. Given I share a project link on LinkedIn/Twitter, when the preview generates, then it shows the project title, description, and cover image.
2. Given I share a post link, when the preview generates, then it shows the post title, excerpt, and cover image.
3. Given I share any link, when the preview generates, then the image is at least 1200x630px (or falls back to a default OG image).

### US3: Bilingual SEO
**As a** search engine,
**I want** to understand that the site has Spanish and English versions of each page,
**So that** I can serve the correct language version to users.

**Acceptance Scenarios:**
1. Given I visit `/projects/my-project?locale=es`, when I inspect the HTML, then I see `<link rel="alternate" hreflang="es">` and `<link rel="alternate" hreflang="en">`.
2. Given I visit any page, when I inspect `<html lang>`, then it matches the current locale (`es` or `en`).

### US4: Scheduled Content Protection
**As a** content creator,
**I want** unpublished/scheduled posts to NOT appear in search engines,
**So that** my content is only visible when I'm ready to publish it.

**Acceptance Scenarios:**
1. Given I create a post with `published_at` in the future, when I check the sitemap, then the post is NOT included.
2. Given I create a post with `published_at` in the future, when I request `/api/seo/post/{slug}`, then I receive a 404 response.
3. Given a scheduled post's `published_at` time arrives and the scheduler publishes it, when the sitemap regenerates, then the post IS included.
4. Given I unpublish a post (status='draft'), when the sitemap regenerates, then the post is removed from the sitemap.

### US5: Internal Linking
**As a** user browsing the portfolio,
**I want** related content to be linked together (project → related posts, post → related project),
**So that** I can discover the full context of the author's work.

**Acceptance Scenarios:**
1. Given I view a project detail page, when I scroll down, then I see related posts (if any exist).
2. Given I view a post detail page, when I scroll down, then I see the related project (if assigned).
3. Given I view any detail page, when I see breadcrumbs, then they reflect the navigation hierarchy.

## Technical Requirements

### TR-001: Head Management Library
System MUST use `@vueuse/head` for client-side meta tag management.

### TR-002: SEO API Endpoint
System MUST provide `GET /api/seo/{type}/{slug}?locale=es` returning title, description, image, url, type, and alternates for each content entity.

### TR-003: Dynamic Title Tags
System MUST update `<title>` tag dynamically on each route navigation.

### TR-004: Meta Description Tags
System MUST set `<meta name="description">` dynamically per page.

### TR-005: Open Graph Tags
System MUST set og:title, og:description, og:image, og:url, og:type per page.

### TR-006: Twitter Card Tags
System MUST set twitter:card, twitter:title, twitter:description, twitter:image per page.

### TR-007: Canonical URLs
System MUST set `<link rel="canonical">` per page.

### TR-008: Hreflang Tags
System MUST set `<link rel="alternate" hreflang="es">` and `<link rel="alternate" hreflang="en">` on bilingual pages.

### TR-009: Sitemap Generation
System MUST generate `public/sitemap.xml` via artisan command `seo:generate-sitemap` containing all published content.

### TR-010: Structured Data
System MUST include JSON-LD structured data:
- Person schema on home page
- CreativeWork schema on project detail pages
- Article schema on post detail pages
- EducationalOccupationalCredential schema on course detail pages

### TR-011: robots.txt
System MUST update `public/robots.txt` to disallow `/admin` and `/api/` and reference the sitemap.

### TR-012: Default Meta in Blade
System MUST render default meta tags in `resources/views/app.blade.php` for crawlers that don't execute JavaScript.

## Out of Scope

- Server-side rendering (SSR) or static site generation (SSG)
- Migration to Nuxt.js or Inertia.js
- AMP pages
- Google Search Console integration
- Analytics/tracking pixels

## Evidence

| ID | Decision | Evidence Level | Evidence | Confidence |
|----|----------|----------------|----------|------------|
| D-001 | Use @vueuse/head over vue-meta | E2 | VueUse docs, bundle size comparison | HIGH |
| D-002 | Hybrid SPA SEO (not SSR) | E1 | Architecture constraint: current Laravel+Vue SPA works well | HIGH |
| D-003 | Artisan command for sitemap | E2 | Laravel ecosystem: spatie/laravel-sitemap exists but artisan command is simpler | HIGH |
| D-004 | JSON-LD over microdata | E2 | Google recommendation, easier to maintain | HIGH |
| D-005 | API-based SEO data | E1 | Current API architecture, locale-aware endpoints | HIGH |
