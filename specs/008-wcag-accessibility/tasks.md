# Tasks: WCAG Accessibility Compliance

**Input**: Design documents from `/specs/008-wcag-accessibility/`

**Prerequisites**: plan.md, spec.md, research.md, contracts/, quickstart.md

**Tests**: Not requested — omitted per specification.

**Organization**: Tasks grouped by user story. Foundational tasks (skip link, focus indicators, announcer, reduced motion) are in Phase 2 since they block all stories.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (e.g., US1, US2, US3)

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Install dependencies, configure build tools

- [x] T001 Install @vueuse/integrations and focus-trap packages via `npm install @vueuse/integrations focus-trap`

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core accessibility infrastructure that ALL user stories depend on

**⚠️ CRITICAL**: No user story work can begin until this phase is complete

- [x] T002 Add `.sr-only` utility class to `resources/css/app.css` — screen-reader-only CSS pattern (position:absolute, clip, overflow:hidden)
- [x] T003 Add global `:focus-visible` outline styles to `resources/css/app.css` — `outline: 2px solid var(--accent); outline-offset: 2px;` with form element overrides
- [x] T004 Remove existing `outline: none` on form elements in `resources/css/app.css` (line ~105) — replace with `outline-hidden` where needed for forced-colors compatibility
- [x] T005 Add skip-link CSS to `resources/css/app.css` — `.skip-link` position-based hide at `top:-100%`, reveal on `:focus-visible` at `top:0`, with focus ring
- [x] T006 Add `html { scroll-behavior: smooth; }` and `@media (prefers-reduced-motion: reduce) { html { scroll-behavior: auto; } }` to `resources/css/app.css`
- [x] T007 [P] Add `@media (prefers-reduced-motion: reduce)` rule to `resources/css/app.css` — disable all CSS transitions and animations (`transition-duration: 0.01ms !important; animation-duration: 0.01ms !important`)
- [x] T008 Create `resources/js/composables/useAnnouncer.js` — singleton composable with `announce(message, politeness)` function, reactive `politeMessage` and `assertiveMessage` refs, clears before setting to force DOM mutation
- [x] T009 Mount aria-live announcer regions in `resources/js/App.vue` — two always-mounted `<div>` elements (polite + assertive) with `aria-live`, `aria-atomic`, `class="sr-only"`, no `v-if`
- [x] T010 Add skip-to-content link as first element in `resources/js/components/PublicShell.vue` — `<a href="#main-content" class="skip-link">Skip to main content</a>` (translatable via locale)
- [x] T011 Add `id="main-content" tabindex="-1"` to `<main>` element in `resources/js/components/PublicShell.vue`
- [x] T012 Add `aria-label` to `<nav>` element in `resources/js/components/PublicShell.vue` — translatable label (e.g., "Main navigation")
- [x] T013 Remove `behavior: 'smooth'` from `scrollBehavior` in `resources/js/router.js` — return only `{ top: 0 }` or savedPosition (CSS handles smooth scroll)

**Checkpoint**: Foundation ready — skip link, focus indicators, announcer, and reduced motion all working. User story implementation can begin.

---

## Phase 3: User Story 1 — Keyboard-Only Navigation (Priority: P1) 🎯 MVP

**Goal**: Every interactive element is reachable and operable via keyboard with visible focus indicators

**Independent Test**: Tab through entire site — all links, buttons, form inputs, modals receive focus with visible 2px outline. Escape closes modals.

### Implementation for User Story 1

