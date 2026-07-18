# Research: WCAG Accessibility Compliance

**Date**: 2026-07-17
**Feature**: 008-wcag-accessibility

## R1: Focus Indicator Strategy

**Decision**: Global `:focus-visible` CSS outline + Tailwind `focus-visible:*` utilities on individual components.

**Rationale**: The `outline` property is the standards-compliant approach. Unlike `ring` (box-shadow), outline is never clipped by `overflow: hidden` (used in `.preview-modal` and `.project-card`). It also works correctly in Windows High Contrast Mode / forced-colors. Tailwind v4's `focus-visible` variant is first-class.

**Implementation**:
- Add global `:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }` to `app.css`
- Override for form elements: `textarea:focus-visible, input:focus-visible, select:focus-visible { outline: none; border-color: var(--muted); }`
- Replace `outline: none` (line 105 of `app.css`) with `outline-hidden` where needed

**Alternatives considered**:
- `focus:ring` Tailwind utility — rejected because box-shadow is clipped by `overflow: hidden`
- `focus:outline-none` — rejected because it removes the indicator entirely
- Third-party focus-trap library for focus styles — overkill

## R2: Modal Focus Trapping

**Decision**: Install `@vueuse/integrations` + `focus-trap` and use `useFocusTrap` composable.

**Rationale**: The `focus-trap` library is the battle-tested standard for focus trapping. The VueUse integration wrapper makes it reactive — activate/deactivate tied to modal `show` prop. Supports `escapeDeactivates` and `returnFocusOnDeactivate` out of the box. The native `inert` attribute makes background unfocusable but doesn't trap Tab cycling, so a JS solution is still needed.

**Implementation**:
- `npm install @vueuse/integrations focus-trap`
- Refactor `ContentPreviewModal.vue` to use `useFocusTrap(modalRef, { immediate: false, escapeDeactivates: true, returnFocusOnDeactivate: true })`
- Add `role="dialog"`, `aria-modal="true"`, `aria-label` to modal container
- Same pattern for video modals in `HomePage.vue` and `ProjectDetailPage.vue`

**Alternatives considered**:
- Native `inert` alone — doesn't trap Tab cycling, only makes background unfocusable
- Manual focus trap implementation — reinvents the wheel; focus-trap handles edge cases (radio groups, iframes, shadow DOM)
- `vue-focus-trap` — less maintained than `focus-trap`

## R3: ARIA Live Regions in Vue 3

**Decision**: Custom `useAnnouncer` composable with singleton polite/assertive regions mounted in `App.vue`.

**Rationale**: Screen readers track DOM mutations, not Vue reactive state. If you use `v-if` to show a live region, the element is created fresh — the screen reader never sees a "text change." The solution is always-mounted regions with reactive text content. Clearing before setting forces a DOM mutation even for repeated messages.

**Implementation**:
- Create `resources/js/composables/useAnnouncer.js` (~15 lines, singleton pattern)
- Mount two `<div aria-live="polite/assertive" class="sr-only">` in `App.vue` (no `v-if`)
- Add `.sr-only` CSS utility to `app.css`
- Use in `ContactPage.vue` (form errors), `ProjectsPage.vue` / `PostsPage.vue` (filter results)

**Alternatives considered**:
- `@vueuse/core` `useAnnouncer` — doesn't exist as a standalone composable
- Third-party `vue-aria-live` — adds dependency for ~15 lines of code
- Per-component live regions — causes duplicate announcements

## R4: Reduced Motion Support

**Decision**: CSS-only approach — set `scroll-behavior: smooth` on `html`, override with `@media (prefers-reduced-motion: reduce) { scroll-behavior: auto }`. Remove `behavior: 'smooth'` from `router.js`.

**Rationale**: The CSS `scroll-behavior` property is respected by the browser when Vue Router's `scrollBehavior` function returns `{ top: 0 }` without a `behavior` key. This is simpler than importing `usePreferredReducedMotion` from `@vueuse/core` in a non-setup context (router.js is a plain module).

**Implementation**:
- Add to `app.css`: `html { scroll-behavior: smooth; }` + `@media (prefers-reduced-motion: reduce) { html { scroll-behavior: auto; } }`
- Modify `router.js` `scrollBehavior`: remove `behavior: 'smooth'`, return only `{ top: 0 }` or savedPosition

**Alternatives considered**:
- `usePreferredReducedMotion` from `@vueuse/core` — requires calling in setup context; router.js is a plain module; more complex for no benefit
- CSS `animation: none` in media query — covers animations but not scrolling; the scroll behavior is the primary concern for this project

## R5: Color Contrast

**Decision**: Current `--muted` values pass WCAG AA. Optionally improve to near-AAA.

**Rationale**: WebAIM contrast calculation shows:
- `#888888` on `#0c0c0c` (dark mode) = 5.51:1 — passes AA (4.5:1 required)
- `#666666` on `#f8f8f6` (light mode) = 5.39:1 — passes AA

Both fail AAA (7:1). For a portfolio site, AA is sufficient and is the stated target.

**Optional improvement** (if desired):
- Dark mode: `--muted: #999999` (6.86:1, near AAA)
- Light mode: `--muted: #555555` (~7.0:1, hits AAA)

**Implementation**: Change two CSS variable values in `app.css`. No code changes needed.

**Alternatives considered**:
- WCAG AAA compliance — higher bar than required; would make muted text too prominent
- Dynamic contrast based on background — overkill for a two-theme site

## R6: Skip Link Pattern

**Decision**: Position-based hide (`top: -100%`), revealed on `:focus-visible`. `tabindex="-1"` on target `<main>`.

**Rationale**: Standard WCAG pattern. `display: none` / `visibility: hidden` remove the element from the accessibility tree — screen readers can't announce it. Position-based hiding keeps it in the DOM. `:focus-visible` (not `:focus`) ensures it only appears during keyboard navigation, not mouse clicks.

**Implementation**:
- Add `<a href="#main-content" class="skip-link">Skip to main content</a>` as first element in `PublicShell.vue`
- Add `id="main-content" tabindex="-1"` to `<main>` in `PublicShell.vue`
- CSS: `.skip-link { position: absolute; top: -100%; ... }` + `.skip-link:focus-visible { top: 0; }`

**Alternatives considered**:
- Skip nav that jumps to first heading — less standard than main content target
- Skip nav with `role="navigation"` — redundant, `<a>` is sufficient

## R7: Form Label Pattern

**Decision**: Visible `<label>` elements above/beside inputs, connected via `for`/`id`.

**Rationale**: Placeholder-only identification fails WCAG 1.3.1 (Info and Relationships) and 3.3.2 (Labels or Instructions). Placeholders disappear when typing and are not reliably announced by all screen readers. Visible labels also help users with cognitive disabilities.

**Implementation**:
- Contact form: Add `<label>` elements above each input, styled with existing CSS variables
- Admin form: Add `<label>` elements (admin is out of scope for this SPEC, but quick fix)
- Required fields: Add `aria-required="true"` + visual indicator (asterisk)

**Alternatives considered**:
- `aria-label` only (no visible label) — fails for cognitive accessibility and voice control
- Floating labels — more complex, still in progress as a pattern; simple labels are clearer

## Dependencies to Install

```bash
npm install @vueuse/integrations focus-trap
```

This brings in `@vueuse/core` as a peer dependency (also useful for future composables).
