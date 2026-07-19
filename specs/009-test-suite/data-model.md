# Data Model: Testing Suite

**Date**: 2026-07-17

## Test Factories

This feature adds model factories for test data generation. No new application entities are created — factories are test infrastructure only.

### Factory Inventory

| Factory | Model | Key Fields | States |
|---------|-------|------------|--------|
| `UserFactory` | User | name, email, password | `admin`, `verified` |
| `CategoryFactory` | Category | slug (JSON), order_column | `published` |
| `ProjectFactory` | Project | name (JSON), slug (JSON), description (JSON), featured, category_id, published_at | `draft`, `published`, `featured` |
| `PostFactory` | Post | title (JSON), slug (JSON), excerpt (JSON), content (JSON), season_id, category_id, published_at | `draft`, `published`, `scheduled` |
| `CourseFactory` | Course | title (JSON), slug (JSON), description (JSON), url, published_at | `draft`, `published` |
| `SeasonFactory` | Season | name (JSON), slug (JSON), order_column | `active` |
| `SocialLinkFactory` | SocialLink | platform, url, order_column | `active` |
| `SiteSettingFactory` | SiteSetting | key, value (JSON) | `defaults` |
| `ContactMessageFactory` | ContactMessage | name, email, subject, message | `read`, `replied` |
| `CapabilityFactory` | Capability | name (JSON), description (JSON), order_column | `active` |
| `ProjectMediaFactory` | ProjectMedia | project_id, type, url, order_column | `image`, `video`, `document` |

### Translatable Content Pattern

All factories that create translatable fields must generate both `es` and `en` JSON values:

```php
// Example: ProjectFactory
'name' => ['es' => fake()->words(3, true), 'en' => fake()->words(3, true)],
'slug' => ['es' => fake()->slug(), 'en' => fake()->slug()],
```

### Factory States

Each factory should define common states:

- **Temporal**: `draft` (no published_at), `published` (published_at = now), `scheduled` (published_at = future)
- **Relational**: `featured` (for Project), `active` (for Season, SocialLink, Capability)
- **Status**: `read`/`replied` (for ContactMessage)

## Test Database Configuration

- **Connection**: SQLite `:memory:` (configured in phpunit.xml)
- **Traits**: `RefreshDatabase` on all feature tests
- **Seeding**: No default seed — factories provide all test data
- **Isolation**: Each test runs in a transaction that rolls back

## Coverage Targets

| Layer | Target | Measurement |
|-------|--------|-------------|
| Backend Models | 100% of relationships, scopes, accessors | PHPUnit coverage |
| Backend Controllers | All API endpoints tested | Feature test count |
| Frontend Composables | 80% line coverage | Vitest coverage |
| Frontend Components | Critical rendering paths | Vitest coverage |
| E2E | 4 critical user journeys | Playwright spec count |
