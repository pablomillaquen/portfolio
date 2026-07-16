# Feature Specification: Admin UX Improvements

**Feature Branch**: `002-admin-ux-improvements`

**Created**: 2026-07-15

**Status**: Draft

**Input**: User description: "Necesito hacer cambios en la sección Admin: tabs para Projects y Posts, marca de estrella para featured, y soporte de imágenes markdown en texto"

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Tabbed Layout for Projects and Posts (Priority: P1)

As an admin, I want the Projects and Posts sections to use a tabbed interface (list tab + form tab) instead of a side-by-side layout, so that I have more space to fill in content details.

**Why this priority**: The current side-by-side layout restricts form width, making it harder to edit long text fields. Tabs provide full-width editing.

**Independent Test**: Navigate to Projects section, verify tabs "List" and "Form" appear. Click an item in List tab, verify view switches to Form tab with data populated. Click "New" button, verify Form tab opens empty.

**Acceptance Scenarios**:

1. **Given** I am in the Projects section, **When** the section loads, **Then** I see two tabs: "List" and "Form", with "List" active by default.
2. **Given** I am on the List tab, **When** I click a project, **Then** the view switches to the Form tab and the form is populated with that project's data.
3. **Given** I am on the List tab, **When** I click "New", **Then** the view switches to the Form tab with an empty form.
4. **Given** I am on the Form tab, **When** I click "Back" or the List tab, **Then** I return to the list view.
5. **Given** I save a project from the Form tab, **Then** I remain on the Form tab with the saved data (not reset to list).

---

### User Story 2 - Featured Star Indicator (Priority: P1)

As an admin, I want featured projects and posts to show a star icon in the list, so that I can quickly identify which items are featured without opening each one.

**Why this priority**: Quick visual identification of featured items improves admin workflow efficiency.

**Independent Test**: Create a project with `featured = true`, verify a star icon appears next to its name in the list. Create a project with `featured = false`, verify no star appears.

**Acceptance Scenarios**:

1. **Given** a project has `featured = true`, **When** I view the Projects list, **Then** a star icon (★) appears next to the project title.
2. **Given** a project has `featured = false`, **When** I view the Projects list, **Then** no star icon appears.
3. **Given** a post has `featured = true`, **When** I view the Posts list, **Then** a star icon (★) appears next to the post title.
4. **Given** a post has `featured = false`, **When** I view the Posts list, **Then** no star icon appears.

---

### User Story 3 - Markdown Image Support in Text Fields (Priority: P2)

As an admin, I want to embed images between text using markdown syntax (e.g., `![alt](url)`) in description and content fields, so that I can create rich content without a separate media upload system.

**Why this priority**: Markdown images allow inline visual content in descriptions and blog posts, which is essential for rich portfolio content.

**Independent Test**: Create a post with markdown image syntax in the content field. View the post on the public site, verify the image renders correctly.

**Acceptance Scenarios**:

1. **Given** I am editing a post's content field, **When** I type `![My Image](https://example.com/photo.jpg)`, **Then** the markdown is stored as-is in the database.
2. **Given** a post contains markdown image syntax, **When** I view the post on the public site, **Then** the image renders as an HTML `<img>` tag.
3. **Given** a project's description field contains markdown image syntax, **When** I view the project on the public site, **Then** the image renders correctly.

---

### Edge Cases

- What happens when the Form tab is open and I click "New"? The form should clear and remain on the Form tab.
- What happens when I toggle the featured checkbox and save? The star should update in the list after save.
- What happens when markdown image URL is broken? The image should show alt text or empty state — no crash.
- What happens when markdown syntax is malformed? It should display as plain text.

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: Projects section MUST display as two tabs: "List" and "Form".
- **FR-002**: Posts section MUST display as two tabs: "List" and "Form".
- **FR-003**: Clicking an item in the List tab MUST switch to the Form tab with that item's data populated.
- **FR-004**: Clicking "New" MUST switch to the Form tab with an empty form.
- **FR-005**: The Form tab MUST have a "Back" button or mechanism to return to the List tab.
- **FR-006**: Projects and posts with `featured = true` MUST display a star icon (★) in the list.
- **FR-007**: Projects and posts with `featured = false` MUST NOT display a star icon.
- **FR-008**: Description and content text fields MUST support markdown syntax, including `![alt](url)` for images.
- **FR-009**: Markdown content MUST be rendered as HTML when displayed on the public site.
- **FR-010**: The admin form MUST continue to accept and store raw markdown text (no WYSIWYG editor required).

### Key Entities

- **Project**: Existing entity. No schema changes. The `description` field stores markdown content.
- **Post**: Existing entity. No schema changes. The `content` field stores markdown content.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Admins can edit project/post details with full-width form (no side panel).
- **SC-002**: Featured items are visually identifiable in under 1 second.
- **SC-003**: Markdown images render correctly on the public site 100% of the time.
- **SC-004**: Admins can switch between list and form views in under 2 clicks.

## Assumptions

- The existing tab navigation (projects, posts, courses, settings, social) remains unchanged.
- The tabbed sub-navigation (List/Form) is within each section's tab, not a new top-level tab.
- Markdown rendering already exists on the public site via `Str::markdown()` (confirmed in `PublicContentController`).
- No new database migrations needed — markdown is stored as plain text in existing JSON fields.
- The star icon can be a simple Unicode character (★) — no icon library needed.
