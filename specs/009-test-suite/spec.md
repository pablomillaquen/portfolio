# Feature Specification: Testing Suite

**Feature Branch**: `009-test-suite`

**Created**: 2026-07-17

**Status**: Draft

**Input**: User description: "Testing Suite de tests (PHPUnit, Vitest, E2E)"

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Backend Unit & Feature Tests (Priority: P1)

As a developer, I want a comprehensive PHPUnit test suite covering models, controllers, and API endpoints so that I can verify backend logic works correctly and catch regressions before deployment.

**Why this priority**: Backend logic is the foundation — API endpoints, data validation, and business rules must be verified first. The constitution already mandates `composer test` before merge.

**Independent Test**: Run `composer test` — all backend tests pass. Can be fully tested by executing the PHPUnit suite and verifying 100% pass rate.

**Acceptance Scenarios**:

1. **Given** the test suite is set up, **When** I run `composer test`, **Then** all PHPUnit tests execute and pass
2. **Given** a model with relationships, **When** I run its unit test, **Then** relationships, scopes, and accessors are verified
3. **Given** an API endpoint, **When** I run its feature test, **Then** request/response, status codes, authentication, and validation are verified
4. **Given** a controller action, **When** I run its feature test, **Then** authorization, input handling, and JSON response structure are verified

---

### User Story 2 - Frontend Unit Tests (Priority: P1)

As a developer, I want Vitest unit tests for Vue composables and component logic so that frontend business logic is verified independently of the DOM.

**Why this priority**: Frontend composables (useAnnouncer, useSEO, etc.) contain logic that must be correct. Vitest runs fast and integrates with Vite.

**Independent Test**: Run `npm run test` — all Vitest tests pass. Can be tested by running the test suite and verifying coverage for composables.

**Acceptance Scenarios**:

1. **Given** the test suite is set up, **When** I run `npm run test`, **Then** all Vitest tests execute and pass
2. **Given** a composable (e.g., useAnnouncer), **When** I run its unit test, **Then** reactive state, function calls, and edge cases are verified
3. **Given** a Vue component with props, **When** I run its unit test, **Then** rendering, events, and prop handling are verified

---

### User Story 3 - End-to-End Tests (Priority: P2)

As a developer, I want E2E tests covering critical user journeys so that I can verify the full stack works together in a real browser.

**Why this priority**: E2E tests validate the integration between frontend and backend — critical paths like navigation, form submission, and content display. P2 because unit/feature tests cover most logic.

**Independent Test**: Run E2E test suite — all critical user journeys pass in a browser. Can be tested by running the full E2E suite against a running application.

**Acceptance Scenarios**:

1. **Given** the application is running, **When** I run E2E tests, **Then** navigation, page rendering, and API integration work end-to-end
2. **Given** the contact form, **When** I submit via E2E test, **Then** form validation, submission, and success feedback work correctly
3. **Given** the admin panel, **When** I log in via E2E test, **Then** authentication, CRUD operations, and logout work correctly
4. **Given** language switching, **When** I toggle via E2E test, **Then** content updates correctly for both locales

---

### User Story 4 - Test Configuration & CI Integration (Priority: P2)

As a developer, I want test configuration that integrates with the CI pipeline so that tests run automatically on every commit and PR.

**Why this priority**: Automated testing ensures quality gates are enforced. P2 because the configuration depends on having tests to run.

**Independent Test**: Push a commit — CI pipeline runs all test suites. Can be tested by triggering a CI run and verifying all suites execute.

**Acceptance Scenarios**:

1. **Given** the CI configuration, **When** I push a commit, **Then** PHPUnit, Vitest, and E2E tests run automatically
2. **Given** a test fails, **When** CI runs, **Then** the pipeline fails and reports which test failed
3. **Given** code coverage thresholds, **When** tests run, **Then** coverage reports are generated and thresholds enforced

---

### Edge Cases

- What happens when a database migration fails during test setup? Tests should use transactions and roll back cleanly.
- What happens when an E2E test depends on external services? Tests should mock external API calls or use test fixtures.
- What happens when frontend and backend tests run in parallel? Database seeding must avoid conflicts.
- What happens when a test is slow? Tests should complete within reasonable time limits (unit <1s, feature <5s, E2E <30s).

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST provide PHPUnit test suite for backend unit and feature tests
- **FR-002**: System MUST provide Vitest test suite for frontend composable and component unit tests
- **FR-003**: System MUST provide E2E test suite for full-stack user journey tests
- **FR-004**: All test suites MUST be runnable via single commands (`composer test`, `npm run test`, E2E command)
- **FR-005**: Tests MUST use database transactions and roll back after each test to ensure isolation
- **FR-006**: E2E tests MUST mock external services (email, third-party APIs) to avoid real side effects
- **FR-007**: Test configuration MUST support both local development and CI environments
- **FR-008**: Code coverage MUST be measurable for both backend and frontend
- **FR-009**: Tests MUST be organized by type (unit, feature, E2E) and by domain (models, controllers, composables)
- **FR-010**: System MUST provide test fixtures/factories for consistent test data

### Key Entities

- **Test Suite**: A collection of tests organized by type (unit, feature, E2E) and domain
- **Test Case**: An individual test with setup, action, and assertion phases
- **Test Factory**: Generates consistent, realistic test data for models
- **Test Fixture**: Predefined data state for specific test scenarios
- **Coverage Report**: Metrics showing which code paths are exercised by tests

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: All existing API endpoints have at least one feature test
- **SC-002**: All Vue composables have at least one unit test
- **SC-003**: Critical user journeys (navigation, contact form, admin CRUD) have E2E coverage
- **SC-004**: `composer test` passes with 0 failures
- **SC-005**: `npm run test` passes with 0 failures
- **SC-006**: E2E test suite completes in under 2 minutes
- **SC-007**: Backend code coverage ≥ 70% (line coverage)
- **SC-008**: Frontend composable coverage ≥ 80% (line coverage)
- **SC-009**: Test suite runs automatically on every push via CI

## Assumptions

- PHPUnit 11.5 is already configured in the project (per constitution)
- Vitest needs to be added to the frontend dependencies
- E2E testing will use Playwright (modern, fast, multi-browser)
- Database tests use SQLite in-memory for speed
- External services (email, storage) will be faked/mocked in tests
- Admin panel is included in E2E scope (critical user journey)
- CI pipeline already exists or will be configured in SPEC-010
