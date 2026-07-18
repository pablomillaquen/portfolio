# Feature Specification: Performance Optimization

**Feature Branch**: `006-performance-optimization`

**Created**: 2026-07-17

**Status**: Draft

**Input**: User description: "Optimización de carga, lazy loading y caching"

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Fast Initial Page Load (Priority: P1)

A visitor arrives at the portfolio homepage for the first time. The page loads quickly — the critical content (header, hero, navigation) appears within 1 second. Non-critical resources (secondary images, below-the-fold content) load progressively as the user scrolls. The visitor perceives the site as fast and responsive, not sluggish.

**Why this priority**: First impression is critical for a developer portfolio. Slow load times cause immediate bounce — 53% of mobile users abandon sites taking >3 seconds to load. This is the foundation for all other performance improvements.

**Independent Test**: Can be fully tested by loading the homepage on a simulated 3G connection and measuring Time to Interactive. Delivers immediate perceived performance improvement.

**Acceptance Scenarios**:

1. **Given** a visitor with a slow connection (3G), **When** they navigate to the homepage, **Then** the initial content (nav, hero section) renders within 1.5 seconds
2. **Given** a visitor on the homepage, **When** they scroll down, **Then** images below the fold load only as they enter the viewport
3. **Given** a visitor, **When** the page loads, **Then** no unused JavaScript or CSS is downloaded upfront

---

### User Story 2 - Fast Route Navigation (Priority: P1)

A visitor navigates between pages (Home → Projects → Posts). Each navigation feels instant — the new page content appears without a full-page reload or blank screen. Only the page-specific code is loaded on demand, not the entire application bundle.

**Why this priority**: SPA navigation is the core UX. Loading 209KB of JavaScript for every page visit is wasteful — most pages need only a fraction of that. Code splitting directly reduces load time for every navigation.

**Independent Test**: Can be tested by navigating between routes and measuring chunk sizes in browser DevTools Network tab. Each route should load <50KB of page-specific code.

**Acceptance Scenarios**:

1. **Given** a visitor on the homepage, **When** they click "Projects", **Then** only the ProjectsPage component code is downloaded (not AdminPage, CourseDetailPage, etc.)
2. **Given** a visitor, **When** they navigate to any route, **Then** the page content appears within 500ms of navigation (on cached visit)
3. **Given** a first-time visitor, **When** they load any page, **Then** the total JavaScript downloaded is under 120KB (gzipped)

---

### User Story 3 - Repeat Visit Performance (Priority: P2)

A visitor returns to the portfolio after their first visit. Pages load significantly faster because static assets are cached by the browser. The site feels instant on repeat visits — assets are served from local cache without network requests.

**Why this priority**: Returning visitors are the most valuable audience (potential employers, collaborators). Caching ensures they get the best experience without any extra effort.

**Independent Test**: Can be tested by loading the site, clearing only the HTML cache, then reloading — static assets should be served from disk cache (Status 304 or 200 from cache).

**Acceptance Scenarios**:

1. **Given** a returning visitor, **When** they load any page, **Then** CSS and JavaScript assets load from browser cache (no network request for unchanged assets)
2. **Given** a returning visitor, **When** they load a page, **Then** the HTML document is the only resource fetched from the server (all hashed assets are cached)
3. **Given** a visitor, **When** they visit the site, **Then** font files load from cache after the first visit (no re-download)

---

### User Story 4 - Admin Panel Isolation (Priority: P2)

An admin user visits the admin panel. The admin-specific code (dashboard, CRUD forms, modals) is loaded only when accessing admin routes. Public visitors never download admin JavaScript — reducing their bundle size significantly.

**Why this priority**: The admin panel is a significant portion of the codebase but is irrelevant to 99% of visitors. Isolating it reduces the public bundle and improves performance for the primary audience.

**Independent Test**: Can be tested by loading the public site and checking the Network tab — no admin-related JavaScript should be downloaded. Only when navigating to `/admin` should admin chunks appear.

**Acceptance Scenarios**:

1. **Given** a public visitor, **When** they load any public page, **Then** zero admin-specific JavaScript is downloaded
2. **Given** an admin user, **When** they navigate to `/admin`, **Then** admin code loads as a separate chunk
3. **Given** a public visitor, **When** they view the bundle analysis, **Then** admin components are not present in any downloaded chunk

