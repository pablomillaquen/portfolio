# Tasks: Professional Knowledge Platform Foundation

**Input**: Design documents from `/specs/004-professional-knowledge-platform-foundation/`

**Prerequisites**: plan.md (required), spec.md (required for user stories), research.md, data-model.md, contracts/

**Tests**: Not explicitly requested — excluded per template guidance.

**Organization**: Tasks are grouped by user story to enable independent implementation and testing of each story.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (e.g., US1, US2, US3)
- Include exact file paths in descriptions

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Database migrations and models that all user stories depend on

- [x] T001 Create seasons table migration in `database/migrations/2026_07_16_000001_create_seasons_table.php`
- [x] T002 [P] Create categories table migration in `database/migrations/2026_07_16_000002_create_categories_table.php`
- [x] T003 [P] Create capabilities table migration in `database/migrations/2026_07_16_000003_create_capabilities_table.php`
- [x] T004 [P] Create project_post pivot table migration in `database/migrations/2026_07_16_000004_create_project_post_table.php`
- [x] T005 [P] Create category_project pivot table migration in `database/migrations/2026_07_16_000005_create_category_project_table.php`
- [x] T006 [P] Create category_season pivot table migration in `database/migrations/2026_07_16_000006_create_category_season_table.php`
- [x] T007 [P] Create capability_project pivot table migration in `database/migrations/2026_07_16_000007_create_capability_project_table.php`
- [x] T008 Add case study fields to projects table in `database/migrations/2026_07_16_000008_add_case_study_fields_to_projects_table.php`
- [x] T009 Add season fields to posts table in `database/migrations/2026_07_16_000009_add_season_fields_to_posts_table.php`
- [x] T010 Create Season model in `app/Models/Season.php`
- [x] T011 [P] Create Category model in `app/Models/Category.php`
- [x] T012 [P] Create Capability model in `app/Models/Capability.php`
- [x] T013 Update Project model with new relationships in `app/Models/Project.php`
- [x] T014 Update Post model with new relationships in `app/Models/Post.php`
- [x] T015 Run migrations and verify schema

**Checkpoint**: Database schema ready — all tables and relationships exist

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core API endpoints and admin CRUD that ALL user stories depend on

**⚠️ CRITICAL**: No user story work can begin until this phase is complete

- [x] T016 Create AdminSeasonController in `app/Http/Controllers/Api/AdminSeasonController.php`
- [x] T017 [P] Create AdminCategoryController in `app/Http/Controllers/Api/AdminCategoryController.php`
- [x] T018 [P] Create AdminCapabilityController in `app/Http/Controllers/Api/AdminCapabilityController.php`
- [x] T019 Add season routes in `routes/web.php` (POST, PUT, DELETE /api/admin/seasons)
- [x] T020 [P] Add category routes in `routes/web.php` (POST, PUT, DELETE /api/admin/categories)
- [x] T021 [P] Add capability routes in `routes/web.php` (POST, PUT, DELETE /api/admin/capabilities)
- [x] T022 Add public season endpoint in `app/Http/Controllers/Api/PublicContentController.php`
- [x] T023 [P] Add public category endpoint in `app/Http/Controllers/Api/PublicContentController.php`
- [x] T024 [P] Add public capability endpoint in `app/Http/Controllers/Api/PublicContentController.php`
- [x] T025 Seed predefined categories (Arquitectura, Investigación, Salud, etc.)
- [x] T026 Seed sample capabilities for testing

**Checkpoint**: Foundation ready — admin CRUD and public endpoints functional

---

## Phase 3: User Story 1 - Explorar capacidades profesionales (Priority: P1) 🎯 MVP

**Goal**: Visitor can identify professional capabilities on homepage

**Independent Test**: Access homepage and verify capabilities section displays 3-5 capabilities with names and descriptions

### Implementation for User Story 1

- [x] T027 [US1] Create CapabilityCard component in `resources/js/components/CapabilityCard.vue`
- [x] T028 [US1] Add capabilities API call in `resources/js/services/api.js`
- [x] T029 [US1] Integrate capabilities section in `resources/js/pages/HomePage.vue`
- [x] T030 [US1] Add capabilities styles in `resources/css/app.css`

