# Quickstart Validation: Admin UX Improvements

**Date**: 2026-07-15
**Feature**: Admin UX Improvements

## Prerequisites

- PHP 8.3+, Composer, Node.js 20+, MySQL
- Application running (`composer dev`)

## Validation Scenarios

### Scenario 1: Tabbed navigation for Projects

1. Log in as admin
2. Click "Projects" tab
3. Verify "List" sub-tab is active, showing all projects
4. Click a project in the list
5. Verify view switches to "Form" sub-tab with project data populated
6. Click "Back" button
7. Verify view returns to "List" sub-tab

### Scenario 2: Tabbed navigation for Posts

1. Log in as admin
2. Click "Posts" tab
3. Verify "List" sub-tab is active, showing all posts
4. Click "New" button
5. Verify view switches to "Form" sub-tab with empty form
6. Fill in post data and save
7. Verify view remains on "Form" sub-tab with saved data

### Scenario 3: Featured star indicator

1. Log in as admin
2. Create a project with `featured = true`
3. Verify star (★) appears next to the project title in the list
4. Create a project with `featured = false`
5. Verify no star appears next to the project title
6. Repeat for posts

### Scenario 4: Markdown image rendering

1. Log in as admin
2. Create a post with content: `![My Photo](https://example.com/photo.jpg)`
3. Save the post
4. View the post on the public site (`/api/posts/{slug}`)
5. Verify the `content` field contains rendered HTML with `<img>` tag

### Scenario 5: Full-width form editing

1. Log in as admin
2. Navigate to Projects → Form tab
3. Verify the form takes full width (no side panel)
4. Verify all fields are accessible and editable
