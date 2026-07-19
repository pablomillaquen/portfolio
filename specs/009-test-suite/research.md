# Research: Testing Suite

**Date**: 2026-07-17

## Research Tasks

### 1. PHPUnit Feature Testing for Laravel 12 API

**Decision**: Use PHPUnit 11.5 with Laravel's built-in testing utilities (RefreshDatabase, Factory, Fake)

**Rationale**:
- Already in composer.json dev dependencies (phpunit/phpunit ^11.5, fakerphp/faker, mockery/mockery)
- Laravel 12 provides `RefreshDatabase` trait for test isolation via transactions
- `Illuminate\Support\Facades\Http::fake()` for mocking external services
- `actingAs()` for authentication testing
- Standard `json()` method for API testing

**Alternatives considered**:
- Pest: Not installed, would add dependency. PHPUnit is sufficient for this scale.
- Mockery: Available but Laravel's built-in fakes (Mail, HTTP, Queue) are preferred for integration tests

**Best Practices**:
- One test class per controller/model
- Use `RefreshDatabase` trait on all feature tests
- Use `arr::only()` to filter response data for assertions
- Test status codes, response structure, validation errors, and authorization
- Group tests by domain (Admin, V1, Auth, Contact)

### 2. Vitest for Vue 3 Component Testing

**Decision**: Install Vitest + @vue/test-utils + jsdom for frontend unit testing

**Rationale**:
- Vitest is Vite-native — shares config with vite.config.js, fast HMR for tests
- @vue/test-utils is the official Vue testing library — `mount()` and `shallowMount()`
- jsdom provides DOM environment for component rendering
- Happy-dom is faster but jsdom is more compatible with Vue ecosystem

**Alternatives considered**:
- Jest: Works but requires separate config, slower with Vite projects
- Happy-dom: Faster but less compatible with some Vue features
- Cypress Component Testing: E2E-focused, overkill for unit tests

**Best Practices**:
- Test composable logic (useAnnouncer) in isolation
- Test component props, events, and slots
- Mock API calls with `vi.mock()`
- Use `nextTick()` for reactive updates
- Test accessibility attributes (aria-*, role)

### 3. Playwright E2E Testing

**Decision**: Install Playwright for E2E testing of critical user journeys

**Rationale**:
- Modern, fast, multi-browser (Chromium, Firefox, WebKit)
- Auto-wait mechanism reduces flaky tests
- Built-in assertions and test generators
- Good Vue SPA support
- API testing capabilities built-in

**Alternatives considered**:
- Cypress: Popular but slower, cloud-dependent for parallel runs, different API
- Puppeteer: Chrome-only, lower-level, more manual setup
- Selenium: Legacy, slower, more flaky

**Best Practices**:
- Use page object model for reusable selectors
- Test against running application (not mocked)
- Use `test.describe()` to group related tests
- Use fixtures for test data setup
- Mock external APIs (email, third-party) with Playwright's `route()` API
- Run against SQLite in-memory database for speed

### 4. Database Test Isolation

**Decision**: Use SQLite in-memory database with RefreshDatabase trait

**Rationale**:
- `phpunit.xml` already configures `DB_CONNECTION=sqlite` and `DB_DATABASE=:memory:`
- RefreshDatabase runs each test in a transaction and rolls back
- Fast — no disk I/O, no cleanup between tests
- Factories generate consistent test data

**Alternatives considered**- MySQL test database: Slower, requires cleanup, potential conflicts- Docker-based: Overhead for this project scale
- Mockery for models: Loses integration confidence

### 5. Test Factory Strategy

**Decision**: Create model factories for all 11 domain models

**Rationale**:
- Only `UserFactory` exists — all other models need factories
- Factories provide realistic, consistent test data
- Support states for different scenarios (published, scheduled, draft)
- Laravel's `factory()` helper integrates with tests

**Alternatives considered**:
- Seeders: Too broad, not granular enough for tests
- Fixtures (JSON): Static, harder to maintain
- Inline data: Duplicated across tests, hard to maintain

### 6. External Service Mocking

**Decision**: Use Laravel's fake() methods and Playwright's route() for mocking

**Rationale**:
- `Mail::fake()` prevents real email sends
- `Http::fake()` mocks external API calls
- `Queue::fake()` prevents real job dispatch
- Playwright's `page.route()` intercepts network requests in E2E

**Alternatives considered**:
- Real external calls: Slow, unreliable, cost money
- Docker-based mocks: Overhead, maintenance burden

## Summary of Technology Choices

| Layer | Tool | Version | Purpose |
|-------|------|---------|---------|
| Backend Unit/Feature | PHPUnit | 11.5 | API endpoint tests, model tests |
| Backend Factories | Faker + Laravel factories | — | Test data generation |
| Frontend Unit | Vitest | latest | Composable and component tests |
| Frontend Components | @vue/test-utils | latest | Vue component mounting |
| Frontend DOM | jsdom | latest | DOM environment for Vitest |
| E2E | Playwright | latest | Full-stack browser tests |
| Database | SQLite in-memory | — | Fast test database |
