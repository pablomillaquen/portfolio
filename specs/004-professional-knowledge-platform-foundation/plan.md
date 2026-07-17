# Implementation Plan: Professional Knowledge Platform Foundation

**Branch**: `004-professional-knowledge-platform-foundation` | **Date**: 2026-07-16 | **Spec**: [spec.md](./spec.md)

**Input**: Foundation specification from `/specs/004-professional-knowledge-platform-foundation/spec.md`

**Note**: This is a Foundation SPEC that defines the knowledge architecture for the entire platform. Implementation will be phased across multiple SPECs.

## Summary

Transform the portfolio from a chronological project catalog into a professional knowledge platform based on capabilities and evidence. The architecture must support projects as case studies, posts as research evidence organized by seasons, and bidirectional relationships between all content types. This Foundation SPEC defines the data model, relationships, and taxonomy that will支撑 all future SPECs.

## Technical Context

**Language/Version**: PHP 8.2+ (Laravel 12), JavaScript ES2022+ (Vue 3)

**Primary Dependencies**: Laravel 12, Vue 3, Tailwind CSS v4, Vite 7

**Storage**: MySQL 8.0+ (Eloquent ORM)

**Testing**: PHPUnit (backend), Vitest (frontend)

**Target Platform**: Web (responsive, mobile-first)

**Project Type**: Web application (SPA frontend + API backend)

**Performance Goals**: <200ms p95 API response, <3s initial page load

**Constraints**: No major visual redesign, existing tech stack, manual season creation

**Scale/Scope**: Personal portfolio (1 author, ~10-20 projects, ~50-100 posts)

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

**Note**: No constitution file exists at `.specify/memory/constitution.md`. Proceeding with best practices.

### Principles Applied

1. **API-First**: All data operations through RESTful API endpoints
2. **Bilingual (ES/EN)**: All translatable fields use JSON columns with locale keys
3. **Admin CRUD Integrity**: Full admin control over all entities
4. **Component-Based Frontend**: Vue 3 Composition API with `<script setup>`
5. **Simplicity Over Abstraction**: No over-engineering, incremental evolution

## Project Structure

### Documentation (this feature)

```text
specs/004-professional-knowledge-platform-foundation/
├── spec.md              # Foundation specification
├── plan.md              # This file
├── research.md          # Phase 0 output
├── data-model.md        # Phase 1 output
├── quickstart.md        # Phase 1 output
├── contracts/           # Phase 1 output
│   └── api-contracts.md
└── tasks.md             # Phase 2 output (not created by /speckit.plan)
```

### Source Code (repository root)

```text
app/
├── Models/              # Eloquent models
│   ├── Project.php      # Existing - will be extended
│   ├── Post.php         # Existing - will be extended
│   ├── Season.php       # NEW - editorial seasons
│   ├── Category.php     # NEW - multi-dimensional taxonomy
│   ├── Capability.php   # NEW - professional capabilities
│   └── ProjectPost.php  # NEW - pivot: project ↔ posts
├── Http/Controllers/Api/
│   ├── PublicContentController.php  # Existing - public API
│   └── AdminSeasonController.php    # NEW - season CRUD
├── Console/Commands/
│   └── PublishScheduledContent.php   # Existing - scheduled publishing
database/
├── migrations/
│   ├── ..._create_seasons_table.php           # NEW
│   ├── ..._create_categories_table.php        # NEW
│   ├── ..._create_capabilities_table.php      # NEW
│   ├── ..._create_category_project_table.php  # NEW (pivot)
│   ├── ..._add_case_study_fields_to_projects.php  # NEW
│   └── ..._add_season_fields_to_posts.php     # NEW
resources/
├── js/
│   ├── pages/
│   │   ├── HomePage.vue         # Existing - will be restructured
│   │   ├── ProjectsPage.vue     # Existing - will add filtering
│   │   ├── ProjectDetailPage.vue # Existing - case study format
│   │   ├── PostsPage.vue        # Existing - will add seasons
│   │   └── PostDetailPage.vue   # Existing - will add navigation
│   └── components/
│       ├── CapabilityCard.vue   # NEW - capability display
│       ├── CategoryFilter.vue   # NEW - category filtering
│       ├── SeasonList.vue       # NEW - season organization
│       └── RelatedContent.vue   # NEW - bidirectional links
```

**Structure Decision**: Extend existing Laravel 12 + Vue 3 SPA structure. No new directories needed at root level. New models, migrations, and components follow existing patterns.

## Complexity Tracking

> **Fill ONLY if Constitution Check has violations that must be justified**

| Violation | Why Needed | Simpler Alternative Rejected Because |
|-----------|------------|-------------------------------------|
| N/A | Foundation SPEC is architectural, not functional | N/A |
