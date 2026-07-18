# ARIA & Accessibility Contracts

**Date**: 2026-07-17
**Feature**: 008-wcag-accessibility

## Contract 1: Skip Link

**Element**: `<a href="#main-content" class="skip-link">`

**Behavior**:
- Visually hidden at `top: -100%` by default
- Becomes visible at `top: 0` on `:focus-visible` (keyboard Tab)
- Activating it scrolls to and focuses `<main id="main-content" tabindex="-1">`
- Focus ring: 2px solid `var(--accent)`, 2px offset

**Screen reader announcement**: "Skip to main content, link"

---

## Contract 2: Modal Dialog

**Trigger**: Any modal-opening action (video play, content preview)

**Container attributes**:
```
role="dialog"
aria-modal="true"
aria-label="[descriptive title]"
```

**Focus behavior**:
1. On open: focus moves to first focusable element inside modal
2. While open: Tab cycles within modal only (focus trap)
3. On close (Escape or close button): focus returns to trigger element

**Close mechanisms**:
- Escape key
- Close button (`aria-label="Close dialog"`)
- Click on overlay backdrop

**Screen reader announcement**: "[Title], dialog" on open

---

## Contract 3: Form Labels & Errors

**Pattern**: Visible `<label>` connected via `for`/`id` to input

**Required fields**:
```
aria-required="true"
```
Plus visual indicator (asterisk `*` next to label)

**Error display**:
```
<p role="alert" aria-live="assertive">[error message]</p>
```
Associated with input via `aria-describedby="[error-id]"`

**Screen reader behavior**:
- On focus: "Field name, required, edit text" (or similar)
- On error: error message announced immediately via `role="alert"`

---

## Contract 4: Filter Buttons

**Element**: `<button>` for category/season filter

**State attributes**:
```
aria-pressed="true"  (when active)
aria-pressed="false" (when inactive)
```

**Result announcement**:
```
<div aria-live="polite" aria-atomic="true" class="sr-only">
  [N] results shown
</div>
```

**Screen reader behavior**:
- On toggle: "Filter name, toggle button, pressed" / "not pressed"
- After filter applied: "[N] results shown" announced via live region

---

## Contract 5: Focus Indicator

**Global rule**:
```
:focus-visible {
  outline: 2px solid var(--accent);
  outline-offset: 2px;
}
```

**Exceptions**:
- Form elements (`input`, `textarea`, `select`): custom focus style via `border-color`
- Modal close button: visible focus ring

**Contrast**: Focus indicator must have ≥ 3:1 contrast against adjacent colors

---

## Contract 6: Reduced Motion

**Media query**:
```css
@media (prefers-reduced-motion: reduce) {
  html { scroll-behavior: auto; }
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    transition-duration: 0.01ms !important;
  }
}
```

**Behavior**: No smooth scrolling, no CSS animations, no transitions when user has enabled "Reduce motion" in OS settings.

---

## Contract 7: ARIA Live Region (Announcer)

**Mount point**: Two always-mounted `<div>` elements in `App.vue`

```
<div aria-live="polite" aria-atomic="true" class="sr-only">{{ politeMessage }}</div>
<div aria-live="assertive" aria-atomic="true" class="sr-only">{{ assertiveMessage }}</div>
```

**Rules**:
- Never use `v-if` on these elements (must always be in DOM)
- Clear text before setting to force DOM mutation (handles repeated messages)
- `polite` for non-urgent updates (filter results, success messages)
- `assertive` for critical errors (form validation failures)
