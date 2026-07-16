# Implementation Plan: Scheduled Publication

**Branch**: `001-scheduled-publication` | **Date**: 2026-07-15 | **Spec**: [spec.md](./spec.md)

**Input**: Feature specification from `/specs/001-scheduled-publication/spec.md`

## Summary

Add automatic scheduled publication for projects and posts. When an admin creates content with a future `published_at` date, the item stays hidden until that time, then auto-publishes. Uses Laravel's existing queue system with a scheduled command to check for due publications.

**Key insight**: The `published_at` field already exists in both `projects` and `posts` tables and models. The missing pieces are:
1. **Backend automation**: A scheduled artisan command that finds items where `published_at <= now()` and `status = 'draft'`, then updates them to `status = 'published'`.
2. **Frontend datetime support**: The admin forms currently use `type="date"` inputs and truncate to date-only (`.substring(0, 10)`). These must be changed to `type="datetime-local"` to allow time selection.

## Technical Context

**Language/Version**: PHP 8.3, Laravel 12

**Primary Dependencies**: Laravel Framework 12 (Queue, Scheduler, Artisan)

**Storage**: MySQL (existing database)

**Testing**: PHPUnit 11.5 (Unit + Feature)

**Target Platform**: Web application (existing Laravel + Vue 3 stack)

**Project Type**: Web application (SPA with REST API)

**Performance Goals**: Scheduled items publish within 60 seconds of their scheduled time

**Constraints**: Must use existing queue infrastructure (`QUEUE_CONNECTION=database`). No new dependencies required.

**Scale/Scope**: Single admin user, low volume content (portfolio scale)

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

| Principle | Status | Notes |
|-----------|--------|-------|
| I. API-First Architecture | PASS | New logic in existing admin controllers + public endpoints remain JSON-only |
| II. Bilingual Support | PASS | No new user-facing strings requiring translation (admin UI already handles `published_at`) |
| III. Admin CRUD Integrity | PASS | Scheduling extends existing `admin.session` middleware-protected controllers |
| IV. Component-Based Frontend | PASS | Admin forms need `type="datetime-local"` and removal of date truncation — changes in existing AdminPage.vue |
| V. Simplicity Over Abstraction | PASS | Simple scheduled command + query filter — no repositories, no DTOs, no new patterns |

**Result**: All gates PASS. No violations to justify.

## Project Structure

### Documentation (this feature)

```text
specs/001-scheduled-publication/
├── plan.md              # This file
├── research.md          # Phase 0 output
├── data-model.md        # Phase 1 output
├── quickstart.md        # Phase 1 output
├── contracts/           # Phase 1 output
│   └── api-contracts.md
└── tasks.md             # Phase 2 output (/speckit.tasks command)
```

### Source Code (repository root)

```text
app/
├── Console/
│   └── Commands/
│       └── PublishScheduledContent.php    # NEW: artisan command
├── Models/
│   ├── Project.php                        # EXISTING: no changes needed
│   └── Post.php                           # EXISTING: no changes needed
├── Http/Controllers/Api/
│   ├── AdminProjectController.php         # EXISTING: add scheduled status logic
│   ├── AdminPostController.php            # EXISTING: add scheduled status logic
│   └── PublicContentController.php        # EXISTING: no changes (status filter sufficient)
└── Providers/
    └── AppServiceProvider.php             # EXISTING: register scheduler

resources/js/
└── pages/
    └── AdminPage.vue                      # EXISTING: datetime inputs + remove truncation

routes/
└── console.php                            # EXISTING: define schedule
```

**Structure Decision**: Single project structure. All changes are in existing files plus 1-2 new files under `app/Console/Commands/`. No new directories needed beyond what exists.

## Complexity Tracking

> No violations to justify — all constitution gates pass.
