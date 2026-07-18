# Implementation Plan: WCAG Accessibility Compliance

**Branch**: `008-wcag-accessibility` | **Date**: 2026-07-17 | **Spec**: [spec.md](spec.md)

**Input**: Feature specification from `/specs/008-wcag-accessibility/spec.md`

## Summary

Add WCAG 2.1 Level AA compliance to the public-facing portfolio. The site already has partial semantic HTML (landmarks, heading hierarchy, breadcrumbs) — this feature completes accessibility with skip links, visible focus indicators, form labels, modal focus trapping, ARIA state attributes, live regions for dynamic content, and reduced-motion support. Target: 0 Lighthouse Accessibility violations.

## Technical Context

**Language/Version**: PHP 8.3, JavaScript ES2022 (Vue 3.5+, Vite 7)
**Primary Dependencies**: Vue 3 (Composition API), Tailwind CSS v4, Laravel 12
**Storage**: N/A (frontend-only feature)
**Testing**: Lighthouse Accessibility audit, VoiceOver manual testing, axe-core automated checks
**Target Platform**: Modern browsers (Chrome 112+, Firefox 112+, Safari 15.5+, Edge 112+)
**Project Type**: SPA (Vue 3 frontend, Laravel API backend)
**Performance Goals**: No measurable performance impact (CSS and ARIA attributes only)
**Constraints**: No new JS dependencies beyond @vueuse/integrations + focus-trap; no SSR
**Scale/Scope**: 9 public pages, 7 shared components, 1 modal type, 1 contact form

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

| Principle | Status | Notes |
|-----------|--------|-------|
| API-First | ✅ PASS | No API changes — frontend-only feature |
| Bilingual (ES/EN) | ✅ PASS | ARIA labels, skip link text, and sr-only content will be translated via existing locale system |
| Admin CRUD Integrity | ✅ PASS | Admin panel excluded from scope |
| Component-Based Frontend | ✅ PASS | New composables (useAnnouncer) follow single-responsibility; modal changes extend existing components |
| Simplicity Over Abstraction | ✅ PASS | Focus on native HTML/ARIA + Tailwind utilities. Only one small composable (useAnnouncer). No complex patterns. |

**Post-Phase 1 Re-check**: All gates pass. The `useAnnouncer` composable is a thin wrapper (~15 lines) — justified by the singleton requirement for live regions. No abstraction layers added.

## Project Structure

### Documentation (this feature)

```text
specs/008-wcag-accessibility/
├── spec.md              # Feature specification
├── plan.md              # This file
├── research.md          # Phase 0 output
├── data-model.md        # Phase 1 output (minimal — no data model for a11y)
├── quickstart.md        # Phase 1 output
├── contracts/           # Phase 1 output (ARIA contracts)
└── tasks.md             # Phase 2 output (/speckit.tasks)
```

### Source Code (repository root)

```text
resources/
├── css/app.css                          # Focus-visible, skip link, sr-only, reduced-motion
├── js/
│   ├── composables/
│   │   └── useAnnouncer.js              # NEW — aria-live region manager
│   ├── components/
│   │   ├── PublicShell.vue              # Skip link, nav aria-labels, aria-live regions
│   │   ├── ContentPreviewModal.vue      # role=dialog, focus trap, Escape key
│   │   ├── CategoryFilter.vue          # aria-pressed on filter buttons
│   │   └── SeasonList.vue              # aria-pressed on season filters
│   ├── pages/
│   │   ├── ContactPage.vue             # Form labels, aria-required, live error region
│   │   ├── HomePage.vue                # Modal accessibility (video)
│   │   ├── ProjectDetailPage.vue       # Modal accessibility (video), iframe title
│   │   ├── ProjectsPage.vue           # Filter aria-pressed, result count live region
│   │   └── PostsPage.vue              # Filter aria-pressed, result count live region
│   └── router.js                       # Remove behavior:'smooth' (CSS handles it)
resources/views/app.blade.php           # No changes needed (lang attr already present)
```

**Structure Decision**: Frontend-only changes. No backend, no new files beyond `useAnnouncer.js`. All modifications extend existing components.

## Complexity Tracking

No constitution violations. No complexity tracking needed.