- [x] T014 [P] [US1] Add `focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--accent)]` to all `<RouterLink>` elements in `resources/js/components/PublicShell.vue` nav
- [x] T015 [P] [US1] Add `focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--accent)]` to interactive elements in `resources/js/pages/HomePage.vue` (profile link, project cards, social links)
- [x] T016 [P] [US1] Add `focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--accent)]` to interactive elements in `resources/js/pages/ProjectsPage.vue`
- [x] T017 [P] [US1] Add `focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--accent)]` to interactive elements in `resources/js/pages/PostsPage.vue`
- [x] T018 [P] [US1] Add `focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--accent)]` to interactive elements in `resources/js/pages/CoursesPage.vue`
- [x] T019 [P] [US1] Add `focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--accent)]` to interactive elements in `resources/js/pages/ContactPage.vue` (inputs, submit button)
- [x] T020 [P] [US1] Add `focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--accent)]` to interactive elements in `resources/js/pages/ProjectDetailPage.vue` (demo/repo links, media items)
- [x] T021 [P] [US1] Add `focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--accent)]` to interactive elements in `resources/js/pages/PostDetailPage.vue`
- [x] T022 [P] [US1] Add `focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--accent)]` to interactive elements in `resources/js/pages/CourseDetailPage.vue`

**Checkpoint**: Keyboard-only user can Tab through all pages and see focus indicators on every interactive element.

---

## Phase 4: User Story 2 — Screen Reader Content Access (Priority: P1)

**Goal**: Screen reader users can navigate via landmarks, headings, and image descriptions

**Independent Test**: Navigate with VoiceOver — rotor shows landmarks (header, main, footer), images have alt text, headings form a logical hierarchy

### Implementation for User Story 2

- [x] T023 [P] [US2] Add `aria-hidden="true"` to decorative images in `resources/js/pages/HomePage.vue` — profile image and any non-informative images (alt="")
- [x] T024 [P] [US2] Add `title` attribute to YouTube iframe in `resources/js/pages/ProjectDetailPage.vue` — translatable title (e.g., "Demo video")
- [x] T025 [P] [US2] Add `aria-hidden="true"` to decorative `<span>` elements (icon-only elements like chevrons, arrows) in `resources/js/components/PublicShell.vue`
- [x] T026 [P] [US2] Add `aria-label` to theme toggle button in `resources/js/components/PublicShell.vue` — translatable (e.g., "Switch to light mode" / "Switch to dark mode")
- [x] T027 [P] [US2] Add `aria-label` to language toggle button in `resources/js/components/PublicShell.vue` — translatable (e.g., "Switch to English" / "Cambiar a español")
- [x] T028 [P] [US2] Add `aria-label` to modal close buttons in `resources/js/components/ContentPreviewModal.vue`, `resources/js/pages/HomePage.vue` (video modal), `resources/js/pages/ProjectDetailPage.vue` (video modal) — translatable (e.g., "Close dialog")
- [x] T029 [US2] Verify heading hierarchy across all pages — ensure `<h1>` → `<h2>` → `<h3>` nesting is logical and no levels are skipped

**Checkpoint**: Screen reader users can navigate via landmarks, hear meaningful image descriptions, and understand page structure.

---

## Phase 5: User Story 3 — Accessible Forms and Error Handling (Priority: P1)

**Goal**: Contact form has visible labels, required field indicators, and live error announcements

**Independent Test**: Fill contact form with screen reader — labels announced, required status clear, errors announced via live region

### Implementation for User Story 3

- [x] T030 [US3] Add visible `<label>` elements to contact form inputs in `resources/js/pages/ContactPage.vue` — connect via `for`/`id`, translatable label text
- [x] T031 [US3] Add `aria-required="true"` and visual indicator (asterisk) to required fields in `resources/js/pages/ContactPage.vue`
- [x] T032 [US3] Add `aria-describedby` to contact form inputs in `resources/js/pages/ContactPage.vue` — point to error message element IDs
- [x] T033 [US3] Wrap contact form error messages with `role="alert"` in `resources/js/pages/ContactPage.vue` — errors announced live on validation failure
- [x] T034 [US3] Use `useAnnouncer` composable in `resources/js/pages/ContactPage.vue` — announce "Message sent successfully" on success, "Form has errors" on validation failure
- [x] T035 [US3] Add `aria-invalid="true"` to contact form inputs with errors in `resources/js/pages/ContactPage.vue`

