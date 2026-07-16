# Quickstart Validation: Admin Content Preview

**Feature**: 003-admin-content-preview
**Date**: 2026-07-16

## Prerequisites

- Portfolio application running locally (`composer dev`)
- Admin user authenticated
- Browser open to admin panel

## Validation Scenarios

### Scenario 1: Preview a New Project (P1)

**Steps**:
1. Navigate to admin panel → Projects tab
2. Click "New" to create a new project
3. Fill in form fields:
   - Title EN: "Test Project"
   - Title ES: "Proyecto de Prueba"
   - Description EN: "This is a **bold** description with [a link](https://example.com)"
   - Description ES: "Esta es una descripción **en negrita** con [un enlace](https://example.com)"
   - Status: draft
4. Click "Preview" button

**Expected**:
- Modal opens showing rendered project
- Title displays "Test Project" (or selected locale)
- Description renders markdown: bold text and clickable link
- Close button returns to editor with all form data preserved

---

### Scenario 2: Preview a New Post (P1)

**Steps**:
1. Navigate to admin panel → Posts tab
2. Click "New" to create a new post
3. Fill in form fields:
   - Title EN: "Test Post"
   - Title ES: "Publicación de Prueba"
   - Content EN: "# Heading\n\n- Item 1\n- Item 2"
   - Content ES: "# Encabezado\n\n- Elemento 1\n- Elemento 2"
   - Status: draft
4. Click "Preview" button

**Expected**:
- Modal opens showing rendered post
- Heading renders as H1
- List renders as bullet points
- Close button returns to editor with form data preserved

---

### Scenario 3: Toggle Language in Preview (P2)

**Steps**:
1. Open preview for content with both EN and ES filled
2. Note the current language displayed
3. Click language toggle button (EN ↔ ES)
4. Verify content switches to other language

**Expected**:
- Language toggle switches between EN and ES
- All text fields update (title, summary/description, details)
- No page reload occurs
- Toggle state resets when modal closes

---

### Scenario 4: Preview Project with Media (P2)

**Steps**:
1. Create a project with:
   - Media item: kind=image, url=https://via.placeholder.com/300, caption EN="Test Image"
2. Click "Preview" button

**Expected**:
- Modal shows image rendered from URL
- Caption displays below image
- Image scales appropriately within modal

---

### Scenario 5: Preview Project with Details (P2)

**Steps**:
1. Create a project with:
   - Detail: label EN="Client", value EN="Acme Corp"
   - Detail: label EN="Year", value EN="2026"
2. Click "Preview" button

**Expected**:
- Modal shows details as definition list or labeled values
- Both details display correctly

---

### Scenario 6: Preview with Missing Fields (Edge Case)

**Steps**:
1. Create a project with only Title EN filled
2. Leave all other fields empty
3. Click "Preview" button

**Expected**:
- Modal opens without errors
- Title displays correctly
- Empty sections are hidden or show gracefully
- No broken layout

---

### Scenario 7: Close Preview Preserves Changes (P1)

**Steps**:
1. Start editing an existing project
2. Make changes to form fields (do NOT save)
3. Click "Preview" button
4. View preview
5. Close preview modal
6. Verify form still has unsaved changes

**Expected**:
- All unsaved changes preserved after closing preview
- No data loss occurs
- Form state unchanged

---

## API Validation

### Test Preview Endpoint Directly

```bash
# Preview a project
curl -X POST http://localhost:8000/api/admin/preview \
  -H "Content-Type: application/json" \
  -H "X-Requested-With: XMLHttpRequest" \
  -d '{
    "type": "project",
    "locale": "en",
    "data": {
      "title": {"es": "Título", "en": "Title"},
      "description": {"es": "Desc **md**", "en": "Desc **md**"}
    }
  }'

# Expected: 200 OK with html, title, locale fields
```

```bash
# Test validation
curl -X POST http://localhost:8000/api/admin/preview \
  -H "Content-Type: application/json" \
  -d '{"type": "project"}'

# Expected: 422 Unprocessable Entity with validation errors
```

## Troubleshooting

| Issue | Solution |
|-------|----------|
| Preview button not appearing | Check AdminPage.vue cta-row section |
| Modal doesn't open | Verify `showPreviewModal` ref is toggling |
| Markdown not rendering | Check `Str::markdown()` is applied in controller |
| Language toggle not working | Verify `locale` parameter is passed to API |
| Form data lost after preview | Ensure preview doesn't modify form refs |
