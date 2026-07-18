# Research: Public API

**Date**: 2026-07-17
**Feature**: SPEC-007

## Decision 1: Authentication — Laravel Sanctum Tokens

**Decision**: Use Laravel Sanctum's personal access tokens for API key authentication.

**Rationale**: Sanctum ships with Laravel 12 — no additional dependencies. Personal access tokens are simple, well-documented, and sufficient for a portfolio API. No need for OAuth2 complexity.

**Alternatives considered**:
- Passport (OAuth2): Full OAuth2 server — overkill for a portfolio API with simple auth needs
- Custom token system: More control but more maintenance — rejected per Simplicity principle
- No auth (all public): Would lose rate limit tiering and usage tracking — rejected

**Pattern**: Consumer sends `X-API-Key: {token}` header. Sanctum validates token. Unauthenticated requests allowed with lower rate limits.

## Decision 2: API Documentation — Scramble

**Decision**: Use Scramble to auto-generate OpenAPI 3.1 documentation from code.

**Rationale**: Scramble infers documentation from Laravel's validation rules, API Resources, and route definitions — no manual annotation needed. Generates both interactive UI (`/docs/api`) and JSON spec (`/docs/api.json`). Maintains itself as code changes.

**Alternatives considered**:
- Swagger UI + hand-written OpenAPI: More control but requires manual maintenance — rejected per Simplicity
- Postman collections: Not browsable, not auto-updating — rejected
- Static README: Not interactive, hard to keep in sync — rejected
- Scribe: Similar to Scramble but less mature Laravel 12 support — rejected

## Decision 3: API Versioning — URL Prefix

**Decision**: Use URL-based versioning: `/api/v1/`, `/api/v2/`, etc.

**Rationale**: Most common and explicit approach. Consumers can see which version they're using. Easy to route and document. No header-based complexity.

**Alternatives considered**:
- Header-based versioning: Less visible, harder to test — rejected
- Query parameter versioning: Non-standard, pollutes URLs — rejected
- No versioning: Would break consumers on any change — rejected

## Decision 4: Rate Limiting — Laravel Throttle

**Decision**: Use Laravel's built-in `throttle` middleware with custom rate limiters defined in `AppServiceProvider`.

**Rationale**: Built into Laravel, no dependencies. Two tiers: anonymous (60/min) and authenticated (120/min). Keys tracked by IP for anonymous, by token ID for authenticated.

**Alternatives considered**:
- Redis-based rate limiting: Better for distributed deployments — unnecessary for single-server portfolio
- Third-party rate limiting service: Adds dependency — rejected per Simplicity
- No rate limiting: Would leave API vulnerable to abuse — rejected

## Decision 5: CORS — Laravel Config

**Decision**: Use Laravel's built-in CORS middleware with `config/cors.php` configuration.

**Rationale**: Laravel 12 includes `HandleCors` middleware in the global stack. Just need to publish and configure `config/cors.php` with allowed origins.

**Alternatives considered**:
- Web server CORS (Nginx/Apache): More performant but user deploys via cPanel — may not have server config access
- No CORS: Would prevent legitimate cross-origin API consumption — rejected

## Decision 6: Existing Endpoint Migration

**Decision**: Keep existing `PublicContentController` routes for SPA backward compatibility. Create new versioned controllers in `Api/V1/` namespace for the public API.

**Rationale**: The SPA currently consumes the existing endpoints. Migrating them would break the frontend. New versioned controllers can share model logic but have cleaner API response formats (pagination, consistent structure).

**Alternatives considered**:
- Migrate existing endpoints to v1: Would break SPA — rejected
- Remove existing endpoints after migration: SPA would need to be updated — deferred to future work
- Duplicate controllers: More code but zero risk — chosen for safety
