# Quickstart: Testing Suite

**Date**: 2026-07-17

## Prerequisites

- PHP 8.3+ with SQLite extension
- Node.js 18+ with npm
- Composer installed
- (Optional) Playwright browsers installed for E2E

## Setup

### Backend Tests

```bash
# Clear config cache
composer test

# This runs: artisan config:clear && artisan test
# First run will execute the 2 example tests (should pass)
```

### Frontend Tests

```bash
# Install test dependencies
npm install --save-dev vitest @vue/test-utils jsdom

# Run tests
npm run test

# Run with coverage
npm run test -- --coverage
```

### E2E Tests

```bash
# Install Playwright
npm install --save-dev @playwright/test

# Install browsers
npx playwright install

# Run E2E tests
npx playwright test

# Run with UI mode
npx playwright test --ui
```

## Validation Scenarios

### Scenario 1: Backend API Tests Pass

**Setup**: Database migrations run, factories loaded
**Command**: `composer test`
**Expected**: All PHPUnit tests pass (0 failures, 0 errors)
**Duration**: <30 seconds

### Scenario 2: Frontend Unit Tests Pass

**Setup**: npm dependencies installed
**Command**: `npm run test`
**Expected**: All Vitest tests pass (0 failures)
**Duration**: <10 seconds

### Scenario 3: E2E Navigation Test

**Setup**: Application running locally (`composer dev`), Playwright installed
**Command**: `npx playwright test e2e/navigation.spec.js`
**Expected**: All public pages load, navigation works, no console errors
**Duration**: <30 seconds

### Scenario 4: E2E Contact Form Test

**Setup**: Application running, database seeded
**Command**: `npx playwright test e2e/contact-form.spec.js`
**Expected**: Form validates, submits, shows success message
**Duration**: <15 seconds

### Scenario 5: Admin CRUD Test

**Setup**: Application running, admin session active
**Command**: `npx playwright test e2e/admin-crud.spec.js`
**Expected**: Admin can create, read, update, delete content
**Duration**: <30 seconds

### Scenario 6: Language Toggle Test

**Setup**: Application running
**Command**: `npx playwright test e2e/language-switch.spec.js`
**Expected**: Language toggle switches content between ES and EN
**Duration**: <15 seconds

### Scenario 7: Code Coverage Report

**Setup**: Tests passing
**Command**: `php artisan test --coverage --min=70`
**Expected**: Coverage report generated, backend coverage ≥70%
**Duration**: <60 seconds

### Scenario 8: Full Test Suite

**Setup**: All dependencies installed, application built
**Command**: Run all three suites sequentially
**Expected**: All tests pass across all suites
**Duration**: <3 minutes total

## Troubleshooting

### Database Issues

- If tests fail with "database is locked", ensure no other process is using the SQLite database
- Run `php artisan config:clear` before testing

### Frontend Test Issues

- If Vitest can't find Vue components, check `vitest.config.js` alias configuration
- Ensure `@vue/test-utils` is installed: `npm list @vue/test-utils`

### E2E Test Issues

- If Playwright can't connect, ensure `composer dev` is running
- Check that the app URL matches `baseURL` in `playwright.config.js`
- Run `npx playwright install` if browsers are missing
