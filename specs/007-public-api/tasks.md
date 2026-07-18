# Tasks: Public API

**Input**: Design documents from `/specs/007-public-api/`

**Prerequisites**: plan.md, spec.md, research.md, data-model.md, contracts/, quickstart.md

**Organization**: Tasks are grouped by user story to enable independent implementation and testing of each story.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (e.g., US1, US2, US3)
- Include exact file paths in descriptions

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Install dependencies and create base configuration

- [x] T001 [P] Install Laravel Sanctum (`composer require laravel/sanctum`) and publish config
- [x] T002 [P] Install Scramble (`composer require dedoc/scramble`) and publish config
- [x] T003 [P] Publish CORS config (`php artisan config:publish cors`) and configure allowed origins in `config/cors.php`

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Database, rate limiters, and route structure that ALL user stories depend on

**⚠️ CRITICAL**: No user story work can begin until this phase is complete

- [x] T004 Run Sanctum migration (`php artisan migrate`) to create `personal_access_tokens` table
- [x] T005 Add `HasApiTokens` trait to `app/Models/User.php`
- [x] T006 Define rate limiters (anonymous 60/min, authenticated 120/min) in `app/Providers/AppServiceProvider.php`
- [x] T007 Create `routes/api.php` with versioned route structure (`/v1/` prefix)
- [x] T008 Register `api.php` routes in `bootstrap/app.php` with `apiPrefix: 'api'`

**Checkpoint**: Foundation ready — Sanctum installed, rate limiters defined, route structure in place

---

## Phase 3: User Story 1 — Discover and Explore the API (Priority: P1) 🎯 MVP

**Goal**: External developers find documentation listing all endpoints, parameters, and example responses with interactive testing.

**Independent Test**: Visit `/docs/api` — see all endpoints documented, can execute requests and see responses.

### Implementation for User Story 1

- [x] T009 [US1] Configure Scramble in `app/Providers/AppServiceProvider.php` — set `apiPath('api/v1')` and expose UI at `/docs/api`
- [x] T010 [US1] Add Scramble configuration to document rate limit headers and authentication requirements
- [x] T011 [US1] Verify docs page loads at `/docs/api` and shows all v1 endpoints
- [x] T012 [US1] Verify OpenAPI JSON spec generates at `/docs/api.json`

**Checkpoint**: Documentation page is live and shows all endpoints

---

## Phase 4: User Story 2 — Authenticate API Requests (Priority: P1)

**Goal**: External consumers can optionally authenticate with API keys for higher rate limits.

**Independent Test**: Create a token, make request with `X-API-Key` header, verify it's recognized. Without key, request still works.

### Implementation for User Story 2

- [x] T013 [US2] Create `app/Http/Middleware/EnsureApiKey.php` — optional API key validation via `X-API-Key` header
- [x] T014 [US2] Register `EnsureApiKey` middleware in `bootstrap/app.php` as middleware alias
- [x] T015 [US2] Apply `EnsureApiKey` middleware to versioned API routes in `routes/api.php`
- [x] T016 [US2] Verify: request with valid token returns 200, request with invalid token returns 401, request without token returns 200 (lower rate limit)

**Checkpoint**: API key auth works — optional, non-blocking for unauthenticated requests

---

## Phase 5: User Story 3 — Consume Portfolio Content via API (Priority: P1)

**Goal**: External applications receive structured, paginated JSON for projects, posts, courses, seasons, categories, and capabilities.

**Independent Test**: Call each `/api/v1/*` endpoint — verify paginated responses with correct fields.

### Implementation for User Story 3

- [x] T017 [P] [US3] Create `app/Http/Resources/ProjectResource.php` — list and detail response formats
- [x] T018 [P] [US3] Create `app/Http/Resources/PostResource.php` — list and detail response formats
- [x] T019 [P] [US3] Create `app/Http/Resources/CourseResource.php` — list and detail response formats
- [x] T020 [P] [US3] Create `app/Http/Resources/SeasonResource.php` — list response format
- [x] T021 [P] [US3] Create `app/Http/Resources/CategoryResource.php` — list response format
- [x] T022 [P] [US3] Create `app/Http/Resources/CapabilityResource.php` — list response format
- [x] T023 [P] [US3] Create `app/Http/Controllers/Api/V1/ProjectController.php` — list (paginated) + show (by slug) endpoints
- [x] T024 [P] [US3] Create `app/Http/Controllers/Api/V1/PostController.php` — list (paginated) + show (by slug) endpoints
- [x] T025 [P] [US3] Create `app/Http/Controllers/Api/V1/CourseController.php` — list (paginated) + show (by slug) endpoints
- [x] T026 [P] [US3] Create `app/Http/Controllers/Api/V1/SeasonController.php` — list endpoint with status filter
- [x] T027 [P] [US3] Create `app/Http/Controllers/Api/V1/CategoryController.php` — list endpoint with dimension filter
- [x] T028 [P] [US3] Create `app/Http/Controllers/Api/V1/CapabilityController.php` — list endpoint
- [x] T029 [US3] Define all v1 routes in `routes/api/v1.php` — projects, posts, courses, seasons, categories, capabilities
- [x] T030 [US3] Verify each endpoint returns paginated response with correct fields per data-model.md

