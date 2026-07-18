# Data Model: WCAG Accessibility Compliance

**Date**: 2026-07-17
**Feature**: 008-wcag-accessibility

## Summary

This feature has no data model. It is a frontend-only accessibility improvement that modifies HTML attributes, CSS, and Vue component templates. No database tables, API endpoints, or persistent state are introduced.

## State Transitions

None. All accessibility state is derived from:
- DOM attributes (`aria-*`, `role`, `tabindex`)
- CSS classes (`:focus-visible`, `.skip-link`, `.sr-only`)
- Vue reactive state (modal `show` prop, filter selections)
- Browser media queries (`prefers-reduced-motion`)
