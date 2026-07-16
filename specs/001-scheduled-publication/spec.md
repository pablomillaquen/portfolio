# Feature Specification: Scheduled Publication

**Feature Branch**: `001-scheduled-publication`

**Created**: 2026-07-15

**Status**: Draft

**Input**: User description: "Necesito crear un método para programar la publicación de nuevos proyectos y nuevos posts. Los crearé y necesito que se publiquen automáticamente en la fecha y hora que se escoja"

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Schedule a New Project (Priority: P1)

As an admin, I want to create a new project and set a future date and time for it to be published automatically, so that I can prepare content in advance and have it go live without manual intervention.

**Why this priority**: This is the core use case — scheduling content publication. Without it, the feature has no value.

**Independent Test**: Can be fully tested by creating a project with a future publication date and verifying it does NOT appear publicly until the scheduled time, then verifying it DOES appear publicly after the scheduled time.

**Acceptance Scenarios**:

1. **Given** I am logged in as an admin, **When** I create a project with a publication date set to 2 days from now, **Then** the project is saved with status "scheduled" and does not appear on the public site.
2. **Given** a project is scheduled for a specific date/time, **When** the system clock reaches that date/time, **Then** the project automatically becomes visible on the public site.
3. **Given** I am logged in as an admin, **When** I view the admin projects list, **Then** scheduled projects show their scheduled publication date.

---

### User Story 2 - Schedule a New Post (Priority: P1)

As an admin, I want to create a new post and set a future date and time for it to be published automatically, so that blog content can be prepared and distributed over time.

**Why this priority**: Posts are a core content type alongside projects. Scheduling both must work from day one.

**Independent Test**: Can be fully tested by creating a post with a future publication date and verifying it does NOT appear publicly until the scheduled time, then verifying it DOES appear publicly after the scheduled time.

**Acceptance Scenarios**:

1. **Given** I am logged in as an admin, **When** I create a post with a publication date set to 1 week from now, **Then** the post is saved with status "scheduled" and does not appear on the public site.
2. **Given** a post is scheduled for a specific date/time, **When** the system clock reaches that date/time, **Then** the post automatically becomes visible on the public site.
3. **Given** I am logged in as an admin, **When** I view the admin posts list, **Then** scheduled posts show their scheduled publication date.

---

### User Story 3 - Edit or Cancel a Scheduled Publication (Priority: P2)

As an admin, I want to change the publication date of a scheduled item or cancel the schedule entirely, so that I have full control over when content goes live.

**Why this priority**: Flexibility to adjust plans is essential — without it, mistakes require deleting and recreating content.

**Independent Test**: Can be tested by scheduling a post, then changing its date to a later time, and verifying it remains hidden until the new time. Also test cancelling a schedule and verifying the item stays hidden.

**Acceptance Scenarios**:

1. **Given** a project is scheduled for tomorrow, **When** I change the publication date to next week, **Then** the project remains hidden until next week.
2. **Given** a post is scheduled, **When** I clear the publication date, **Then** the post remains in draft/hidden state and does not publish automatically.
3. **Given** a project is already published, **When** I set a new publication date in the future, **Then** the project becomes hidden again and re-publishes at the new date.

---

### Edge Cases

- What happens when the scheduled date/time is in the past at the moment of saving? The item SHOULD publish immediately.
- What happens if the system is down at the scheduled time? The item SHOULD publish as soon as the system comes back online.
- What happens if an admin tries to schedule two items at the exact same date/time? Both SHOULD be published — there is no conflict.
- What happens if the queue worker is not running? Scheduled publications will not trigger until the worker resumes.

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST allow admins to set a publication date and time when creating or editing a project.
- **FR-002**: System MUST allow admins to set a publication date and time when creating or editing a post.
- **FR-003**: Projects and posts with a future publication date MUST NOT appear on public API endpoints (`/api/projects`, `/api/posts`).
- **FR-004**: System MUST automatically change a scheduled item's status to "published" when the scheduled date/time is reached.
- **FR-005**: System MUST publish any overdue scheduled items when the system starts or the queue worker resumes.
- **FR-006**: Admins MUST be able to view the scheduled publication date in the admin panel for projects and posts.
- **FR-007**: Admins MUST be able to edit or clear the publication date of a scheduled item.
- **FR-008**: System MUST support both projects and posts with the same scheduling mechanism.
- **FR-009**: If a publication date is set to a past date/time, the item MUST publish immediately upon save.

### Key Entities

- **Project**: Existing entity. Needs a new optional `published_at` field (datetime, nullable) to hold the scheduled publication date.
- **Post**: Existing entity. Needs a new optional `published_at` field (datetime, nullable) to hold the scheduled publication date.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Admins can schedule a publication in under 30 seconds (set date and save).
- **SC-002**: 100% of scheduled items publish within 60 seconds of their scheduled time.
- **SC-003**: Zero scheduled items appear publicly before their scheduled date/time.
- **SC-004**: Admins can reschedule or cancel a publication in under 15 seconds.

## Assumptions

- The existing `admin.session` middleware and admin CRUD controllers will be extended, not replaced.
- Laravel's queue system (configured with `QUEUE_CONNECTION=database` per `.env.example`) is available for background jobs.
- The system already has a reliable way to determine "now" (server time).
- The public API endpoints already filter by status — this feature extends that filtering to include scheduled items.
- Timezone handling follows the application's configured timezone (default: server timezone).
