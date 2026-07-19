# Test Conventions: Testing Suite

**Date**: 2026-07-17

## Test Directory Structure

### Backend (PHPUnit)

```text
tests/
├── TestCase.php                    # Base class with RefreshDatabase, WithFaker
├── Feature/
│   ├── Api/
│   │   ├── Auth/
│   │   │   └── AuthControllerTest.php
│   │   ├── Contact/
│   │   │   └── ContactControllerTest.php
│   │   ├── Seo/
│   │   │   └── SeoControllerTest.php
│   │   ├── PublicContent/
│   │   │   └── PublicContentControllerTest.php
│   │   ├── Admin/
│   │   │   ├── AdminCategoryControllerTest.php
│   │   │   ├── AdminProjectControllerTest.php
│   │   │   ├── AdminPostControllerTest.php
│   │   │   ├── AdminCourseControllerTest.php
│   │   │   ├── AdminSeasonControllerTest.php
│   │   │   ├── AdminCapabilityControllerTest.php
│   │   │   ├── AdminSocialLinkControllerTest.php
│   │   │   └── AdminSiteSettingControllerTest.php
│   │   └── V1/
│   │       ├── CategoryControllerTest.php
│   │       ├── ProjectControllerTest.php
│   │       ├── PostControllerTest.php
│   │       ├── CourseControllerTest.php
│   │       ├── SeasonControllerTest.php
│   │       └── CapabilityControllerTest.php
│   └── Models/
│       ├── CategoryTest.php
│       ├── ProjectTest.php
│       ├── PostTest.php
│       ├── CourseTest.php
│       ├── SeasonTest.php
│       ├── SocialLinkTest.php
│       ├── SiteSettingTest.php
│       ├── ContactMessageTest.php
│       ├── CapabilityTest.php
│       ├── ProjectMediaTest.php
│       └── UserTest.php
```

### Frontend (Vitest)

```text
resources/js/__tests__/
├── composables/
│   └── useAnnouncer.test.js
├── components/
│   ├── CategoryFilter.test.js
│   ├── ContentPreviewModal.test.js
│   └── PublicShell.test.js
└── pages/
    └── ContactPage.test.js
```

### E2E (Playwright)

```text
e2e/
├── playwright.config.js
├── navigation.spec.js
├── contact-form.spec.js
├── admin-crud.spec.js
├── language-switch.spec.js
└── fixtures/
    └── data.js
```

## Naming Conventions

### PHPUnit

- Test classes: `ascalCase{Entity}Test` (e.g., `ProjectControllerTest`)
- Test methods: `test_` prefix + snake_case description (e.g., `test_can_list_projects`)
- Files: `PascalCaseTest.php` in matching directory

### Vitest

- Test files: `*.test.js` matching source file name
- Test describe blocks: Component/composable name
- Test cases: descriptive sentence (e.g., 'announces message to polite region')

### Playwright

- Test files: `*.spec.js` with descriptive name
- Test describe blocks: Feature area
- Test cases: user-facing action (e.g., 'submits contact form successfully')

## Assertion Patterns

### PHPUnit API Tests

```php
// List endpoint
$this->getJson('/api/v1/projects')
    ->assertOk()
    ->assertJsonCount(3, 'data');

// Show endpoint
$this->getJson('/api/v1/projects/' . $project->id)
    ->assertOk()
    ->assertJsonPath('data.name.en', $project->name['en']);

// Validation
$this->postJson('/api/v1/contact', [])
    ->assertUnprocessable()
    ->assertJsonValidationErrors(['name', 'email']);

// Authorization
$this->getJson('/api/admin/projects')
    ->assertUnauthorized();
```

### Vitest Component Tests

```js
// Mount and assert
const wrapper = mount(Component, { props: { items: [] } })
expect(wrapper.find('[role="list"]').exists()).toBe(true)

// Emit event
await wrapper.find('button').trigger('click')
expect(wrapper.emitted('update')).toBeTruthy()
```

### Playwright E2E Tests

```js
// Navigate and assert
await page.goto('/')
await expect(page.locator('h1')).toBeVisible()

// Fill form
await page.fill('[data-testid="contact-name"]', 'Test User')
await page.click('[data-testid="contact-submit"]')
await expect(page.locator('.success-message')).toBeVisible()
```

## Test Data Strategy

- **Factories**: All test data created via model factories
- **States**: Use factory states for common scenarios (published, draft, featured)
- **Translatable fields**: Always provide both `es` and `en` values
- **Relationships**: Use factory associations (e.g., `Project::factory()->for($category)`)
- **External services**: Always fake/mock — never real calls in tests