**Checkpoint**: Contact form is fully accessible — labels, required fields, error handling all work with screen reader.

---

## Phase 6: User Story 4 — Modal Accessibility (Priority: P2)

**Goal**: All modals have focus trapping, ARIA attributes, Escape-to-close, and focus return

**Independent Test**: Open modal with keyboard, Tab cycles within modal only, Escape closes and returns focus to trigger

### Implementation for User Story 4

- [x] T036 [US4] Refactor `resources/js/components/ContentPreviewModal.vue` — add `role="dialog"`, `aria-modal="true"`, `aria-label`, integrate `useFocusTrap` from `@vueuse/integrations`, add Escape key handler
- [x] T037 [US4] Refactor video modal in `resources/js/pages/HomePage.vue` — add `role="dialog"`, `aria-modal="true"`, `aria-label`, integrate `useFocusTrap`, add Escape key handler, store trigger element reference for focus return
- [x] T038 [US4] Refactor video modal in `resources/js/pages/ProjectDetailPage.vue` — add `role="dialog"`, `aria-modal="true"`, `aria-label`, integrate `useFocusTrap`, add Escape key handler, store trigger element reference for focus return

**Checkpoint**: All modals trap focus, close on Escape, return focus to trigger.

---

## Phase 7: User Story 5 — Color Contrast and Visual Accessibility (Priority: P2)

**Goal**: All text meets WCAG AA contrast ratios, reduced motion respected

**Independent Test**: Lighthouse audit shows 0 contrast failures; enable reduced motion and verify no smooth scroll/animations

### Implementation for User Story 5

- [x] T039 [P] [US5] Audit and update `--muted` CSS variable values in `resources/css/app.css` if needed — current values already pass AA (5.51:1 dark, 5.39:1 light), optionally bump to `#999999`/`#555555` for near-AAA
- [x] T040 [P] [US5] Verify `@media (prefers-reduced-motion: reduce)` rules in `resources/css/app.css` cover all animated elements — check for any `transition` or `animation` properties not covered by the global rule

**Checkpoint**: All text passes contrast audit. Reduced motion disables all animations.

---

## Phase 8: User Story 6 — Filter and Toggle Accessibility (Priority: P3)

**Goal**: Filter buttons communicate pressed state via ARIA, result count announced

**Independent Test**: Screen reader announces "pressed"/"not pressed" on filter toggle, announces result count after filtering

### Implementation for User Story 6

- [x] T041 [P] [US6] Add `aria-pressed` to category filter buttons in `resources/js/components/CategoryFilter.vue` — bind to active state
- [x] T042 [P] [US6] Add `aria-pressed` to season filter buttons in `resources/js/pages/PostsPage.vue` — bind to active state
- [x] T043 [US6] Integrate `useAnnouncer` in `resources/js/pages/ProjectsPage.vue` — announce result count when category filter changes
- [x] T044 [US6] Integrate `useAnnouncer` in `resources/js/pages/PostsPage.vue` — announce result count when season filter changes

**Checkpoint**: Filters communicate state to screen readers. Result count announced after filtering.

---

## Phase 9: Polish & Cross-Cutting Concerns

**Purpose**: Final validation and cleanup

