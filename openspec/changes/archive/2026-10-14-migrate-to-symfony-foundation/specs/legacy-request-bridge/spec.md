## Purpose

Executes legacy ImpressCMS scripts and serves legacy static assets behind the new host. To clients, modules and themes the result is indistinguishable from running ImpressCMS directly, while paths are resolved safely and failures do not disclose internals.

## ADDED Requirements

### Requirement: Legacy static assets are served directly
The system SHALL serve an existing non-PHP file under the legacy root, with an allow-listed extension, as a file response without executing PHP. The response SHALL carry an explicit MIME type for known web asset extensions and support conditional requests.

#### Scenario: Stylesheet request
- **WHEN** `GET /themes/iTheme/style.css` arrives and the file exists
- **THEN** the response status is 200, `Content-Type` is `text/css; charset=UTF-8` and the body equals the file contents

#### Scenario: Conditional request for an unchanged asset
- **WHEN** a request for an existing asset carries an `If-None-Match` header that equals the asset's current ETag
- **THEN** the response status is 304 with an empty body

### Requirement: Legacy scripts execute with their original environment
The system SHALL execute an existing PHP file under the legacy root in-process. During execution, `$_GET`, `$_POST`, `$_COOKIE`, `$_FILES`, `$_REQUEST` and `$_SERVER` (including `SCRIPT_NAME`, `SCRIPT_FILENAME`, `PHP_SELF`, `REQUEST_URI` and `DOCUMENT_ROOT`) SHALL be populated and the working directory SHALL be set exactly as if the web server had invoked that script directly.

#### Scenario: Script sees its own script name
- **WHEN** `GET /modules/system/admin.php?fct=users` is bridged
- **THEN** inside the script `$_SERVER['SCRIPT_NAME']` is `/modules/system/admin.php`, `$_GET['fct']` is `users` and `getcwd()` is the `modules/system` directory under the legacy root

#### Scenario: Global variables keep working
- **WHEN** a legacy script declares a top-level variable and a function in another included file accesses it with `global`
- **THEN** the function sees the same value as it would without the bridge

### Requirement: Unmatched legacy paths fall back to the legacy front controller
The system SHALL execute the legacy root `index.php` for a legacy-owned request whose path does not correspond to an existing file.

#### Scenario: Unknown path
- **WHEN** `GET /does-not-exist` is legacy-owned
- **THEN** the legacy `index.php` is executed

### Requirement: Path traversal is rejected
The system SHALL refuse to serve or execute any target that resolves outside the legacy root. It SHALL also refuse to serve or execute files under configured non-public legacy directories, such as the trust path, cache and compiled templates.

#### Scenario: Encoded traversal attempt
- **WHEN** `GET /%2e%2e/%2e%2e/etc/passwd` arrives
- **THEN** no file outside the legacy root is read or executed and the response status is 404

#### Scenario: Compiled template directory
- **WHEN** `GET /templates_c/some_compiled_template.php` arrives
- **THEN** the file is not executed and the response status is 404

### Requirement: Legacy response semantics are preserved
The system SHALL turn a legacy script's output into the HTTP response with an unchanged body, the status code set by the script, every header the script set (including multiple `Set-Cookie` headers) and redirects. It SHALL do so whether the script ends normally or calls `exit()`/`die()`.

#### Scenario: Redirect followed by exit
- **WHEN** a legacy script sends `Location: /user.php` with status 302 and then calls `exit()`
- **THEN** the client receives status 302 with `Location: /user.php`

#### Scenario: Multiple cookies
- **WHEN** a legacy script sets two cookies in one request
- **THEN** the response contains two `Set-Cookie` headers with the original attributes

### Requirement: The bridge does not interfere with legacy session handling
The system SHALL NOT start, name or configure a PHP session before the legacy application does. Session start-up, naming, cookie parameters and the save handler SHALL remain under the control of the legacy application or of the shared session defined in the `session-identity` capability.

#### Scenario: POST to a legacy login
- **WHEN** a login form is posted to `/user.php` and the site uses a custom session name and the database session handler
- **THEN** the session is stored by the database session handler under the configured session name, and a later request with that cookie is authenticated

### Requirement: Failures do not disclose internals outside debug mode
When debug mode is off and legacy execution fails with an uncaught throwable or a fatal error, the system SHALL return a generic 500 error page that contains no file system paths, exception messages, stack traces or partial page output. When debug mode is on, it SHALL include the request, resolution, exception and trace details. In both modes the full details SHALL be logged.

#### Scenario: Fatal error in production
- **WHEN** debug mode is off and a legacy script throws an uncaught exception
- **THEN** the response status is 500, the body contains no path under the legacy root and no exception message, and the details appear in the application log

#### Scenario: Fatal error in debug mode
- **WHEN** debug mode is on and a legacy script throws an uncaught exception
- **THEN** the response contains the exception class, message, location and stack trace

### Requirement: Process state is restored after legacy execution
After legacy execution completes or fails, the system SHALL restore the superglobals, working directory and output buffering level that the host had before it. This SHALL be done so that the same process can handle long-running workers or sub-requests safely.

#### Scenario: Output buffering level
- **WHEN** a legacy script opens nested output buffers and does not close them
- **THEN** after the bridged request the output buffering level equals the level before execution
