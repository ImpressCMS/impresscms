# request-ownership Specification

## Purpose
Decides, for every incoming HTTP request, whether the new framework-based application or the legacy ImpressCMS application handles it. This lets functionality move to the new application one route at a time.
## Requirements
### Requirement: Single public entry point
The system SHALL accept requests for the host through one entry point: `index.php` in the web root. That entry point SHALL receive `/` and `/index.php`, and every request that the web server routes to it, and dispatch each one to the new application or to the legacy application. Legacy scripts that the web server executes directly SHALL keep working, and SHALL share the session and identity with the new application.

#### Scenario: Request reaches the front controller
- **WHEN** an HTTP request is routed to the web root's `index.php`
- **THEN** the host handles it and exactly one of the two applications produces the response

#### Scenario: Directly executed legacy script
- **WHEN** the web server executes `/user.php` directly, without routing it to `index.php`
- **THEN** the legacy application responds as without the host, and a user logged in there is the authenticated identity of the new application on the next request

### Requirement: Explicitly registered routes are owned by the new application
The system SHALL hand a request to the new application when its path and method match a route registered in the new application.

#### Scenario: Registered route matches
- **WHEN** a `GET /up` request arrives and `/up` is a registered route
- **THEN** the new application responds with status 200, content type `text/plain; charset=UTF-8` and body `ok`

#### Scenario: Registered route takes precedence over a legacy file
- **WHEN** a route is registered for a path that also exists as a file under the legacy root
- **THEN** the new application handles the request and the legacy file is not executed

### Requirement: New-application routes can take over legacy URLs
The system SHALL let a route of the new application claim an existing legacy URL without a dedicated URL prefix. This includes URLs whose path ends in `.php`, and variants of one script distinguished by query parameters. Requests to the same script that the route does not claim SHALL still go to the legacy application.

#### Scenario: Query-specific takeover of a legacy admin page
- **WHEN** a route claims `/modules/system/admin.php` only when the query parameter `fct` is `users`, and `GET /modules/system/admin.php?fct=users` arrives
- **THEN** the new application handles the request

#### Scenario: Other variants of the same script stay legacy
- **WHEN** the same route exists and `GET /modules/system/admin.php?fct=preferences` arrives
- **THEN** the legacy application handles the request

### Requirement: Reserved path prefixes are owned by the new application
The system SHALL hand a request to the new application when its path matches a configured reserved path or reserved prefix (`prefix/*`), even if no concrete route matches.

#### Scenario: Unmatched path under a reserved prefix
- **WHEN** `admin-next/*` is reserved and `GET /admin-next/unknown` arrives with no matching route
- **THEN** the new application responds with 404 and the legacy application is not invoked

### Requirement: Method mismatch on an owned route
The system SHALL respond with 405 Method Not Allowed, and an `Allow` header listing the permitted methods, when a request's path matches a route of the new application but its method does not.

#### Scenario: POST to a GET-only route
- **WHEN** a `POST /up` request arrives and `/up` only allows `GET`/`HEAD`
- **THEN** the response status is 405 and the `Allow` header contains `GET`

### Requirement: Everything else is legacy-owned
The system SHALL hand every request that is neither route-matched nor under a reserved path to the legacy application.

#### Scenario: Legacy page request
- **WHEN** `GET /modules/system/admin.php?fct=preferences` arrives and no route or reserved path matches
- **THEN** the legacy application handles the request

