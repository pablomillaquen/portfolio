# Quickstart Validation: Scheduled Publication

**Date**: 2026-07-15
**Feature**: Scheduled Publication

## Prerequisites

- PHP 8.3+, Composer, Node.js 20+, MySQL
- Database migrated (`php artisan migrate`)
- Queue worker running (`php artisan queue:listen`)

## Validation Scenarios

### Scenario 1: Schedule a project for future publication

1. Log in as admin
2. Create a new project with `published_at` set to 2 minutes from now (using the datetime picker)
3. Verify the project appears in admin list with status "draft" and the scheduled datetime
4. Verify the project does NOT appear at `GET /api/projects`
5. Wait 2 minutes (or manually run `php artisan content:publish-scheduled`)
6. Verify the project now appears at `GET /api/projects`

### Scenario 2: Publish immediately with past date

1. Log in as admin
2. Create a new project with `published_at` set to 1 hour ago
3. Verify the project immediately appears at `GET /api/projects` with status "published"

### Scenario 3: Reschedule a published project

1. Log in as admin
2. Edit a published project, set `published_at` to 1 week from now
3. Verify the project disappears from `GET /api/projects`
4. Verify the admin list shows the new scheduled date
5. Run `php artisan content:publish-scheduled` — project should NOT publish (date is still future)
6. Clear `published_at` — verify the project stays in draft

### Scenario 4: Same behavior for posts

Repeat scenarios 1-3 using posts instead of projects.

### Scenario 5: Command catches overdue items

1. Manually set a project's `published_at` to 5 minutes ago and `status` to `draft` in the database
2. Run `php artisan content:publish-scheduled`
3. Verify the project's status changed to `published`

## Commands

```bash
# Run the scheduler manually (once)
php artisan content:publish-scheduled

# Run the scheduler via the scheduler (checks every minute)
php artisan schedule:run

# Check scheduled command output
php artisan content:publish-scheduled --verbose
```
