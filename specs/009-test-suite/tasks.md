# Tasks: Testing Suite

**Input**: Design documents from `/specs/009-test-suite/`

**Prerequisites**: plan.md, spec.md, research.md, data-model.md, contracts/, quickstart.md

**Tests**: This feature IS the test suite. All tasks are tests or test infrastructure.

**Organization**: Tasks grouped by user story. Foundational tasks (factories, config) are in Phase 2 since they block all stories.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (e.g., US1, US2, US3)

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Install test dependencies, configure build tools

- [X] T001 Install Vitest, @vue/test-utils, and jsdom via `npm install --save-dev vitest @vue/test-utils jsdom`
- [X] T002 Install Playwright via `npm install --save-dev @playwright/test`
- [X] T003 Install Playwright browsers via `npx playwright install`
- [X] T004 Create `vitest.config.js` at project root — configure jsdom environment, Vue alias, test file patterns
- [X] T005 Create `e2e/playwright.config.js` — configure baseURL (localhost:8000), webServer, test directory, timeout
- [X] T006 Update `tests/TestCase.php` — add `RefreshDatabase` and `WithFaker` traits as default for all feature tests

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Model factories and test scripts that ALL user stories depend on

**⚠️ CRITICAL**: No user story work can begin until this phase is complete

- [X] T007 [P] Create `CategoryFactory.php` in `database/factories/` — slug (JSON es/en), order_column, published state
- [X] T008 [P] Create `SeasonFactory.php` in `database/factories/` — name (JSON), slug (JSON), order_column, active state
- [X] T009 [P] Create `CapabilityFactory.php` in `database/factories/` — name (JSON), description (JSON), order_column
- [X] T010 [P] Create `SocialLinkFactory.php` in `database/factories/` — platform, url, order_column, active state
- [X] T011 [P] Create `SiteSettingFactory.php` in `database/factories/` — key, value (JSON), defaults state
- [X] T012 [P] Create `ContactMessageFactory.php` in `database/factories/` — name, email, subject, message, read/replied states
- [X] T013 [P] Create `ProjectFactory.php` in `database/factories/` — name (JSON), slug (JSON), description (JSON), featured, category_id, published_at, draft/published/featured states
- [X] T014 [P] Create `PostFactory.php` in `database/factories/` — title (JSON), slug (JSON), excerpt (JSON), content (JSON), season_id, category_id, published_at, draft/published/scheduled states
- [X] T015 [P] Create `CourseFactory.php` in `database/factories/` — title (JSON), slug (JSON), description (JSON), url, published_at, draft/published states
- [X] T016 [P] Create `ProjectMediaFactory.php` in `database/factories/` — project_id, type, url, order_column, image/video/document states
- [X] T017 [P] Update `UserFactory.php` in `database/factories/` — add admin and verified states
- [X] T018 Add `test:unit` and `test:e2e` scripts to `package.json` — `"test": "vitest run"`, `"test:unit": "vitest run"`, `"test:e2e": "npx playwright test"`

**Checkpoint**: Foundation ready — all factories exist, test scripts configured. User story implementation can begin.

---

## Phase 3: User Story 1 — Backend Unit & Feature Tests (Priority: P1) 🎯 MVP

**Goal**: Comprehensive PHPUnit test suite covering all 11 models and 19 API controllers

**Independent Test**: Run `composer test` — all PHPUnit tests pass with 0 failures

### Model Tests for User Story 1

- [X] T019 [P] [US1] Create `CategoryTest.php` in `tests/Feature/Models/` — test relationships (projects, posts), scopes, translatable fields
- [X] T020 [P] [US1] Create `SeasonTest.php` in `tests/Feature/Models/` — test relationships (posts), scopes, ordering
- [X] T021 [P] [US1] Create `CapabilityTest.php` in `tests/Feature/Models/` — test relationships (projects), translatable fields
- [X] T022 [P] [US1] Create `SocialLinkTest.php` in `tests/Feature/Models/` — test ordering, platform validation
- [X] T023 [P] [US1] Create `SiteSettingTest.php` in `tests/Feature/Models/` — test key-value storage, JSON values
- [X] T024 [P] [US1] Create `ContactMessageTest.php` in `tests/Feature/Models/` — test status transitions, required fields
- [X] T025 [P] [US1] Create `ProjectTest.php` in `tests/Feature/Models/` — test relationships (category, media), scopes (published), translatable fields
- [X] T026 [P] [US1] Create `PostTest.php` in `tests/Feature/Models/` — test relationships (season, category), scopes (published, scheduled), translatable fields
- [X] T027 [P] [US1] Create `CourseTest.php` in `tests/Feature/Models/` — test relationships, scopes, translatable fields
- [X] T028 [P] [US1] Create `ProjectMediaTest.php` in `tests/Feature/Models/` — test relationship (project), type validation
- [X] T029 [P] [US1] Create `UserTest.php` in `tests/Feature/Models/` — test admin flag, authentication relationship

