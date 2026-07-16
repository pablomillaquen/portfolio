# Tasks: Admin UX Improvements

**Input**: Design documents from `/specs/002-admin-ux-improvements/`

**Prerequisites**: plan.md, spec.md, research.md, data-model.md

**Tests**: Not explicitly requested — excluded per template guidance.

**Organization**: Tasks are grouped by user story. All changes are in a single file (`AdminPage.vue`), so parallel opportunities are limited.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (e.g., US1, US2, US3)
- Include exact file paths in descriptions

---

## Phase 1: User Story 1 — Tabbed Layout for Projects & Posts (Priority: P1) 🎯 MVP

**Goal**: Replace side-by-side list/form layout with tabbed sub-navigation (List tab + Form tab) for Projects and Posts sections

**Independent Test**: Navigate to Projects, verify "List" and "Form" tabs appear. Click item → switches to Form tab with data. Click "New" → switches to Form tab empty. Click "Back" → returns to list.

### Implementation for User Story 1

- [x] T001 [US1] Add `projectSectionTab` reactive ref (default: `'list'`) in `AdminPage.vue` script section
- [x] T002 [US1] Add `postSectionTab` reactive ref (default: `'list'`) in `AdminPage.vue` script section
- [x] T003 [US1] Update `fillProject()` in `AdminPage.vue` to set `projectSectionTab.value = 'form'` after populating data
- [x] T004 [US1] Update `fillPost()` in `AdminPage.vue` to set `postSectionTab.value = 'form'` after populating data
- [x] T005 [US1] Update `resetProjectForm()` in `AdminPage.vue` to set `projectSectionTab.value = 'form'` (for "New" button)
- [x] T006 [US1] Update `resetPostForm()` in `AdminPage.vue` to set `postSectionTab.value = 'form'` (for "New" button)
- [x] T007 [US1] Restructure Projects section template in `AdminPage.vue` — wrap existing list panel in `v-if="projectSectionTab === 'list'"`, wrap form in `v-if="projectSectionTab === 'form'"`
- [x] T008 [US1] Restructure Posts section template in `AdminPage.vue` — wrap existing list panel in `v-if="postSectionTab === 'list'"`, wrap form in `v-if="postSectionTab === 'form'"`
- [x] T009 [US1] Add "Back" button at top of Projects form in `AdminPage.vue` — `<button @click="projectSectionTab = 'list'">← Back</button>`
- [x] T010 [US1] Add "Back" button at top of Posts form in `AdminPage.vue` — `<button @click="postSectionTab = 'list'">← Back</button>`
- [x] T011 [US1] Update Projects section heading in `AdminPage.vue` — move "New" button to only show on List tab (`v-if="projectSectionTab === 'list'"`)
- [x] T012 [US1] Update Posts section heading in `AdminPage.vue` — move "New" button to only show on List tab (`v-if="postSectionTab === 'list'"`)
- [x] T013 [US1] Update `saveProject()` in `AdminPage.vue` — after save, set `projectSectionTab.value = 'form'` (remain on form with saved data)
- [x] T014 [US1] Update `savePost()` in `AdminPage.vue` — after save, set `postSectionTab.value = 'form'` (remain on form with saved data)

**Checkpoint**: Projects and Posts sections now use tabbed layout with List/Form sub-tabs

---

## Phase 2: User Story 2 — Featured Star Indicator (Priority: P1)

**Goal**: Show ★ star icon next to featured projects and posts in admin lists

**Independent Test**: Create project with `featured = true` → star appears. Create project with `featured = false` → no star. Same for posts.

### Implementation for User Story 2

- [x] T015 [P] [US2] Add star indicator to Projects list in `AdminPage.vue` — inside `admin-list-item` button, add `<span v-if="project.featured">★</span>` before title
- [x] T016 [P] [US2] Add star indicator to Posts list in `AdminPage.vue` — inside `admin-list-item` button, add `<span v-if="post.featured">★</span>` before title

**Checkpoint**: Featured items visually identified with star icon

---

## Phase 3: User Story 3 — Markdown Image Support (Priority: P2)

**Goal**: Verify markdown images work in description/content fields

**Independent Test**: No code changes needed — markdown already renders via `Str::markdown()` in `PublicContentController`.

### Verification for User Story 3

- [x] T017 [US3] Verify markdown rendering works — no code changes required, `Str::markdown()` already handles `![alt](url)` syntax in `PublicContentController.php:149,182`

**Checkpoint**: Markdown images confirmed working — no implementation needed

---

## Phase 4: Polish & Validation

**Purpose**: Final validation

- [x] T018 Run `npm run build` to verify no compilation errors
- [x] T19 Run quickstart.md validation scenarios

---

## Dependencies & Execution Order

### Phase Dependencies

- **US1 (Phase 1)**: No dependencies — can start immediately
- **US2 (Phase 2)**: Can start in parallel with US1 — different template sections
- **US3 (Phase 3)**: No code changes — verification only
- **Polish (Phase 4)**: Depends on all user stories being complete

### User Story Dependencies

- **US1 (Tabbed Layout)**: Can start immediately — no dependencies
- **US2 (Star Indicator)**: Can start in parallel with US1 — independent template changes
- **US3 (Markdown)**: No implementation needed — already works

### Parallel Opportunities

- T015 + T016 (star indicators for Projects and Posts) — can run in parallel
- US1 and US2 can be implemented concurrently since they touch different template sections

---

## Parallel Example: US1 + US2

```bash
# US1 and US2 can be done in parallel:
Task: "T001-T014 Restructure Projects and Posts templates for tabbed layout"
Task: "T015-T016 Add star indicators to list items"
```

---

## Implementation Strategy

### MVP First (US1 Only)

1. Complete Phase 1: Tabbed layout for Projects and Posts
2. **STOP and VALIDATE**: Test tabbed navigation works correctly
3. Deploy/demo if ready

### Incremental Delivery

1. Add US1 → Test independently → Deploy/Demo (MVP!)
2. Add US2 → Test star indicator → Deploy/Demo
3. US3 → Verification only → No deployment needed

---

## Notes

- All changes are in a single file: `resources/js/pages/AdminPage.vue`
- No backend changes, no schema changes, no new dependencies
- Markdown rendering already works — US3 is verification only
- The `fillProject()` and `fillPost()` functions already populate forms — just need to add tab switching
- The `admin-grid` class currently creates side-by-side layout — needs to be removed or replaced with tabbed structure