**Checkpoint**: Capabilities displayed on homepage — US1 complete

---

## Phase 4: User Story 2 - Buscar proyectos por temática (Priority: P1)

**Goal**: Visitor can filter projects by categories

**Independent Test**: Access /projects, select category filter, verify filtered results

### Implementation for User Story 2

- [x] T031 [US2] Create CategoryFilter component in `resources/js/components/CategoryFilter.vue`
- [x] T032 [US2] Add category filter logic in `resources/js/pages/ProjectsPage.vue`
- [x] T033 [US2] Add projects filtering endpoint in `app/Http/Controllers/Api/PublicContentController.php`
- [x] T034 [US2] Add category styles in `resources/css/app.css`

**Checkpoint**: Category filtering works — US2 complete

---

## Phase 5: User Story 3 - Comprender un caso de estudio (Priority: P2)

**Goal**: Project displays as structured case study with Problem, Approach, Contribution

**Independent Test**: Access project detail, verify all case study sections are present

### Implementation for User Story 3

- [x] T035 [US3] Update project migration to include case study fields
- [x] T036 [US3] Add case study sections in `resources/js/pages/ProjectDetailPage.vue`
- [x] T037 [US3] Add case study API response in `app/Http/Controllers/Api/PublicContentController.php`
- [x] T038 [US3] Add case study styles in `resources/css/app.css`

**Checkpoint**: Projects display as case studies — US3 complete

---

## Phase 6: User Story 4 - Explorar temporadas (Priority: P2)

**Goal**: Posts are organized by seasons with episode ordering

**Independent Test**: Access /posts, verify seasons displayed, click season, verify episodes ordered

### Implementation for User Story 4

- [x] T039 [US4] Create SeasonList component in `resources/js/components/SeasonList.vue`
- [x] T040 [US4] Add seasons API call in `resources/js/services/api.js`
- [x] T041 [US4] Integrate seasons in `resources/js/pages/PostsPage.vue`
- [x] T042 [US4] Add season navigation (previous/next) in `resources/js/pages/PostDetailPage.vue`
- [x] T043 [US4] Add season styles in `resources/css/app.css`

**Checkpoint**: Posts organized by seasons — US4 complete

---

## Phase 7: User Story 5 - Navegar entre conocimiento relacionado (Priority: P3)

**Goal**: Bidirectional navigation between projects and posts

**Independent Test**: Navigate from project to related post, then back to project

### Implementation for User Story 5

- [x] T044 [US5] Create RelatedContent component in `resources/js/components/RelatedContent.vue`
- [x] T045 [US5] Add related posts to project detail in `resources/js/pages/ProjectDetailPage.vue`
- [x] T046 [US5] Add related project to post detail in `resources/js/pages/PostDetailPage.vue`
- [x] T047 [US5] Add related content API response in `app/Http/Controllers/Api/PublicContentController.php`
- [x] T048 [US5] Add related content styles in `resources/css/app.css`

**Checkpoint**: Bidirectional navigation works — US5 complete

---

## Phase 8: User Story 6 - Descubrir contenido futuro (Priority: P3)

**Goal**: Indicators for future content formats (videos, papers)

**Independent Test**: Access post/project, verify future content indicators visible

### Implementation for User Story 6

- [x] T049 [US6] Add future content indicator component in `resources/js/components/FutureContentIndicator.vue`
- [x] T050 [US6] Integrate indicators in post detail in `resources/js/pages/PostDetailPage.vue`
- [x] T051 [US6] Integrate indicators in project detail in `resources/js/pages/ProjectDetailPage.vue`
- [x] T052 [US6] Add future content styles in `resources/css/app.css`

**Checkpoint**: Future content indicators visible — US6 complete

---

## Phase 9: Polish & Cross-Cutting Concerns

**Purpose**: Final validation and improvements