- [x] T045 Run quickstart.md validation scenarios — verify all 8 scenarios pass
- [x] T046 Run Lighthouse Accessibility audit on all public pages — verify score ≥ 95 and 0 critical violations
- [x] T047 Run axe-core scan on all public pages via browser extension — verify 0 violations
- [x] T048 [P] Manual VoiceOver test on macOS — navigate home, projects, posts, courses, contact pages via screen reader
- [x] T049 Commit all changes with descriptive message

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies — can start immediately
- **Foundational (Phase 2)**: Depends on T001 (npm install) — BLOCKS all user stories
- **US1 Keyboard Nav (Phase 3)**: Depends on Foundational (T002-T013)
- **US2 Screen Reader (Phase 4)**: Depends on Foundational (T002-T013). Can run in parallel with US1.
- **US3 Forms (Phase 5)**: Depends on Foundational (T002-T013). Can run in parallel with US1/US2.
- **US4 Modals (Phase 6)**: Depends on T001 (focus-trap installed). Can run in parallel with US1-US3.
- **US5 Contrast (Phase 7)**: Depends on Foundational (T002-T007). Can run in parallel with US1-US4.
- **US6 Filters (Phase 8)**: Depends on T008 (useAnnouncer). Can run in parallel with US1-US5.
- **Polish (Phase 9)**: Depends on ALL user stories being complete

### User Story Dependencies

- **US1 (P1)**: Depends on Foundational phase only
- **US2 (P1)**: Depends on Foundational phase only — independent of US1
- **US3 (P1)**: Depends on Foundational phase only — independent of US1/US2
- **US4 (P2)**: Depends on T001 only (focus-trap install) — independent of US1-US3
- **US5 (P2)**: Depends on Foundational phase only — independent of US1-US4
- **US6 (P3)**: Depends on T008 (useAnnouncer) — independent of US1-US5

### Parallel Opportunities

**Phase 2 (Foundational)**: T003, T004, T005, T006, T007 can run in parallel (different CSS sections). T008 and T009 can run in parallel.

**Phase 3 (US1)**: T014-T022 all run in parallel (different page files).

**Phase 4 (US2)**: T023-T028 all run in parallel (different files).

**Phase 5 (US3)**: T030-T032 can run in parallel (same file but different elements).

**Phase 6 (US4)**: T036-T038 all run in parallel (different files).

**Phase 7 (US5)**: T039 and T040 can run in parallel.

**Phase 8 (US6)**: T041 and T042 can run in parallel.

---

## Parallel Example: User Story 1

```bash
# All page focus-indicator tasks can run in parallel:
Task: "T014 [P] [US1] Add focus-visible to PublicShell.vue nav"
Task: "T015 [P] [US1] Add focus-visible to HomePage.vue"
Task: "T016 [P] [US1] Add focus-visible to ProjectsPage.vue"
Task: "T017 [P] [US1] Add focus-visible to PostsPage.vue"
Task: "T018 [P] [US1] Add focus-visible to CoursesPage.vue"
Task: "T019 [P] [US1] Add focus-visible to ContactPage.vue"
Task: "T020 [P] [US1] Add focus-visible to ProjectDetailPage.vue"
Task: "T021 [P] [US1] Add focus-visible to PostDetailPage.vue"
Task: "T022 [P] [US1] Add focus-visible to CourseDetailPage.vue"
```

---

## Implementation Strategy

### MVP First (User Stories 1-3 Only)

1. Complete Phase 1: Setup (T001)
2. Complete Phase 2: Foundational (T002-T013) — CRITICAL
3. Complete Phase 3: US1 Keyboard Navigation (T014-T022)
4. Complete Phase 4: US2 Screen Reader (T023-T029)
5. Complete Phase 5: US3 Forms (T030-T035)
6. **STOP and VALIDATE**: Run Lighthouse + VoiceOver + manual keyboard test
7. Deploy if score ≥ 95

### Full Delivery

1. Setup + Foundational → Foundation ready
2. US1 + US2 + US3 → Core accessibility (P1 stories) → Validate → Deploy (MVP!)
3. US4 Modals → Validate → Deploy
4. US5 Contrast → Validate → Deploy
5. US6 Filters → Validate → Deploy
6. Polish → Final audit → Done

---

## Notes

- [P] tasks = different files, no dependencies
- [Story] label maps task to specific user story for traceability
- Each user story is independently completable and testable
- Commit after each phase or logical group
- Stop at any checkpoint to validate story independently
- All ARIA labels and skip link text must be translatable (ES/EN) via existing locale system
