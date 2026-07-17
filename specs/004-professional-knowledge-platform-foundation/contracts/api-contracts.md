# API Contracts: Professional Knowledge Platform Foundation

**Feature**: SPEC-004
**Date**: 2026-07-16
**Status**: Phase 1 Design

## Overview

This document defines the API contracts for the Professional Knowledge Platform Foundation. All endpoints follow RESTful conventions and use JSON for data exchange.

## Base URL

```
/api
```

## Authentication

All admin endpoints require `admin.session` middleware.
Public endpoints require no authentication.

---

## Public Endpoints

### GET /api/capabilities

**Purpose**: Retrieve professional capabilities for display on homepage.

**Response**:
```json
{
  "data": [
    {
      "id": 1,
      "slug": "arquitectura-software",
      "name": {
        "en": "Software Architecture",
        "es": "Arquitectura de Software"
      },
      "description": {
        "en": "Design and implementation of scalable software systems",
        "es": "Diseño e implementación de sistemas de software escalables"
      },
      "sort_order": 1
    }
  ]
}
```

**Status Codes**:
- `200 OK`: Success
- `500 Internal Server Error`: Server error

---

### GET /api/categories

**Purpose**: Retrieve categories for filtering projects.

**Query Parameters**:
- `dimension` (optional): Filter by dimension (domain, capability, technology, methodology)

**Response**:
```json
{
  "data": [
    {
      "id": 1,
      "slug": "arquitectura",
      "dimension": "domain",
      "name": {
        "en": "Architecture",
        "es": "Arquitectura"
      },
      "description": {
        "en": "Software architecture projects",
        "es": "Proyectos de arquitectura de software"
      }
    }
  ]
}
```

**Status Codes**:
- `200 OK`: Success
- `500 Internal Server Error`: Server error

---

### GET /api/seasons

**Purpose**: Retrieve seasons for organizing posts.

**Query Parameters**:
- `status` (optional): Filter by status (active, completed, upcoming)

**Response**:
```json
{
  "data": [
    {
      "id": 1,
      "slug": "evidencia-en-salud",
      "status": "active",
      "name": {
        "en": "Evidence in Healthcare",
        "es": "Evidencia en Salud"
      },
      "description": {
        "en": "Series on evidence-based healthcare software",
        "es": "Serie sobre software de salud basado en evidencia"
      },
      "posts_count": 5,
      "sort_order": 1
    }
  ]
}
```

**Status Codes**:
- `200 OK`: Success
- `500 Internal Server Error`: Server error

---

### GET /api/projects

**Purpose**: Retrieve projects with filtering and pagination.

**Query Parameters**:
- `category` (optional): Filter by category slug
- `capability` (optional): Filter by capability slug
- `featured` (optional): Filter featured projects
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 12)

**Response**:
```json
{
  "data": [
    {
      "id": 1,
      "slug": "sistema-equipos-medicos",
      "status": "published",
      "featured": true,
      "cover_image_url": "https://example.com/image.jpg",
      "title": {
        "en": "Medical Equipment System",
        "es": "Sistema de Equipos Médicos"
      },
      "summary": {
        "en": "Healthcare equipment maintenance platform",
        "es": "Plataforma de mantenimiento de equipos de salud"
      },
      "categories": [
        {
          "slug": "salud",
          "name": {
            "en": "Healthcare",
            "es": "Salud"
          }
        }
      ],
      "capabilities": [
        {
          "slug": "arquitectura-software",
          "name": {
            "en": "Software Architecture",
            "es": "Arquitectura de Software"
          }
        }
      ]
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 2,
    "per_page": 12,
    "total": 15
  }
}
```

**Status Codes**:
- `200 OK`: Success
- `400 Bad Request`: Invalid query parameters
- `500 Internal Server Error`: Server error

---

### GET /api/projects/{slug}

**Purpose**: Retrieve a single project with all details.

