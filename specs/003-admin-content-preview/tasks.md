# Tasks: Admin Content Preview

**Input**: Design documents from `/specs/003-admin-content-preview/`

**Prerequisites**: plan.md, spec.md, research.md, data-model.md, contracts/

**Tests**: Not explicitly requested — excluded per template guidance.

**Organization**: Tasks are grouped by user story. Backend and frontend tasks are separated to enable parallel work.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (e.g., US1, US2, US3)
- Include exact file paths in descriptions

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Backend preview endpoint that all user stories depend on

- [x] T001 Create AdminPreviewController in `app/Http/Controllers/Api/AdminPreviewController.php` with `previewProject()` method
- [x] T002 [P] Create `POST /api/admin/preview` route in `routes/api.php` with `admin.session` middleware
- [x] T003 [P] Create ContentPreviewModal component in `resources/js/components/ContentPreviewModal.vue`

**Checkpoint**: Backend endpoint and modal component skeleton ready

---

## Phase 2: User Story 1 - Preview Post/Project Before Publishing (Priority: P1) 🎯 MVP

**Goal**: Admin can click Preview button to see how content will look to visitors

**Independent Test**: Create a draft post/project, click Preview, verify modal opens with rendered content, close modal and verify form data preserved

### Implementation for User Story 1

- [x] T004 [US1] Implement `previewProject()` method in `app/Http/Controllers/Api/AdminPreviewController.php` — accept JSON body, render markdown via `Str::markdown()`, return HTML
- [x] T005 [US1] Implement `previewPost()` method in `app/Http/Controllers/Api/AdminPreviewController.php` — same pattern as project but for post fields
- [x] T006 [US1] Add request validation in `AdminPreviewController` — validate type (project/post), locale (en/es), data (required object)
- [x] T007 [US1] Add ContentPreviewModal template in `resources/js/components/ContentPreviewModal.vue` — Teleport to body, modal overlay, backdrop click to close, close button
- [x] T008 [US1] Add ContentPreviewModal props and events — `html`, `title`, `locale`, `show` props; `close` event
- [x] T009 [US1] Add preview state refs in `resources/js/pages/AdminPage.vue` — `showPreviewModal`, `previewType`, `previewLocale`, `previewHtml`
- [x] T010 [US1] Add `openPreview(type)` function in `resources/js/pages/AdminPage.vue` — send form data to `/api/admin/preview`, set modal state
- [x] T011 [US1] Add "Preview" button to project form in `resources/js/pages/AdminPage.vue` — in `.cta-row` section
- [x] T012 [US1] Add "Preview" button to post form in `resources/js/pages/AdminPage.vue` — in `.cta-row` section
- [x] T013 [US1] Add ContentPreviewModal component to AdminPage template — pass props, handle close event
- [x] T014 [US1] Add preview modal styles in `resources/css/app.css` — reuse existing modal-overlay and modal-content patterns

**Checkpoint**: Preview button works for both projects and posts, modal opens/closes correctly

---

## Phase 3: User Story 2 - Preview with Bilingual Content (Priority: P2)

**Goal**: Toggle between Spanish and English in preview

**Independent Test**: Open preview with both EN/ES content, toggle language, verify all text fields update

### Implementation for User Story 2

- [x] T015 [US2] Add language toggle button in `resources/js/components/ContentPreviewModal.vue` — EN/ES toggle in modal header
- [x] T016 [US2] Add `toggle-locale` event in `resources/js/components/ContentPreviewModal.vue` — emit when language toggled
- [x] T017 [US2] Add `togglePreviewLocale()` function in `resources/js/pages/AdminPage.vue` — call preview API again with new locale
- [x] T018 [US2] Add preview endpoint locale parameter handling — accept locale in request, use `TranslatableContent::text()` for resolution

**Checkpoint**: Language toggle works without page reload, all text fields update

---

## Phase 4: User Story 3 - Preview Media and Rich Content (Priority: P2)

**Goal**: Preview renders images, videos, and markdown formatting correctly

**Independent Test**: Create project with images and markdown, verify all media renders in preview

### Implementation for User Story 3

- [x] T019 [US3] Add media rendering in `AdminPreviewController::previewProject()` — transform media array, render images with captions
- [x] T020 [US3] Add details rendering in `AdminPreviewController::previewProject()` — transform details array, render as labeled values
- [x] T021 [US3] Add stack rendering in `AdminPreviewController::previewProject()` — render stack array as tags/chips
- [x] T022 [US3] Add content preview HTML structure — wrap rendered content in semantic HTML with preview classes
- [x] T023 [US3] Add preview content styles in `resources/css/app.css` — style preview-project, preview-media, preview-details sections

**Checkpoint**: Media, details, and stack render correctly in preview

---

## Phase 5: Polish & Cross-Cutting Concerns

**Purpose**: Final validation and improvements

- [x] T024 Run `npm run build` to verify no compilation errors
- [x] T025 Run `composer test` to verify no PHP errors
- [x] T026 Run quickstart.md validation scenarios

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies — can start immediately
- **US1 (Phase 2)**: Depends on Setup completion — BLOCKS US2 and US3
- **US2 (Phase 3)**: Depends on US1 completion (uses same modal and preview function)
- **US3 (Phase 4)**: Depends on US1 completion (uses same modal and preview function)
- **Polish (Phase 5)**: Depends on all user stories being complete

### User Story Dependencies

- **US1 (Preview Core)**: Can start after Setup — No dependencies on other stories
- **US2 (Bilingual Toggle)**: Depends on US1 (same modal, same preview function)
- **US3 (Media/Rich Content)**: Depends on US1 (same modal, same preview function)

### Within Each User Story

- Backend implementation before frontend integration
- Core functionality before edge cases
- Story complete before moving to next priority

### Parallel Opportunities

- T002 + T003 (route registration + modal component) — can run in parallel
- T004 + T005 (project + post preview methods) — can run in parallel
- T019 + T020 + T021 (media, details, stack rendering) — can run in parallel

---

## Parallel Example: Setup Phase

```bash
# Launch route and modal component together:
Task: "T002 Create POST /api/admin/preview route in routes/api.php"
Task: "T003 Create ContentPreviewModal component in resources/js/components/ContentPreviewModal.vue"
```

---

## Implementation Strategy

### MVP First (US1 Only)

1. Complete Phase 1: Setup (backend endpoint + modal skeleton)
2. Complete Phase 2: User Story 1 (preview core functionality)
3. **STOP and VALIDATE**: Test preview works for projects and posts
4. Deploy/demo if ready

### Incremental Delivery

1. Add Setup → Backend ready
2. Add US1 → Preview works → Deploy/Demo (MVP!)
3. Add US2 → Language toggle → Deploy/Demo
4. Add US3 → Media rendering → Deploy/Demo
5. Each story adds value without breaking previous stories

---

## Notes

- All changes are in 4 files: `AdminPreviewController.php`, `routes/api.php`, `AdminPage.vue`, `ContentPreviewModal.vue`, `app.css`
- No database schema changes needed
- No new frontend dependencies required
- Reuses existing `Str::markdown()` and `TranslatableContent` logic
- Follows existing modal pattern from HomePage.vue
