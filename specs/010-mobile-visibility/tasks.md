# Tasks: Mobile Visibility Improvements

**Input**: Design documents from `/specs/010-mobile-visibility/`

**Prerequisites**: plan.md, spec.md, research.md, contracts/, quickstart.md

**Tests**: Not requested — manual testing via quickstart.md scenarios.

**Organization**: Tasks grouped by user story. Foundational CSS tasks are in Phase 2 since they block all stories.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (e.g., US1, US2, US3)

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: No setup needed — feature uses existing Vue 3 + CSS stack

*No tasks in this phase.*

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core CSS breakpoints and touch styles that ALL user stories depend on

**⚠️ CRITICAL**: No user story work can begin until this phase is complete

- [X] T001 Add responsive breakpoints to `resources/css/app.css` — `@media (max-width: 480px)` for small phone, `@media (max-width: 768px)` for tablet, keep existing `@media (max-width: 900px)` unchanged
- [X] T002 Add touch target sizing to `resources/css/app.css` — `min-height: 44px; min-width: 44px` on buttons, links, inputs, filter buttons, ghost buttons
- [X] T003 Add tap delay elimination to `resources/css/app.css` — `touch-action: manipulation` on all interactive elements (buttons, links, inputs, `[role="button"]`, `[role="link"]`)
- [X] T004 Add list card stacking to `resources/css/app.css` — at `@media (max-width: 480px)`: `.list-card { flex-direction: column; }`, `.post-cover { width: 100%; min-width: unset; }`
- [X] T005 Add post navigation stacking to `resources/css/app.css` — at `@media (max-width: 480px)`: `.post-navigation { grid-template-columns: 1fr; }`

**Checkpoint**: Foundation ready — responsive breakpoints, touch targets, and stacking rules in place. User story implementation can begin.

---

## Phase 3: User Story 1 — Mobile Navigation (Priority: P1) 🎯 MVP

**Goal**: Hamburger menu with slide-out navigation drawer on mobile

**Independent Test**: Open site at 375px viewport — tap hamburger — drawer opens with all links — tap link — drawer closes and navigates

### Implementation for User Story 1

- [X] T006 [US1] Create `resources/js/components/MobileNavDrawer.vue` — slide-out drawer component with `isOpen` prop, nav links, theme toggle, locale toggle, emits close on link click
- [X] T007 [US1] Add hamburger button CSS to `resources/css/app.css` — `.hamburger-btn` hidden on desktop, visible at `@media (max-width: 768px)`, CSS-only icon with 3 spans
- [X] T008 [US1] Add navigation drawer CSS to `resources/css/app.css` — `.nav-drawer` slide-from-right, `.nav-overlay` backdrop, transitions, z-index
- [X] T009 [US1] Modify `resources/js/components/PublicShell.vue` — add `isMenuOpen` ref, import MobileNavDrawer, add hamburger button (hidden on desktop), toggle drawer on click, close on Escape and outside tap, lock body scroll when open
- [X] T010 [US1] Add body scroll locking CSS to `resources/css/app.css` — `body.menu-open { overflow: hidden; }`

**Checkpoint**: Hamburger menu works on mobile. Desktop layout unchanged.

---

## Phase 4: User Story 2 — Responsive Layouts (Priority: P1)

**Goal**: All pages display correctly on small screens without horizontal overflow

**Independent Test**: Navigate all pages at 375px — no horizontal scroll, content readable, images scale

### Implementation for User Story 2

- [X] T011 [P] [US2] Add page padding responsive rules to `resources/css/app.css` — reduce horizontal padding on small phones, ensure content doesn't touch edges
- [X] T012 [P] [US2] Add hero card CTA row stacking to `resources/css/app.css` — at `@media (max-width: 480px)`: `.cta-row { flex-direction: column; }` for HomePage CTA buttons
- [X] T013 [P] [US2] Add contact form mobile styles to `resources/css/app.css` — full-width inputs, adequate spacing, tappable submit button at `@media (max-width: 480px)`
- [X] T014 [P] [US2] Add project detail media grid responsive rules to `resources/css/app.css` — reduce `min-height` on gallery images at small phone breakpoint
- [X] T015 [P] [US2] Add tablet breakpoint grid rules to `resources/css/app.css` — at `@media (max-width: 768px)`: project grid 2 columns, course grid adjustments

**Checkpoint**: All pages display correctly from 320px to 1920px with no horizontal overflow.

---

## Phase 5: User Story 3 — Touch-Friendly Interactions (Priority: P2)

**Goal**: Interactive elements are easy to tap and respond immediately on touch devices

**Independent Test**: Tap all buttons and links on mobile — easy to hit, immediate response

### Implementation for User Story 3

