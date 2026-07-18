# Implementation Plan: Public API

**Branch**: `007-public-api` | **Date**: 2026-07-17 | **Spec**: [spec.md](spec.md)

**Input**: Feature specification from `/specs/007-public-api/spec.md`

## Summary

Add a documented public API with optional API key authentication, rate limiting, versioned endpoints, and an interactive documentation page. The existing public endpoints in `PublicContentController` will be migrated to `/api/v1/` routes. API keys use Laravel Sanctum tokens. Documentation auto-generates from code using Scramble (OpenAPI 3.1).

## Technical Context

**Language/Version**: PHP 8.3, JavaScript (ES2022+)

**Primary Dependencies**: Laravel 12, Laravel Sanctum (built-in), Scramble (OpenAPI docs)

**Storage**: MySQL (existing database)

**Testing**: PHPUnit 11.5 (backend), manual API testing via documentation page

**Target Platform**: REST API consumed by external web/mobile applications

**Project Type**: Web application with public REST API

**Performance Goals**: API responses <200ms p95, rate limiting prevents abuse

**Constraints**: Admin routes remain session-based. Contact form remains web-based. Existing SPA continues consuming current endpoints.

**Scale/Scope**: Portfolio site with ~10 public endpoints, low-to-moderate external traffic

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

| Principle | Status | Notes |
|-----------|--------|-------|
| I. API-First | ✅ PASS | This SPEC formalizes the API with proper versioning, auth, and docs. Aligns with principle. |
| II. Bilingual | ✅ PASS | API responses include locale resolution. Documentation supports both languages. |
| III. Admin CRUD | ✅ PASS | Admin routes remain session-based. Public API only exposes published content. |
| IV. Component-Based | ✅ PASS | No frontend changes — API-only feature. |
| V. Simplicity | ✅ PASS | Uses built-in Sanctum, Scramble auto-generates docs, standard Laravel patterns. |

## Project Structure

### Documentation (this feature)

```text
specs/007-public-api/
├── plan.md              # This file
├── research.md          # Phase 0 output
├── data-model.md        # Phase 1 output
├── quickstart.md        # Phase 1 output
├── contracts/           # Phase 1 output
└── tasks.md             # Phase 2 output (/speckit.tasks)
```

### Source Code (repository root)

```text
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── V1/                    # New: versioned public controllers
│   │       │   ├── ProjectController.php
│   │       │   ├── PostController.php
│   │       │   ├── CourseController.php
│   │       │   ├── SeasonController.php
│   │       │   ├── CategoryController.php
│   │       │   └── CapabilityController.php
│   │       └── PublicContentController.php  # Existing (kept for SPA)
│   └── Middleware/
│       └── EnsureApiKey.php           # New: optional API key validation
├── Models/
│   └── ApiKey.php                     # New: Sanctum personal access token model
├── Providers/
│   └── AppServiceProvider.php         # Modified: rate limiter definitions
config/
├── sanctum.php                        # New: Sanctum configuration
├── cors.php                           # New: CORS configuration
routes/
├── api.php                            # New: versioned API routes
├── api/
│   └── v1.php                         # New: v1 endpoint definitions
└── web.php                            # Existing (SPA routes unchanged)
public/
└── docs/
    └── api.json                       # New: generated OpenAPI spec
```

**Structure Decision**: Single-project web application. New files added to existing Laravel structure. Versioned controllers in `Api/V1/` namespace. Existing `PublicContentController` kept for SPA backward compatibility.

## Complexity Tracking

No constitution violations. All changes use built-in Laravel features (Sanctum, Scramble, throttle middleware).
