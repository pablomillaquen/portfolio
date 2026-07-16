# Data Model: Scheduled Publication

**Date**: 2026-07-15
**Feature**: Scheduled Publication

## Existing Entities (no schema changes)

### Project

| Field | Type | Nullable | Notes |
|-------|------|----------|-------|
| id | bigint (PK) | no | Auto-increment |
| slug | string | no | Unique |
| status | string | no | Default: `draft`. Values: `draft`, `published` |
| featured | boolean | no | Default: false |
| sort_order | unsigned int | no | Default: 0 |
| cover_image_url | string | yes | |
| demo_url | string | yes | |
| repository_url | string | yes | |
| title | json | no | `{es: "...", en: "..."}` |
| summary | json | no | `{es: "...", en: "..."}` |
| description | json | no | `{es: "...", en: "..."}` |
| details | json | yes | |
| stack | json | yes | |
| published_at | timestamp | yes | **Schedule field** — nullable datetime for planned publication |
| created_at | timestamp | no | |
| updated_at | timestamp | no | |

### Post

| Field | Type | Nullable | Notes |
|-------|------|----------|-------|
| id | bigint (PK) | no | Auto-increment |
| type | string | no | Default: `internal`. Values: `internal`, `external` |
| slug | string | no | Unique |
| status | string | no | Default: `draft`. Values: `draft`, `published` |
| featured | boolean | no | Default: false |
| cover_image_url | string | yes | |
| external_url | string | yes | |
| share_enabled | boolean | no | Default: true |
| title | json | no | `{es: "...", en: "..."}` |
| excerpt | json | no | `{es: "...", en: "..."}` |
| content | json | yes | `{es: "...", en: "..."}` |
| published_at | timestamp | yes | **Schedule field** — nullable datetime for planned publication |
| created_at | timestamp | no | |
| updated_at | timestamp | no | |

## State Transitions

### Content Lifecycle

```
draft ──────────────────> published (auto, when published_at <= now)
draft ──────────────────> published (immediate, if published_at is past at save)
published ──────────────> draft (when admin sets future published_at)
published ──────────────> published (admin keeps published, no change)
```

### Rules

| Current State | Trigger | New State | Condition |
|---------------|---------|-----------|-----------|
| draft | Scheduled command runs | published | `published_at <= now()` and `published_at IS NOT NULL` |
| draft | Admin saves with past date | published | `published_at <= now()` at save time |
| published | Admin sets future `published_at` | draft | `published_at > now()` |
| published | Admin clears `published_at` | published | No change — item stays published |
| draft | Admin clears `published_at` | draft | No change — item stays draft |

## Validation Rules (existing, unchanged)

- `status`: required, in `draft`, `published`
- `published_at`: nullable, date

## Relationships

- `Project` hasMany `ProjectMedia` (existing, unchanged)
- `Post` has no related entities (existing, unchanged)
