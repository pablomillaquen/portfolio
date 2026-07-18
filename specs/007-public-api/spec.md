# Feature Specification: Public API

**Feature Branch**: `007-public-api`

**Created**: 2026-07-17

**Status**: Draft

**Input**: User description: "API pública documentada para consumidores externos"

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Discover and Explore the API (Priority: P1)

An external developer visits the portfolio and wants to integrate with its content. They find a documentation page that describes all available endpoints, request/response formats, authentication requirements, and rate limits. They can test endpoints directly from the documentation page. The developer understands how to use the API within minutes.

**Why this priority**: Without documentation, the API is unusable by external consumers. Documentation is the entry point for all other API interactions.

**Independent Test**: Can be fully tested by visiting the documentation page — all endpoints are listed with example requests and responses. Delivers immediate value by making the existing API discoverable.

**Acceptance Scenarios**:

1. **Given** an external developer, **When** they visit the API documentation page, **Then** they see a list of all public endpoints with descriptions
2. **Given** a developer, **When** they view an endpoint, **Then** they see the HTTP method, path, parameters, and example response
3. **Given** a developer, **When** they want to test an endpoint, **Then** they can execute a request directly from the documentation page and see the response

---

### User Story 2 - Authenticate API Requests (Priority: P1)

An external consumer wants to make authenticated requests to the API. They register for an API key, include it in their requests, and receive authorized responses. The authentication is simple — a key passed in the request header — not complex OAuth flows.

**Why this priority**: Authentication is required before rate limiting and usage tracking can work. It's a foundational capability for all authenticated API features.

**Independent Test**: Can be tested by creating an API key, making a request with it, and verifying the response. Without a valid key, requests still work but are subject to stricter rate limits.

**Acceptance Scenarios**:

1. **Given** a consumer, **When** they send a request with a valid API key in the header, **Then** the request is authenticated and returns the expected response
2. **Given** a consumer, **When** they send a request without an API key, **Then** the request still succeeds but with lower rate limits
3. **Given** a consumer, **When** they send a request with an invalid API key, **Then** the request is rejected with a clear error message

---

### User Story 3 - Consume Portfolio Content via API (Priority: P1)

An external application wants to display portfolio content — projects, posts, courses — in their own interface. They make API calls and receive structured JSON responses with all the content they need. The responses include pagination for lists and full detail for individual items.

**Why this priority**: This is the core value of a public API — enabling external consumers to access portfolio content programmatically.

**Independent Test**: Can be tested by calling each content endpoint and verifying the response structure matches the documented format. Each endpoint returns valid JSON with the expected fields.

**Acceptance Scenarios**:

1. **Given** a consumer, **When** they request the projects list, **Then** they receive a paginated list of published projects with summaries
2. **Given** a consumer, **When** they request a specific project by slug, **Then** they receive the full project detail including all translatable fields
3. **Given** a consumer, **When** they request posts or courses, **Then** they receive appropriately structured responses with pagination

---

### User Story 4 - Rate Limiting and Fair Use (Priority: P2)

The API enforces rate limits to prevent abuse. Anonymous requests have a lower limit than authenticated requests. When a consumer exceeds the limit, they receive a clear error with retry-after information. The limits are generous enough for legitimate use cases.

**Why this priority**: Rate limiting protects the service from abuse while ensuring legitimate consumers are not blocked. It's important for production reliability but not blocking for initial adoption.

**Independent Test**: Can be tested by making rapid requests and verifying the rate limit response appears at the documented threshold.

**Acceptance Scenarios**:

1. **Given** an anonymous consumer, **When** they make more than 60 requests per minute, **Then** they receive a 429 Too Many Requests response
2. **Given** an authenticated consumer, **When** they make more than 120 requests per minute, **Then** they receive a 429 Too Many Requests response
3. **Given** a consumer, **When** they receive a rate limit response, **Then** the response includes `X-RateLimit-Remaining` and `Retry-After` headers

---

### User Story 5 - API Versioning (Priority: P2)

The API uses versioned endpoints so that future changes don't break existing consumers. Consumers can pin to a specific version and continue using it even when newer versions are released.

**Why this priority**: Versioning is critical for long-term stability but doesn't affect initial adoption. It can be introduced before the first major breaking change.

**Independent Test**: Can be tested by requesting the same endpoint with different version prefixes and verifying different response formats (if versions differ).

**Acceptance Scenarios**:

1. **Given** a consumer, **When** they use `/api/v1/projects`, **Then** they receive the v1 response format
2. **Given** a consumer, **When** a v2 is released, **Then** `/api/v1/projects` continues to work unchanged
3. **Given** a consumer, **When** they don't specify a version, **Then** the latest stable version is used

---

### Edge Cases

- What happens when a consumer requests a resource that doesn't exist? The API returns a 404 with a structured error response
- What happens when a consumer sends malformed JSON? The API returns a 400 with a descriptive error message
- What happens when the API is under maintenance? The API returns a 503 with a retry-after header
- What happens when a consumer uses an expired API key? The API returns a 401 with instructions to regenerate

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST provide a documentation page listing all public API endpoints with descriptions and example responses
- **FR-002**: System MUST support API key authentication via `X-API-Key` request header
- **FR-003**: System MUST allow unauthenticated requests with stricter rate limits
- **FR-004**: System MUST return structured JSON responses for all endpoints
- **FR-005**: System MUST support pagination for list endpoints with `page` and `per_page` parameters
- **FR-006**: System MUST return consistent error responses with `error`, `message`, and `details` fields
- **FR-007**: System MUST enforce rate limits: 60 requests/minute for anonymous, 120 for authenticated
- **FR-008**: System MUST version API endpoints under `/api/v1/` prefix
- **FR-009**: System MUST support CORS for cross-origin requests from documented origins
- **FR-010**: System MUST document response headers: `X-RateLimit-Limit`, `X-RateLimit-Remaining`, `X-RateLimit-Reset`

### Key Entities

- **API Key**: A unique token identifying a consumer — has a label, key value, rate limit tier, and active status
- **API Endpoint**: A documented URL path with HTTP method, parameters, and response schema
- **Rate Limit State**: Tracks request counts per API key per time window

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: External developer can make their first successful API call within 5 minutes of reading the documentation
- **SC-002**: All public endpoints return valid JSON responses with correct status codes
- **SC-003**: Documentation page loads in under 2 seconds and includes interactive testing
- **SC-004**: Rate limiting prevents abuse — no single consumer can consume more than their allocated quota
- **SC-005**: API versioning allows introducing breaking changes without affecting existing consumers

## Assumptions

- The existing public endpoints in `PublicContentController` will be migrated to the versioned API routes
- API key management will be a simple admin interface — not a full developer portal
- Token-based auth (Laravel Sanctum) will be used for API key management
- The documentation page will be a static HTML/JSON file served from the public directory — not a third-party tool
- CORS will be configured for the production domain only
- Admin routes remain session-based and are not part of the public API
- The contact form endpoint remains web-based (not part of public API)
