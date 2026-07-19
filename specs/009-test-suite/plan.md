# Implementation Plan: Testing Suite

**Branch**: `009-test-suite` | **Date**: 2026-07-17 | **Spec**: [spec.md](spec.md)
**Status**: ✅ COMPLETE | **Completed**: 2026-07-17 | **Tests**: 153 PHPUnit, 13 Vitest, 16 Playwright specs

**Input**: Feature specification from `/specs/009-test-suite/spec.md`

## Summary

Add comprehensive testing infrastructure to the portfolio: PHPUnit feature tests for all 19 API controllers and 11 models, Vitest unit tests for the Vue composable and components, and Playwright E2E tests for critical user journeys. Currently only scaffold tests exist — zero project-specific tests, zero frontend tests, zero E2E tests.

## Technical Context

**Language/Version**: PHP 8.3, JavaScript ES2022 (Vue 3.5+, Vite 7)

**Primary Dependencies**: Laravel 12, PHPUnit 11.5, Vitest (new), Playwright (new), @vue/test-utils (new)

**Storage**: MySQL (production), SQLite `:memory:` (tests)

**Testing**: PHPUnit 11.5 (backend), Vitest (frontend), Playwright (E2E)

**Target Platform**: Modern browsers (Chrome 112+, Firefox 112+, Safari 15.5+, Edge 112+)

**Project Type**: SPA (Vue 3 frontend, Laravel API backend)

**Performance Goals**: Unit tests <1s each, feature tests <5s each, E2E suite <2 minutes total

**Constraints**: No new backend dependencies beyond what's in composer.json; Vitest and Playwright are frontend-only additions. Database tests use SQLite in-memory for speed.

**Scale/Scope**: 11 models, 19 controllers, 1 composable, 7 components, 9 pages, 3 middleware, 9 resources

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

| Principle | Status | Notes |
|-----------|--------|-------|
| API-First | ✅ PASS | Tests verify API endpoints return correct JSON responses |
| Bilingual (ES/EN) | ✅ PASS | Tests include locale switching scenarios |
| Admin CRUD Integrity | ✅ PASS | Admin controller tests verify `admin.session` middleware |
| Component-Based Frontend | ✅ PASS | Vitest tests verify component props, events, composables |
| Simplicity Over Abstraction | ✅ PASS | Standard Laravel/Vue testing patterns — no complex test frameworks or abstractions |

**Post-Phase 1 Re-check**: All gates pass. Tests use standard Laravel and Vue testing patterns (factory/fake for backend, mount for frontend). No abstraction layers.

## Project Structure

### Documentation (this feature)

```text
specs/009-test-suite/
├── spec.md              # Feature specification
├── plan.md              # This file
├── research.md          # Phase 0 output
├── data-model.md        # Phase 1 output (test factories)
├── quickstart.md        # Phase 1 output
├── contracts/           # Phase 1 output (test conventions)
└── tasks.md             # Phase 2 output (/speckit.tasks)
```

### Source Code (repository root)

```text
tests/
├── TestCase.php                          # Base class (add RefreshDatabase, WithFaker)
├── Feature/
│   ├── Api/
│   │   ├── Auth/                         # AuthController tests
│   │   ├── Contact/                      # ContactController tests
│   │   ├── Seo/                          # SeoController tests
│   │   ├── PublicContent/                # PublicContentController tests
│   │   ├── Admin/                        # Admin*Controller tests (8 controllers)
│   │   └── V1/                           # V1*Controller tests (6 public endpoints)
│   └── Models/                           # Model unit tests (11 models)
├── Unit/                                 # (reserved for pure PHP unit tests)
└── factories/                            # (move to database/factories/ — Laravel convention)

database/factories/                       # NEW factories for all 11 models
├── CategoryFactory.php
├── ProjectFactory.php
├── PostFactory.php
├── CourseFactory.php
├── SeasonFactory.php
├── SocialLinkFactory.php
├── SiteSettingFactory.php
├── ContactMessageFactory.php
├── CapabilityFactory.php
├── ProjectMediaFactory.php
└── UserFactory.php                       # EXISTS — extend for test use

resources/js/
├── __tests__/                            # NEW — Vitest test files
│   ├── composables/
│   │   └── useAnnouncer.test.js
│   ├── components/
│   │   ├── CategoryFilter.test.js
│   │   ├── ContentPreviewModal.test.js
│   │   └── PublicShell.test.js
│   └── pages/
│       └── ContactPage.test.js
└── ...existing files

e2e/                                      # NEW — Playwright E2E tests
├── playwright.config.js
├── navigation.spec.js                    # Public page navigation
├── contact-form.spec.js                  # Contact form submission
├── admin-crud.spec.js                    # Admin login + CRUD
├── language-switch.spec.js               # ES/EN toggle
└── fixtures/
    └── data.js                           # Test data fixtures

vitest.config.js                          # NEW — Vitest configuration
```

**Structure Decision**: Standard Laravel + Vue testing structure. PHPUnit tests in `tests/Feature/` organized by domain. Vitest tests in `resources/js/__tests__/` mirroring source structure. Playwright tests in `e2e/` at project root.

## Complexity Tracking

No constitution violations. No complexity tracking needed.
