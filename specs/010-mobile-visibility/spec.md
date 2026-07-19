# Feature Specification: Mobile Visibility Improvements

**Feature Branch**: `010-mobile-visibility`

**Created**: 2026-07-17

**Status**: Draft

**Input**: User description: "Mejorar la visibilidad en mobile"

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Mobile Navigation (Priority: P1)

As a mobile visitor, I want a hamburger menu that opens a navigation drawer so that I can access all sections of the portfolio without the header overflowing.

**Why this priority**: The current header has no mobile collapse mechanism — navigation links overflow or wrap awkwardly on screens below 600px. This is the most critical mobile UX gap.

**Independent Test**: Open the site on a phone (or 375px viewport) — tap the hamburger icon — navigation drawer slides in with all links — tap a link — drawer closes and page navigates.

**Acceptance Scenarios**:

1. **Given** a mobile viewport (<768px), **When** I view the header, **Then** a hamburger button is visible and the horizontal nav links are hidden
2. **Given** the hamburger menu is closed, **When** I tap the hamburger button, **Then** a navigation drawer opens with all nav links, theme toggle, and locale toggle
3. **Given** the navigation drawer is open, **When** I tap a nav link, **Then** the drawer closes and I navigate to that page
4. **Given** the navigation drawer is open, **When** I tap outside the drawer or press Escape, **Then** the drawer closes
5. **Given** a desktop viewport (≥768px), **When** I view the header, **Then** the hamburger is hidden and horizontal navigation is displayed as before

---

### User Story 2 - Responsive Layouts (Priority: P1)

As a mobile visitor, I want all pages to display correctly on small screens without horizontal scrolling or cramped content.

**Why this priority**: The current single breakpoint (900px) doesn't handle small phones (<480px) or tablets (768px-900px) well. List cards, post navigation, and form layouts need mobile-specific adjustments.

**Independent Test**: Open each page on a 375px viewport — verify no horizontal overflow, content is readable, images scale properly, and touch targets are large enough.

**Acceptance Scenarios**:

1. **Given** a small phone viewport (<480px), **When** I view a list card (posts, courses), **Then** the image stacks above the text instead of side-by-side
2. **Given** a small phone viewport, **When** I view post detail navigation (prev/next), **Then** the buttons stack vertically instead of side-by-side
3. **Given** a tablet viewport (768px-900px), **When** I view the project grid, **Then** the layout uses 2 columns appropriately
4. **Given** any mobile viewport, **When** I view the contact form, **Then** inputs are full-width and the submit button is easily tappable
5. **Given** any mobile viewport, **When** I view page content, **Then** there is adequate padding and no content touches the screen edges

---

### User Story 3 - Touch-Friendly Interactions (Priority: P2)

As a mobile visitor, I want interactive elements to be easy to tap and use on a touch screen.

**Why this priority**: Touch targets need to be at least 44x44px per WCAG guidelines, and tap delays should be minimized for responsive feel.

**Independent Test**: Tap all buttons, links, and interactive elements on mobile — verify they are easy to hit with a finger and respond immediately.

**Acceptance Scenarios**:

1. **Given** a touch device, **When** I tap any button or link, **Then** it responds immediately without 300ms tap delay
2. **Given** a touch device, **When** I view interactive elements, **Then** tap targets are at least 44x44px in size
3. **Given** a touch device, **When** I use the filter buttons (category, season), **Then** they are large enough to tap comfortably

---

### User Story 4 - Mobile Performance (Priority: P2)

As a mobile visitor on a cellular connection, I want the site to load quickly and feel responsive.

**Why this priority**: Mobile users often have slower connections. Performance impacts usability and engagement.

**Independent Test**: Load the site on a simulated 3G connection — verify the page loads within 3 seconds and interactions feel instant.

**Acceptance Scenarios**:

1. **Given** a slow connection, **When** I load any page, **Then** the initial content appears within 2 seconds
2. **Given** a mobile viewport, **When** I scroll through content, **Then** the scroll is smooth at 60fps
3. **Given** a mobile device, **When** I view images, **Then** they load progressively without layout shift

---

### Edge Cases

- What happens when the device is rotated from portrait to landscape? The layout should adapt fluidly without breaking.
- What happens when the navigation drawer is open and the device is rotated? The drawer should remain open and adapt.
- What happens on very small screens (<320px)? Content should remain readable with appropriate font scaling.
- What happens with the admin panel on mobile? Admin is out of scope for this spec.
- What happens with the image lightbox/gallery on mobile? Touch gestures for swipe should be considered.

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST display a hamburger menu button on viewports below 768px
- **FR-002**: System MUST provide a slide-out or dropdown navigation drawer accessible via the hamburger button
- **FR-003**: The navigation drawer MUST contain all public navigation links, theme toggle, and locale toggle
- **FR-004**: The navigation drawer MUST close when a link is tapped, when tapped outside, or when Escape is pressed
- **FR-005**: System MUST collapse list cards (posts, courses) to stacked layout on viewports below 480px
- **FR-006**: System MUST stack post navigation (prev/next) vertically on viewports below 480px
- **FR-007**: System MUST provide at least 3 responsive breakpoints: small phone (<480px), tablet (768px-900px), desktop (≥900px)
- **FR-008**: System MUST ensure all interactive elements have minimum 44x44px tap targets on touch devices
- **FR-009**: System MUST eliminate 300ms tap delay on interactive elements
- **FR-010**: System MUST maintain adequate padding on all pages so content does not touch screen edges
- **FR-011**: System MUST handle device rotation without layout breaking
- **FR-012**: The hamburger button MUST be hidden on viewports ≥768px

### Key Entities

- **Navigation Drawer**: A slide-out or dropdown panel containing navigation links and controls
- **Hamburger Button**: A toggle button that opens/closes the navigation drawer
- **Responsive Breakpoint**: A viewport width threshold where layout changes occur
- **Tap Target**: An interactive element sized for touch input

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: The site is fully usable on a 375px viewport (iPhone SE) with no horizontal overflow
- **SC-002**: All pages load and display correctly on viewports from 320px to 1920px
- **SC-003**: The navigation drawer opens and closes within 300ms
- **SC-004**: All tap targets are at least 44x44px on touch devices
- **SC-005**: No horizontal scrollbar appears on any page at any viewport width
- **SC-006**: Google Mobile-Friendly Test passes for all public pages
- **SC-007**: Lighthouse Mobile Performance score ≥ 80 (on 3G throttled connection)
- **SC-008**: Users can complete key tasks (navigate, read, contact) on mobile without pinch-to-zoom

## Assumptions

- Desktop layout remains unchanged — this spec only adds mobile improvements
- Admin panel is out of scope — mobile admin is a separate concern
- The existing Tailwind CSS v4 installation can be leveraged for responsive utilities
- The existing CSS media query at 900px will be extended with additional breakpoints
- No new JavaScript frameworks needed — vanilla Vue 3 reactivity for hamburger toggle
- Image optimization (WebP, lazy loading) is out of scope — covered by SPEC-006
- PWA features (service worker, offline) are out of scope
