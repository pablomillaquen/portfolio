# Quickstart: Professional Knowledge Platform Foundation

**Feature**: SPEC-004
**Date**: 2026-07-16
**Status**: Phase 1 Design

## Overview

This document provides validation scenarios to verify the Professional Knowledge Platform Foundation works correctly. Each scenario tests a specific user story or functional requirement.

## Prerequisites

1. Laravel 12 application running
2. Database migrated with new tables (seasons, categories, capabilities, pivots)
3. Sample data seeded
4. Vue 3 frontend compiled

## Validation Scenarios

### Scenario 1: Explore Professional Capabilities (US1)

**Goal**: Verify capabilities are displayed on homepage.

**Steps**:
1. Navigate to homepage
2. Scroll to capabilities section
3. Verify 3-5 capabilities are displayed
4. Verify each capability has name and description

**Expected Outcome**:
- Capabilities section visible
- Each capability shows name (ES/EN)
- Each capability shows brief description
- No layout issues

**Test Command**:
```bash
php artisan test --filter=CapabilityTest
```

---

### Scenario 2: Filter Projects by Category (US2)

**Goal**: Verify category filtering works.

**Steps**:
1. Navigate to /projects
2. Verify category filters are displayed
3. Select "Arquitectura" category
4. Verify only architecture projects are shown
5. Select multiple categories
6. Verify projects matching any category are shown

**Expected Outcome**:
- Category filters visible
- Filtering updates project list
- Multiple selection works (OR logic)
- Clear filter shows all projects

**Test Command**:
```bash
php artisan test --filter=CategoryFilterTest
```

---

### Scenario 3: View Case Study (US3)

**Goal**: Verify project displays as case study.

**Steps**:
1. Navigate to /projects
2. Click on a project
3. Verify case study structure is displayed
4. Verify all sections are present: Problem, Approach, Contribution, What it demonstrates
5. Verify related posts section is visible

**Expected Outcome**:
- Project page loads correctly
- All case study sections visible
- Content is readable and well-formatted
- Related posts section shows linked articles

**Test Command**:
```bash
php artisan test --filter=CaseStudyTest
```

---

### Scenario 4: Explore Seasons (US4)

**Goal**: Verify posts are organized by seasons.

**Steps**:
1. Navigate to /posts
2. Verify seasons are displayed as groups
3. Click on a season
4. Verify posts are ordered by episode number
5. Verify navigation (previous/next) works

**Expected Outcome**:
- Seasons displayed as distinct groups
- Posts ordered by episode number
- Season name and description visible
- Navigation between episodes works

**Test Command**:
```bash
php artisan test --filter=SeasonTest
```

---

### Scenario 5: Navigate Related Content (US5)

**Goal**: Verify bidirectional navigation between projects and posts.

**Steps**:
1. Navigate to a project page
2. Scroll to related posts section
3. Click on a related post
4. Verify post page loads
5. Verify back link to project exists
6. Click back link
7. Verify project page loads

**Expected Outcome**:
- Related posts visible on project page
- Post page shows related project link
- Navigation is fluid
- No dead ends

**Test Command**:
```bash
php artisan test --filter=RelatedContentTest
```

---

### Scenario 6: Future Content Indicators (US6)

**Goal**: Verify indicators for future content formats.

**Steps**:
1. Navigate to a post page
2. Verify "Video coming soon" indicator is visible
3. Navigate to a project page
4. Verify multimedia section shows future format support

**Expected Outcome**:
- Future content indicators visible
- User understands content will evolve
- No broken links or placeholders

**Test Command**:
```bash
php artisan test --filter=FutureContentTest
```

---

## API Validation

### Test Public API Endpoints

```bash
# Get capabilities
curl -X GET http://localhost:8000/api/capabilities

# Get categories
curl -X GET http://localhost:8000/api/categories

# Get seasons
curl -X GET http://localhost:8000/api/seasons

# Get projects with filtering
curl -X GET "http://localhost:8000/api/projects?category=salud"

# Get single project
curl -X GET http://localhost:8000/api/projects/sistema-equipos-medicos

# Get posts with filtering
curl -X GET "http://localhost:8000/api/posts?season=evidencia-en-salud"

# Get single post
curl -X GET http://localhost:8000/api/posts/diseno-sistema-equipos
```

### Test Admin API Endpoints

```bash
# Create season
curl -X POST http://localhost:8000/api/admin/seasons \
  -H "Content-Type: application/json" \
  -H "X-Requested-With: XMLHttpRequest" \
  -d '{
    "slug": "test-season",
    "status": "draft",
    "name": {"en": "Test Season", "es": "Temporada de Prueba"},
    "description": {"en": "Test", "es": "Prueba"}
  }'

# Create category
curl -X POST http://localhost:8000/api/admin/categories \
  -H "Content-Type: application/json" \
  -H "X-Requested-With: XMLHttpRequest" \
  -d '{
    "slug": "test-category",
    "dimension": "domain",
    "name": {"en": "Test Category", "es": "Categoría de Prueba"}
  }'
```

---

## Database Validation

### Verify New Tables Exist

```sql
SHOW TABLES LIKE 'seasons';
SHOW TABLES LIKE 'categories';
SHOW TABLES LIKE 'capabilities';
SHOW TABLES LIKE 'project_post';
SHOW TABLES LIKE 'category_project';
SHOW TABLES LIKE 'category_season';
SHOW TABLES LIKE 'capability_project';
```

### Verify New Columns Exist

```sql
-- Projects table
DESCRIBE projects;
-- Should show: problem, approach, contribution, what_it_demonstrates, project_status

-- Posts table
DESCRIBE posts;
-- Should show: season_id, episode_number, related_project_id
```

### Verify Indexes Exist

```sql
SHOW INDEX FROM projects WHERE Column_name = 'status';
SHOW INDEX FROM posts WHERE Column_name = 'season_id';
```

---

## Frontend Validation

### Verify Vue Components Render

1. Homepage: CapabilityCard component renders
2. Projects page: CategoryFilter component renders
3. Project detail: Case study sections render
4. Posts page: SeasonList component renders
5. Post detail: RelatedContent component renders

### Verify Responsive Design

1. Test on mobile (375px width)
2. Test on tablet (768px width)
3. Test on desktop (1200px width)
4. Verify no horizontal scroll
5. Verify touch targets are adequate

---

## Performance Validation

### Measure API Response Time

```bash
# Should be <200ms p95
ab -n 100 -c 10 http://localhost:8000/api/projects
```

### Measure Page Load Time

1. Open Chrome DevTools
2. Navigate to project page
3. Verify First Contentful Paint <1.5s
4. Verify Largest Contentful Paint <2.5s

---

## Success Criteria Validation

| Criterion | Validation Method | Expected |
|-----------|-------------------|----------|
| SC-001: Projects migrated | Database check | All projects have case study fields |
| SC-002: Posts in seasons | Database check | All published posts have season_id |
| SC-003: Project has related post | Database check | Each project has ≥1 related post |
| SC-004: Bidirectional navigation | Manual test | Navigate project ↔ post works |
| SC-005: Comprehension time | User test | <2 minutes to understand profile |
| SC-006: Capabilities identified | Manual test | <10 seconds to find capabilities |
| SC-007: Category filtering | API test | All predefined categories work |
