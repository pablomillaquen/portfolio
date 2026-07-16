# API Contracts: Scheduled Publication

**Date**: 2026-07-15

## Admin Endpoints (no changes to existing contracts)

### POST /api/admin/projects

Existing endpoint. `published_at` is already accepted as an optional datetime field (ISO 8601 format: `YYYY-MM-DDTHH:MM`).

**Behavior change**: When `published_at` is in the past at save time, the system automatically sets `status = 'published'`. When `published_at` is in the future, the system sets `status = 'draft'`.

### PUT /api/admin/projects/{project}

Existing endpoint. Same behavior changes as POST.

**New behavior**: When `published_at` is set to a future date on an already-published project, `status` changes to `draft'`. When `published_at` is cleared (null), the project stays in its current status.

### POST /api/admin/posts

Existing endpoint. Same behavior changes as projects.

### PUT /api/admin/posts/{post}

Existing endpoint. Same behavior changes as posts.

## Public Endpoints (no changes needed)

### GET /api/projects

Returns only items where `status = 'published'`. No change — the scheduled command ensures items are only `published` when their `published_at` has passed.

### GET /api/posts

Same as projects.

## Scheduled Command

### Artisan Command: `content:publish-scheduled`

Runs every minute via Laravel scheduler.

**Behavior**:
1. Query `projects` where `status = 'draft'` AND `published_at IS NOT NULL` AND `published_at <= now()`
2. Query `posts` where `status = 'draft'` AND `published_at IS NOT NULL` AND `published_at <= now()`
3. Update all matching records to `status = 'published'`
4. Log the count of published items

**Output**: JSON/log entry with count of items published per run.