- [x] T053 Run `npm run build` to verify no compilation errors
- [x] T054 Run `php artisan test` to verify no PHP errors
- [x] T055 Run quickstart.md validation scenarios
- [x] T056 Verify responsive design on mobile (375px)
- [x] T057 Verify responsive design on tablet (768px)
- [x] T058 Performance check: API responses <200ms

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies — can start immediately
- **Foundational (Phase 2)**: Depends on Setup completion — BLOCKS all user stories
- **US1 (Phase 3)**: Depends on Foundational completion — No dependencies on other stories
- **US2 (Phase 4)**: Depends on Foundational completion — No dependencies on other stories
- **US3 (Phase 5)**: Depends on Foundational completion — No dependencies on other stories
- **US4 (Phase 6)**: Depends on Foundational completion — No dependencies on other stories
- **US5 (Phase 7)**: Depends on US3 and US4 completion (needs project and post structures)
- **US6 (Phase 8)**: Depends on US3 and US4 completion (needs project and post pages)
- **Polish (Phase 9)**: Depends on all user stories being complete

### User Story Dependencies

- **US1 (Capabilities)**: Can start after Foundational — No dependencies on other stories
- **US2 (Categories)**: Can start after Foundational — No dependencies on other stories
- **US3 (Case Study)**: Can start after Foundational — No dependencies on other stories
- **US4 (Seasons)**: Can start after Foundational — No dependencies on other stories
- **US5 (Related Content)**: Depends on US3 (project structure) and US4 (post structure)
- **US6 (Future Content)**: Depends on US3 and US4 (needs project and post pages)

### Within Each User Story

- Models before services
- Services before endpoints
- Endpoints before frontend integration
- Core implementation before polish

### Parallel Opportunities

- T002 + T003 + T004 + T005 + T006 + T007 (all pivot migrations) — can run in parallel
- T011 + T012 (Category and Capability models) — can run in parallel
- T017 + T018 (Admin controllers) — can run in parallel
- T020 + T021 (routes) — can run in parallel
- T023 + T024 (public endpoints) — can run in parallel
- US1 + US2 + US3 + US4 — can run in parallel after Foundational

---

## Parallel Example: Setup Phase

```bash
# Launch all pivot migrations together:
Task: "T004 Create project_post pivot table migration"
Task: "T005 Create category_project pivot table migration"
Task: "T006 Create category_season pivot table migration"
Task: "T007 Create capability_project pivot table migration"

# Launch all models together:
Task: "T011 Create Category model"
Task: "T012 Create Capability model"
```

---

## Parallel Example: User Stories

```bash
# After Foundational phase, launch all P1 stories:
Task: "T027 Create CapabilityCard component (US1)"
Task: "T031 Create CategoryFilter component (US2)"

# Launch all P2 stories:
Task: "T035 Update project migration (US3)"
Task: "T039 Create SeasonList component (US4)"
```

---

## Implementation Strategy

### MVP First (US1 + US2 Only)

1. Complete Phase 1: Setup
2. Complete Phase 2: Foundational (CRITICAL - blocks all stories)
3. Complete Phase 3: US1 (Capabilities)
4. Complete Phase 4: US2 (Categories)
5. **STOP and VALIDATE**: Test capabilities and filtering independently
6. Deploy/demo if ready

### Incremental Delivery

1. Complete Setup + Foundational → Foundation ready
2. Add US1 → Capabilities visible → Deploy/Demo
3. Add US2 → Categories filtering → Deploy/Demo
4. Add US3 → Case study format → Deploy/Demo
5. Add US4 → Season organization → Deploy/Demo
6. Add US5 → Related content navigation → Deploy/Demo
7. Add US6 → Future content indicators → Deploy/Demo
8. Each story adds value without breaking previous stories

### Full Foundation SPEC Delivery

1. Complete all phases (Setup → Foundational → US1-US6 → Polish)
2. Run quickstart.md validation
3. Verify all success criteria
4. Tag baseline: `v1.0.0-foundation`

---

## Notes

- [P] tasks = different files, no dependencies
- [Story] label maps task to specific user story for traceability
- Each user story should be independently completable and testable
- Commit after each task or logical group
- Stop at any checkpoint to validate story independently
- This is a Foundation SPEC — implementation will be phased across multiple SPECs
