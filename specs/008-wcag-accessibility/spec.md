# Feature Specification: WCAG Accessibility Compliance

**Feature Branch**: `008-wcag-accessibility`

**Created**: 2026-07-17

**Status**: Draft

**Input**: User description: "Cumplimiento WCAG, screen readers, navegación por teclado"

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Keyboard-Only Navigation (Priority: P1)

A visitor who cannot use a mouse (due to motor disability, repetitive strain injury, or personal preference) navigates the entire public portfolio using only the keyboard. They can reach every interactive element — links, buttons, form inputs, modals — via Tab/Shift+Tab, activate them with Enter/Space, dismiss modals with Escape, and always see a clear visual indicator of where focus is on the page.

**Why this priority**: Keyboard navigation is the foundation of web accessibility. Without it, assistive technology users cannot interact with the site at all. This is also a legal requirement under WCAG 2.1 SC 2.1.1.

**Independent Test**: Can be fully tested by unplugging the mouse and navigating the entire site — home, projects list, project detail, posts, courses, contact form, theme toggle, language toggle, and any modals — using only Tab, Shift+Tab, Enter, Space, and Escape. Every element receives a visible focus indicator.

**Acceptance Scenarios**:

1. **Given** a keyboard-only user on the home page, **When** they press Tab repeatedly, **Then** focus moves through skip-link → nav links → profile image → project cards → footer links in logical order
2. **Given** a keyboard user with a modal open, **When** they press Escape, **Then** the modal closes and focus returns to the element that triggered it
3. **Given** a keyboard user on any page, **When** an element receives focus, **Then** a visible focus ring (minimum 2px outline, high contrast) appears around that element
4. **Given** a keyboard user on the contact form, **When** they Tab through fields, **Then** each input receives focus in顺序 and the submit button is reachable

---

### User Story 2 - Screen Reader Content Access (Priority: P1)

A blind or low-vision visitor using a screen reader (NVDA, VoiceOver, JAWS) navigates the portfolio. They hear meaningful landmarks (header, main, footer), skip repetitive navigation, understand page structure through headings, receive image descriptions, hear form errors announced live, and encounter properly labeled interactive elements.

**Why this priority**: Screen reader access is the core purpose of WCAG compliance. The site already has partial semantic HTML — this story completes the picture so all content is perceivable.

**Independent Test**: Can be tested by navigating with VoiceOver (macOS) or NVDA (Windows) — the screen reader should announce landmarks, headings, image descriptions, form labels, and dynamic content changes without visual confirmation.

**Acceptance Scenarios**:

1. **Given** a screen reader user on any page, **When** the page loads, **Then** they can use the rotor/outlines to jump between landmarks (header, main, footer) and each is properly labeled
2. **Given** a screen reader user, **When** they encounter an image, **Then** the screen reader announces a meaningful description (not "image" or file name)
3. **Given** a screen reader user on the contact form, **When** they submit with errors, **Then** the error messages are announced automatically via a live region
4. **Given** a screen reader user, **When** a modal opens, **Then** focus is moved into the modal and the screen reader announces the modal title

---

### User Story 3 - Accessible Forms and Error Handling (Priority: P1)

A visitor fills out the contact form. Every input has a visible label (not just placeholder text). Required fields are announced as required. When submission fails, errors are associated with specific fields and announced live. The form is usable by screen reader, keyboard, and voice control users.

**Why this priority**: Forms are the primary interactive element for visitors. Without proper labels and error handling, the form is unusable for assistive technology users.

**Independent Test**: Can be tested by filling the contact form with screen reader enabled — all labels should be announced, required status should be clear, and errors should be associated with fields.

**Acceptance Scenarios**:

1. **Given** a screen reader user on the contact form, **When** they navigate to the name field, **Then** the screen reader announces "Name, required" (or equivalent in the active language)
2. **Given** a user submitting the form with empty fields, **When** validation errors appear, **Then** each error message is programmatically associated with its input and announced via `aria-live`
3. **Given** any user, **When** they look at the form, **Then** visible labels are present above or beside each input (not relying on placeholder text alone)

---

### User Story 4 - Modal Accessibility (Priority: P2)

A visitor triggers a modal (video preview, content preview). The modal has proper ARIA attributes, focus is trapped inside it while open, Escape closes it, and focus returns to the trigger element on close. The screen reader announces the modal purpose and its content.

**Why this priority**: Modals are a common accessibility trap. Without focus trapping and proper ARIA, keyboard and screen reader users get stuck or lost.

**Independent Test**: Can be tested by opening a modal with keyboard, verifying focus is trapped (Tab cycles within modal only), pressing Escape closes it, and focus returns to the trigger.

**Acceptance Scenarios**:

1. **Given** a user opens a modal, **When** the modal appears, **Then** focus moves to the first focusable element inside the modal
2. **Given** a user with a modal open, **When** they press Tab, **Then** focus cycles through focusable elements within the modal only (not to elements behind)
3. **Given** a user with a modal open, **When** they press Escape, **Then** the modal closes and focus returns to the element that opened it

---

### User Story 5 - Color Contrast and Visual Accessibility (Priority: P2)

A low-vision visitor reads text on the portfolio. All text meets WCAG AA contrast ratios (4.5:1 for normal text, 3:1 for large text). Focus indicators are clearly visible against both light and dark backgrounds. The site respects the user's `prefers-reduced-motion` setting.

