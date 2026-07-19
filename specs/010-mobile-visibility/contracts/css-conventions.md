# CSS Conventions: Mobile Visibility

**Date**: 2026-07-17

## Responsive Breakpoints

```css
/* Small phone — stacked layouts */
@media (max-width: 480px) { ... }

/* Tablet — hamburger visible, 2-col grids */
@media (max-width: 768px) { ... }

/* Existing desktop breakpoint (UNCHANGED) */
@media (max-width: 900px) { ... }
```

**Note**: Use `max-width` to match existing convention. Mobile-first (`min-width`) is an alternative but would require rewriting existing rules.

## Hamburger Menu CSS

```css
/* Hamburger button — hidden on desktop */
.hamburger-btn { display: none; }

@media (max-width: 768px) {
    .hamburger-btn { display: flex; }
    .primary-nav { display: none; }
    .primary-nav.is-open { display: flex; }
}

/* Hamburger icon animation */
.hamburger-icon span { transition: transform 0.3s ease; }
.hamburger-icon.is-open span:nth-child(1) { transform: rotate(45deg); }
.hamburger-icon.is-open span:nth-child(2) { opacity: 0; }
.hamburger-icon.is-open span:nth-child(3) { transform: rotate(-45deg); }
```

## Touch Target Sizing

```css
/* Apply to all interactive elements */
button, a, input, select, textarea,
.filter-button, .ghost-button, .cta-button {
    min-height: 44px;
    min-width: 44px;
}
```

## Tap Delay Elimination

```css
/* Apply to all interactive elements */
button, a, input, select, textarea,
[role="button"], [role="link"], [role="tab"] {
    touch-action: manipulation;
}
```

## List Card Stacking

```css
/* Default: side-by-side */
.list-card { display: flex; gap: 1.5rem; }
.post-cover { width: 180px; min-width: 180px; }

/* Small phone: stacked */
@media (max-width: 480px) {
    .list-card { flex-direction: column; }
    .post-cover { width: 100%; min-width: unset; }
}
```

## Post Navigation Stacking

```css
/* Default: side-by-side */
.post-navigation { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

/* Small phone: stacked */
@media (max-width: 480px) {
    .post-navigation { grid-template-columns: 1fr; }
}
```

## Navigation Drawer

```css
/* Drawer — slides from right */
.nav-drawer {
    position: fixed;
    top: 0;
    right: 0;
    width: 280px;
    height: 100vh;
    transform: translateX(100%);
    transition: transform 0.3s ease;
    z-index: 100;
}
.nav-drawer.is-open { transform: translateX(0); }

/* Overlay */
.nav-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
    z-index: 99;
}
.nav-overlay.is-visible { opacity: 1; pointer-events: auto; }
```
