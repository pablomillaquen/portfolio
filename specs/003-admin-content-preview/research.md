# Research: Admin Content Preview

**Feature**: 003-admin-content-preview
**Date**: 2026-07-16

## Research Tasks

### 1. How to render markdown server-side for preview

**Decision**: Use Laravel's `Str::markdown()` method (already used in `PublicContentController`)

**Rationale**:
- Already implemented and tested in the codebase
- Ensures preview matches published appearance exactly
- No additional dependencies needed

**Alternatives Considered**:
- Client-side markdown library (marked, markdown-it): Rejected due to duplication risk and potential rendering differences
- Parsedown PHP library: Already included via Laravel, but `Str::markdown()` is the standard approach

### 2. How to handle bilingual content in preview

**Decision**: Accept `locale` query parameter and use `TranslatableContent::text()` for resolution

**Rationale**:
- Follows existing pattern in `PublicContentController`
- Consistent with how public API handles language
- Simple implementation

**Alternatives Considered**:
- Return both languages and let frontend select: Rejected to minimize response size and keep logic server-side

### 3. How to pass unsaved form data to preview endpoint

**Decision**: POST request with form data in JSON body

**Rationale**:
- Supports all field types (strings, objects, arrays)
- No URL length limitations
- Standard REST approach

**Alternatives Considered**:
- GET with query parameters: Rejected due to URL length limits and complex nested data
- FormData multipart: Rejected as unnecessarily complex for JSON data

### 4. How to render project details and media in preview

**Decision**: Reuse existing `projectPayload()` transformation logic from `PublicContentController`

**Rationale**:
- Already handles media array transformation
- Already handles details array transformation
- Ensures consistency with public API

**Alternatives Considered**:
- Custom preview transformation: Rejected as duplication

### 5. How to handle markdown in project details

**Decision**: Apply `Str::markdown()` to detail values that contain markdown content

**Rationale**:
- Details can contain rich text in value fields
- Maintains consistency with description field

**Alternatives Considered**:
- Plain text only: Rejected as limiting content richness

## Research Summary

All NEEDS CLARIFICATION items resolved:
- Markdown rendering: `Str::markdown()` (existing)
- Bilingual support: `TranslatableContent::text()` (existing)
- Data passing: POST with JSON body
- Content transformation: Reuse `projectPayload()` logic

No new dependencies required. All technical decisions align with existing codebase patterns.
