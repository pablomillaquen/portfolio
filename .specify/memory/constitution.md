# Portfolio Constitution

## Core Principles

### I. API-First Architecture

Every feature exposes a clean REST API endpoint under `/api`. The Vue 3 SPA consumes these endpoints exclusively — no server-rendered data injection. API routes MUST be versioned under `/api` prefix. Controllers MUST return JSON responses. The backend is a pure API; the frontend is a pure consumer.

### II. Bilingual Support (ES/EN)

All public-facing content MUST support both Spanish and English. Translation keys MUST be defined for all user-visible strings. The language toggle MUST persist user preference. Default locale is `en` with `es` as fallback.

### III. Admin CRUD Integrity

All admin operations (projects, posts, courses, social links, site settings) MUST go through the `admin.session` middleware. CRUD controllers MUST validate input, sanitize output, and return structured JSON responses. Deletions MUST be confirmed before execution.

### IV. Component-Based Frontend

Vue 3 components MUST use Composition API with `<script setup>`. Components MUST be single-responsibility and reusable. Shared UI patterns (cards, modals, forms) MUST be extracted into `resources/js/components/`. Page-level views live in `resources/js/pages/`.

### V. Simplicity Over Abstraction

Start with the simplest solution that works. Avoid premature abstraction — no repositories, no DTOs, no complex design patterns unless the problem genuinely requires them. YAGNI applies: do not build for hypothetical future needs.

## Technology Stack

- **Backend**: Laravel 12, PHP 8.3, MySQL
- **Frontend**: Vue 3 (Composition API), Vue Router 5, Axios
- **Styling**: Tailwind CSS v4
- **Build**: Vite 7 + laravel-vite-plugin
- **Testing**: PHPUnit 11.5 (Unit + Feature suites)

All dependencies MUST be declared in `composer.json` (PHP) or `package.json` (JS). Do not introduce new frameworks or libraries without explicit justification.

## Development Workflow

- Run `composer dev` for local development (server, queue, logs, Vite HMR)
- Run `composer test` to execute PHPUnit suite
- Run `npm run build` for production asset compilation
- All code changes MUST pass `composer test` before merge
- Use `.editorconfig` conventions: 4-space indent, LF line endings, UTF-8

## Governance

This constitution supersedes all other development practices for the Portfolio project. Amendments require: (1) documented rationale, (2) version bump following semver, (3) updated templates if principles are added or removed. All PRs and code reviews MUST verify compliance with these principles.

**Version**: 1.0.0 | **Ratified**: 2026-07-15 | **Last Amended**: 2026-07-15
