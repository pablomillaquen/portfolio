# Research: Admin UX Improvements

**Date**: 2026-07-15
**Feature**: Admin UX Improvements

## R1: How to implement tabbed sub-navigation within each section

**Decision**: Add a `sectionTab` reactive ref (e.g., `projectSectionTab`, `postSectionTab`) that toggles between `'list'` and `'form'`. Use `v-if` to conditionally render the list panel or the form panel.

**Rationale**: Simple reactive state — no router changes, no new components. The existing top-level `tab` ref already controls which section is visible. Adding a sub-tab ref per section is consistent and minimal.

**Alternatives considered**:
- **Vue Router nested routes**: Would require router changes and URL management — overkill for a simple UI toggle.
- **Separate components**: Would split the form logic across files — violates YAGNI for this scope.

## R2: How to display star icon for featured items

**Decision**: Use Unicode character `★` (U+2605) inline next to the item title in the admin list. Conditionally render with `v-if="item.featured"`.

**Rationale**: No icon library needed. Unicode star is universally supported, simple to implement, and visually clear. No CSS complexity.

**Alternatives considered**:
- **Font Awesome or icon library**: Extra dependency — violates simplicity principle.
- **SVG star**: More work for no benefit over Unicode.
- **CSS pseudo-element**: Possible but less accessible than inline text.

## R3: How to handle markdown images in text fields

**Decision**: No changes needed. The admin form already accepts raw text. The public site already renders markdown via `Str::markdown()` in `PublicContentController` (confirmed at lines 149 and 182). Markdown image syntax `![alt](url)` is supported by Laravel's markdown parser.

**Rationale**: The infrastructure is already in place. Admin types raw markdown → stored in JSON → rendered as HTML on public site. No additional work required.

**Alternatives considered**:
- **WYSIWYG editor**: Would require a new library (Tiptap, CKEditor) — overkill for this use case.
- **Image upload integration**: Would require backend changes — out of scope.

## R4: How to handle save behavior in tabbed view

**Decision**: After saving, remain on the Form tab with the saved data populated. Add a `sectionTab` state that stays on `'form'` after save.

**Rationale**: Matches spec requirement (FR-005, acceptance scenario 5). Admin can continue editing or navigate back manually.

**Alternatives considered**:
- **Switch back to list after save**: Would lose context — poor UX for editing multiple fields.

## Summary

The implementation requires:
1. Add `sectionTab` reactive refs for Projects and Posts sections
2. Restructure Projects and Posts template to use `v-if` for list/form switching
3. Add `★` star icon next to featured items in list
4. Add "Back" button on form to return to list
5. No backend changes — markdown already renders
6. No new dependencies