- [X] T016 [P] [US3] Add filter button touch sizing to `resources/css/app.css` — ensure CategoryFilter and SeasonList buttons have adequate padding and min-height at mobile breakpoints
- [X] T017 [P] [US3] Add ghost button touch sizing to `resources/css/app.css` — theme toggle, locale toggle buttons have min-height 44px
- [X] T018 [P] [US3] Add CTA button touch sizing to `resources/css/app.css` — primary action buttons have min-height 44px and adequate padding

**Checkpoint**: All interactive elements meet 44x44px minimum on touch devices.

---

## Phase 6: User Story 4 — Mobile Performance (Priority: P2)

**Goal**: Site loads quickly and scrolls smoothly on mobile

**Independent Test**: Load on 3G — initial content in 2 seconds, smooth 60fps scroll

### Implementation for User Story 4

- [X] T019 [US4] Add `will-change: transform` to navigation drawer CSS in `resources/css/app.css` — hint browser for GPU-accelerated animation
- [X] T020 [US4] Add `-webkit-overflow-scrolling: touch` to scrollable containers in `resources/css/app.css` — smooth momentum scrolling on iOS
- [X] T021 [US4] Verify no layout shift during drawer animation — test at 375px viewport

**Checkpoint**: Drawer animation is smooth at 60fps, no janky scroll.

---

## Phase 7: Polish & Cross-Cutting Concerns

**Purpose**: Final validation and cleanup

- [X] T022 Run quickstart.md validation scenarios — verify all 12 scenarios pass
- [X] T023 Test at 320px viewport (Galaxy S5) — verify no overflow, readable content
- [X] T024 Test at 375px viewport (iPhone SE) — verify hamburger, drawer, stacking
- [X] T025 Test at 768px viewport (iPad) — verify tablet layout, hamburger visible
- [X] T026 Test at 1920px viewport — verify desktop unchanged
- [X] T027 Test device rotation (portrait ↔ landscape) — verify layout adapts
- [X] T028 Run Lighthouse Mobile audit — verify Performance ≥ 80
- [X] T029 Verify keyboard navigation — Escape closes drawer, Tab navigates links
- [X] T030 Commit all changes with descriptive message

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies — skipped (no setup needed)
- **Foundational (Phase 2)**: No dependencies — can start immediately
- **US1 Mobile Navigation (Phase 3)**: Depends on T001-T003 (breakpoints, touch styles) — BLOCKS navigation testing
- **US2 Responsive Layouts (Phase 4)**: Depends on T001 (breakpoints) — can run in parallel with US1
- **US3 Touch Interactions (Phase 5)**: Depends on T002-T003 (touch styles) — can run in parallel with US1/US2
- **US4 Mobile Performance (Phase 6)**: Depends on US1 (drawer exists) — can run after US1
- **Polish (Phase 7)**: Depends on ALL user stories being complete

### User Story Dependencies

- **US1 (P1)**: Depends on Phase 2 only (CSS foundation)
- **US2 (P1)**: Depends on T001 (breakpoints) — independent of US1
- **US3 (P2)**: Depends on T002-T003 (touch styles) — independent of US1/US2
- **US4 (P2)**: Depends on US1 (drawer component exists)

### Parallel Opportunities

**Phase 2 (Foundational)**: T001-T005 can run in parallel (all CSS additions to same file but different sections).

**Phase 4 (US2)**: T011-T015 all run in parallel (different CSS sections).

**Phase 5 (US3)**: T016-T018 all run in parallel (different CSS sections).

---

## Parallel Example: User Story 2

```bash
# All responsive layout tasks can run in parallel:
Task: "T011 [US2] Add page padding responsive rules"
Task: "T012 [US2] Add hero card CTA row stacking"
Task: "T013 [US2] Add contact form mobile styles"
Task: "T014 [US2] Add project detail media grid responsive rules"
Task: "T015 [US2] Add tablet breakpoint grid rules"
```

---

## Implementation Strategy

### MVP First (User Stories 1-2 Only)

1. Complete Phase 2: Foundational (T001-T005) — CSS foundation
2. Complete Phase 3: US1 Mobile Navigation (T006-T010) — hamburger works
3. Complete Phase 4: US2 Responsive Layouts (T011-T015) — pages display correctly
4. **STOP and VALIDATE**: Test at 375px, 768px, 1920px viewports
5. Commit

### Full Delivery

1. Foundational → Foundation ready
2. US1 Mobile Navigation → Hamburger works (MVP!)
3. US2 Responsive Layouts → Pages display correctly
4. US3 Touch Interactions → Tap targets adequate
5. US4 Mobile Performance → Smooth scroll
6. Polish → Lighthouse ≥80, all scenarios pass

---

## Notes

- [P] tasks = different CSS sections, no dependencies
- [Story] label maps task to specific user story for traceability
- Each user story is independently completable and testable
- Commit after each phase or logical group
- Stop at any checkpoint to validate story independently
- Desktop layout MUST remain unchanged — test at 1920px after each change
- Admin panel is OUT OF SCOPE
