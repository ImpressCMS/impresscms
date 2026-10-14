## Purpose

Lets pages handled by the framework application run inside the ImpressCMS administration as if they were legacy admin pages. It covers rendering in the admin theme, legacy-compatible redirect messages, and the access rules of system admin functions.

## ADDED Requirements

### Requirement: Framework admin pages render inside the admin theme
The system SHALL render the content of a framework-handled admin page inside the configured ImpressCMS admin theme. The page SHALL include the admin header, menu and footer and the theme's assets, exactly as the legacy `icms_cp_header()`/`icms_cp_footer()` pair produces them. Content templates SHALL be resolved through the ImpressCMS template system (for example `db:system_adm_mimetype.html`).

#### Scenario: Admin page with a content template
- **WHEN** a framework admin page renders the template `db:system_adm_mimetype.html` with its variables
- **THEN** the response contains the admin theme around that template's output, with the same structure as the legacy page

### Requirement: Admin rendering does not terminate the request
Rendering an admin page SHALL neither call `exit()` nor send output or headers directly. Status, headers (including the no-cache and `X-Frame-Options` headers that the admin header sets) and body SHALL be returned as the framework response.

#### Scenario: Headers set by the admin header
- **WHEN** a framework admin page is rendered
- **THEN** the response carries `Cache-Control: no-store, no-cache, must-revalidate` and `X-Frame-Options: SAMEORIGIN`, sent once and not duplicated

### Requirement: Redirect messages behave as in legacy
When a framework admin page finishes an action with a message and a target URL, the system SHALL behave like the legacy `redirect_header()` for the site's configuration:
- When `use_custom_redirection` is enabled, it SHALL redirect directly to the target and show the message on the next page.
- Otherwise, it SHALL render the redirect page (`db:system_redirect.html`) with the message, the target URL and the configured delay.

It SHALL only redirect to URLs on the site itself.

#### Scenario: Redirect page mode
- **WHEN** `use_custom_redirection` is disabled and an action completes with the message "Saved"
- **THEN** the response is the redirect page in the admin theme, showing "Saved" and linking to the target URL

#### Scenario: Custom redirection mode
- **WHEN** `use_custom_redirection` is enabled and an action completes with the message "Saved"
- **THEN** the response is a 302 redirect to the target URL, and the next page shows "Saved"

#### Scenario: External target
- **WHEN** an action names `https://example.org/` as its target
- **THEN** the system redirects to the site's start page instead

### Requirement: System admin function access follows legacy rules
For a framework page that implements a system admin function, the system SHALL grant access only to a logged-in user who is an administrator of the system module and who is either a webmaster or holds the `system_admin` right for that function's category. A denied request SHALL behave like the legacy page: a redirect to the site's start page with the "no permission" message.

#### Scenario: Anonymous visitor
- **WHEN** an anonymous visitor requests a framework system admin function
- **THEN** the response matches the legacy denial for that function, a redirect to the start page with the "no permission" message

#### Scenario: Administrator without the category right
- **WHEN** a system module administrator who is not a webmaster and lacks the `system_admin` right for the mimetype category requests the mimetype page
- **THEN** access is denied as in legacy

#### Scenario: Webmaster
- **WHEN** a webmaster requests the mimetype page
- **THEN** access is granted
