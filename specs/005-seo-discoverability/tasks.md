# SPEC-005: Tasks

## Phase 1: Head Management Foundation

- [ ] T001 [P] Install `@vueuse/head` package: `npm install @vueuse/head`
- [ ] T002 [P] Create head app and register plugin in `resources/js/app.js`
- [ ] T003 [P] Add route meta fields (titleKey, description) to all routes in `resources/js/router.js`
- [ ] T004 [US1] Implement base `useHead()` in `resources/js/App.vue` with site name and default meta

## Phase 2: SEO API Endpoint

- [ ] T005 [P] Create `app/Http/Controllers/Api/SeoController.php` with `project()`, `post()`, `course()`, `home()` methods
- [ ] T006 [P] Add SEO routes to `routes/web.php`: `GET /api/seo/{type}/{slug}`
- [ ] T007 [US1] Implement response structure: title, description, image, url, type, alternates (es/en)

## Phase 3: Dynamic Meta Tags per Page

- [ ] T008 [US1] Add `useHead()` to `HomePage.vue` — fetch SEO from `/api/seo/home`
- [ ] T009 [US1] Add `useHead()` to `ProjectDetailPage.vue` — fetch SEO from `/api/seo/project/{slug}`
- [ ] T010 [US1] Add `useHead()` to `PostDetailPage.vue` — fetch SEO from `/api/seo/post/{slug}`
- [ ] T011 [US1] Add `useHead()` to `CourseDetailPage.vue` — fetch SEO from `/api/seo/course/{slug}`
- [ ] T012 [US1] Add static `useHead()` to `ProjectsPage.vue`, `PostsPage.vue`, `CoursesPage.vue`, `ContactPage.vue`

## Phase 4: Blade Template SEO Injection

- [ ] T013 [P] Modify `resources/views/app.blade.php` to accept SEO data from controller
- [ ] T014 [US1] Add default meta tags in Blade: title, description, og:title, og:description, og:image, og:url, twitter:card
- [ ] T015 [US3] Add dynamic `<html lang="{{ app()->getLocale() }}">` and default hreflang links

## Phase 5: Sitemap Generation + Automation

- [ ] T016 [P] Create `app/Console/Commands/GenerateSitemap.php` artisan command
- [ ] T017 [US1] Implement static page URLs: `/`, `/projects`, `/posts`, `/courses`, `/contact`
- [ ] T018 [US1] Implement dynamic page URLs: each published project, post, course with slug and lastmod
- [ ] T019 [US1] Write sitemap XML to `public/sitemap.xml` with proper namespace
- [ ] T020 [P] Create `app/Observers/ContentObserver.php` — listens to created/updated/deleted on Project, Post, Course
- [ ] T021 [P] Create `app/Jobs/RegenerateSitemap.php` — queued job with 60s debounce lock
- [ ] T022 [P] Register observers in `app/Providers/AppServiceProvider.php` for Project, Post, Course models

## Phase 6: Structured Data (JSON-LD)

- [ ] T023 [US1] Add Person schema to `resources/views/app.blade.php` (server-rendered)
- [ ] T024 [US1] Add CreativeWork schema to `ProjectDetailPage.vue`
- [ ] T025 [US1] Add Article schema to `PostDetailPage.vue`
- [ ] T026 [US1] Add EducationalOccupationalCredential schema to `CourseDetailPage.vue`

## Phase 7: robots.txt & Final Touches

- [ ] T027 [P] Update `public/robots.txt` to disallow `/admin` and `/api/`, add sitemap reference
- [ ] T028 [US3] Ensure `<html lang>` switches dynamically with locale changes
- [ ] T029 [US2] Add fallback OG image for pages without cover image

## Verification

- [ ] T030 [V] Run `npm run build` — verify no errors
- [ ] T031 [V] Manual test: navigate to each page, verify `<title>` updates
- [ ] T032 [V] Manual test: inspect meta tags on project detail page
- [ ] T033 [V] Manual test: inspect JSON-LD on project detail page
- [ ] T034 [V] Run `php artisan seo:generate-sitemap`, verify XML output
- [ ] T035 [V] Test sitemap at `http://localhost:8000/sitemap.xml`
- [ ] T036 [V] Create/edit a project in admin, verify sitemap auto-regenerates
- [ ] T037 [V] Share project link on social media preview tool, verify OG tags
- [ ] T038 [V] Run Lighthouse SEO audit, target score >90

---

## Dependency Map

```
T001 → T002 → T004 → T008-T012
T005 → T006 → T007
T013 → T014 → T015
T016 → T017 → T018 → T019
T016 → T020 → T021 → T022 (automation chain)
T023 → T024-T026
T027 → T028 → T029
T030-T038 (verification, after all phases)
```

## Parallelizable

- T001 + T005 + T013 + T016 + T023 + T027 (independent starts)
- T008-T012 (all pages can be done in parallel after T004 + T007)
- T024-T026 (all detail pages in parallel after T023)
- T020-T022 (observer chain, after T016)
