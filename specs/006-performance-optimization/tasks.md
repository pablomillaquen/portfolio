# Tasks: Performance Optimization

**Input**: Design documents from `/specs/006-performance-optimization/`

**Prerequisites**: plan.md, spec.md, research.md, data-model.md, contracts/, quickstart.md

**Organization**: Tasks are grouped by user story to enable independent implementation and testing of each story.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (e.g., US1, US2, US3)
- Include exact file paths in descriptions

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Vite configuration and caching middleware — foundation for all user stories

- [x] T001 [P] Add `splitVendorChunkPlugin` import and plugin to `vite.config.js`
- [x] T002 [P] Create `CacheHeaders` middleware in `app/Http/Middleware/CacheHeaders.php`
- [x] T003 Register `CacheHeaders` middleware globally in `bootstrap/app.php`

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Blade template updates that enable all user stories

- [x] T004 [P] Add preload hints for critical fonts in `resources/views/app.blade.php`
- [x] T005 [P] Add `<noscript>` fallback message in `resources/views/app.blade.php`
- [x] T006 Add `font-display=swap` to Google Fonts URL in `resources/views/app.blade.php` (verify already present)

**Checkpoint**: Foundation ready — Vite splits chunks, middleware caches assets, template has preload/noscript

---

## Phase 3: User Story 1 — Fast Initial Page Load (Priority: P1) 🎯 MVP

**Goal**: Critical content renders within 1.5s on 3G. Non-critical resources load progressively on scroll.

**Independent Test**: Load homepage on simulated 3G — nav/hero renders <1.5s, below-fold images load on scroll

### Implementation for User Story 1

- [x] T007 [US1] Create `useLazyImage` composable in `resources/js/composables/useLazyImage.js` using IntersectionObserver
- [x] T008 [US1] Apply `loading="lazy"` attribute to below-fold images in `resources/js/pages/HomePage.vue`
- [x] T009 [US1] Apply `loading="lazy"` to images in `resources/js/pages/ProjectsPage.vue`
- [x] T010 [US1] Apply `loading="lazy"` to images in `resources/js/pages/PostsPage.vue`
- [x] T011 [US1] Apply `loading="lazy"` to images in `resources/js/pages/CoursesPage.vue`

**Checkpoint**: Homepage loads fast, images below fold load on scroll

---

## Phase 4: User Story 2 — Fast Route Navigation (Priority: P1)

**Goal**: Each route loads only its own code. Navigation between pages feels instant.

**Independent Test**: Navigate between routes — DevTools Network shows new chunk loaded per route, no admin code in public chunks

### Implementation for User Story 2

- [x] T012 [US2] Convert all route imports to dynamic `import()` in `resources/js/router.js` (keep HomePage static)
- [x] T013 [US2] Verify code splitting by running `npm run build` and checking for multiple JS chunks in `public/build/assets/`
- [x] T014 [US2] Verify admin code is isolated — public routes do not include admin chunk

**Checkpoint**: Each route loads its own chunk, total first-load JS <120KB gzipped

---

## Phase 5: User Story 3 — Repeat Visit Performance (Priority: P2)

**Goal**: Returning visitors get pages from cache in <500ms. Static assets are served without network requests.

**Independent Test**: Load site, reload — CSS/JS show "(from disk cache)" in DevTools Network tab

### Implementation for User Story 3

- [x] T015 [US3] Verify `CacheHeaders` middleware sets `Cache-Control: public, max-age=31536000, immutable` on `/build/assets/*` using `curl -sI`
- [x] T016 [US3] Verify HTML document returns `Cache-Control: no-cache` using `curl -sI`
- [x] T017 [US3] Verify repeat visit behavior — load site twice, check DevTools Network for cache hits

**Checkpoint**: Repeat visits serve all static assets from browser cache

---

## Phase 6: User Story 4 — Admin Panel Isolation (Priority: P2)

**Goal**: Public visitors download zero admin JavaScript. Admin code loads only on `/admin` routes.

**Independent Test**: Load public site — Network tab shows no admin-related JS. Navigate to `/admin` — admin chunk loads separately.

### Implementation for User Story 4

