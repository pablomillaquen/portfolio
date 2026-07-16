# Feature Specification: Admin Content Preview

**Feature Branch**: `003-admin-content-preview`

**Created**: 2026-07-16

**Status**: Draft

**Input**: User description: "En la vista de admin, quiero tener una vista previa del post o proyecto, dado que no puedo verla hasta que está publicada."

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Preview Post/Project Before Publishing (Priority: P1)

As an admin user, I want to see a preview of how my post or project will look to visitors before I publish it, so I can verify the content, formatting, and layout are correct before making it public.

**Why this priority**: This is the core value proposition — the ability to preview content before publishing. Without this, admins must publish to see the result, which is risky and inefficient.

**Independent Test**: Can be fully tested by creating a draft post/project and clicking the preview button to see the rendered view without publishing.

**Acceptance Scenarios**:

1. **Given** I am editing a post or project in the admin panel, **When** I click the "Preview" button, **Then** I see a modal or new view showing how the content will appear to visitors.
2. **Given** I am viewing a preview, **When** I close the preview, **Then** I return to the admin editor with all my changes preserved.
3. **Given** I am previewing a draft post/project, **When** I view the preview, **Then** the content renders with proper formatting (markdown, images, bilingual text) exactly as it will appear when published.

---

### User Story 2 - Preview with Bilingual Content (Priority: P2)

As an admin user, I want to toggle between Spanish and English in the preview, so I can verify both language versions look correct before publishing.

**Why this priority**: Bilingual support is a core principle. Verifying both languages is essential for quality assurance.

**Independent Test**: Can be tested by creating a post with both ES and EN content, opening preview, and switching languages to verify both render correctly.

**Acceptance Scenarios**:

1. **Given** I am previewing a post/project with both ES and EN content, **When** I toggle the language, **Then** the preview updates to show the selected language.
2. **Given** I am previewing content, **When** I switch languages, **Then** all text fields (title, summary, description) update to the selected language.

---

### User Story 3 - Preview Media and Rich Content (Priority: P2)

As an admin user, I want the preview to properly render images, videos, and markdown formatting, so I can verify media placement and rich content appearance.

**Why this priority**: Ensures media and formatting are displayed correctly, which is critical for portfolio content quality.

**Independent Test**: Can be tested by creating a project with images and markdown content, opening preview, and verifying all media renders correctly.

**Acceptance Scenarios**:

1. **Given** I am previewing a project with images, **When** I view the preview, **Then** images display correctly with their captions.
2. **Given** I am previewing a post with markdown content, **When** I view the preview, **Then** markdown renders as formatted text (headers, lists, bold, italic, links).

---

### Edge Cases

- What happens when previewing content with missing fields (e.g., no cover image)?
- How does preview handle very long content or large images?
- What happens if network connection is lost during preview?

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST provide a "Preview" button on both post and project edit forms in the admin panel.
- **FR-002**: System MUST display preview in a modal overlay or dedicated view without navigating away from the editor.
- **FR-003**: System MUST render preview content using the same rendering logic as the public-facing views.
- **FR-004**: System MUST support language toggle (ES/EN) in preview mode.
- **FR-005**: System MUST preserve all unsaved changes when opening/closing preview.
- **FR-006**: System MUST render markdown content as formatted text in preview.
- **FR-007**: System MUST display images and media with proper sizing and captions.
- **FR-008**: System MUST handle draft status content without requiring publication.

### Key Entities

- **Preview Request**: Contains the current form data (unsaved) to be rendered for preview.
- **Preview Response**: The rendered HTML content matching public view appearance.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Admins can preview content in under 2 seconds after clicking the preview button.
- **SC-002**: Preview accurately reflects published appearance 100% of the time (no visual differences).
- **SC-003**: Admins can switch languages in preview without page reload.
- **SC-004**: All markdown formatting renders correctly in preview (headers, lists, emphasis, links, images).

## Assumptions

- The existing public content rendering logic (Str::markdown(), bilingual content structure) will be reused for preview.
- Preview will work with both saved and unsaved (draft) content.
- The admin panel single-file architecture (AdminPage.vue) will be maintained.
- Preview modal will use existing modal patterns in the codebase.
- No new backend endpoints are needed — preview can use existing public API endpoints with draft content access for admin users.
