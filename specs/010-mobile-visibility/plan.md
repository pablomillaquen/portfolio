# Implementation Plan: Mobile Visibility Improvements

**Branch**: `010-mobile-visibility` | **Date**: 2026-07-17 | **Spec**: [spec.md](spec.md)

**Input**: Feature specification from `/specs/010-mobile-visibility/spec.md`

## Summary

Add mobile-first responsive improvements to the portfolio: hamburger navigation, responsive breakpoints, stacked list cards, touch-friendly interactions, and mobile performance optimizations. Currently the site has minimal mobile support — a single breakpoint at 900px, no hamburger menu, and no touch-specific styles.

## Technical Context

**Language/Version**: PHP 8.3, JavaScript ES2022 (Vue 3.5+, Vite 7)

**Primary Dependencies**: Laravel 12, Vue 3 (Composition API), Tailwind CSS v4 (installed, unused)

**Storage**: N/A (frontend-only feature)

**Testing**: Lighthouse Mobile, Chrome DevTools device emulation, manual testing on real devices

**Target Platform**: Mobile browsers (iOS Safari 15.5+, Chrome Android 112+, Samsung Internet)

**Project Type**: SPA (Vue 3 frontend, Laravel API backend)

**Performance Goals**: Mobile Performance score ≥80, no horizontal overflow, 60fps scroll

**Constraints**: Desktop layout must remain unchanged. Admin panel out of scope. No new JS dependencies.

**Scale/Scope**: 9 public pages, 7 shared components, 1 navigation component, 1 CSS file

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

| Principle | Status | Notes |
|-----------|--------|-------|
| API-First | ✅ PASS | No API changes — frontend-only CSS/JS feature |
| Bilingual (ES/EN) | ✅ PASS | Hamburger menu labels must be translatable via locale system |
| Admin CRUD Integrity | ✅ PASS | Admin panel excluded from scope |
| Component-Based Frontend | ✅ PASS | Hamburger menu is a new component; changes extend existing PublicShell |
| Simplicity Over Abstraction | ✅ PASS | Vanilla Vue 3 reactivity for menu toggle. CSS media queries. No complex patterns. |

**Post-Phase 1 Re-check**: All gates pass. Feature is pure CSS + minimal JS (reactive toggle). No abstraction layers.

## Project Structure

### Documentation (this feature)

```text
specs/010-mobile-visibility/
├── spec.md              # Feature specification
├── plan.md              # This file
├── research.md          # Phase 0 output
├── quickstart.md        # Phase 1 output
├── contracts/           # Phase 1 output (CSS conventions)
└── tasks.md             # Phase 2 output (/speckit.tasks)
```

### Source Code (repository root)

```text
resources/
├── css/app.css                          # ADD: breakpoints, hamburger, responsive rules
├── js/
│   ├── components/
│   │   ├── PublicShell.vue              # MODIFY: add hamburger toggle, mobile nav drawer
│   │   ├── MobileNavDrawer.vue          # NEW — slide-out navigation drawer
│   │   ├── CategoryFilter.vue           # MODIFY: touch-friendly sizing
│   │   └── SeasonList.vue              # MODIFY: touch-friendly sizing
│   ├── pages/
│   │   ├── HomePage.vue                 # MODIFY: CTA row responsive
│   │   ├── PostsPage.vue                # MODIFY: list-card stacking
│   │   ├── CoursesPage.vue             # MODIFY: list-card stacking
│   │   ├── PostDetailPage.vue          # MODIFY: post-navigation stacking
│   │   ├── ContactPage.vue             # MODIFY: form mobile padding
│   │   └── ProjectDetailPage.vue       # MODIFY: media-grid adjustments
│   └── router.js                       # NO CHANGES (CSS handles smooth scroll)
```

**Structure Decision**: Frontend-only changes. One new component (MobileNavDrawer.vue), one modified component (PublicShell.vue), CSS additions to app.css, and minor responsive adjustments to page components.

## Complexity Tracking

No constitution violations. No complexity tracking needed.