**Why this priority**: Visual clarity is essential for low-vision users. The current `--muted` color fails contrast ratios. Reduced motion support prevents vestibular distress.

**Independent Test**: Can be tested with a contrast checker tool on all text-background combinations. Can be tested by enabling "Reduce motion" in OS settings and verifying animations are disabled.

**Acceptance Scenarios**:

1. **Given** a low-vision user on the dark theme, **When** they read body text, **Then** the contrast ratio is at least 4.5:1 against the background
2. **Given** a user with `prefers-reduced-motion: reduce` enabled, **When** they navigate between pages, **Then** smooth scrolling is disabled and no animations play
3. **Given** a keyboard user, **When** an element receives focus, **Then** the focus indicator has at least 3:1 contrast against adjacent colors

---

### User Story 6 - Filter and Toggle Accessibility (Priority: P3)

A visitor uses assistive technology to filter projects by category or posts by season. Filter buttons communicate their pressed/selected state via ARIA. The screen reader announces how many results are shown after filtering.

**Why this priority**: Interactive filters are a secondary feature. Ensuring their state is communicated completes the accessibility picture for dynamic content.

**Independent Test**: Can be tested with a screen reader — toggling a filter should announce the new state and result count.

**Acceptance Scenarios**:

1. **Given** a screen reader user on the projects page, **When** they activate a category filter, **Then** the button announces "pressed" or "not pressed" state
2. **Given** a user applying a filter, **When** results update, **Then** the result count is announced via a live region

---

### Edge Cases

- What happens when a screen reader encounters a decorative image? It is hidden from the accessibility tree via `aria-hidden="true"` or empty `alt=""`
- What happens when focus enters a dynamically rendered list (infinite scroll, lazy-loaded content)? New content is announced via a live region
- What happens when the theme changes while a modal is open? Focus ring colors adjust to remain visible against the new background
- What happens when language switches mid-page? All ARIA labels and form labels update to reflect the new language
- What happens when a form has both required and optional fields? Required fields are marked with `aria-required="true"` and visually indicated

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST provide a skip-to-content link visible on focus at the top of every page
- **FR-002**: System MUST set visible focus indicators on all interactive elements (minimum 2px outline, 3:1 contrast ratio)
- **FR-003**: System MUST provide `<label>` elements or `aria-label` attributes for all form inputs
- **FR-004**: System MUST announce dynamic content changes (form errors, filter results) via `aria-live` regions
- **FR-005**: System MUST trap focus within open modals and return focus to trigger on close
- **FR-006**: System MUST add `role="dialog"` and `aria-modal="true"` to all modal containers
- **FR-007**: System MUST support Escape key to close modals
- **FR-008**: System MUST add `aria-label` to all `<nav>` elements to distinguish them
- **FR-009**: System MUST add `aria-pressed` to toggle/filter buttons
- **FR-010**: System MUST hide decorative images from screen readers via `aria-hidden="true"` or empty `alt`
- **FR-011**: System MUST add `title` attribute to all iframes (videos)
- **FR-012**: System MUST provide accessible names for icon-only buttons (theme toggle, language toggle, modal close)
- **FR-013**: System MUST respect `prefers-reduced-motion: reduce` by disabling smooth scrolling and animations
- **FR-014**: System MUST ensure all text meets WCAG AA contrast ratios (4.5:1 normal, 3:1 large)
- **FR-015**: System MUST use `aria-required="true"` and visual indicators for required form fields

### Key Entities

- **Focus Indicator**: A visible outline or ring around the currently focused interactive element, with sufficient contrast against the background
- **ARIA Live Region**: A container with `aria-live` that announces content changes to screen readers
- **Skip Link**: A hidden link at page top that becomes visible on focus, jumping to the main content area
- **Modal Trap**: A mechanism that restricts Tab focus to within an open modal dialog

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Keyboard-only user can complete all primary tasks (navigate, view projects, submit contact form) without a mouse
- **SC-002**: Site passes automated accessibility audit (axe-core or Lighthouse Accessibility) with 0 critical violations
- **SC-003**: All form inputs have associated labels that screen readers announce correctly
- **SC-004**: All interactive elements have visible focus indicators when navigated via keyboard
- **SC-005**: All text meets WCAG AA contrast ratios (4.5:1 for normal text)
- **SC-006**: Screen reader users can navigate the site structure via landmarks and headings without encountering unlabeled or ambiguous elements
- **SC-007**: Users with `prefers-reduced-motion` enabled experience no smooth scrolling or CSS animations

## Assumptions

- WCAG 2.1 Level AA is the target compliance level (not AAA)
- Testing will use VoiceOver on macOS and Lighthouse automated audit as primary verification tools
- The existing semantic HTML foundation (`<header>`, `<nav>`, `<main>`, `<footer>`, breadcrumbs) is preserved and extended
- Admin panel (`/admin`) is NOT in scope for this SPEC — accessibility focus is on public-facing pages
- The `outline: none` global CSS reset will be replaced with proper focus-visible styles, not removed entirely
- Vue 3's reactivity system makes `aria-live` regions straightforward to implement
- No third-party accessibility library is required — native HTML/ARIA attributes and Tailwind utilities suffice
- Existing bilingual content pattern (ES/EN) will include translated ARIA labels
