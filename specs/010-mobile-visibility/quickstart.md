# Quickstart: Mobile Visibility Improvements

**Date**: 2026-07-17

## Prerequisites

- Node.js 18+ with npm
- Chrome browser for DevTools device emulation
- Application running (`composer dev`)

## Validation Scenarios

### Scenario 1: Hamburger Menu Visible on Mobile

**Setup**: Open Chrome DevTools, set device to iPhone SE (375px)
**Command**: Navigate to any public page
**Expected**: Hamburger icon visible in header, horizontal nav links hidden
**Duration**: <5 seconds

### Scenario 2: Navigation Drawer Opens and Closes

**Setup**: Mobile viewport (375px)
**Command**: Tap hamburger icon
**Expected**: Drawer slides in from right with all nav links, theme toggle, locale toggle
**Duration**: <1 second

### Scenario 3: Navigation Drawer Link Works

**Setup**: Drawer is open
**Command**: Tap "Projects" link
**Expected**: Drawer closes, page navigates to /projects
**Duration**: <1 second

### Scenario 4: Navigation Drawer Dismiss

**Setup**: Drawer is open
**Command**: Tap outside drawer (on overlay)
**Expected**: Drawer closes
**Duration**: <1 second

### Scenario 5: Escape Key Closes Drawer

**Setup**: Drawer is open
**Command**: Press Escape key
**Expected**: Drawer closes
**Duration**: <1 second

### Scenario 6: Desktop Layout Unchanged

**Setup**: Desktop viewport (1920px)
**Command**: Navigate to any page
**Expected**: Horizontal nav visible, hamburger hidden, layout identical to before
**Duration**: <5 seconds

### Scenario 7: List Cards Stack on Small Screens

**Setup**: Mobile viewport (375px)
**Command**: Navigate to /posts or /courses
**Expected**: List cards show image above text (stacked layout)
**Duration**: <5 seconds

### Scenario 8: Post Navigation Stacks

**Setup**: Mobile viewport (375px)
**Command**: Navigate to a post detail page with prev/next
**Expected**: Prev/Next buttons stack vertically
**Duration**: <5 seconds

### Scenario 9: No Horizontal Overflow

**Setup**: Mobile viewport (375px)
**Command**: Navigate through all pages (home, projects, posts, courses, contact)
**Expected**: No horizontal scrollbar on any page
**Duration**: <30 seconds

### Scenario 10: Touch Targets Adequate

**Setup**: Mobile viewport (375px) or real device
**Command**: Tap all buttons and links
**Expected**: All interactive elements are easy to tap (≥44x44px)
**Duration**: <30 seconds

### Scenario 11: Google Mobile-Friendly Test

**Setup**: Site deployed to public URL
**Command**: Submit URL to Google Mobile-Friendly Test
**Expected**: All public pages pass
**Duration**: Manual check

### Scenario 12: Lighthouse Mobile Performance

**Setup**: Chrome DevTools, throttled to 3G, mobile device emulation
**Command**: Run Lighthouse audit on home page
**Expected**: Performance score ≥ 80
**Duration**: <30 seconds

## Troubleshooting

### Hamburger Not Visible
- Check that viewport is below 768px
- Check that CSS media query is loaded (no cache issues)
- Verify `.hamburger-btn` has `display: flex` at mobile breakpoint

### Drawer Not Sliding
- Check that `isMenuOpen` ref is toggling
- Verify CSS `transform: translateX()` transition is applied
- Check z-index is high enough (100+)

### Horizontal Overflow
- Check for fixed-width elements without `max-width: 100%`
- Verify images have `width: 100%` and `object-fit: cover`
- Check for `overflow-x: hidden` on body if needed
