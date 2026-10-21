## MODIFIED Requirements

### Requirement: Single public entry point
The system SHALL accept requests for the host through one entry point: `index.php` in the web root. That entry point SHALL receive `/` and `/index.php`, and every request that the web server routes to it, and dispatch each one to the new application or to the legacy application. Legacy scripts that the web server executes directly SHALL keep working, and SHALL share the session and identity with the new application.

#### Scenario: Request reaches the front controller
- **WHEN** an HTTP request is routed to the web root's `index.php`
- **THEN** the host handles it and exactly one of the two applications produces the response

#### Scenario: Directly executed legacy script
- **WHEN** the web server executes `/user.php` directly, without routing it to `index.php`
- **THEN** the legacy application responds as without the host, and a user logged in there is the authenticated identity of the new application on the next request