---

### User Story 5 - Image Optimization (Priority: P3)

A visitor views the portfolio and sees images that load quickly and look sharp. Open Graph images for social sharing are optimized for fast load when links are shared. Future content images use modern formats and responsive sizing.

**Why this priority**: The current 1.3MB OG image is primarily used by social crawlers (not user-facing), but optimizing it reduces bandwidth and improves social share preview load time. This sets the foundation for future content images.

**Independent Test**: Can be tested by sharing a portfolio link on social media — the preview image should load quickly and render at the correct dimensions.

**Acceptance Scenarios**:

1. **Given** a user shares a portfolio link on social media, **When** the platform fetches the OG image, **Then** the image loads in under 1 second
2. **Given** the OG image, **When** served, **Then** its file size is under 200KB (from current 1.3MB)
3. **Given** future content images, **When** added to the portfolio, **Then** they use modern formats (WebP/AVIF) with responsive sizing

---

### Edge Cases

- What happens when a visitor has JavaScript disabled? The site should show a noscript fallback message — the Blade template already provides server-side meta tags as a partial fallback
- What happens when a cached asset is updated? The Vite hash in filenames ensures new versions are fetched — cache-busting is automatic
- What happens when a visitor is on an extremely slow connection (<100kbps)? The critical path should still render; non-critical resources degrade gracefully
- What happens when the admin panel code fails to load? Admin users should see an error state, not a blank page

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST split JavaScript bundles by route — each page loads only its own component code on demand
- **FR-002**: System MUST lazy-load images below the fold — images load only when entering the viewport
- **FR-003**: System MUST serve static assets with immutable cache headers — hashed assets (CSS, JS, fonts) are cached indefinitely by the browser
- **FR-004**: System MUST serve the HTML document with short cache duration — ensuring users get updates when content changes
- **FR-005**: System MUST isolate admin panel code into a separate chunk — public visitors never download admin JavaScript
- **FR-006**: System MUST optimize the Open Graph image — reduce from 1.3MB to under 200KB using modern compression
- **FR-007**: System MUST configure Vite build with manual chunks — separate vendor libraries (Vue, Vue Router) from application code
- **FR-008**: System MUST add font-display: swap to web fonts — preventing invisible text during font load
- **FR-009**: System MUST preload critical resources — fonts and critical CSS are loaded with high priority
- **FR-010**: System MUST provide a noscript fallback — visitors with JavaScript disabled see a meaningful message

### Key Entities

- **Asset**: Static files (CSS, JS, images, fonts) served to visitors — each has a content-hash filename for cache-busting
- **Route Chunk**: A JavaScript file containing code for a specific page — loaded on demand when the route is visited
- **Cache Policy**: HTTP headers controlling how long browsers store and reuse assets — immutable for hashed files, short for HTML

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Total JavaScript downloaded on first page load is under 120KB gzipped (from current ~72KB which includes all pages)
- **SC-002**: Each route-specific chunk is under 50KB gzipped
- **SC-003**: Initial page content (nav, hero) renders within 1.5 seconds on 3G connection
- **SC-004**: Repeat visits load pages in under 500ms (all assets from cache)
- **SC-005**: Public visitors download zero admin-specific JavaScript
- **SC-006**: Open Graph image file size is reduced by 85% (from 1.3MB to under 200KB)
- **SC-007**: No render-blocking resources block initial page paint

## Assumptions

- Vite's built-in code splitting handles route-level chunking via dynamic imports — no additional bundler configuration needed beyond manual chunks
- The current 209KB JS bundle can be reduced by ~40% through route splitting alone (admin, courses, and posts are rarely visited on first load)
- Browser caching is the primary caching strategy — no server-side page caching needed for a portfolio with infrequent content updates
- The queue worker (configured via cPanel) handles sitemap regeneration — performance optimization is client-side focused
- Font files (Google Fonts) are external and cannot be self-hosted without changing the font loading strategy
- Image optimization applies only to the OG image for now — future content images will follow the same pattern when added
- The admin panel is a small audience (single admin user) — code splitting for admin is a secondary benefit, not a primary goal
