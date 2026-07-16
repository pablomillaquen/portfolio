# Implementation Plan: Admin Content Preview

**Branch**: `003-admin-content-preview` | **Date**: 2026-07-16 | **Spec**: [spec.md](./spec.md)

**Input**: Feature specification from `/specs/003-admin-content-preview/spec.md`

## Summary

Add preview functionality to the admin panel allowing users to see how posts/projects will look to visitors before publishing. The preview renders content using the same server-side markdown and bilingual logic as public views, displayed in a modal overlay.

## Technical Context

**Language/Version**: PHP 8.3, Vue 3 (Composition API)

**Primary Dependencies**: Laravel 12, Tailwind CSS v4, Axios

**Storage**: MySQL (existing database, no schema changes)

**Testing**: PHPUnit 11.5

**Target Platform**: Web (SPA)

**Project Type**: Web application (Laravel API + Vue SPA)

**Performance Goals**: Preview renders in under 2 seconds

**Constraints**: No new frontend dependencies; reuse existing modal patterns

**Scale/Scope**: Single admin user, no concurrency concerns

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

| Principle | Status | Notes |
|-----------|--------|-------|
| I. API-First Architecture | ✅ PASS | Preview endpoint under `/api/admin/preview` |
| II. Bilingual Support (ES/EN) | ✅ PASS | Language toggle in preview modal |
| III. Admin CRUD Integrity | ✅ PASS | Preview endpoint uses `admin.session` middleware |
| IV. Component-Based Frontend | ✅ PASS | Preview modal follows existing patterns |
| V. Simplicity Over Abstraction | ✅ PASS | Server-side rendering reuses existing `Str::markdown()` logic |

## Project Structure

### Documentation (this feature)

```text
specs/003-admin-content-preview/
├── spec.md              # Feature specification
├── plan.md              # This file
├── research.md          # Phase 0 output
├── data-model.md        # Phase 1 output
├── quickstart.md        # Phase 1 output
├── contracts/           # Phase 1 output
└── tasks.md             # Phase 2 output (created by /speckit.tasks)
```

### Source Code (repository root)

```text
app/
├── Http/Controllers/Api/
│   ├── AdminPreviewController.php    # NEW: Preview endpoint
│   ├── AdminProjectController.php    # Existing (no changes)
│   └── AdminPostController.php       # Existing (no changes)
├── Support/
│   └── TranslatableContent.php       # Existing (reused)
└── Models/
    ├── Project.php                   # Existing (no changes)
    └── Post.php                      # Existing (no changes)

resources/js/
├── pages/
│   └── AdminPage.vue                 # MODIFIED: Add preview button + modal
├── components/
│   └── ContentPreviewModal.vue       # NEW: Reusable preview modal
└── css/
    └── app.css                       # MODIFIED: Add preview modal styles

routes/
└── api.php                           # MODIFIED: Add preview route
```

**Structure Decision**: Follow existing Laravel + Vue SPA architecture. Preview modal is a new component in `resources/js/components/`. Backend preview endpoint is a new controller under `app/Http/Controllers/Api/`.

## Complexity Tracking

*No violations — all principles satisfied.*

## Design Decisions

### Approach: Server-Side Preview Rendering

**Decision**: Create a new API endpoint that accepts form data and returns rendered HTML.

**Rationale**:
- Reuses existing `Str::markdown()` and `TranslatableContent` logic
- Ensures preview matches published appearance exactly (SC-002)
- No new frontend dependencies needed
- Follows API-First Architecture principle

**Alternatives Considered**:
1. **Client-side markdown rendering**: Would require adding a markdown library (e.g., marked, markdown-it) to the frontend. Rejected because it duplicates backend logic and risks rendering differences.
2. **Save-as-draft-then-preview**: Would require saving unsaved changes first. Rejected because it doesn't meet FR-005 (preserve unsaved changes).

### Preview Modal Pattern

**Decision**: Use the existing modal pattern from `HomePage.vue` (Teleport + overlay + backdrop click to close).

**Rationale**:
- Consistent UX with existing welcome modal
- Proven pattern in the codebase
- Simple implementation

### Language Toggle in Preview

**Decision**: Frontend sends `locale` query parameter to preview endpoint.

**Rationale**:
- Follows existing bilingual pattern in `PublicContentController`
- No backend changes needed for language support

## API Contract

### POST /api/admin/preview

**Purpose**: Render content preview from form data

**Auth**: `admin.session` middleware required

**Request Body**:

```json
{
  "type": "project" | "post",
  "locale": "en" | "es",
  "data": {
    // Project fields
    "title": { "es": "...", "en": "..." },
    "summary": { "es": "...", "en": "..." },
    "description": { "es": "...", "en": "..." },
    "details": [...],
    "media": [...],
    "stack": [...],
    "cover_image_url": "...",
    "demo_url": "...",
    "repository_url": "...",
    
    // Post fields (when type=post)
    "content": { "es": "...", "en": "..." },
    "excerpt": { "es": "...", "en": "..." },
    "external_url": "..."
  }
}
```

**Response**:

```json
{
  "html": "<div class='preview-content'>...</div>",
  "title": "Rendered title",
  "locale": "en"
}
```

## Frontend Changes

### AdminPage.vue Modifications

1. Add `showPreviewModal` ref (boolean)
2. Add `previewType` ref ('project' | 'post')
3. Add `previewLocale` ref ('en' | 'es')
4. Add `previewHtml` ref (string)
5. Add `openPreview(type)` function:
   - Sends current form data to `/api/admin/preview`
   - Sets modal state and displays result
6. Add "Preview" button to project and post forms (in `.cta-row`)
7. Add `<ContentPreviewModal>` component to template

### ContentPreviewModal.vue (New Component)

**Props**:
- `html` (String): Rendered HTML content
- `title` (String): Content title
- `locale` (String): Current language ('en' | 'es')
- `show` (Boolean): Visibility state

**Events**:
- `close`: Emitted when user closes modal
- `toggle-locale`: Emitted when user switches language

**Template**:
- Teleport to body
- Modal overlay with backdrop
- Language toggle button (EN/ES)
- Preview content area (v-html)
- Close button

## Success Criteria Verification

| Criterion | Verification Method |
|-----------|---------------------|
| SC-001: Preview in <2 seconds | Manual timing during testing |
| SC-002: Matches published appearance | Visual comparison with published view |
| SC-003: Language toggle without reload | Test toggle in modal |
| SC-004: Markdown renders correctly | Verify headers, lists, emphasis, links, images |
