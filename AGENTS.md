# AGENTS.md — Portfolio (Pablo Millaquen)

## Stack

Laravel 12 (PHP 8.3) + Vue 3 SPA + Tailwind CSS v4 + Vite 7. MySQL database.

## Commands

```bash
composer dev        # Starts Laravel server + queue + logs + Vite HMR (all in one)
composer test       # Clears config cache then runs PHPUnit
npm run build       # Production Vite build
npm run test        # Vitest unit tests
npm run test:e2e    # Playwright e2e tests
```

## Architecture

- **Routes**: `routes/web.php` has public API + admin CRUD + SPA catch-all. `routes/api.php` is a separate v1 API (RequireApiKey middleware).
- **Admin auth**: `admin.session` middleware. All admin routes are under `/api/admin/`.
- **Frontend SPA**: Vue Router catch-all at `/{any?}` returns `resources/views/app.blade.php`. Vue pages live in `resources/js/pages/`.
- **Models**: `app/Models/` — Project, Post, Course, Season, Category, Capability. Posts have `type` field (`internal`/`external` only — no `article`).
- **Seeders**: `database/seeders/` — run with `php artisan db:seed --class=SeederName`.

## Conventions

- Vue 3 Composition API with `<script setup>` only — no Options API.
- Post `excerpt` and `content` fields must be populated in both ES and EN.
- Post type validation only allows `internal` or `external`.
- Posts have no `categories()` relationship — only `season()`, `relatedProject()`, `projects()`.
- Projects use pivot tables: `category_project`, `capability_project`, `project_post` — none have `is_primary`.
- Admin preview is rendered via `AdminPreviewController` and should match public view section order.

## Content Source

Article content comes from `/Users/pablomillaquen/Proyectos/Publicaciones/1. Ley 21719/` with folders `articulo_1` through `articulo_8`. Each contains `publicacion.md` (article body), `portafolio.md` (case study for articles 1-5), and `resumen.md` (excerpt). Files `video.md` and `linkedin.md` are ignored.
