# Implementation Plan: Admin UX Improvements

**Branch**: `002-admin-ux-improvements` | **Date**: 2026-07-15 | **Spec**: [spec.md](./spec.md)

**Input**: Feature specification from `/specs/002-admin-ux-improvements/spec.md`

## Summary

Improve the admin panel UX with three changes: (1) tabbed sub-navigation (List/Form) for Projects and Posts sections to provide full-width editing, (2) star icon for featured items in admin lists, and (3) markdown image support in description/content fields.

**Key insight**: All changes are frontend-only (AdminPage.vue). Markdown rendering already works on the public site via `Str::markdown()` in `PublicContentController`. No backend changes, no schema changes, no new dependencies.

## Technical Context

**Language/Version**: PHP 8.3, Laravel 12, Vue 3 (Composition API)

**Primary Dependencies**: Laravel 12, Vue 3, Tailwind CSS v4 (existing)

**Storage**: MySQL (no schema changes)

**Testing**: PHPUnit 11.5 (existing tests)

**Target Platform**: Web application (existing SPA)

**Project Type**: Web application (SPA with REST API)

**Performance Goals**: No performance impact — frontend-only changes

**Constraints**: No new dependencies. All changes in `AdminPage.vue`. Markdown rendering already works.

**Scale/Scope**: Single admin user, low volume content (portfolio scale)

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

| Principle | Status | Notes |
|-----------|--------|-------|
| I. API-First Architecture | PASS | No API changes — frontend-only |
| II. Bilingual Support | PASS | No new user-facing strings requiring translation (tab labels are English only in admin) |
| III. Admin CRUD Integrity | PASS | No changes to admin controllers or middleware |
| IV. Component-Based Frontend | PASS | Changes in existing AdminPage.vue, no new components needed |
| V. Simplicity Over Abstraction | PASS | Simple tab switching, star icon, markdown rendering — no complex patterns |

**Result**: All gates PASS. No violations to justify.

## Project Structure

### Documentation (this feature)

```text
specs/002-admin-ux-improvements/
├── plan.md              # This file
├── research.md          # Phase 0 output
├── data-model.md        # Phase 1 output
├── quickstart.md        # Phase 1 output
├── contracts/           # Phase 1 output (empty — no API changes)
└── tasks.md             # Phase 2 output (/speckit.tasks command)
```

### Source Code (repository root)

```text
resources/js/
└── pages/
    └── AdminPage.vue                      # EXISTING: all changes in this file

resources/css/
└── (existing styles)                      # May need minor tab/star styling
```

**Structure Decision**: All changes are in `AdminPage.vue`. No new files, no backend changes, no schema changes.

## Complexity Tracking

> No violations to justify — all constitution gates pass.
