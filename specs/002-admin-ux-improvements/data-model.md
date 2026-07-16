# Data Model: Admin UX Improvements

**Date**: 2026-07-15
**Feature**: Admin UX Improvements

## Existing Entities (no schema changes)

This feature requires NO data model changes. All improvements are UI-only.

### Project (unchanged)

| Field | Type | Notes |
|-------|------|-------|
| id | bigint (PK) | Auto-increment |
| slug | string | Unique |
| status | string | `draft`, `published` |
| featured | boolean | **Displayed as ★ in admin list** |
| title | json | `{es: "...", en: "..."}` |
| description | json | Stores markdown — **supports `![alt](url)` images** |
| ... | ... | All other fields unchanged |

### Post (unchanged)

| Field | Type | Notes |
|-------|------|-------|
| id | bigint (PK) | Auto-increment |
| slug | string | Unique |
| status | string | `draft`, `published` |
| featured | boolean | **Displayed as ★ in admin list** |
| title | json | `{es: "...", en: "..."}` |
| content | json | Stores markdown — **supports `![alt](url)` images** |
| ... | ... | All other fields unchanged |

## State Transitions

No state transitions affected. This feature is purely visual/UI.

## Relationships

No relationship changes.
