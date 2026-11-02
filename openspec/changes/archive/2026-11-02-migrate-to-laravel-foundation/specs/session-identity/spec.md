## Purpose

Provides one session and one authenticated identity across requests handled by the new application and by the legacy ImpressCMS application. This lets migrated pages rely on the existing ImpressCMS login and group permissions.

## ADDED Requirements

### Requirement: One session across both applications
The system SHALL use a single session for both applications. The session name, cookie parameters, lifetime and storage SHALL be the ones configured in ImpressCMS: `use_mysession`, `session_name`, `session_expire` and the database session handler. Data written to the session by one application SHALL be readable by the other on subsequent requests.

#### Scenario: Value written by legacy, read by new application
- **WHEN** a legacy page stores a value in the session and the next request with the same session cookie is handled by a new-application route
- **THEN** the new-application route reads the same value

#### Scenario: Custom session name
- **WHEN** ImpressCMS is configured with `use_mysession` enabled and session name `MYSITE`
- **THEN** responses from both applications use the `MYSITE` session cookie and no second session cookie is issued

### Requirement: The ImpressCMS user is the authenticated identity in the new application
For requests handled by the new application, the system SHALL treat the user logged in through ImpressCMS as the authenticated user. It SHALL expose that user's id, login name, display name and group memberships. It SHALL treat the request as anonymous when no ImpressCMS user is logged in.

#### Scenario: Logged-in user
- **WHEN** a user logs in via the legacy `/user.php` and then requests a new-application route
- **THEN** that route sees the same user id and group memberships as `icms::$user` would provide

#### Scenario: Anonymous visitor
- **WHEN** a visitor without a valid ImpressCMS session requests a new-application route
- **THEN** the request is treated as anonymous with the anonymous group only

### Requirement: Login, logout and session expiry remain owned by ImpressCMS
The system SHALL keep the existing ImpressCMS login, logout, autologin and session-expiry behaviour. A logout or expiry SHALL take effect for both applications immediately.

#### Scenario: Logout
- **WHEN** a logged-in user logs out via the legacy logout and then requests a new-application route
- **THEN** that route treats the request as anonymous

### Requirement: Group-based access control for new-application routes
The system SHALL allow a new-application route to require an authenticated user, membership of the ImpressCMS webmasters group, or a named ImpressCMS group permission for the current module. Anonymous visitors who are denied SHALL be redirected to the ImpressCMS login page with a return URL. Authenticated users who are denied SHALL receive 403.

#### Scenario: Admin route as anonymous visitor
- **WHEN** an anonymous visitor requests a new-application route that requires webmaster access
- **THEN** the response redirects to the ImpressCMS login page with the original URL as the return target

#### Scenario: Admin route as non-admin
- **WHEN** an authenticated user who is not in the webmasters group requests that route
- **THEN** the response status is 403

#### Scenario: Admin route as webmaster
- **WHEN** a member of the webmasters group requests that route
- **THEN** the route is executed

### Requirement: CSRF protection is interoperable
A form rendered by one application and submitted to the other SHALL be validated using the ImpressCMS security token mechanism. Tokens issued by the ImpressCMS security service SHALL be accepted by new-application routes that opt into legacy token validation.

#### Scenario: Legacy token accepted by new route
- **WHEN** a form containing an ImpressCMS security token is posted to a new-application route that requires legacy token validation
- **THEN** the token is accepted once and rejected on replay
