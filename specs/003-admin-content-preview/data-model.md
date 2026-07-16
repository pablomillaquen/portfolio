# Data Model: Admin Content Preview

**Feature**: 003-admin-content-preview
**Date**: 2026-07-16

## Overview

This feature does not introduce new database entities. It reuses existing Project and Post models with their current schema. The preview functionality operates on form data (unsaved) and renders it using the same transformation logic as public views.

## Existing Entities Used

### Project

| Field | Type | Description |
|-------|------|-------------|
| `id` | integer | Primary key |
| `slug` | string | URL-friendly identifier |
| `status` | string | draft/published |
| `featured` | boolean | Featured flag |
| `sort_order` | integer | Display order |
| `cover_image_url` | string (nullable) | Cover image URL |
| `demo_url` | string (nullable) | Live demo URL |
| `repository_url` | string (nullable) | Source code URL |
| `title` | JSON | Bilingual title {es, en} |
| `summary` | JSON | Bilingual summary {es, en} |
| `description` | JSON | Bilingual markdown description {es, en} |
| `details` | JSON (nullable) | Array of {label: {es,en}, value: {es,en}} |
| `stack` | JSON (nullable) | Array of technology strings |
| `published_at` | datetime (nullable) | Publication date |

### Post

| Field | Type | Description |
|-------|------|-------------|
| `id` | integer | Primary key |
| `type` | string | internal/external |
| `slug` | string | URL-friendly identifier |
| `status` | string | draft/published |
| `featured` | boolean | Featured flag |
| `cover_image_url` | string (nullable) | Cover image URL |
| `external_url` | string (nullable) | External link URL |
| `share_enabled` | boolean | Share button enabled |
| `title` | JSON | Bilingual title {es, en} |
| `excerpt` | JSON | Bilingual excerpt {es, en} |
| `content` | JSON (nullable) | Bilingual markdown content {es, en} |
| `published_at` | datetime (nullable) | Publication date |

### ProjectMedia

| Field | Type | Description |
|-------|------|-------------|
| `id` | integer | Primary key |
| `project_id` | integer | Foreign key to projects |
| `kind` | string | image/video |
| `url` | string | Media URL |
| `caption` | JSON | Bilingual caption {es, en} |
| `sort_order` | integer | Display order |

## Preview Request/Response Structure

### Preview Request (POST body)

```json
{
  "type": "project",
  "locale": "en",
  "data": {
    "title": { "es": "Título", "en": "Title" },
    "summary": { "es": "Resumen", "en": "Summary" },
    "description": { "es": "Descripción **markdown**", "en": "Description **markdown**" },
    "details": [
      { "label": { "es": "Cliente", "en": "Client" }, "value": { "es": "Acme", "en": "Acme" } }
    ],
    "media": [
      { "kind": "image", "url": "https://...", "caption": { "es": "Foto", "en": "Photo" } }
    ],
    "stack": ["Laravel", "Vue"],
    "cover_image_url": "https://...",
    "demo_url": "https://...",
    "repository_url": "https://..."
  }
}
```

### Preview Response

```json
{
  "html": "<article class='project-preview'>...</article>",
  "title": "Title",
  "locale": "en"
}
```

## State Transitions

No state transitions involved. Preview is a read-only operation on form data.

## Validation Rules

Preview endpoint validates:
- `type` must be 'project' or 'post'
- `locale` must be 'en' or 'es'
- `data` must be an object
- Required fields based on type (title, etc.)

No database validation occurs since preview operates on unsaved form data.
