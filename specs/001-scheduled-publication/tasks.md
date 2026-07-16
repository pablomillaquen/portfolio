# Tasks: Scheduled Publication

**Input**: Design documents from `/specs/001-scheduled-publication/`

**Prerequisites**: plan.md, spec.md, research.md, data-model.md, contracts/api-contracts.md

**Tests**: Not explicitly requested — excluded per template guidance.

**Organization**: Tasks are grouped by user story to enable independent implementation and testing of each story.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (e.g., US1, US2, US3)
- Include exact file paths in descriptions

---

## Phase 1: Foundational (Backend Automation)

**Purpose**: Create the artisan command and scheduler registration that ALL user stories depend on

**⚠️ CRITICAL**: No user story work is testable until this phase is complete

- [x] T001 Create `PublishScheduledContent` artisan command in `app/Console/Commands/PublishScheduledContent.php` — queries `projects` and `posts` where `status = 'draft'` AND `published_at IS NOT NULL` AND `published_at <= now()`, updates to `status = 'published'`, logs count
- [x] T002 Register scheduler in `routes/console.php` — add `Schedule::command('content:publish-scheduled')->everyMinute()`

**Checkpoint**: Backend automation ready — scheduled command runs every minute and publishes overdue items

---

## Phase 2: User Story 1 + 2 — Schedule Projects & Posts (Priority: P1) 🎯 MVP

**Goal**: Admins can create projects and posts with a future datetime, items stay hidden until scheduled time, then auto-publish

**Independent Test**: Create a project with a datetime 2 minutes from now, verify it does NOT appear at `GET /api/projects`, wait 2 minutes (or run `php artisan content:publish-scheduled`), verify it appears

### Implementation for User Story 1 + 2

- [x] T003 [P] [US1] Add scheduled status logic to `AdminProjectController.php` `store()` method — after validation, if `published_at` is in the past set `status = 'published'`, if in the future set `status = 'draft'`
- [x] T004 [P] [US2] Add scheduled status logic to `AdminPostController.php` `store()` method — same logic as T003
- [x] T005 [P] [US1] Add scheduled status logic to `AdminProjectController.php` `update()` method — if `published_at` set to future on published item, set `status = 'draft'`; if `published_at` cleared, keep current status
- [x] T006 [P] [US2] Add scheduled status logic to `AdminPostController.php` `update()` method — same logic as T005
- [x] T007 [P] [US1] Change project form input in `AdminPage.vue:283` — `type="date"` → `type="datetime-local"`
- [x] T008 [P] [US2] Change post form input in `AdminPage.vue:354` — `type="date"` → `type="datetime-local"`
- [x] T009 [P] [US1] Remove date truncation in `AdminPage.vue:82` `fillProject()` — delete `.substring(0, 10)` so full datetime is preserved
- [x] T010 [P] [US2] Remove date truncation in `AdminPage.vue:89` `fillPost()` — delete `.substring(0, 10)` so full datetime is preserved

**Checkpoint**: US1 and US2 fully functional — admin can schedule projects and posts with datetime, items auto-publish

---

## Phase 3: User Story 3 — Edit or Cancel Scheduled Publication (Priority: P2)

**Goal**: Admins can reschedule or cancel a scheduled publication

**Independent Test**: Schedule a post, change date to next week, verify it stays hidden. Clear `published_at`, verify it stays draft.

### Implementation for User Story 3

- [x] T011 [US3] Verify reschedule behavior — when `published_at` changed to future on published item, `AdminProjectController::update()` sets `status = 'draft'` (covered by T005, T006 — confirm edge case handling)
- [x] T012 [US3] Verify cancel behavior — when `published_at` cleared, item stays in current status (covered by T005, T006 — confirm null handling)

**Checkpoint**: All user stories independently functional — scheduling, auto-publish, reschedule, and cancel all work

---

## Phase 4: Polish & Validation

**Purpose**: Final validation and cross-cutting concerns

- [x] T013 Run quickstart.md validation scenarios — verify all 5 scenarios pass
- [x] T014 Run `composer test` to ensure no regressions

---

## Dependencies & Execution Order

### Phase Dependencies

- **Foundational (Phase 1)**: No dependencies — can start immediately
- **US1 + US2 (Phase 2)**: Depends on Phase 1 completion — BLOCKS testing
- **US3 (Phase 3)**: Depends on Phase 2 completion — tests reschedule/cancel
- **Polish (Phase 4)**: Depends on all user stories being complete

### User Story Dependencies

- **US1 (Schedule Project)**: Can start after Phase 1 — No dependencies on other stories
- **US2 (Schedule Post)**: Can start after Phase 1 — No dependencies on other stories
- **US3 (Edit/Cancel)**: Can start after Phase 2 — Depends on controller logic being in place

### Within Each User Story

- Backend controller logic before frontend changes
- Frontend changes are independent of each other (different input elements)

### Parallel Opportunities

- T003 + T004 (controller store logic) — different files
- T005 + T006 (controller update logic) — different files
- T007 + T008 (form inputs) — different lines, same file but independent
- T009 + T010 (truncation removal) — different functions, same file

---

## Parallel Example: User Story 1 + 2

```bash
# Launch all controller changes together:
Task: "T003 Add scheduled status logic to AdminProjectController store()"
Task: "T004 Add scheduled status logic to AdminPostController store()"

# Launch all frontend changes together:
Task: "T007 Change project form input type"
Task: "T008 Change post form input type"
Task: "T009 Remove project date truncation"
Task: "T010 Remove post date truncation"
```

---

## Implementation Strategy

### MVP First (US1 + US2 Combined)

1. Complete Phase 1: Foundational (artisan command + scheduler)
2. Complete Phase 2: US1 + US2 (controller logic + frontend)
3. **STOP and VALIDATE**: Test scheduling a project and a post with datetime
4. Deploy/demo if ready

### Incremental Delivery

1. Complete Foundational → Backend automation ready
2. Add US1 + US2 → Test independently → Deploy/Demo (MVP!)
3. Add US3 → Test reschedule/cancel → Deploy/Demo
4. Each story adds value without breaking previous stories

---

## Notes

- [P] tasks = different files, no dependencies
- [Story] label maps task to specific user story for traceability
- Each user story should be independently completable and testable
- Commit after each task or logical group
- Stop at any checkpoint to validate story independently
- The `published_at` field and validation already exist — no migrations or model changes needed