### V1 Public API Tests for User Story 1

- [X] T030 [P] [US1] Create `CategoryControllerTest.php` in `tests/Feature/Api/V1/` — test list, show, translatable response
- [X] T031 [P] [US1] Create `ProjectControllerTest.php` in `tests/Feature/Api/V1/` — test list, show, filtering by category, featured
- [X] T032 [P] [US1] Create `PostControllerTest.php` in `tests/Feature/Api/V1/` — test list, show, filtering by season/category
- [X] T033 [P] [US1] Create `CourseControllerTest.php` in `tests/Feature/Api/V1/` — test list, show
- [X] T034 [P] [US1] Create `SeasonControllerTest.php` in `tests/Feature/Api/V1/` — test list, show
- [X] T035 [P] [US1] Create `CapabilityControllerTest.php` in `tests/Feature/Api/V1/` — test list, show

### Admin API Tests for User Story 1

- [X] T036 [US1] Create `AuthControllerTest.php` in `tests/Feature/Api/Auth/` — test login, logout, current user, unauthorized access
- [X] T037 [US1] Create `ContactControllerTest.php` in `tests/Feature/Api/Contact/` — test submit, validation, Mail fake
- [X] T038 [US1] Create `SeoControllerTest.php` in `tests/Feature/Api/Seo/` — test SEO data endpoint
- [X] T039 [US1] Create `PublicContentControllerTest.php` in `tests/Feature/Api/PublicContent/` — test public content aggregation
- [X] T040 [US1] Create `AdminCategoryControllerTest.php` in `tests/Feature/Api/Admin/` — test CRUD, auth required, validation
- [X] T041 [US1] Create `AdminProjectControllerTest.php` in `tests/Feature/Api/Admin/` — test CRUD, auth required, validation, translatable input
- [X] T042 [US1] Create `AdminPostControllerTest.php` in `tests/Feature/Api/Admin/` — test CRUD, auth required, validation, scheduled publishing
- [X] T043 [US1] Create `AdminCourseControllerTest.php` in `tests/Feature/Api/Admin/` — test CRUD, auth required, validation
- [X] T044 [US1] Create `AdminSeasonControllerTest.php` in `tests/Feature/Api/Admin/` — test CRUD, auth required, validation
- [X] T045 [US1] Create `AdminCapabilityControllerTest.php` in `tests/Feature/Api/Admin/` — test CRUD, auth required, validation
- [X] T046 [US1] Create `AdminSocialLinkControllerTest.php` in `tests/Feature/Api/Admin/` — test CRUD, auth required, validation
- [X] T047 [US1] Create `AdminSiteSettingControllerTest.php` in `tests/Feature/Api/Admin/` — test CRUD, auth required, validation

**Checkpoint**: `composer test` passes with all model + controller tests. Backend coverage ≥70%.

---

## Phase 4: User Story 2 — Frontend Unit Tests (Priority: P1)

**Goal**: Vitest unit tests for Vue composable and critical components

**Independent Test**: Run `npm run test` — all Vitest tests pass

### Implementation for User Story 2

- [X] T048 [P] [US2] Create `useAnnouncer.test.js` in `resources/js/__tests__/composables/` — test announce function, polite/assertive modes, clear-then-set pattern
- [X] T049 [P] [US2] Create `CategoryFilter.test.js` in `resources/js/__tests__/components/` — test aria-pressed state, emit update event, render categories
- [X] T050 [P] [US2] Create `ContentPreviewModal.test.js` in `resources/js/__tests__/components/` — test role="dialog", aria-modal, Escape key, focus trap
- [X] T051 [P] [US2] Create `PublicShell.test.js` in `resources/js/__tests__/components/` — test skip link, nav aria-label, theme toggle
- [X] T052 [P] [US2] Create `ContactPage.test.js` in `resources/js/__tests__/pages/` — test form rendering, labels, aria-required, submit handling

**Checkpoint**: `npm run test` passes. Composable coverage ≥80%.

---

## Phase 5: User Story 3 — End-to-End Tests (Priority: P2)

**Goal**: Playwright E2E tests covering critical user journeys

**Independent Test**: Run `npx playwright test` — all E2E specs pass against running application

### Implementation for User Story 3