- [x] T018 [US4] Verify admin route uses dynamic `import()` (done in T012) — confirm admin chunk is separate
- [x] T019 [US4] Run `npm run build` and verify admin code is not in vendor or homepage chunks
- [x] T020 [US4] Verify public visitor (not logged in) never downloads admin chunk

**Checkpoint**: Admin code fully isolated from public bundle

---

## Phase 7: User Story 5 — Image Optimization (Priority: P3)

**Goal**: OG image reduced from 1.3MB to <200KB. Social share previews load fast.

**Independent Test**: Share portfolio link on social media — preview image loads quickly. Check file size of WebP.

### Implementation for User Story 5

- [x] T021 [US5] Convert `public/img/og_image.png` to WebP using `cwebp -q 80` → `public/img/og_image.webp`
- [x] T022 [US5] Verify WebP file size is <200KB using `ls -lh public/img/og_image.webp`
- [x] T023 [US5] Update OG image meta tags in `resources/views/app.blade.php` to reference WebP with PNG fallback
- [x] T024 [US5] Update JSON-LD `og:image` in `resources/views/app.blade.php` to reference WebP

**Checkpoint**: OG image optimized, social previews load fast

---

## Phase 8: Polish & Cross-Cutting Concerns

**Purpose**: Final validation and cleanup

- [x] T025 Run `npm run build` — verify production build completes without errors
- [x] T026 Verify all success criteria from spec using `quickstart.md` validation scenarios
- [x] T027 [P] Commit all changes with descriptive message

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies — can start immediately
- **Foundational (Phase 2)**: Depends on Setup completion — BLOCKS all user stories
- **User Stories (Phase 3-7)**: All depend on Foundational phase completion
  - US1 (P1) and US2 (P1) can run in parallel
  - US3 (P2) can run after US1/US2 (shares middleware setup)
  - US4 (P2) can run after US2 (shares router changes)
  - US5 (P3) is independent — can run in parallel with US1-US4
- **Polish (Phase 8)**: Depends on all user stories being complete

### User Story Dependencies

- **US1 (P1)**: Can start after Foundational — No dependencies on other stories
- **US2 (P1)**: Can start after Foundational — No dependencies on other stories
- **US3 (P2)**: Can start after Foundational — Uses CacheHeaders from Phase 1
- **US4 (P2)**: Can start after Foundational — Uses router changes from US2 (T012)
- **US5 (P3)**: Fully independent — Can start after Foundational

### Within Each User Story

- Implementation tasks within a story can often run in parallel (marked [P])
- Verify after each story completes before moving to next

### Parallel Opportunities

- T001 + T002: Vite config and middleware can be created simultaneously
- T004 + T005 + T006: Blade template changes can be done simultaneously
- T008 + T009 + T010 + T011: Image lazy loading across all pages simultaneously
- US1 and US2 can be implemented in parallel (different files)
- US5 is fully independent — can run alongside any other story

---

## Parallel Example: User Story 1

```bash
# Launch all image lazy-loading tasks together:
Task: "Apply loading=lazy to images in HomePage.vue"
Task: "Apply loading=lazy to images in ProjectsPage.vue"
Task: "Apply loading=lazy to images in PostsPage.vue"
Task: "Apply loading=lazy to images in CoursesPage.vue"
```

---

## Implementation Strategy

### MVP First (User Story 1 + User Story 2)

1. Complete Phase 1: Setup (Vite config + CacheHeaders middleware)
2. Complete Phase 2: Foundational (preload + noscript)
3. Complete Phase 3: US1 — lazy image loading
4. Complete Phase 4: US2 — route code splitting
5. **STOP and VALIDATE**: Test performance — initial load <1.5s, routes split correctly
6. Deploy if ready

### Incremental Delivery

1. Setup + Foundational → Foundation ready
2. Add US1 + US2 (P1) → Test independently → Deploy (MVP!)
3. Add US3 + US4 (P2) → Test independently → Deploy
4. Add US5 (P3) → Test independently → Deploy
5. Each story adds value without breaking previous stories

---

## Notes

- [P] tasks = different files, no dependencies
- [Story] label maps task to specific user story for traceability
- Each user story should be independently completable and testable
- Commit after each task or logical group
- Stop at any checkpoint to validate story independently
