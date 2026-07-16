# API Contract: Admin Content Preview

**Feature**: 003-admin-content-preview
**Date**: 2026-07-16

## Endpoint

### POST /api/admin/preview

**Purpose**: Render content preview from form data without saving to database

**Authentication**: Required (`admin.session` middleware)

**Content-Type**: application/json

---

### Request

#### Headers

```
Content-Type: application/json
X-Requested-With: XMLHttpRequest
```

#### Body

```json
{
  "type": "project",
  "locale": "en",
  "data": {
    "title": {
      "es": "Título del Proyecto",
      "en": "Project Title"
    },
    "summary": {
      "es": "Resumen del proyecto",
      "en": "Project summary"
    },
    "description": {
      "es": "Descripción con **markdown**",
      "en": "Description with **markdown**"
    },
    "details": [
      {
        "label": {
          "es": "Cliente",
          "en": "Client"
        },
        "value": {
          "es": "Acme Corp",
          "en": "Acme Corp"
        }
      }
    ],
    "media": [
      {
        "kind": "image",
        "url": "https://example.com/image.jpg",
        "caption": {
          "es": "Foto del proyecto",
          "en": "Project photo"
        }
      }
    ],
    "stack": ["Laravel", "Vue", "Tailwind"],
    "cover_image_url": "https://example.com/cover.jpg",
    "demo_url": "https://demo.example.com",
    "repository_url": "https://github.com/example/project"
  }
}
```

#### Field Descriptions

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `type` | string | Yes | Content type: "project" or "post" |
| `locale` | string | Yes | Language: "en" or "es" |
| `data` | object | Yes | Form data to preview |
| `data.title` | object | Yes | Bilingual title {es, en} |
| `data.summary` | object | No | Bilingual summary (projects only) |
| `data.description` | object | No | Bilingual markdown description (projects) |
| `data.content` | object | No | Bilingual markdown content (posts) |
| `data.excerpt` | object | No | Bilingual excerpt (posts only) |
| `data.details` | array | No | Project details array |
| `data.media` | array | No | Project media array |
| `data.stack` | array | No | Technology stack array |
| `data.cover_image_url` | string | No | Cover image URL |
| `data.demo_url` | string | No | Demo URL (projects only) |
| `data.repository_url` | string | No | Repository URL (projects only) |
| `data.external_url` | string | No | External URL (posts only) |

---

### Response

#### Success (200 OK)

```json
{
  "html": "<article class=\"preview-project\"><h1>Project Title</h1><div class=\"preview-summary\">Project summary</div><div class=\"preview-description\"><p>Description with <strong>markdown</strong></p></div><div class=\"preview-details\"><dl><dt>Client</dt><dd>Acme Corp</dd></dl></div><div class=\"preview-media\"><img src=\"https://example.com/image.jpg\" alt=\"Project photo\"></div><div class=\"preview-stack\"><span>Laravel</span><span>Vue</span><span>Tailwind</span></div></article>",
  "title": "Project Title",
  "locale": "en"
}
```

#### Response Fields

| Field | Type | Description |
|-------|------|-------------|
| `html` | string | Rendered HTML content |
| `title` | string | Resolved title for display |
| `locale` | string | Resolved locale |

---

### Error Responses

#### 401 Unauthorized

```json
{
  "message": "Unauthenticated"
}
```

#### 422 Validation Error

```json
{
  "message": "The data field is required.",
  "errors": {
    "type": ["The type field is required."],
    "locale": ["The locale field is required."],
    "data": ["The data field is required."]
  }
}
```

#### 500 Server Error

```json
{
  "message": "Failed to render preview"
}
```

---

## Usage Examples

### Preview a Project

```javascript
const response = await api.post('/api/admin/preview', {
  type: 'project',
  locale: 'en',
  data: projectForm
});
// response.data.html contains rendered preview
```

### Preview a Post

```javascript
const response = await api.post('/api/admin/preview', {
  type: 'post',
  locale: 'es',
  data: postForm
});
// response.data.html contains rendered preview
```

### Toggle Language in Preview

```javascript
// Switch from EN to ES
const response = await api.post('/api/admin/preview', {
  type: 'project',
  locale: 'es',  // Changed locale
  data: projectForm
});
```
