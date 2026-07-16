# Research: Scheduled Publication

**Date**: 2026-07-15
**Feature**: Scheduled Publication

## R1: How to auto-publish items when `published_at` is reached

**Decision**: Use a Laravel artisan command registered in `routes/console.php` via `Schedule::command()` to run every minute.

**Rationale**: Laravel's built-in scheduler is the simplest and most reliable approach. No external services (cron, Redis, etc.) beyond what's already configured. The command queries for items where `published_at <= now()` and `status = 'draft'`, then updates them to `status = 'published'`.

**Alternatives considered**:
- **Queue job dispatched at creation time** (`PublishScheduledContent::dispatch($model)->delay($published_at)`): Rejected because if the queue worker restarts or the job fails, there's no fallback. The scheduled command is idempotent and catches all overdue items.
- **Database event/listener**: Overly complex for this scale. No benefit over a simple command.
- **Observer on model save**: Would require dispatching a delayed job, same issues as queue job approach.

## R2: How to filter scheduled items from public endpoints

**Decision**: Add a `where('published_at', '<=', now())` clause to public queries, or simply check `status = 'published'` (which the auto-publish command ensures).

**Rationale**: The existing public endpoints already filter by `status = 'published'`. Once the scheduled command updates `status` from `draft` to `published`, the item automatically appears in public queries. No additional filtering needed in public controllers.

**Alternatives considered**:
- **Add explicit `published_at <= now()` to public queries**: Unnecessary — the `status` field is the source of truth. The scheduled command is the bridge between `published_at` and `status`.
- **Add a `is_published` computed attribute**: Extra complexity with no benefit.

## R3: How to handle "publish immediately if date is in the past"

**Decision**: In the admin controllers' `store` and `update` methods, after validation, check if `published_at` is in the past. If so, set `status = 'published'` automatically.

**Rationale**: This is the simplest approach — handle it at save time rather than relying on the scheduled command to catch it on the next run. Immediate feedback for the admin.

**Alternatives considered**:
- **Let the scheduled command handle it**: Would introduce up to 60 seconds of delay. Not ideal UX.
- **Add a middleware or model event**: Overkill for two controllers.

## R4: How to handle "reschedule a published item"

**Decision**: When an admin sets a future `published_at` on an already-published item, set `status = 'draft'`. The scheduled command will re-publish it at the new time.

**Rationale**: Consistent with the spec requirement. The `status` field becomes the runtime state, while `published_at` is the intended schedule.

**Alternatives considered**:
- **Add a separate `scheduled_status` field**: Extra column, extra complexity. The existing `status` field is sufficient.

## R5: How to handle "cancel a scheduled publication"

**Decision**: When an admin clears `published_at` (sets to null), the item stays in `draft` status and the scheduled command ignores it.

**Rationale**: Already works by default — the command only processes items with a non-null `published_at` in the past.

## Summary

The implementation requires:
1. One new artisan command (`PublishScheduledContent`)
2. Registration in `routes/console.php` (run every minute)
3. Small logic additions to `AdminProjectController` and `AdminPostController` (handle past dates + reschedule)
4. No changes to `PublicContentController` (existing `status = 'published'` filter is sufficient)
5. No database migrations (field already exists)
6. No new dependencies

## R6: Frontend datetime support

**Decision**: Change `type="date"` inputs to `type="datetime-local"` in `AdminPage.vue` and remove the `.substring(0, 10)` truncation in `fillProject()` and `fillPost()`.

**Rationale**: The spec requires scheduling by "fecha y hora" (date and time). The current frontend only supports date selection. The `type="datetime-local"` HTML input is the simplest native solution — no date picker library needed.

**Changes**:
- `AdminPage.vue:283` — `<input v-model="projectForm.published_at" type="date">` → `type="datetime-local"`
- `AdminPage.vue:354` — `<input v-model="postForm.published_at" type="date">` → `type="datetime-local"`
- `AdminPage.vue:82` — Remove `.substring(0, 10)` truncation in `fillProject()`
- `AdminPage.vue:89` — Remove `.substring(0, 10)` truncation in `fillPost()`