- [X] T053 [US3] Create `e2e/fixtures/data.js` — test data fixtures (admin credentials, project data, contact form data)
- [X] T054 [P] [US3] Create `e2e/navigation.spec.js` — test public page navigation, page rendering, no console errors
- [X] T055 [P] [US3] Create `e2e/contact-form.spec.js` — test form validation, submission, success message, Mail fake
- [X] T056 [P] [US3] Create `e2e/admin-crud.spec.js` — test admin login, CRUD operations, logout, session protection
- [X] T057 [P] [US3] Create `e2e/language-switch.spec.js` — test ES/EN toggle, content updates, locale persistence

**Checkpoint**: E2E suite completes in <2 minutes. All critical user journeys covered.

---

## Phase 6: Polish & Cross-Cutting Concerns

**Purpose**: Final validation, coverage thresholds, documentation

- [X] T058 Add code coverage threshold to phpunit.xml — min 70% line coverage for backend
- [X] T059 Add coverage configuration to vitest.config.js — min 80% for composable files
- [X] T060 Run quickstart.md validation scenarios — verify all 8 scenarios pass
- [X] T061 Run `composer test` — verify 0 failures, coverage ≥70%
- [X] T062 Run `npm run test` — verify 0 failures, composable coverage ≥80%
- [X] T063 Run `npx playwright test` — verify all E2E specs pass
- [X] T064 Commit all changes with descriptive message

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies — can start immediately
- **Foundational (Phase 2)**: Depends on T001-T006 (npm install, config files) — BLOCKS all user stories
- **US1 Backend Tests (Phase 3)**: Depends on Phase 2 (factories must exist)
- **US2 Frontend Tests (Phase 4)**: Depends on T004 (vitest.config.js) — can run in parallel with US1
- **US3 E2E Tests (Phase 5)**: Depends on T005 (playwright.config.js) — can run in parallel with US1/US2
- **Polish (Phase 6)**: Depends on ALL user stories being complete

### User Story Dependencies

- **US1 (P1)**: Depends on Phase 2 only (factories)
- **US2 (P1)**: Depends on T004 (vitest.config.js) — independent of US1
- **US3 (P2)**: Depends on T005 (playwright.config.js) — independent of US1/US2

### Parallel Opportunities

**Phase 2 (Foundational)**: T007-T017 all run in parallel (different factory files).

**Phase 3 (US1)**: T019-T029 (model tests) all run in parallel. T030-T035 (V1 tests) all run in parallel. T040-T047 (admin tests) all run in parallel.

**Phase 4 (US2)**: T048-T052 all run in parallel (different test files).

**Phase 5 (US3)**: T054-T057 all run in parallel (different spec files).

---

## Parallel Example: User Story 1

```bash
# All model tests can run in parallel:
Task: "T019 [US1] Create CategoryTest.php"
Task: "T020 [US1] Create SeasonTest.php"
Task: "T021 [US1] Create CapabilityTest.php"
Task: "T022 [US1] Create SocialLinkTest.php"
Task: "T023 [US1] Create SiteSettingTest.php"
Task: "T024 [US1] Create ContactMessageTest.php"
Task: "T025 [US1] Create ProjectTest.php"
Task: "T026 [US1] Create PostTest.php"
Task: "T027 [US1] Create CourseTest.php"
Task: "T028 [US1] Create ProjectMediaTest.php"
Task: "T029 [US1] Create UserTest.php"

# All V1 API tests can run in parallel:
Task: "T030 [US1] Create V1/CategoryControllerTest.php"
Task: "T031 [US1] Create V1/ProjectControllerTest.php"
Task: "T032 [US1] Create V1/PostControllerTest.php"
Task: "T033 [US1] Create V1/CourseControllerTest.php"
Task: "T034 [US1] Create V1/SeasonControllerTest.php"
Task: "T035 [US1] Create V1/CapabilityControllerTest.php"
```

---

## Implementation Strategy

### MVP First (User Stories 1-2 Only)

1. Complete Phase 1: Setup (T001-T006)
2. Complete Phase 2: Foundational (T007-T018) — FACTORIES + SCRIPTS
3. Complete Phase 3: US1 Backend Tests (T019-T047)
4. Complete Phase 4: US2 Frontend Tests (T048-T052)
5. **STOP and VALIDATE**: `composer test` + `npm run test`
6. Deploy test infrastructure

### Full Delivery

1. Setup + Foundational → Foundation ready
2. US1 Backend Tests → Validate → `composer test` passes (MVP!)
3. US2 Frontend Tests → Validate → `npm run test` passes
4. US3 E2E Tests → Validate → `npx playwright test` passes
5. Polish → Coverage thresholds → Done

---

## Notes

- [P] tasks = different files, no dependencies
- [Story] label maps task to specific user story for traceability
- Each user story is independently completable and testable
- Commit after each phase or logical group
- Stop at any checkpoint to validate story independently
- This spec IS the test suite — all tasks create or configure tests
- CI integration deferred to SPEC-010 (user story 4)