**Response**:
```json
{
  "data": {
    "id": 1,
    "slug": "sistema-equipos-medicos",
    "status": "published",
    "featured": true,
    "cover_image_url": "https://example.com/image.jpg",
    "demo_url": "https://demo.example.com",
    "repository_url": "https://github.com/example/project",
    "title": {
      "en": "Medical Equipment System",
      "es": "Sistema de Equipos Médicos"
    },
    "summary": {
      "en": "Healthcare equipment maintenance platform",
      "es": "Plataforma de mantenimiento de equipos de salud"
    },
    "description": {
      "en": "Full description...",
      "es": "Descripción completa..."
    },
    "problem": {
      "en": "Healthcare facilities struggle with equipment maintenance...",
      "es": "Las instalaciones de salud luchan con el mantenimiento..."
    },
    "approach": {
      "en": "We designed a modular system...",
      "es": "Diseñamos un sistema modular..."
    },
    "contribution": {
      "en": "Created an open-source maintenance platform...",
      "es": "Creamos una plataforma de mantenimiento open-source..."
    },
    "what_it_demonstrates": {
      "en": "Demonstrates expertise in healthcare software...",
      "es": "Demuestra experiencia en software de salud..."
    },
    "project_status": "completed",
    "details": [
      {
        "label": {
          "en": "Client",
          "es": "Cliente"
        },
        "value": {
          "en": "Healthcare Provider",
          "es": "Proveedor de Salud"
        }
      }
    ],
    "stack": ["Laravel", "Vue.js", "MySQL"],
    "media": [
      {
        "id": 1,
        "kind": "image",
        "url": "https://example.com/screenshot.jpg",
        "caption": {
          "en": "Dashboard screenshot",
          "es": "Captura del dashboard"
        }
      }
    ],
    "categories": [
      {
        "slug": "salud",
        "name": {
          "en": "Healthcare",
          "es": "Salud"
        }
      }
    ],
    "capabilities": [
      {
        "slug": "arquitectura-software",
        "name": {
          "en": "Software Architecture",
          "es": "Arquitectura de Software"
        }
      }
    ],
    "related_posts": [
      {
        "id": 1,
        "slug": "diseno-sistema-equipos",
        "title": {
          "en": "Designing the Equipment System",
          "es": "Diseñando el Sistema de Equipos"
        },
        "season": {
          "slug": "evidencia-en-salud",
          "name": {
            "en": "Evidence in Healthcare",
            "es": "Evidencia en Salud"
          }
        },
        "episode_number": 1
      }
    ],
    "published_at": "2026-03-15T10:00:00Z"
  }
}
```

**Status Codes**:
- `200 OK`: Success
- `404 Not Found`: Project not found
- `500 Internal Server Error`: Server error

---

### GET /api/posts

**Purpose**: Retrieve posts with filtering and pagination.

**Query Parameters**:
- `season` (optional): Filter by season slug
- `project` (optional): Filter by project slug
- `featured` (optional): Filter featured posts
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 12)

**Response**:
```json
{
  "data": [
    {
      "id": 1,
      "slug": "diseno-sistema-equipos",
      "status": "published",
      "featured": true,
      "cover_image_url": "https://example.com/image.jpg",
      "title": {
        "en": "Designing the Equipment System",
        "es": "Diseñando el Sistema de Equipos"
      },
      "excerpt": {
        "en": "A deep dive into designing...",
        "es": "Un análisis profundo del diseño..."
      },
      "season": {
        "slug": "evidencia-en-salud",
        "name": {
          "en": "Evidence in Healthcare",
          "es": "Evidencia en Salud"
        }
      },
      "episode_number": 1,
      "related_project": {
        "slug": "sistema-equipos-medicos",
        "title": {
          "en": "Medical Equipment System",
          "es": "Sistema de Equipos Médicos"
        }
      },
      "published_at": "2026-03-20T10:00:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 12,
    "total": 30
  }
}
```

**Status Codes**:
- `200 OK`: Success
- `400 Bad Request`: Invalid query parameters
- `500 Internal Server Error`: Server error

---

### GET /api/posts/{slug}

**Purpose**: Retrieve a single post with all details.

**Response**:
```json
{
  "data": {
    "id": 1,
    "slug": "diseno-sistema-equipos",
    "status": "published",
    "featured": true,
    "cover_image_url": "https://example.com/image.jpg",
    "share_enabled": true,
    "title": {
      "en": "Designing the Equipment System",
      "es": "Diseñando el Sistema de Equipos"
    },
    "excerpt": {
      "en": "A deep dive into designing...",
      "es": "Un análisis profundo del diseño..."
    },
    "content": {
      "en": "Full markdown content...",
      "es": "Contenido completo en markdown..."
    },
    "season": {
      "id": 1,
      "slug": "evidencia-en-salud",
      "name": {
        "en": "Evidence in Healthcare",
        "es": "Evidencia en Salud"
      }
    },
    "episode_number": 1,
    "related_project": {
      "id": 1,
      "slug": "sistema-equipos-medicos",
      "title": {
        "en": "Medical Equipment System",
        "es": "Sistema de Equipos Médicos"
      }
    },
    "navigation": {
      "previous": {
        "slug": null,
        "title": null
      },
      "next": {
        "slug": "implementacion-sistema",
        "title": {
          "en": "Implementing the System",
          "es": "Implementando el Sistema"
        }
      }
    },
    "published_at": "2026-03-20T10:00:00Z"
  }
}
```

**Status Codes**:
- `200 OK`: Success
- `404 Not Found`: Post not found
- `500 Internal Server Error`: Server error

---

## Admin Endpoints

### POST /api/admin/seasons

**Purpose**: Create a new season.