**Checkpoint**: All content endpoints work, return paginated JSON, include correct fields

---

## Phase 6: User Story 4 — Rate Limiting and Fair Use (Priority: P2)

**Goal**: Rate limits enforce fair use — anonymous 60/min, authenticated 120/min. Clear error responses with retry info.

**Independent Test**: Make rapid requests — verify 429 response at threshold with `X-RateLimit-*` headers.

### Implementation for User Story 4

- [x] T031 [US4] Apply `throttle:api-anonymous` middleware to public content routes in `routes/api/v1.php`
- [x] T032 [US4] Apply `throttle:api-authenticated` middleware when API key is present
- [x] T033 [US4] Add custom rate limit response handler — return JSON with `retry_after` field on 429
- [x] T034 [US4] Verify rate limit headers (`X-RateLimit-Limit`, `X-RateLimit-Remaining`, `X-RateLimit-Reset`) are present in responses
- [x] T035 [US4] Verify 429 response format matches contract: `{"message": "...", "retry_after": N}`

**Checkpoint**: Rate limiting works, headers present, error format consistent

---

## Phase 7: User Story 5 — API Versioning (Priority: P2)

**Goal**: API endpoints use `/api/v1/` prefix. Existing SPA routes remain unchanged.

**Independent Test**: `/api/v1/projects` works. `/api/projects` (old) still works for SPA.

### Implementation for User Story 5

- [x] T036 [US5] Verify `/api/v1/` prefix works for all new versioned endpoints
- [x] T037 [US5] Verify existing SPA endpoints (`/api/projects`, `/api/posts`, etc.) still work unchanged
- [x] T038 [US5] Verify Scramble documents only v1 routes, not legacy endpoints

**Checkpoint**: Versioning works, backward compatibility maintained

---

## Phase 8: Polish & Cross-Cutting Concerns

**Purpose**: Final validation and cleanup

- [x] T039 Run `quickstart.md` validation scenarios — verify all 7 scenarios pass
- [x] T040 [P] Commit all changes with descriptive message

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies — can start immediately
- **Foundational (Phase 2)**: Depends on Setup completion — BLOCKS all user stories
- **US1 + US2 (P1)**: Can run in parallel after Foundational — independent of each other
- **US3 (P1)**: Can run after Foundational — depends on US2 middleware being available
- **US4 (P2)**: Depends on US3 (needs routes to apply throttle to)
- **US5 (P2)**: Can run in parallel with US3 — verifies existing routes
- **Polish (Phase 8)**: Depends on all user stories being complete

### User Story Dependencies

- **US1 (Documentation)**: Can start after Foundational — no dependencies on other stories
- **US2 (Auth)**: Can start after Foundational — no dependencies on other stories
- **US3 (Content)**: Depends on US2 (middleware applied to routes)
- **US4 (Rate Limiting)**: Depends on US3 (needs routes defined)
- **US5 (Versioning)**: Can start after Foundational — verifies existing routes

### Within Each User Story

- Resource classes (T017-T022) can all run in parallel
- Controllers (T023-T028) can all run in parallel (different files)
- Route definition (T029) must come after controllers
- Verification (T030) must come after routes

### Parallel Opportunities

- T001 + T002 + T003: Package installs can run simultaneously
- T017-T022: All API Resource classes can be created simultaneously
- T023-T028: All V1 controllers can be created simultaneously
- US1 and US2 can be implemented in parallel (different files)
- US5 can run alongside US3 (verification, no new code)

---

## Parallel Example: User Story 3

```bash
# Launch all API Resource classes together:
Task: "Create ProjectResource.php"
Task: "Create PostResource.php"
Task: "Create CourseResource.php"
Task: "Create SeasonResource.php"
Task: "Create CategoryResource.php"
Task: "Create CapabilityResource.php"

# Then launch all V1 controllers together:
Task: "Create V1/ProjectController.php"
Task: "Create V1/PostController.php"
Task: "Create V1/CourseController.php"
Task: "Create V1/SeasonController.php"
Task: "Create V1/CategoryController.php"
Task: "Create V1/CapabilityController.php"
```

---

## Implementation Strategy

### MVP First (User Stories 1 + 2 + 3)

1. Complete Phase 1: Setup (Sanctum, Scramble, CORS)
2. Complete Phase 2: Foundational (migration, rate limiters, route structure)
3. Complete Phase 3: US1 — Documentation page
4. Complete Phase 4: US2 — API key authentication
5. Complete Phase 5: US3 — Content endpoints
6. **STOP and VALIDATE**: Test all endpoints, verify docs, test auth
7. Deploy if ready

### Incremental Delivery

1. Setup + Foundational → Foundation ready
2. Add US1 + US2 + US3 (P1) → Test independently → Deploy (MVP!)
3. Add US4 + US5 (P2) → Test independently → Deploy
4. Each story adds value without breaking previous stories

---

## Notes

- [P] tasks = different files, no dependencies
- [Story] label maps task to specific user story for traceability
- Each user story should be independently completable and testable
- Commit after each task or logical group
- Stop at any checkpoint to validate story independently
