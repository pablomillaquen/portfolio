# Quickstart Validation: WCAG Accessibility Compliance

**Date**: 2026-07-17
**Feature**: 008-wcag-accessibility

## Prerequisites

- Node.js 22+ and npm installed
- Project dependencies installed (`npm install`)
- Development server running (`composer dev` or `php artisan serve` + `npm run dev`)

## Scenario 1: Skip Link

**Steps**:
1. Open the home page in Chrome
2. Press Tab once — skip link "Skip to main content" appears at top of page
3. Press Enter — page scrolls to main content, focus moves to `<main>` element
4. Press Tab — focus moves to first interactive element inside main content (not header/nav)

**Expected**: Skip link is visually hidden until focused, then appears with a visible focus ring. After activation, keyboard focus is inside main content.

## Scenario 2: Keyboard Navigation (Full Page)

**Steps**:
1. Open the home page
2. Press Tab repeatedly through the entire page
3. Verify every interactive element receives focus: skip link → nav links → profile image → project cards → social links → footer links
4. Verify every focused element has a visible 2px outline focus ring
5. Navigate to Projects page, then Posts page, then Contact page — repeat Tab check

**Expected**: All interactive elements are reachable via keyboard. No element is skipped. Focus indicator is always visible.

## Scenario 3: Modal Focus Trap

**Steps**:
1. Open the home page
2. Navigate to a project card with a video modal (if available) or content preview modal
3. Press Enter/Space to open the modal
4. Press Tab repeatedly — focus cycles within modal only (never escapes to background)
5. Press Escape — modal closes, focus returns to the card that opened it

**Expected**: Focus is trapped inside the modal. Background content is not focusable. Escape closes the modal and restores focus to trigger.

## Scenario 4: Form Labels and Errors

**Steps**:
1. Navigate to the Contact page
2. Tab to the first input — screen reader should announce "Name, required, edit text" (or equivalent)
3. Submit the form with empty fields
4. Verify error messages appear and are announced via screen reader
5. Verify each error message is visually associated with its input

**Expected**: All form inputs have associated labels. Required fields are announced. Errors are announced live.

## Scenario 5: Filter State (Screen Reader)

**Steps**:
1. Navigate to the Projects page
2. Activate a category filter button
3. Screen reader should announce "Filter name, toggle button, pressed"
4. Results update — screen reader announces "[N] results shown"
5. Toggle the filter off — screen reader announces "Filter name, toggle button, not pressed"

**Expected**: Filter state is communicated via `aria-pressed`. Result count is announced via live region.

## Scenario 6: Reduced Motion

**Steps**:
1. Enable "Reduce motion" in macOS System Settings → Accessibility → Display (or equivalent OS setting)
2. Navigate between pages — scrolling should be instant (no smooth scroll)
3. Open/close modals — no transition animations
4. Check CSS: `scroll-behavior: auto` is applied on `<html>`

**Expected**: No smooth scrolling, no CSS animations, no transitions when reduced motion is enabled.

## Scenario 7: Lighthouse Accessibility Audit

**Steps**:
1. Open the home page in Chrome
2. Open DevTools → Lighthouse tab
3. Run Accessibility audit
4. Check score and violations

**Expected**: Score ≥ 95. Zero critical violations (missing labels, missing alt text, contrast failures).

## Scenario 8: Automated axe-core Check

**Steps**:
1. Install axe DevTools browser extension
2. Open the home page
3. Run axe-core scan
4. Repeat for Projects, Posts, Contact pages

**Expected**: Zero violations across all public pages.