**Request**:
```json
{
  "slug": "evidencia-en-salud",
  "status": "draft",
  "name": {
    "en": "Evidence in Healthcare",
    "es": "Evidencia en Salud"
  },
  "description": {
    "en": "Series on evidence-based healthcare software",
    "es": "Serie sobre software de salud basado en evidencia"
  },
  "sort_order": 1
}
```

**Response**:
```json
{
  "data": {
    "id": 1,
    "slug": "evidencia-en-salud",
    "status": "draft",
    "name": {
      "en": "Evidence in Healthcare",
      "es": "Evidencia en Salud"
    },
    "description": {
      "en": "Series on evidence-based healthcare software",
      "es": "Serie sobre software de salud basado en evidencia"
    },
    "sort_order": 1,
    "created_at": "2026-07-16T10:00:00Z"
  }
}
```

**Status Codes**:
- `201 Created`: Success
- `422 Unprocessable Entity`: Validation error
- `500 Internal Server Error`: Server error

---

### PUT /api/admin/seasons/{id}

**Purpose**: Update an existing season.

**Request**: Same as POST

**Response**: Same as POST

**Status Codes**:
- `200 OK`: Success
- `404 Not Found`: Season not found
- `422 Unprocessable Entity`: Validation error
- `500 Internal Server Error`: Server error

---

### DELETE /api/admin/seasons/{id}

**Purpose**: Delete a season.

**Response**:
```json
{
  "message": "Season deleted successfully"
}
```

**Status Codes**:
- `200 OK`: Success
- `404 Not Found`: Season not found
- `409 Conflict`: Season has posts
- `500 Internal Server Error`: Server error

---

### POST /api/admin/categories

**Purpose**: Create a new category.

**Request**:
```json
{
  "slug": "salud",
  "dimension": "domain",
  "name": {
    "en": "Healthcare",
    "es": "Salud"
  },
  "description": {
    "en": "Healthcare projects",
    "es": "Proyectos de salud"
  }
}
```

**Response**:
```json
{
  "data": {
    "id": 1,
    "slug": "salud",
    "dimension": "domain",
    "name": {
      "en": "Healthcare",
      "es": "Salud"
    },
    "description": {
      "en": "Healthcare projects",
      "es": "Proyectos de salud"
    },
    "created_at": "2026-07-16T10:00:00Z"
  }
}
```

**Status Codes**:
- `201 Created`: Success
- `422 Unprocessable Entity`: Validation error
- `500 Internal Server Error`: Server error

---

### PUT /api/admin/categories/{id}

**Purpose**: Update an existing category.

**Request**: Same as POST

**Response**: Same as POST

**Status Codes**:
- `200 OK`: Success
- `404 Not Found`: Category not found
- `422 Unprocessable Entity`: Validation error
- `500 Internal Server Error`: Server error

---

### DELETE /api/admin/categories/{id}

**Purpose**: Delete a category.

**Response**:
```json
{
  "message": "Category deleted successfully"
}
```

**Status Codes**:
- `200 OK`: Success
- `404 Not Found`: Category not found
- `409 Conflict`: Category has projects
- `500 Internal Server Error`: Server error

---

### POST /api/admin/projects/{id}/categories

**Purpose**: Assign categories to a project.

**Request**:
```json
{
  "category_ids": [1, 2, 3]
}
```

**Response**:
```json
{
  "message": "Categories assigned successfully"
}
```

**Status Codes**:
- `200 OK`: Success
- `404 Not Found`: Project not found
- `422 Unprocessable Entity`: Invalid category IDs
- `500 Internal Server Error`: Server error

---

### POST /api/admin/posts/{id}/season

**Purpose**: Assign a post to a season.

**Request**:
```json
{
  "season_id": 1,
  "episode_number": 1
}
```

**Response**:
```json
{
  "message": "Post assigned to season successfully"
}
```

**Status Codes**:
- `200 OK`: Success
- `404 Not Found`: Post not found
- `422 Unprocessable Entity`: Invalid season ID or episode number
- `500 Internal Server Error`: Server error

---

### POST /api/admin/posts/{id}/project

**Purpose**: Link a post to a related project.

**Request**:
```json
{
  "project_id": 1
}
```

**Response**:
```json
{
  "message": "Post linked to project successfully"
}
```

**Status Codes**:
- `200 OK`: Success
- `404 Not Found`: Post not found
- `422 Unprocessable Entity`: Invalid project ID
- `500 Internal Server Error`: Server error

---

## Error Response Format

All errors follow a consistent format:

```json
{
  "message": "Error message",
  "errors": {
    "field": [
      "Validation error message"
    ]
  }
}
```

## Pagination Format

All paginated responses include:

```json
{
  "data": [...],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 12,
    "total": 60
  }
}
```

## Translatable Response Format

All translatable fields use:

```json
{
  "field": {
    "en": "English value",
    "es": "Valor en español"
  }
}
```
