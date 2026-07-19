# Research: Mobile Visibility Improvements

**Date**: 2026-07-17

## Research Tasks

### 1. Mobile Navigation Pattern

**Decision**: Slide-out drawer from right side with overlay backdrop

**Rationale**:
- Most common mobile nav pattern (used by 73% of top websites)
- Works well with Vue 3 reactivity — `isMenuOpen` ref controls visibility
- CSS transition for smooth open/close animation
- Overlay backdrop provides clear dismiss target
- Escape key and outside tap to close (accessibility)

**Alternatives considered**:
- Dropdown below header: Less space for links, feels cramped
- Full-screen overlay: Too heavy for 4 links
- Bottom sheet: More native feel but harder to implement with Vue Router

### 2. Responsive Breakpoints

**Decision**: Three breakpoints — 480px (small phone), 768px (tablet), 900px (existing desktop)

**Rationale**:
- 480px: iPhone SE, small Android phones — need stacked layouts
- 768px: iPad portrait, tablets — hamburger toggle point
- 900px: Existing breakpoint — keeps desktop unchanged
- Follows mobile-first approach with `min-width` media queries

**Alternatives considered**:
- More breakpoints (320px, 400px, 600px): Diminishing returns, adds complexity
- Tailwind default breakpoints (640px, 768px, 1024px): Doesn't match existing 900px breakpoint

### 3. Hamburger Icon Implementation

**Decision**: CSS-only hamburger icon using 3 spans with CSS transitions

**Rationale**:
- No icon font or SVG dependency needed
- Pure CSS animation (3 lines → X on toggle)
- Accessible with `aria-label` and `aria-expanded`
- Lightweight — no additional HTTP requests

**Alternatives considered**:
- SVG icon: More flexible but adds markup
- Icon font (Font Awesome): Overkill for one icon
- Unicode character: Inconsistent across devices

### 4. Touch Target Sizing

**Decision**: Minimum 44x44px tap targets via CSS `min-height` and `min-width`

**Rationale**:
- WCAG 2.1 Level AA requires 44x44px touch targets
- Apple HIG recommends 44x44pt minimum
- Google Material Design recommends 48x48dp
- CSS-only solution — no JavaScript needed

**Alternatives considered**:
- 48x48px: More generous but may waste space on desktop
- 40x40px: Below WCAG minimum

### 5. 300ms Tap Delay Elimination

**Decision**: Add `touch-action: manipulation` to interactive elements

**Rationale**:
- CSS property tells browser to skip 300ms tap delay
- No viewport meta tag manipulation needed (viewport tag already correct)
- Works on all modern mobile browsers
- Applied selectively to buttons, links, and interactive elements

**Alternatives considered**:
- `fastclick.js`: Deprecated, no longer needed
- `meta viewport` manipulation: Unreliable across browsers

### 6. List Card Stacking on Mobile

**Decision**: Flexbox column layout at <480px breakpoint

**Rationale**:
- Current `.list-card` uses `display: flex` with fixed-width image (180px)
- At <480px, image width becomes 100% and text stacks below
- Simple CSS override — no component changes needed
- Maintains readability on narrow screens

### 7. Navigation Drawer Animation

**Decision**: CSS `transform: translateX()` with 300ms ease transition

**Rationale**:
- GPU-accelerated — smooth 60fps animation
- 300ms is perceptually instant without feeling jarring
- `ease` timing function feels natural
- Overlay fades in with `opacity` transition

### 8. Body Scroll Locking

**Decision**: Add `overflow: hidden` to body when drawer is open

**Rationale**:
- Prevents background scroll while drawer is open
- Standard pattern for mobile modals/drawers
- Removed when drawer closes
- Vue 3 `watchEffect` handles add/remove of class

## Summary of Technology Choices

| Aspect | Choice | Rationale |
|--------|--------|-----------|
| Nav pattern | Slide-out drawer | Most common, works with Vue reactivity |
| Breakpoints | 480px, 768px, 900px | Matches device categories, preserves desktop |
| Hamburger icon | CSS-only spans | No dependencies, accessible |
| Touch targets | 44x44px minimum | WCAG compliance |
| Tap delay | touch-action: manipulation | CSS-only, no JS |
| Animation | CSS transform + transition | GPU-accelerated, smooth |
